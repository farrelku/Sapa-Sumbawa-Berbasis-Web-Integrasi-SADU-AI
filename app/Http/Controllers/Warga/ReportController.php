<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportTimeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $stats = [
            'total' => Report::where('user_id', $user->id)->count(),
            'menunggu' => Report::where('user_id', $user->id)->where('status', 'menunggu_verifikasi')->count(),
            'diproses' => Report::where('user_id', $user->id)->whereIn('status', ['didisposisikan', 'diproses'])->count(),
            'selesai' => Report::where('user_id', $user->id)->where('status', 'selesai')->count(),
        ];

        $recentReports = Report::with('opd')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('warga.dashboard', compact('stats', 'recentReports'));
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Report::with('opd')->where('user_id', $user->id)->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                  ->orWhere('ticket_number', 'like', $search);
            });
        }

        $reports = $query->paginate(10)->withQueryString();

        return view('warga.reports.index', compact('reports'));
    }

    public function create()
    {
        $categories = config('sumbawa.categories');
        $districts = config('sumbawa.districts');

        return view('warga.reports.create', compact('categories', 'districts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string'],
            'subdistrict' => ['required', 'string'],
            'location_detail' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string', 'min:20'],
            'urgency' => ['required', 'in:rendah,sedang,mendesak'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // Max 5MB
        ], [
            'title.required' => 'Judul laporan wajib diisi.',
            'category.required' => 'Pilih kategori pengaduan.',
            'subdistrict.required' => 'Pilih kecamatan lokasi kejadian.',
            'location_detail.required' => 'Detail lokasi/patokan wajib diisi.',
            'description.required' => 'Deskripsi laporan wajib diisi.',
            'description.min' => 'Deskripsi laporan minimal 20 karakter agar jelas dan mudah ditindaklanjuti.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 5MB.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('reports', 'public');
        }

        // Generate unique ticket number: SBW-YYYYMM-XXXX
        $ticketNumber = 'SBW-' . date('Ym') . '-' . strtoupper(Str::random(4));
        while (Report::where('ticket_number', $ticketNumber)->exists()) {
            $ticketNumber = 'SBW-' . date('Ym') . '-' . strtoupper(Str::random(4));
        }

        $report = Report::create([
            'ticket_number' => $ticketNumber,
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'category' => $validated['category'],
            'subdistrict' => $validated['subdistrict'],
            'location_detail' => $validated['location_detail'],
            'description' => $validated['description'],
            'urgency' => $validated['urgency'],
            'photo_path' => $photoPath,
            'status' => 'menunggu_verifikasi',
        ]);

        ReportTimeline::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'status' => 'menunggu_verifikasi',
            'title' => 'Laporan Berhasil Diajukan',
            'notes' => 'Laporan telah diterima oleh sistem Sapa Sumbawa dan sedang menunggu proses verifikasi serta disposisi dari Admin Pemkab.',
            'actor_type' => 'warga',
        ]);

        return redirect()->route('warga.reports.show', $report->id)
            ->with('success', 'Laporan Anda berhasil dikirim dengan Nomor Tiket #' . $ticketNumber . '! Petugas akan segera memverifikasinya.');
    }

    public function show($id)
    {
        $report = Report::with(['opd', 'timelines.user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('warga.reports.show', compact('report'));
    }
}
