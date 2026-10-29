<?php

namespace Tests\Feature;

use App\Models\Opd;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SapaSumbawaFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_homepage_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Sapa Sumbawa');
        $response->assertSee('Suara Warga');
    }

    public function test_register_choice_page_loads()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Saya adalah Warga');
        $response->assertSee('Perangkat Daerah (OPD)');
    }

    public function test_warga_registration_flow()
    {
        $response = $this->post('/register/warga', [
            'name' => 'Warga Baru Sumbawa',
            'nik' => '5204012345678901',
            'phone' => '081234567899',
            'subdistrict' => 'Sumbawa',
            'address' => 'Jl. Kebayan No. 10',
            'email' => 'wargabaru@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/warga/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'wargabaru@gmail.com',
            'role' => 'warga',
            'nik' => '5204012345678901',
        ]);
    }

    public function test_opd_registration_flow()
    {
        $pupr = Opd::where('code', 'PUPR')->first();

        $response = $this->post('/register/opd', [
            'opd_id' => $pupr->id,
            'name' => 'Staf PUPR Baru',
            'nip' => '19900101 201501 1 001',
            'position' => 'Staf Bidang Bina Marga',
            'phone' => '081399887766',
            'email' => 'staf.pupr@sumbawakab.go.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/opd/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'staf.pupr@sumbawakab.go.id',
            'role' => 'opd',
            'opd_id' => $pupr->id,
        ]);
    }

    public function test_warga_can_create_report_and_admin_disposisi_to_opd_and_opd_resolves()
    {
        // 1. Citizen creates report
        $warga = User::where('role', 'warga')->first();
        $this->actingAs($warga);

        $createResponse = $this->post('/warga/laporan', [
            'title' => 'Jalan Berlubang di Depan Pasar Seketeng',
            'category' => 'Jalan Rusak & Jembatan',
            'subdistrict' => 'Sumbawa',
            'location_detail' => 'Depan gerbang utama Pasar Seketeng',
            'description' => 'Aspal amblas sedalam 20 cm menyebabkan kemacetan dan sering membuat pengendara motor terjatuh saat hujan.',
            'urgency' => 'mendesak',
        ]);

        $report = Report::where('title', 'Jalan Berlubang di Depan Pasar Seketeng')->first();
        $this->assertNotNull($report);
        $this->assertEquals('menunggu_verifikasi', $report->status);
        $createResponse->assertRedirect('/warga/laporan/' . $report->id);

        // 2. Admin logs in and performs disposisi to Dinas PUPR
        $admin = User::where('role', 'admin')->first();
        $pupr = Opd::where('code', 'PUPR')->first();
        $this->actingAs($admin);

        $disposisiResponse = $this->post('/admin/laporan/' . $report->id . '/disposisi', [
            'opd_id' => $pupr->id,
            'admin_notes' => 'Harap tim Dinas PUPR segera melakukan penambalan darurat.',
            'urgency' => 'mendesak',
        ]);

        $disposisiResponse->assertRedirect('/admin/laporan/' . $report->id);
        $report->refresh();
        $this->assertEquals('didisposisikan', $report->status);
        $this->assertEquals($pupr->id, $report->opd_id);

        // 3. OPD logs in and updates status to diproses
        $opdUser = User::where('role', 'opd')->where('opd_id', $pupr->id)->first();
        $this->actingAs($opdUser);

        $processResponse = $this->post('/opd/laporan/' . $report->id . '/proses', [
            'process_notes' => 'Tim teknisi Bina Marga telah turun ke lokasi membawa alat patching aspal.',
        ]);
        $processResponse->assertRedirect('/opd/laporan/' . $report->id);
        $report->refresh();
        $this->assertEquals('diproses', $report->status);

        // 4. OPD marks as completed (selesai)
        $resolveResponse = $this->post('/opd/laporan/' . $report->id . '/selesai', [
            'resolution_notes' => 'Penambalan aspal telah selesai 100%. Jalanan kini mulus dan aman dilewati warga.',
        ]);
        $resolveResponse->assertRedirect('/opd/laporan/' . $report->id);
        $report->refresh();
        $this->assertEquals('selesai', $report->status);
        $this->assertNotNull($report->resolved_at);

        // 5. Citizen tracks the report
        $trackResponse = $this->get('/lacak?ticket=' . $report->ticket_number);
        $trackResponse->assertStatus(200);
        $trackResponse->assertSee('Selesai');
        $trackResponse->assertSee('Dinas PUPR');
    }

    public function test_ai_chatbot_endpoint()
    {
        $response = $this->postJson('/api/ai/chat', [
            'message' => 'Ada jalan berlubang di Karang Dima, lapor ke dinas apa?',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonPath('suggested_category', 'Jalan Rusak & Jembatan');
    }
}
