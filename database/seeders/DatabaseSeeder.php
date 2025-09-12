<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\RelayChannel;
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
        Role::create(['name' => 'admin']);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => '123'
        ])->assignRole('admin');

        $device = Device::create(['name' => 'NodeMCU-1']);
        for ($i=1;$i<=4;$i++) {
            RelayChannel::create([
                'device_id'=>$device->id,
                'channel'=>$i,
                'state'=>0
            ]);
        }
    }
}
