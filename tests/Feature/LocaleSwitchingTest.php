<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleSwitchingTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_default_language_is_persian_and_rtl(): void
    {
        config(['app.locale' => 'fa']);

        $this->get('/')
            ->assertOk()
            ->assertSee('dir="rtl"', false)
            ->assertSee('lang="fa"', false)
            ->assertSee('ورود')
            ->assertSee('فارسی');
    }

    public function test_the_language_can_be_switched_to_english(): void
    {
        $this->get('/lang/en')->assertRedirect('/');

        $this->get('/')
            ->assertOk()
            ->assertSee('dir="ltr"', false)
            ->assertSee('Log in')
            ->assertSee('English');
    }

    public function test_the_selected_language_persists_for_the_admin_panel(): void
    {
        $this->get('/lang/fa')->assertRedirect('/');

        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('dir="rtl"', false)
            ->assertSee('فارسی');
    }

    public function test_switching_to_an_unsupported_language_is_rejected(): void
    {
        $this->get('/lang/fr')->assertNotFound();
    }

    public function test_the_user_menu_offers_both_languages(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/lang/en')->assertRedirect('/');

        $this->get('/admin')
            ->assertOk()
            ->assertSee('فارسی')
            ->assertSee('/lang/fa');
    }

    public function test_the_missing_short_link_page_is_localized(): void
    {
        $this->get('/lang/en')->assertRedirect('/');

        $this->get('/ZZZZZZ')
            ->assertNotFound()
            ->assertSee("This link isn't available");
    }
}
