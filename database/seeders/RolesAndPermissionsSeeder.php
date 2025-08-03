<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat Permissions
        // Contoh untuk Data Pendaftaran
        Permission::firstOrCreate(['name' => 'view_registrations']);
        Permission::firstOrCreate(['name' => 'create_registrations']);
        Permission::firstOrCreate(['name' => 'edit_registrations']);
        Permission::firstOrCreate(['name' => 'delete_registrations']);

        // Contoh untuk Data Pemeriksaan
        Permission::firstOrCreate(['name' => 'view_examinations']);
        Permission::firstOrCreate(['name' => 'create_examinations']);
        Permission::firstOrCreate(['name' => 'edit_examinations']);
        Permission::firstOrCreate(['name' => 'delete_examinations']);

        // Contoh untuk Data Pasien
        Permission::firstOrCreate(['name' => 'view_patients']);
        Permission::firstOrCreate(['name' => 'create_patients']);
        Permission::firstOrCreate(['name' => 'edit_patients']);
        Permission::firstOrCreate(['name' => 'delete_patients']);

        // Contoh untuk Data Dokter
        Permission::firstOrCreate(['name' => 'view_doctors']);
        Permission::firstOrCreate(['name' => 'create_doctors']);
        Permission::firstOrCreate(['name' => 'edit_doctors']);
        Permission::firstOrCreate(['name' => 'delete_doctors']);

        // Contoh untuk Data Pembayaran
        Permission::firstOrCreate(['name' => 'view_payments']);
        Permission::firstOrCreate(['name' => 'create_payments']);
        Permission::firstOrCreate(['name' => 'edit_payments']);
        Permission::firstOrCreate(['name' => 'delete_payments']);

        // Contoh untuk Data Rekam Medis
        Permission::firstOrCreate(['name' => 'view_medical_records']);
        Permission::firstOrCreate(['name' => 'create_medical_records']);
        Permission::firstOrCreate(['name' => 'edit_medical_records']);
        Permission::firstOrCreate(['name' => 'delete_medical_records']);

        // Contoh untuk Resep Obat
        Permission::firstOrCreate(['name' => 'view_prescriptions']);
        Permission::firstOrCreate(['name' => 'create_prescriptions']);
        Permission::firstOrCreate(['name' => 'edit_prescriptions']);
        Permission::firstOrCreate(['name' => 'delete_prescriptions']);

        // Contoh untuk Data Obat
        Permission::firstOrCreate(['name' => 'view_medicines']);
        Permission::firstOrCreate(['name' => 'create_medicines']);
        Permission::firstOrCreate(['name' => 'edit_medicines']);
        Permission::firstOrCreate(['name' => 'delete_medicines']);

        // Contoh untuk Laporan Resep Obat
        Permission::firstOrCreate(['name' => 'view_prescription_reports']);

        // Contoh untuk Laporan Pembayaran
        Permission::firstOrCreate(['name' => 'view_payment_reports']);

        // Contoh untuk Laporan Rekam Medis
        Permission::firstOrCreate(['name' => 'view_medical_record_reports']);

        // Contoh untuk Dashboard
        Permission::firstOrCreate(['name' => 'view_dashboard']);

        // Buat Roles dan berikan Permissions
        $roleAdmin = Role::firstOrCreate(['name' => 'staff_administrasi']);
        $allPermissions = Permission::all();
        $permissionsToExclude = [
            'create_examinations',
            'edit_examinations',
            'delete_examinations',
            'view_medical_records',
            'create_medical_records',
            'edit_medical_records',
            'delete_medical_records'
        ];
        $permissionsToGive = $allPermissions->filter(function ($permission) use ($permissionsToExclude) {
            return !in_array($permission->name, $permissionsToExclude);
        });

        $roleAdmin->givePermissionTo($permissionsToGive->pluck('name'));

        $roleDokter = Role::firstOrCreate(['name' => 'dokter']);
        $roleDokter->givePermissionTo([
            'view_examinations',
            'create_examinations',
            'edit_examinations',
            'view_patients',
            'view_medical_records',
            'create_medical_records',
            'edit_medical_records',
            'view_prescriptions',
            'create_prescriptions',
            'edit_prescriptions',
            'view_medicines',
            'create_medicines',
            'edit_medicines',
            'delete_medicines',
            'view_prescription_reports',
            'view_medical_record_reports',
        ]);

        $rolePasien = Role::firstOrCreate(['name' => 'pasien']);
        $rolePasien->givePermissionTo([
            'view_registrations', // Pasien bisa melihat status pendaftaran mereka
            'view_medical_records', // Pasien bisa melihat rekam medis mereka sendiri
        ]);

        // Buat contoh user dan berikan role
        $userAdmin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin Senyumku', 'password' => bcrypt('password')]
        );
        $userAdmin->assignRole('staff_administrasi');

    }
}
