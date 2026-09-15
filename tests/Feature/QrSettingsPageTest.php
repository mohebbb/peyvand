<?php

namespace Tests\Feature;

use App\Filament\Resources\ShortLinks\Pages\ListShortLinks;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class QrSettingsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_qr_defaults_page_renders_with_a_live_preview(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/qr-settings')
            ->assertOk()
            ->assertSee('QR code defaults')
            ->assertSee('data:image/svg+xml;base64', false);
    }

    public function test_the_qr_defaults_page_is_linked_from_the_navigation(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin')
            ->assertOk()
            ->assertSee('/admin/qr-settings');
    }

    public function test_the_short_links_table_renders_with_the_qr_actions(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(ListShortLinks::class)
            ->assertSuccessful();
    }
}
