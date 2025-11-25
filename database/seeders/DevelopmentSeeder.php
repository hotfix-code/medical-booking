<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Data\Locales as LocalesData;
use Database\Data\Permissions as PermissionsData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create a super-admin role
        $superRole = Role::create(['name' => 'super-admin']);

        // Create admin and setting a super-admin role
        $johnDoe = User::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'admin@medibook.org',
            'locale' => 'en',
        ]);
        $johnDoe->assignRole($superRole);

        // Create a test user only for development seeder
        $testUser = User::factory()->create(['email' => 'tester@medibook.org']);
        $testRole = Role::create(['name' => 'tester']);
        $testUser->assignRole($testRole);

        // Create all permissions
        $permissions = [];
        $permissionsData = PermissionsData::list();
        foreach ($permissionsData as $permission)
        {
            $permissions[] = Permission::create($permission);
        }
        $testRole->syncPermissions($permissions);

        // Create default doctor role and assign permissions
        $doctorRole = Role::create(['name' => 'doctor']);
        $doctorPermissions = PermissionsData::assignListToDoctor();
        $doctorRole->syncPermissions($doctorPermissions);

        // Create default patient role and assign permissions
        $patientRole = Role::create(['name' => 'patient']);
        $patientPermissions = PermissionsData::assignListToPatient();
        $patientRole->syncPermissions($patientPermissions);

        // Create locales in the settings table
        $localesData = LocalesData::list();
        foreach ($localesData as $locale)
        {
            DB::table('locales')->insert($locale);
        }

        DB::table('settings')->insert([
            'key' => 'default_locale',
            'value' => 'en',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a default document type
        DocumentType::factory()->create([
            'code' => 'DNI',
            'name' => 'Documento Nacional de Identidad',
        ]);
    }
}
