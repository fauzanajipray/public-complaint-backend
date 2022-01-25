<?php

namespace Database\Seeders;

use App\Models\Recipient;
use App\Models\Role;
use App\Models\StatusComplaint;
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

        Recipient::create(['name' => 'Kepala Desa']);
        Recipient::create(['name' => 'Kepala Dusun Karangrejo']);
        Recipient::create(['name' => 'Kepala Dusun Gondang Rejo']);
        Recipient::create(['name' => 'Kepala Dusun Karangnongko']);
        Recipient::create(['name' => 'Kepala Dusun Brangkal']);

        StatusComplaint::create(['status' => 'Menunggu']);
        StatusComplaint::create(['status' => 'Diproses']);
        StatusComplaint::create(['status' => 'Selesai']);
        StatusComplaint::create(['status' => 'Batal']);
        
        User::factory(5)->create();
    }
}
