<?php

namespace Database\Seeders;

use App\Models\Receiver;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'User']);

        Receiver::create(['name' => 'Kepala Desa']);
        Receiver::create(['name' => 'Kepala Dusun Karangrejo']);
        Receiver::create(['name' => 'Kepala Dusun Gondang Rejo']);
        Receiver::create(['name' => 'Kepala Dusun Karangnongko']);
        Receiver::create(['name' => 'Kepala Dusun Brangkal']);
        
        User::factory(5)->create();
    }
}
