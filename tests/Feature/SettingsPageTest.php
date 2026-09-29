<?php

use App\Enums\Role;
use App\Models\Locale;
use App\Models\Permission;
use App\Models\Role as RoleModel;
use App\Models\User;
use Database\Data\Locales;

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

it('shows the appearance and general tabs to a super admin', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::SuperAdmin->value);

    $this->actingAs($user)
        ->get('/settings')
        ->assertOk()
        ->assertSeeText(__('settings.title'))
        ->assertSeeText(__('settings.subtitle'))
        ->assertSeeText(__('settings.tabs.appearance'))
        ->assertSeeText(__('settings.tabs.general'))
        ->assertSeeText(__('nav.items.settings'))
        ->assertSee('data-settings-panel="appearance"', false)
        ->assertSee('data-settings-panel="general" hidden', false);
});

it('opens the general tab from the query string', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::SuperAdmin->value);

    $this->actingAs($user)
        ->get('/settings?tab=general')
        ->assertOk()
        ->assertSee('data-settings-panel="general"', false)
        ->assertSee('data-settings-panel="appearance" hidden', false)
        ->assertSeeText(__('settings.general.empty'));
});

it('falls back to appearance for an unknown tab', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::SuperAdmin->value);

    $this->actingAs($user)
        ->get('/settings?tab=other')
        ->assertOk()
        ->assertSee('data-settings-panel="appearance"', false)
        ->assertSee('data-settings-panel="general" hidden', false);
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

it('allows a user with setting.edit', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(Permission::findOrCreate('setting.edit', 'web'));

    $this->actingAs($user)
        ->get('/settings')
        ->assertOk()
        ->assertSeeText(__('settings.tabs.appearance'));
});
