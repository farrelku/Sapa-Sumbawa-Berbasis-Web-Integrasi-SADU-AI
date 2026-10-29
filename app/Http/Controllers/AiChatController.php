<?php

namespace App\Http\Controllers;

use App\Models\Opd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    public function index()
    {
        $opds = Opd::where('is_active', true)->get();
        return view('ai-chat', compact('opds'));
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $message = trim($request->input('message'));
        $history = $request->input('history', []);

        // 1. Cek Google Gemini API Key
        $geminiKey = config('services.gemini.key') ?? env('GEMINI_API_KEY');
        if (!empty($geminiKey) && !str_starts_with($geminiKey, 'gsk_')) {
            try {
                $response = $this->callGeminiApi($message, $geminiKey, $history);
                if ($response) {
                    $meta = $this->detectMeta($message);
                    return response()->json([
                        'success' => true,
                        'reply' => $response,
                        'suggested_category' => $meta['category'],
                        'target_opd' => $meta['opd'],
                        'source' => 'Google Gemini 3.8 Flash',
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('Gemini API call failed: ' . $e->getMessage());
            }
        }

        // 2. Cek Groq API Key
        $groqKey = config('services.groq.key') ?? env('GROQ_API_KEY');
        if (!empty($groqKey)) {
            try {
                $response = $this->callGroqApi($message, $groqKey, $history);
                if ($response) {
                    $meta = $this->detectMeta($message);
                    return response()->json([
                        'success' => true,
                        'reply' => $response,
                        'suggested_category' => $meta['category'],
                        'target_opd' => $meta['opd'],
                        'source' => 'Groq AI (Llama/GPT)',
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('Groq API call failed: ' . $e->getMessage());
            }
        }


        // 3. Cek OpenAI API Key
        $openaiKey = config('services.openai.key') ?? env('OPENAI_API_KEY');
        if (!empty($openaiKey) && !str_starts_with($openaiKey, 'gsk_')) {
            try {
                $response = $this->callOpenAiApi($message, $openaiKey, $history);
                if ($response) {
                    $meta = $this->detectMeta($message);
                    return response()->json([
                        'success' => true,
                        'reply' => $response,
                        'suggested_category' => $meta['category'],
                        'target_opd' => $meta['opd'],
                        'source' => 'OpenAI',
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('OpenAI API call failed: ' . $e->getMessage());
            }
        }

        // 4. Fallback jika offline / API bermasalah
        $localReply = $this->generateLocalAiResponse($message);

        return response()->json([
            'success' => true,
            'reply' => $localReply['reply'],
            'suggested_category' => $localReply['category'] ?? null,
            'target_opd' => $localReply['opd'] ?? null,
            'source' => 'Asisten Cerdas Sumbawa',
        ]);
    }

    protected function callGroqApi(string $message, string $apiKey, array $history): ?string
    {
        $url = "https://api.groq.com/openai/v1/chat/completions";

        $systemPrompt = "Anda adalah Asisten AI Resmi dari platform 'Sapa Sumbawa' (Sistem Aspirasi & Pengaduan Warga Kabupaten Sumbawa, NTB).
Tugas Anda membantu warga Sumbawa yang ingin melapor masalah fasilitas publik (jalan rusak, jembatan, sampah, lampu penerangan jalan/PJU mati, drainase tersumbat, ketertiban umum, pelayanan dinas lambat, adminduk KTP/KK, dll) atau bertanya seputar pelayanan publik di Kabupaten Sumbawa.
Prinsip jawaban:
1. Ramah, santun, solutif, gunakan bahasa Indonesia yang baik dengan sentuhan lokal Samawa.
2. Jika warga mengeluhkan masalah tertentu, sebutkan dinas/OPD yang berwenang menanganinya di Pemkab Sumbawa:
   - Jalan & Jembatan rusak, drainase: Dinas Pekerjaan Umum dan Penataan Ruang (PUPR)
   - Lampu jalan mati (PJU), marka jalan, rambu lalu lintas: Dinas Perhubungan (DISHUB)
   - Tumpukan sampah liar, kebersihan taman, pencemaran: Dinas Lingkungan Hidup (DLH)
   - Ketertiban umum, penertiban PKL, gangguan ketentraman: Satpol PP
   - Pelayanan puskesmas, obat, kesehatan: Dinas Kesehatan (DINKES)
   - Administrasi KTP-el, KK, akta, IKD: Dinas Kependudukan & Pencatatan Sipil (DUKCAPIL)
3. Beritahu alur: Warga buat laporan di portal Sapa Sumbawa -> Admin Pemkab memverifikasi dan mendisposisikan ke dinas terkait -> Tim dinas turun ke lapangan -> Laporan ditandai selesai beserta bukti foto hasil pekerjaan.
4. Buat jawaban yang rapi dan mudah dibaca.";

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        foreach ($history as $h) {
            if (isset($h['role'], $h['content'])) {
                $messages[] = ['role' => $h['role'], 'content' => $h['content']];
            }
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        $response = Http::withoutVerifying()
            ->withToken($apiKey)
            ->timeout(20)
            ->post($url, [
                'model' => 'openai/gpt-oss-120b',
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 900,
            ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['choices'][0]['message']['content'] ?? null;
        }

        // Coba model fallback Groq jika model di atas sibuk
        if ($response->status() !== 200) {
            $fallbackResp = Http::withoutVerifying()
                ->withToken($apiKey)
                ->timeout(15)
                ->post($url, [
                    'model' => 'qwen/qwen3.8-27b',
                    'messages' => $messages,
                    'temperature' => 0.7,
                    'max_tokens' => 800,
                ]);

            if ($fallbackResp->successful()) {
                $data = $fallbackResp->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }
        }

        return null;
    }

    protected function callGeminiApi(string $message, string $apiKey, array $history): ?string
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent?key=" . $apiKey;

        $systemPrompt = "Anda adalah Asisten AI Resmi dari platform 'Sapa Sumbawa' (Sistem Aspirasi & Pengaduan Warga Kabupaten Sumbawa, NTB).
Tugas Anda membantu warga Sumbawa yang ingin melapor masalah fasilitas publik (jalan rusak, jembatan, sampah, lampu penerangan jalan/PJU mati, drainase tersumbat, ketertiban umum, pelayanan dinas lambat, adminduk KTP/KK, dll) atau bertanya seputar pelayanan publik di Kabupaten Sumbawa.
Prinsip jawaban:
1. Ramah, santun, solutif, gunakan bahasa Indonesia yang baik dengan sentuhan lokal Samawa (misal salam 'Salam Samawa!').
2. Jika warga mengeluhkan masalah tertentu, sebutkan dinas/OPD yang berwenang menanganinya di Pemkab Sumbawa:
   - Jalan & Jembatan rusak, drainase: Dinas Pekerjaan Umum dan Penataan Ruang (PUPR)
   - Lampu jalan mati (PJU), marka jalan, rambu lalu lintas: Dinas Perhubungan (DISHUB)
   - Tumpukan sampah liar, kebersihan taman, pencemaran: Dinas Lingkungan Hidup (DLH)
   - Ketertiban umum, penertiban PKL, gangguan ketentraman: Satpol PP
   - Pelayanan puskesmas, obat, kesehatan: Dinas Kesehatan (DINKES)
   - Administrasi KTP-el, KK, akta, IKD: Dinas Kependudukan & Pencatatan Sipil (DUKCAPIL)
3. Beritahu alur: Warga buat laporan di portal Sapa Sumbawa -> Admin Pemkab memverifikasi dan mendisposisikan ke dinas terkait -> Tim dinas turun ke lapangan -> Laporan ditandai selesai beserta bukti foto hasil pekerjaan.
4. Buat jawaban yang rapi, informatif, dan mudah dibaca.";

        $contents = [];
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $systemPrompt . "\n\nPertanyaan Warga: " . $message]]
        ];

        $response = Http::withoutVerifying()->timeout(15)->post($url, [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 900,
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        }

        return null;
    }


    protected function callOpenAiApi(string $message, string $apiKey, array $history): ?string
    {
        $url = "https://api.openai.com/v1/chat/completions";

        $response = Http::withoutVerifying()->withToken($apiKey)->timeout(15)->post($url, [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => "Anda adalah Asisten AI Resmi Sapa Sumbawa."],
                ['role' => 'user', 'content' => $message]
            ],
            'max_tokens' => 600,
            'temperature' => 0.7,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['choices'][0]['message']['content'] ?? null;
        }

        return null;
    }

    protected function detectMeta(string $message): array
    {
        $lower = strtolower($message);

        if (str_contains($lower, 'jalan') || str_contains($lower, 'lubang') || str_contains($lower, 'jembatan') || str_contains($lower, 'aspal')) {
            return [
                'category' => 'Jalan Rusak & Jembatan',
                'opd' => 'Dinas PUPR Kabupaten Sumbawa',
            ];
        }

        if (str_contains($lower, 'sampah') || str_contains($lower, 'limbah') || str_contains($lower, 'tps') || str_contains($lower, 'kebersihan')) {
            return [
                'category' => 'Kebersihan & Sampah',
                'opd' => 'Dinas Lingkungan Hidup (DLH)',
            ];
        }

        if (str_contains($lower, 'lampu') || str_contains($lower, 'pju') || str_contains($lower, 'rambu') || str_contains($lower, 'marka') || str_contains($lower, 'parkir')) {
            return [
                'category' => 'Penerangan Jalan (PJU)',
                'opd' => 'Dinas Perhubungan (DISHUB)',
            ];
        }

        if (str_contains($lower, 'ktp') || str_contains($lower, 'kk') || str_contains($lower, 'akta') || str_contains($lower, 'adminduk')) {
            return [
                'category' => 'Pelayanan Adminduk (KTP/KK)',
                'opd' => 'Dinas Dukcapil Kab. Sumbawa',
            ];
        }

        if (str_contains($lower, 'puskesmas') || str_contains($lower, 'obat') || str_contains($lower, 'sehat') || str_contains($lower, 'dokter')) {
            return [
                'category' => 'Pelayanan Kesehatan & Puskesmas',
                'opd' => 'Dinas Kesehatan (DINKES)',
            ];
        }

        return [
            'category' => null,
            'opd' => null,
        ];
    }

    protected function generateLocalAiResponse(string $message): array
    {
        $lower = strtolower($message);

        if (str_contains($lower, 'jalan') || str_contains($lower, 'lubang') || str_contains($lower, 'jembatan') || str_contains($lower, 'aspal') || str_contains($lower, 'rusak')) {
            return [
                'category' => 'Jalan Rusak & Jembatan',
                'opd' => 'Dinas Pekerjaan Umum dan Penataan Ruang (PUPR)',
                'reply' => "Halo Warga Sumbawa! Untuk pengaduan mengenai **jalan rusak, aspal berlubang, atau jembatan retak**, laporan Anda akan ditangani oleh **Dinas PUPR Kabupaten Sumbawa** (Bidang Bina Marga).\n\n" .
                    "📌 **Langkah-langkah melapor:**\n" .
                    "1. Masuk ke menu **Buat Laporan** di dashboard Anda.\n" .
                    "2. Pilih kategori: `Jalan Rusak & Jembatan`.\n" .
                    "3. Pilih kecamatan (misal: Sumbawa, Labuhan Badas, Moyo Hilir, dll).\n" .
                    "4. Berikan patokan detail dan unggah foto kondisi jalan berlubang.\n" .
                    "5. Pilih urgensi 'Mendesak' jika membahayakan pengendara motor.\n\n" .
                    "Setelah Anda kirim, Admin Pemkab akan memverifikasi dan meneruskannya (disposisi) ke Dinas PUPR agar segera dijadwalkan penambalan/perbaikan!",
            ];
        }

        if (str_contains($lower, 'sampah') || str_contains($lower, 'bau') || str_contains($lower, 'kotor') || str_contains($lower, 'kebersihan') || str_contains($lower, 'limbah') || str_contains($lower, 'tps')) {
            return [
                'category' => 'Kebersihan & Sampah',
                'opd' => 'Dinas Lingkungan Hidup (DLH)',
                'reply' => "Terima kasih atas kepedulian Anda terhadap kebersihan Tana Samawa! Laporan terkait **tumpukan sampah liar, TPS meluap, atau pencemaran lingkungan** adalah wewenang **Dinas Lingkungan Hidup (DLH) Kabupaten Sumbawa**.\n\n" .
                    "🌱 **Tips Melapor:**\n" .
                    "• Foto titik lokasi penumpukan sampah secara jelas.\n" .
                    "• Tuliskan nama jalan, desa/kelurahan, dan patokan lokasi terdekat.\n" .
                    "• DLH akan mengerahkan armada truk pengangkut sampah setelah disposisi dari Admin diterima.",
            ];
        }

        return [
            'reply' => "Salam Samawa! 👋 Ada keluhan seputar fasilitas publik di Kabupaten Sumbawa (jalan rusak, lampu jalan padam, sampah, atau pelayanan dinas) yang ingin Anda tanyakan?",
        ];
    }
}
