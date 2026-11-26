<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_and_toolsman_can_open_log_and_export_csv_with_date_filter(): void
    {
        $admin = User::factory()->create(['role' => 'SUPERADMIN', 'name' => 'Admin Laporan']);
        $toolsman = User::factory()->create(['role' => 'TOOLSMAN']);
        $user = User::factory()->create(['role' => 'USER']);

        $this->actingAs($user)->get(route('activity_logs.index'))->assertForbidden();

        $this->actingAs($toolsman)->get(route('activity_logs.index'))
            ->assertOk()
            ->assertSee('Riwayat Aktivitas');

        $this->makeLog($admin, 'approve_loan', 'Disetujui di Januari', '2026-01-15 10:00:00');
        $this->makeLog($admin, 'reject_loan', 'Ditolak di Februari', '2026-02-15 10:00:00');

        $filters = [
            'from' => '2026-01-01',
            'to' => '2026-01-31',
        ];

        $this->actingAs($admin)->get(route('activity_logs.index', $filters))
            ->assertOk()
            ->assertSee('Disetujui di Januari')
            ->assertDontSee('Ditolak di Februari');

        $response = $this->actingAs($admin)->get(route('activity_logs.export-csv', $filters));
        $response->assertOk();

        $csv = $response->streamedContent();
        $this->assertStringContainsString('No,Pengguna,Aksi,Deskripsi,"IP Alamat",Waktu', $csv);
        $this->assertStringContainsString('Admin Laporan', $csv);
        $this->assertStringContainsString('Setujui Pinjaman', $csv);
        $this->assertStringContainsString('Disetujui di Januari', $csv);
        $this->assertStringNotContainsString('Ditolak di Februari', $csv);
    }

    private function makeLog(User $user, string $action, string $description, string $createdAt): ActivityLog
    {
        $log = new ActivityLog([
            'user_id' => $user->id,
            'action' => $action,
            'description' => $description,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Tests',
        ]);
        $log->created_at = $createdAt;
        $log->updated_at = $createdAt;
        $log->save();

        return $log;
    }
}
