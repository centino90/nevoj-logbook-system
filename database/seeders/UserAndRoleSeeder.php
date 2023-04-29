<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserAndRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // CREATE ROLES
        $adminRole = Role::where('name', 'admin')->first();
        if(!$adminRole) {
            $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        }
        $professorRole = Role::where('name', 'professor')->first();
        if(!$professorRole) {
            $professorRole = Role::create(['name' => 'professor', 'guard_name' => 'web']);
        }

        // CREATE 1 ADMIN and 5 PROFESSOR USERS

        if(User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->count() === 0) {
            User::create([
                'name' => 'admin',
                'email' => 'admin@test.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ])->assignRole($adminRole);
        }

        if(User::whereHas('roles', function($q) {
            $q->where('name', 'professor');
        })->count() === 0) {
            User::create([
                'name' => 'professor one',
                'email' => 'professor1@test.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ])->assignRole($professorRole);
            User::create([
                'name' => 'professor two',
                'email' => 'professor2@test.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ])->assignRole($professorRole);
            User::create([
                'name' => 'professor three',
                'email' => 'professor3@test.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ])->assignRole($professorRole);
            User::create([
                'name' => 'professor four',
                'email' => 'professor4@test.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ])->assignRole($professorRole);
            User::create([
                'name' => 'professor five',
                'email' => 'professor5@test.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ])->assignRole($professorRole);
        }
    }
}
