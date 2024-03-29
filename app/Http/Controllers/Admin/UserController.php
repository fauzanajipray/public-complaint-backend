<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $requests)
    {
        // dd($requests);
        $users = User::select('users.*');
        $data['users'] = User::search()
                            ->role()
                            ->orderByDate()
                            ->status() 
                            ->paginate(20)
                            ->withQueryString();
        
        // dd($data['users']);

        return view('admin.user.index', compact('data', 'requests'));
    }

    public function show(Request $requests, $id)
    {
        $data['user'] = User::findOrFail($id);

        $data['user']->setRelation('complaints', 
            $data['user']
                ->complaints()
                ->paginate(10, ['*'], 'pg_cp')
                ->withQueryString()
        );

        $data['user']->complaints->map(function($complaint) {
            $complaint->setAttribute('position_name', $complaint->position->name);
        });

        $data['user']->setRelation('comments', 
            $data['user']
                ->comments()
                ->paginate(10, ['*'], 'pg_cm')
                ->withQueryString()
        );
        
        $data['positions'] = Position::get()->filter(function ($position) {
            return $position->user == null;
        });
        
        $data['position_complaints'] = null;

        if ($data['user']->role_id == 3) {
            $data['position_complaints'] = Complaint::select('complaints.*')
                ->position($data['user']->position->id)
                ->paginate(10, ['*'], 'pg_pscp')
                ->withQueryString();
        }

        return view('admin.user.show', compact('data', 'requests'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function setting(Request $request, $id)
    {
        $requests = $request->all();
        $validator = Validator::make($requests, [
            'password' => 'required|string',
            'role' => 'nullable|integer',
            'position' => 'nullable|integer',
        ]);
        if ($validator->fails()) {   
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        $admin = User::find(session('admin_id'));
        $user = User::find($id);

        if (!Hash::check($requests['password'], $admin->password)) {
            return redirect()->back()->with('error', 'Password salah');
        } 

        if (isset($requests['role'])) {

            $user->role_id = $requests['role'];
            if ($requests['role'] == 3) {
                if(isset($requests['position'])) {
                    $user->position_id = $requests['position'];
                } else {
                    return redirect()->back()->with('error', 'Jabatan harus diisi');
                }
            } else {
                $user->position_id = null;
            }
        }
        $user->update();
        return redirect()->back()->with('success', 'Update data sukses');
    }
}
