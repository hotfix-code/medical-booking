<?php

use App\Enums\Role;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Role as RoleModel;
use App\Models\User;

beforeEach(function () {
    $this->withoutVite();

    foreach (Role::values() as $name) {
        RoleModel::create(['name' => $name]);
    }
});

function dashboardUserWithRole(string $role): User
{
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user->fresh();
}

it('shows today and latest patients lists to an admin', function () {
    $this->actingAs(dashboardUserWithRole(Role::SuperAdmin->value))
        ->get('/dashboard')
        ->assertOk()
        ->assertSeeText(__('dashboard.lists.today_title'))
        ->assertSeeText(__('dashboard.lists.patients_title'))
        ->assertSeeText(__('dashboard.charts.specialties_title'))
        ->assertSeeText(__('dashboard.illustration.body.admin'));
});

it('keeps the today list and hides latest patients and specialties for a doctor', function () {
    $user = dashboardUserWithRole(Role::Doctor->value);
    Doctor::factory()->for($user)->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSeeText(__('dashboard.lists.today_title'))
        ->assertDontSeeText(__('dashboard.lists.patients_title'))
        ->assertDontSeeText(__('dashboard.charts.specialties_title'))
        ->assertSeeText(__('dashboard.illustration.body.doctor'));
});

it('hides today, latest patients and specialties lists for a patient', function () {
    $user = dashboardUserWithRole(Role::Patient->value);
    Patient::factory()->for($user)->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertDontSeeText(__('dashboard.lists.today_title'))
        ->assertDontSeeText(__('dashboard.lists.patients_title'))
        ->assertDontSeeText(__('dashboard.charts.specialties_title'))
        ->assertSeeText(__('dashboard.illustration.body.patient'));
});
