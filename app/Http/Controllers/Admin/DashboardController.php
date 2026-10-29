<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Opd;
use App\Models\Report;
use App\Models\ReportTimeline;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total' => Report::count(),
            'menunggu' => Report::where('status', 'menunggu_verifikasi')->count(),
            'didisposisikan' => Report::where('status', 'didisposisikan')->count(),
            'diproses' => Report::where('status', 'diproses')->count(),
            'selesai' => Report::where('status', 'selesai')->count(),
            'ditolak' => Report::where('status', 'ditolak')->count(),
            'warga_count' => User::where('role', 'warga')->count(),
            'opd_users_count' => User::where('role', 'opd')->count(),
        ];

        // Laporan darurat / belum didisposisikan
        $pendingReports = Report::with(['user'])
            ->where('status', 'menunggu_verifikasi')
            ->latest()
            ->take(6)
            ->get();

        // Statistik per OPD
        $opds = Opd::withCount([
            'reports',
            'reports as pending_reports_count' => function ($q) {
                $q->whereIn('status', ['didisposisikan', 'diproses']);
            },
            'reports as completed_reports_count' => function ($q) {
                $q->where('status', 'selesai');
            },
        ])->get();

        return view('admin.dashboard', compact('stats', 'pendingReports', 'opds'));
    }

    public function reports(Request $request)
    {
        $query = Report::with(['user', 'opd'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('opd_id')) {
            $query->where('opd_id', $request->opd_id);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        if ($request->filled('subdistrict')) {
            $query->where('subdistrict', $request->subdistrict);
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
        $opds = Opd::where('is_active', true)->orderBy('name')->get();
        $categories = config('sumbawa.categories');
        $districts = config('sumbawa.districts');

        return view('admin.reports.index', compact('reports', 'opds', 'categories', 'districts'));
    }

    public function showReport($id)
    {
        $report = Report::with(['user', 'opd', 'assignedBy', 'timelines.user'])->findOrFail($id);
        $opds = Opd::where('is_active', true)->orderBy('name')->get();

        return view('admin.reports.show', compact('report', 'opds'));
    }

    public function disposisi(Request $request, $id)
    {
        $request->validate([
            'opd_id' => ['required', 'exists:opds,id'],
            'admin_notes' => ['required', 'string', 'max:1000'],
            'urgency' => ['nullable', 'in:rendah,sedang,mendesak'],
        ], [
            'opd_id.required' => 'Pilih OPD tujuan disposisi laporan.',
            'admin_notes.required' => 'Catatan instruksi untuk OPD wajib diisi.',
        ]);

        $report = Report::findOrFail($id);
        $targetOpd = Opd::findOrFail($request->opd_id);

        $report->opd_id = $targetOpd->id;
        $report->assigned_by_id = Auth::id();
        $report->admin_notes = $request->admin_notes;
        $report->status = 'didisposisikan';
        $report->dispatched_at = now();

        if ($request->filled('urgency')) {
            $report->urgency = $request->urgency;
        }

        $report->save();

        ReportTimeline::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'status' => 'didisposisikan',
            'title' => 'Laporan Didisposisikan ke ' . $targetOpd->short_name,
            'notes' => 'Admin Pemkab meneruskan laporan kepada ' . $targetOpd->name . '. Catatan instruksi: "' . $request->admin_notes . '"',
            'actor_type' => 'admin',
        ]);

        return redirect()->route('admin.reports.show', $report->id)
            ->with('success', 'Laporan berhasil didisposisikan ke ' . $targetOpd->name . '!');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan laporan wajib diisi.',
            'rejection_reason.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $report = Report::findOrFail($id);
        $report->status = 'ditolak';
        $report->rejection_reason = $request->rejection_reason;
        $report->save();

        ReportTimeline::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'status' => 'ditolak',
            'title' => 'Laporan Ditolak oleh Admin',
            'notes' => 'Alasan penolakan: ' . $request->rejection_reason,
            'actor_type' => 'admin',
        ]);

        return redirect()->route('admin.reports.show', $report->id)
            ->with('info', 'Laporan telah ditandai sebagai ditolak.');
    }

    public function opds()
    {
        $opds = Opd::withCount(['reports', 'users'])->get();
        return view('admin.opds.index', compact('opds'));
    }

    public function users(Request $request)
    {
        $query = User::with('opd')->latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('email', 'like', $search)
                  ->orWhere('nip', 'like', $search)
                  ->orWhere('nik', 'like', $search);
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }
}
