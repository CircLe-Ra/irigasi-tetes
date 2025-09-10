<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Role::create(['name' => 'developer']);
        Role::create(['name' => 'host']);
        Role::create(['name' => 'officer']);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'developer@devel.com',
            'password' => '123'
        ])->assignRole('developer');

        User::factory()->create([
            'name' => 'Rully Suherlan',
            'email' => 'rully@host.com',
            'password' => '123'
        ])->assignRole('host');

        User::factory()->create([
            'name' => 'Fikri Suleman',
            'email' => 'fikri@host.com',
            'password' => '123'
        ])->assignRole('host');

    }
}
