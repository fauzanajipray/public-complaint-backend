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
        $users = User::select('users.*');
        if (isset(request()->order)) {
            $users = $users->orderByDate();
        } else {
            $users = $users->orderBy('id', 'asc');
        }
        $data['users'] = $users->search()
                               ->status()
                               ->role()
                               ->paginate(20)
                               ->withQueryString();

        return view('admin.user.index', compact('data', 'requests'));
    }

    public function show(Request $requests, $id)
    {
        $data['user'] = User::findOrFail($id);

        $data['user']->setRelation('complaints', $data['user']->complaints()->joinPosition()
                    ->paginate($perPage = 2, $columns = ['*'], $pageName = 'pg_cp')->withQueryString()
        );

        $data['user']->setRelation('comments', $data['user']->comments()->paginate(
            $perPage = 5, $columns = ['*'], $pageName = 'pg_cm')->withQueryString()
        );
        
        $data['positions'] = Position::get()->filter(function ($position) {
            return $position->user == null;
        });
        
        $data['position_complaints'] = null;

        if ($data['user']->role_id == 3) {
            $data['position_complaints'] = Complaint::select('complaints.*')->position($data['user']->position->id)
                                        ->paginate($perPage = 10, $columns = ['*'], $pageName = 'pg_pscp')->withQueryString();
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
