<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Database\Data\Locales as LocalesData;
use Database\Data\Permissions as PermissionsData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create all permissions
        $permissionsData = PermissionsData::list();
        foreach ($permissionsData as $permission)
        {
            Permission::create($permission);
        }

        // Create a super-admin role
        $superRole = Role::create(['name' => 'super-admin']);

        // Create admin and setting a super-admin role
        $johnDoe = User::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'admin@medibook.org',
            'locale' => 'en',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        $johnDoe->assignRole($superRole);

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
        foreach ($localesData as $lang)
        {
            DB::table('locales')->insert($lang);
        }

        DB::table('settings')->insert([
            'key' => 'default_locale',
            'value' => 'en',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a default document type
        DocumentType::create([
            'code' => 'DNI',
            'name' => 'Documento Nacional de Identidad',
        ]);
    }
}
