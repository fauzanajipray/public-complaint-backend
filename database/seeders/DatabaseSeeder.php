<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\Position;
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
        Role::create(['name' => 'Staff']);
        
        Position::create(['name' => 'Kepala Desa']);
        Position::create(['name' => 'Kepala Dusun Karangrejo']);
        Position::create(['name' => 'Kepala Dusun Gondang Rejo']);
        Position::create(['name' => 'Kepala Dusun Karangnongko']);
        Position::create(['name' => 'Kepala Dusun Brangkal']);
        
        User::factory(5)->create();
        Complaint::factory(100)->create();
    }
}
