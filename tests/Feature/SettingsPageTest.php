<?php

use App\Enums\Role;
use App\Models\Locale;
use App\Models\Permission;
use App\Models\Role as RoleModel;
use App\Models\User;
use App\Support\AppearancePalette;
use Database\Data\Locales;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->withoutVite();

    foreach (Role::values() as $name) {
        RoleModel::create(['name' => $name]);
    }

    foreach (Locales::list() as $locale) {
        Locale::create($locale);
    }
});

it('redirects guests to login', function () {
    $this->get('/settings')->assertRedirect(route('login'));
});

it('shows the appearance tab to a super admin', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::SuperAdmin->value);

    $this->actingAs($user)
        ->get('/settings')
        ->assertOk()
        ->assertSeeText(__('settings.title'))
        ->assertSeeText(__('settings.subtitle'))
        ->assertSeeText(__('settings.tabs.appearance'))
        ->assertSeeText(__('nav.items.settings'))
        ->assertSee('data-settings-panel="appearance"', false)
        ->assertDontSee('data-settings-tab="general"', false)
        ->assertDontSee('data-settings-panel="general"', false);
});

it('keeps appearance open for a general or unknown tab query', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::SuperAdmin->value);

    $this->actingAs($user)
        ->get('/settings?tab=general')
        ->assertOk()
        ->assertSee('data-settings-panel="appearance"', false)
        ->assertDontSee('data-settings-panel="general"', false)
        ->assertDontSee('data-settings-panel="appearance" hidden', false);

    $this->actingAs($user)
        ->get('/settings?tab=other')
        ->assertOk()
        ->assertSee('data-settings-panel="appearance"', false)
        ->assertDontSee('data-settings-panel="general"', false);
});

it('denies users without setting.edit', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::Doctor->value);

    $this->actingAs($user)
        ->get('/settings')
        ->assertNotFound();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertDontSeeText(__('nav.items.settings'));
});

it('shows light and dark sidebar styles without changing the menu', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::SuperAdmin->value);

    $this->actingAs($user)
        ->get('/settings')
        ->assertOk()
        ->assertSeeText(__('settings.style.title'))
        ->assertSeeText(__('settings.style.light'))
        ->assertSeeText(__('settings.style.dark'))
        ->assertSeeText(__('settings.style.light_caption'))
        ->assertSeeText(__('settings.style.dark_caption'))
        ->assertDontSeeText('Compacto')
        ->assertDontSeeText('Minimalista')
        ->assertSee('name="sidebar_style" value="light" checked', false)
        ->assertDontSee('name="sidebar_style" value="dark" checked', false)
        ->assertSeeText(__('settings.preview.title'))
        ->assertSeeText(__('settings.preview.subtitle'))
        ->assertSee('data-settings-preview', false)
        ->assertSee('data-sidebar-style="light"', false)
        ->assertSee('settings-skeleton', false)
        ->assertSeeText(__('settings.sidebar_color.title'))
        ->assertSeeText(__('settings.sidebar_color.subtitle'))
        ->assertSeeText(__('settings.sidebar_color.none'))
        ->assertSee('name="sidebar_color" value="" checked', false)
        ->assertDontSee('name="sidebar_color" value="#3585fc" checked', false)
        ->assertDontSee('data-sidebar-color=', false)
        ->assertSeeText(__('settings.accent_color.title'))
        ->assertSeeText(__('settings.accent_color.subtitle'))
        ->assertSee('name="accent_color" value="" checked', false)
        ->assertDontSee('name="accent_color" value="#3886fd" checked', false)
        ->assertDontSee('data-accent-color=', false)
        ->assertSee('data-menu-styles="light"', false)
        ->assertDontSee('--menu-bg:', false)
        ->assertDontSee('--primary-rgb:', false)
        ->assertSeeText(__('common.brand'))
        ->assertSee('brand-mark', false)
        ->assertDontSee('brand-logos/logo.png', false)
        ->assertSee('assets/js/appearance.js', false)
        ->assertSee('clearAppearanceSwitcher()', false)
        ->assertSee('applyAppearance(', false)
        ->assertSeeText(__('settings.save'))
        ->assertSee('action="'.route('settings.appearance.update').'"', false)
        ->assertSee('data-settings-save', false);

    expect(file_get_contents(public_path('assets/js/appearance.js')))
        ->toContain("localStorage.removeItem('sashMenu')")
        ->toContain("localStorage.removeItem('sashverticalstyles')");
});

it('applies a stored dark style to the real menu', function () {
    DB::table('settings')->insert([
        'key' => 'appearance.sidebar_style',
        'value' => 'dark',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $user = User::factory()->create();
    $user->assignRole(Role::SuperAdmin->value);

    $this->actingAs($user)
        ->get('/settings')
        ->assertOk()
        ->assertSee('name="sidebar_style" value="dark" checked', false)
        ->assertDontSee('name="sidebar_style" value="light" checked', false)
        ->assertSee('data-sidebar-style="dark"', false)
        ->assertSee('data-menu-styles="dark"', false)
        ->assertDontSee('--menu-bg:', false);
});

it('paints the real menu when a sidebar color is stored', function () {
    DB::table('settings')->insert([
        'key' => 'appearance.sidebar_color',
        'value' => '#273249',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $user = User::factory()->create();
    $user->assignRole(Role::SuperAdmin->value);

    $this->actingAs($user)
        ->get('/settings')
        ->assertOk()
        ->assertSee('name="sidebar_color" value="#273249" checked', false)
        ->assertDontSee('name="sidebar_color" value="" checked', false)
        ->assertSee('data-sidebar-color="#273249"', false)
        ->assertSee('--settings-preview-sidebar: #273249', false)
        ->assertSee('--settings-preview-ink: '.AppearancePalette::ink('#273249'), false)
        ->assertSee('data-sidebar-style="light"', false)
        ->assertSee('data-menu-styles="light"', false)
        ->assertSee('--menu-bg: #273249', false)
        ->assertSee('--menu-prime-color: '.AppearancePalette::ink('#273249'), false)
        ->assertSee('data-sidebar-ink="light"', false);
});

it('paints the accent on the real pages when an accent color is stored', function () {
    DB::table('settings')->insert([
        'key' => 'appearance.accent_color',
        'value' => '#6c4db5',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $user = User::factory()->create();
    $user->assignRole(Role::SuperAdmin->value);

    $this->actingAs($user)
        ->get('/settings')
        ->assertOk()
        ->assertSee('name="accent_color" value="#6c4db5" checked', false)
        ->assertDontSee('name="accent_color" value="" checked', false)
        ->assertSee('data-accent-color="#6c4db5"', false)
        ->assertSee('--settings-preview-accent: #6c4db5', false)
        ->assertSee('name="sidebar_color" value="" checked', false)
        ->assertSee('data-menu-styles="light"', false)
        ->assertSee('--primary-rgb: '.AppearancePalette::rgb('#6c4db5'), false)
        ->assertDontSee('--menu-bg:', false);
});

it('ignores an accent color outside the palette', function () {
    DB::table('settings')->insert([
        'key' => 'appearance.accent_color',
        'value' => '#ff00ff',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $user = User::factory()->create();
    $user->assignRole(Role::SuperAdmin->value);

    $this->actingAs($user)
        ->get('/settings')
        ->assertOk()
        ->assertDontSee('data-accent-color=', false)
        ->assertDontSee('value="#ff00ff"', false)
        ->assertSee('name="accent_color" value="" checked', false)
        ->assertSee('data-menu-styles="light"', false)
        ->assertDontSee('--primary-rgb:', false);
});

it('ignores a sidebar color outside the palette', function () {
    DB::table('settings')->insert([
        'key' => 'appearance.sidebar_color',
        'value' => '#ff00ff',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $user = User::factory()->create();
    $user->assignRole(Role::SuperAdmin->value);

    $this->actingAs($user)
        ->get('/settings')
        ->assertOk()
        ->assertDontSee('data-sidebar-color=', false)
        ->assertDontSee('value="#ff00ff"', false)
        ->assertSee('name="sidebar_color" value="" checked', false)
        ->assertSee('data-menu-styles="light"', false)
        ->assertDontSee('--menu-bg:', false);
});

it('allows a user with setting.edit', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::findOrCreate('setting.edit', 'web'));

    $this->actingAs($user)
        ->get('/settings')
        ->assertOk()
        ->assertSeeText(__('settings.tabs.appearance'));
});

it('redirects guests when saving appearance', function () {
    $this->put(route('settings.appearance.update'), [
        'sidebar_style' => 'dark',
        'sidebar_color' => '',
        'accent_color' => '',
    ])->assertRedirect(route('login'));
});

it('denies saving appearance without setting.edit', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::Doctor->value);

    $this->actingAs($user)
        ->put(route('settings.appearance.update'), [
            'sidebar_style' => 'dark',
            'sidebar_color' => '#273249',
            'accent_color' => '#6c4db5',
        ])
        ->assertNotFound();

    expect(DB::table('settings')->where('key', 'like', 'appearance.%')->count())->toBe(0);
});

it('stores a valid appearance and applies it on the next page', function () {
    foreach ([
        'default_locale' => 'es',
        'appointments.allow_multiple_per_day' => '1',
        'appointments.min_days_between' => '2',
        'appearance.sidebar_style' => 'light',
    ] as $key => $value) {
        DB::table('settings')->insert([
            'key' => $key,
            'value' => $value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    $user = User::factory()->create();
    $user->assignRole(Role::SuperAdmin->value);

    $this->actingAs($user)
        ->put(route('settings.appearance.update'), [
            'sidebar_style' => 'DARK',
            'sidebar_color' => '#273249',
            'accent_color' => '#6C4DB5',
        ])
        ->assertRedirect(route('settings.index', ['tab' => 'appearance']))
        ->assertSessionHas('success', __('settings.flash.saved'));

    expect(DB::table('settings')->where('key', 'appearance.sidebar_style')->value('value'))->toBe('dark')
        ->and(DB::table('settings')->where('key', 'appearance.sidebar_style')->count())->toBe(1)
        ->and(DB::table('settings')->where('key', 'appearance.sidebar_color')->value('value'))->toBe('#273249')
        ->and(DB::table('settings')->where('key', 'appearance.accent_color')->value('value'))->toBe('#6c4db5')
        ->and(DB::table('settings')->where('key', 'default_locale')->value('value'))->toBe('es')
        ->and(DB::table('settings')->where('key', 'appointments.allow_multiple_per_day')->value('value'))->toBe('1')
        ->and(DB::table('settings')->where('key', 'appointments.min_days_between')->value('value'))->toBe('2');

    $this->actingAs($user)
        ->get('/settings?tab=appearance')
        ->assertOk()
        ->assertSeeText(__('settings.flash.saved'))
        ->assertSee('name="sidebar_style" value="dark" checked', false)
        ->assertSee('name="sidebar_color" value="#273249" checked', false)
        ->assertSee('name="accent_color" value="#6c4db5" checked', false)
        ->assertSee('data-sidebar-style="dark"', false)
        ->assertSee('data-menu-styles="dark"', false)
        ->assertSee('--menu-bg: #273249', false)
        ->assertSee('--primary-rgb: '.AppearancePalette::rgb('#6c4db5'), false);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('data-menu-styles="dark"', false)
        ->assertSee('--menu-bg: #273249', false)
        ->assertSee('--menu-prime-color: '.AppearancePalette::ink('#273249'), false)
        ->assertSee('--primary-rgb: '.AppearancePalette::rgb('#6c4db5'), false)
        ->assertSee('clearAppearanceSwitcher()', false);
});

it('stores none as an empty color', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::SuperAdmin->value);

    $this->actingAs($user)
        ->put(route('settings.appearance.update'), [
            'sidebar_style' => 'light',
            'sidebar_color' => '',
            'accent_color' => '',
        ])
        ->assertRedirect(route('settings.index', ['tab' => 'appearance']));

    expect(DB::table('settings')->where('key', 'appearance.sidebar_color')->value('value'))->toBe('')
        ->and(DB::table('settings')->where('key', 'appearance.accent_color')->value('value'))->toBe('');

    $this->actingAs($user)
        ->get('/settings')
        ->assertOk()
        ->assertSee('name="sidebar_style" value="light" checked', false)
        ->assertSee('name="sidebar_color" value="" checked', false)
        ->assertSee('name="accent_color" value="" checked', false)
        ->assertDontSee('data-sidebar-color=', false)
        ->assertDontSee('data-accent-color=', false)
        ->assertSee('data-menu-styles="light"', false)
        ->assertDontSee('--menu-bg:', false)
        ->assertDontSee('--primary-rgb:', false);
});

it('keeps the login page on the template default', function () {
    foreach ([
        'appearance.sidebar_style' => 'dark',
        'appearance.sidebar_color' => '#273249',
        'appearance.accent_color' => '#6c4db5',
    ] as $key => $value) {
        DB::table('settings')->insert([
            'key' => $key,
            'value' => $value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    $this->get('/login')
        ->assertOk()
        ->assertDontSee('data-menu-styles=', false)
        ->assertDontSee('--menu-bg', false)
        ->assertDontSee('--primary-rgb', false)
        ->assertDontSee('#273249', false)
        ->assertDontSee('#6c4db5', false)
        ->assertSeeText(__('common.brand'))
        ->assertSee('brand-mark', false)
        ->assertDontSee('brand-logos/logo.png', false);
});

it('rejects an invalid style or color without changing stored keys', function () {
    foreach ([
        'default_locale' => 'es',
        'appearance.sidebar_style' => 'light',
        'appearance.sidebar_color' => '#273249',
    ] as $key => $value) {
        DB::table('settings')->insert([
            'key' => $key,
            'value' => $value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    $user = User::factory()->create();
    $user->assignRole(Role::SuperAdmin->value);

    $this->actingAs($user)
        ->from('/settings')
        ->put(route('settings.appearance.update'), [
            'sidebar_style' => 'compact',
            'sidebar_color' => '#273249',
            'accent_color' => '#6c4db5',
        ])
        ->assertRedirect('/settings')
        ->assertSessionHasErrors('sidebar_style');

    $this->actingAs($user)
        ->from('/settings')
        ->put(route('settings.appearance.update'), [
            'sidebar_style' => 'dark',
            'sidebar_color' => '#ff00ff',
            'accent_color' => '#6c4db5',
        ])
        ->assertRedirect('/settings')
        ->assertSessionHasErrors('sidebar_color');

    $this->actingAs($user)
        ->from('/settings')
        ->put(route('settings.appearance.update'), [
            'sidebar_style' => 'dark',
            'sidebar_color' => '#273249',
            'accent_color' => '#ffffff',
        ])
        ->assertRedirect('/settings')
        ->assertSessionHasErrors('accent_color');

    expect(DB::table('settings')->where('key', 'appearance.sidebar_style')->value('value'))->toBe('light')
        ->and(DB::table('settings')->where('key', 'appearance.sidebar_color')->value('value'))->toBe('#273249')
        ->and(DB::table('settings')->where('key', 'appearance.accent_color')->doesntExist())->toBeTrue()
        ->and(DB::table('settings')->where('key', 'default_locale')->value('value'))->toBe('es');
});

it('allows a user with setting.edit to save appearance', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::findOrCreate('setting.edit', 'web'));

    $this->actingAs($user)
        ->put(route('settings.appearance.update'), [
            'sidebar_style' => 'dark',
            'sidebar_color' => '',
            'accent_color' => '',
        ])
        ->assertRedirect(route('settings.index', ['tab' => 'appearance']));

    expect(DB::table('settings')->where('key', 'appearance.sidebar_style')->value('value'))->toBe('dark');
});
