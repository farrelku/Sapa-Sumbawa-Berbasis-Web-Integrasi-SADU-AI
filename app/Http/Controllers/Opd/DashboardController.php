<?php

namespace App\Http\Controllers\Opd;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportTimeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $opd = $user->opd;

        if (!$opd) {
            abort(403, 'Akun Anda belum terasosiasi dengan Perangkat Daerah / OPD manapun.');
        }

        $stats = [
            'total' => Report::where('opd_id', $opd->id)->count(),
            'didisposisikan' => Report::where('opd_id', $opd->id)->where('status', 'didisposisikan')->count(),
            'diproses' => Report::where('opd_id', $opd->id)->where('status', 'diproses')->count(),
            'selesai' => Report::where('opd_id', $opd->id)->where('status', 'selesai')->count(),
        ];

        $pendingActionReports = Report::with(['user'])
            ->where('opd_id', $opd->id)
            ->whereIn('status', ['didisposisikan', 'diproses'])
            ->orderByRaw("CASE WHEN urgency = 'mendesak' THEN 1 WHEN urgency = 'sedang' THEN 2 ELSE 3 END")
            ->latest('dispatched_at')
            ->take(6)
            ->get();

        return view('opd.dashboard', compact('stats', 'pendingActionReports', 'opd'));
    }

    public function reports(Request $request)
    {
        $opd = Auth::user()->opd;
        $query = Report::with('user')->where('opd_id', $opd->id)->latest('dispatched_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                  ->orWhere('ticket_number', 'like', $search)
                  ->orWhere('location_detail', 'like', $search);
            });
        }

        $reports = $query->paginate(12)->withQueryString();

        return view('opd.reports.index', compact('reports', 'opd'));
    }

    public function showReport($id)
    {
        $opd = Auth::user()->opd;
        $report = Report::with(['user', 'assignedBy', 'timelines.user'])
            ->where('opd_id', $opd->id)
            ->findOrFail($id);

        return view('opd.reports.show', compact('report', 'opd'));
    }

    public function startProcess(Request $request, $id)
    {
        $opd = Auth::user()->opd;
        $report = Report::where('opd_id', $opd->id)->findOrFail($id);

        $request->validate([
            'process_notes' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'process_notes.required' => 'Catatan progres penanganan wajib diisi.',
            'process_notes.min' => 'Catatan minimal 5 karakter.',
        ]);

        $report->status = 'diproses';
        $report->opd_notes = $request->process_notes;
        $report->save();

        ReportTimeline::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'status' => 'diproses',
            'title' => 'Penanganan Sedang Berlangsung oleh ' . $opd->short_name,
            'notes' => 'Tindak lanjut oleh ' . Auth::user()->name . ' (' . Auth::user()->position . '): ' . $request->process_notes,
            'actor_type' => 'opd',
        ]);

        return redirect()->route('opd.reports.show', $report->id)
            ->with('success', 'Status laporan berhasil diubah ke "Sedang Ditangani".');
    }

    public function resolve(Request $request, $id)
    {
        $opd = Auth::user()->opd;
        $report = Report::where('opd_id', $opd->id)->findOrFail($id);

        $request->validate([
            'resolution_notes' => ['required', 'string', 'min:10', 'max:1000'],
            'resolution_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ], [
            'resolution_notes.required' => 'Laporan hasil penanganan wajib diisi.',
            'resolution_notes.min' => 'Laporan hasil penanganan minimal 10 karakter.',
            'resolution_photo.image' => 'Bukti penyelesaian harus berupa file foto/gambar.',
        ]);

        $resolutionPhotoPath = null;
        if ($request->hasFile('resolution_photo')) {
            $resolutionPhotoPath = $request->file('resolution_photo')->store('resolutions', 'public');
            $report->resolution_photo_path = $resolutionPhotoPath;
        }

        $report->status = 'selesai';
        $report->opd_notes = $request->resolution_notes;
        $report->resolved_at = now();
        $report->save();

        ReportTimeline::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'status' => 'selesai',
            'title' => 'Laporan Berhasil Diselesaikan oleh ' . $opd->short_name,
            'notes' => 'Penyelesaian oleh ' . Auth::user()->name . ': ' . $request->resolution_notes,
            'actor_type' => 'opd',
        ]);

        return redirect()->route('opd.reports.show', $report->id)
            ->with('success', 'Selamat! Laporan berhasil diselesaikan dan dicatat dalam sistem.');
    }
}
