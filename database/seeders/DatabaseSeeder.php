<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetail;
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

        Role::updateOrCreate([ 'id' => 1, ], [ 'name' => 'Admin' ]);
        Role::updateOrCreate([ 'id' => 2, ], [ 'name' => 'User' ]);
        Role::updateOrCreate([ 'id' => 3, ], [ 'name' => 'Staff' ]);

        Position::updateOrCreate([ 'id' => 1, ],['name' => 'Kepala Desa']);
        Position::updateOrCreate([ 'id' => 2, ],['name' => 'Kepala Dusun Karangrejo']);
        Position::updateOrCreate([ 'id' => 3, ],['name' => 'Kepala Dusun Gondang Rejo']);
        Position::updateOrCreate([ 'id' => 4, ],['name' => 'Kepala Dusun Karangnongko']);
        Position::updateOrCreate([ 'id' => 5, ],['name' => 'Kepala Dusun Brangkal']);
        
        $user = User::factory(20)->create();
        $user->each(function ($u) {
            $u->detail()->save(UserDetail::factory()->make());
        });

        Complaint::factory(155)->create();
    }
}
