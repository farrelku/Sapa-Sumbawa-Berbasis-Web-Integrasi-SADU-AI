<?php

namespace App\Http\Controllers;

use App\Models\Opd;
use App\Models\Report;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Report::count(),
            'selesai' => Report::where('status', 'selesai')->count(),
            'diproses' => Report::whereIn('status', ['didisposisikan', 'diproses'])->count(),
            'menunggu' => Report::where('status', 'menunggu_verifikasi')->count(),
        ];

        $latestReports = Report::with(['opd'])
            ->latest()
            ->take(6)
            ->get();

        $opds = Opd::withCount('reports')
            ->where('is_active', true)
            ->get();

        $categories = config('sumbawa.categories');
        $districts = config('sumbawa.districts');

        return view('home', compact('stats', 'latestReports', 'opds', 'categories', 'districts'));
    }

    public function track(Request $request)
    {
        $ticket = trim($request->input('ticket'));
        $report = null;

        if ($ticket) {
            $report = Report::with(['opd', 'timelines.user'])
                ->where('ticket_number', $ticket)
                ->first();
        }

        return view('track', compact('report', 'ticket'));
    }

    public function publicReports(Request $request)
    {
        $query = Report::with(['opd'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('subdistrict')) {
            $query->where('subdistrict', $request->subdistrict);
        }

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('ticket_number', 'like', $searchTerm)
                  ->orWhere('location_detail', 'like', $searchTerm);
            });
        }

        $reports = $query->paginate(9)->withQueryString();
        $categories = config('sumbawa.categories');
        $districts = config('sumbawa.districts');

        return view('reports.public-index', compact('reports', 'categories', 'districts'));
    }

    public function showPublicReport($ticket_number)
    {
        $report = Report::with(['opd', 'timelines.user'])
            ->where('ticket_number', $ticket_number)
            ->firstOrFail();

        return view('reports.public-show', compact('report'));
    }

    public function opds()
    {
        $opds = Opd::withCount('reports')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('opds', compact('opds'));
    }
}
