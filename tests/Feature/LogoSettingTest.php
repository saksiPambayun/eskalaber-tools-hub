<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\LogoSetting;
use App\Models\Place;
use App\Models\Tool;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class LogoSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_and_regular_user_cannot_manage_logo(): void
    {
        $this->get('/admin/logo-setting')->assertRedirect('/login');
        $this->get('/toolsman/logo-setting')->assertRedirect('/login');

        $user = User::factory()->create(['role' => 'USER']);

        $this->actingAs($user)->get('/admin/logo-setting')->assertForbidden();
        $this->actingAs($user)->get('/toolsman/logo-setting')->assertForbidden();
    }

    public function test_admin_sees_default_logo_setting(): void
    {
        $admin = User::factory()->create(['role' => 'SUPERADMIN']);

        $this->actingAs($admin)
            ->get('/admin/logo-setting')
            ->assertOk()
            ->assertSee(LogoSetting::DEFAULT_TITLE)
            ->assertSee(LogoSetting::DEFAULT_SUBTITLE)
            ->assertSee('images/download.jpeg');
    }

    public function test_toolsman_can_update_texts_and_upload_logo(): void
    {
        $toolsman = User::factory()->create(['role' => 'TOOLSMAN']);
        $uploadedPath = null;

        try {
            $response = $this->actingAs($toolsman)->post('/toolsman/logo-setting/update', [
                'title' => 'Lab Sarpras',
                'subtitle' => 'Eskalaber Tools Hub 2026',
                'image' => UploadedFile::fake()->image('logo-baru.png', 120, 120),
            ]);

            $response->assertRedirect('/toolsman/logo-setting')
                ->assertSessionHas('success');

            $setting = LogoSetting::first();
            $uploadedPath = $setting->image;

            $this->assertSame('Lab Sarpras', $setting->title);
            $this->assertSame('Eskalaber Tools Hub 2026', $setting->subtitle);
            $this->assertStringStartsWith('uploads/logo/logo-', $setting->image);
            $this->assertFileExists(public_path($setting->image));
            $this->assertDatabaseHas('activity_logs', [
                'user_id' => $toolsman->id,
                'action' => 'UPDATE_LOGO_SETTING',
            ]);

            $this->actingAs($toolsman)
                ->get('/toolsman/logo-setting')
                ->assertOk()
                ->assertSee('Lab Sarpras')
                ->assertSee('Eskalaber Tools Hub 2026')
                ->assertSee($setting->image);
        } finally {
            if ($uploadedPath && file_exists(public_path($uploadedPath))) {
                unlink(public_path($uploadedPath));
            }
        }
    }

    public function test_update_rejects_invalid_file_and_empty_text(): void
    {
        $admin = User::factory()->create(['role' => 'SUPERADMIN']);

        $this->actingAs($admin)->post('/admin/logo-setting/update', [
            'title' => '',
            'subtitle' => 'Subteks',
            'image' => UploadedFile::fake()->create('script.txt', 10, 'text/plain'),
        ])->assertSessionHasErrors(['title', 'image']);

        $this->assertDatabaseCount('logo_settings', 0);
    }

    public function test_catalog_detail_uses_custom_logo_setting(): void
    {
        $tool = $this->makeTool();

        LogoSetting::create([
            'title' => 'Logo Kustom',
            'subtitle' => 'Subteks Kustom',
            'image' => 'uploads/logo/kustom.png',
        ]);

        $this->get('/catalog/detail/' . $tool->id)
            ->assertOk()
            ->assertSee('Logo Kustom')
            ->assertSee('Subteks Kustom')
            ->assertSee('uploads/logo/kustom.png');
    }

    public function test_catalog_detail_falls_back_to_default_logo(): void
    {
        $tool = $this->makeTool();

        $this->get('/catalog/detail/' . $tool->id)
            ->assertOk()
            ->assertSee('Sarana &amp; Prasarana', false)
            ->assertSee('images/download.jpeg');
    }

    private function makeTool(): Tool
    {
        return Tool::create([
            'name' => 'Alat Uji',
            'code' => 'TEST-' . uniqid(),
            'category_id' => Category::create(['name' => 'Kategori Uji'])->id,
            'type_id' => Type::create(['name' => 'Tipe Uji'])->id,
            'place_id' => Place::create(['name' => 'Gudang Uji'])->id,
            'stock' => 3,
            'status' => 'available',
        ]);
    }
}
