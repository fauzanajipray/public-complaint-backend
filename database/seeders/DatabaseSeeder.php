<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\Recipient;
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

        Recipient::create(['name' => 'Kepala Desa']);
        Recipient::create(['name' => 'Kepala Dusun Karangrejo']);
        Recipient::create(['name' => 'Kepala Dusun Gondang Rejo']);
        Recipient::create(['name' => 'Kepala Dusun Karangnongko']);
        Recipient::create(['name' => 'Kepala Dusun Brangkal']);

        User::factory(5)->create();
        Complaint::factory(25)->create();
    }
}
