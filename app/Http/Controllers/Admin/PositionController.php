<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
{
    public function index(Request $requests)
    {
        $data['positions'] = Position::with('user', 'complaints')->paginate(20);

        return view('admin.position.index', compact('data', 'requests'));
    }

    //create
    public function create()
    {   
        return view('admin.position.create');
    }

    //show
    public function show(Request $requests, $id)
    {
        $data['position'] = Position::with('user', 'complaints')->findOrFail($id);
        $data['position']->setRelation('complaints', $data['position']->complaints()->limit(10)->get());
        
        return view('admin.position.show', compact('data', 'requests'));
    }

    //store
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), Position::$rules);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $position = new Position;
        $position->name = $request->name;
        $position->description = $request->description;
        $save = $position->save();
        if($save){
            return redirect('admin/position')->with('success', 'Data berhasil dibuat');
        }
        return redirect('admin/position')->with('error', 'Data gagal dibuat');
    }

    //edit
    public function edit($id)
    {
        $data['position'] = Position::findOrFail($id);
        return view('admin.position.edit', compact('data'));
    }

    //update
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), Position::$rules);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $position = Position::findOrFail($id);
        $position->name = $request->name;
        $position->description = $request->description;
        $save = $position->save();
        if($save){
            return redirect('admin/position')->with('success', 'Data berhasil diubah');
        }
        return redirect('admin/position')->with('error', 'Data gagal diubah');
    }

    //destroy
    public function destroy(Request $requests, $id)
    {
        $admin = User::find(session('admin_id'));

        if (!Hash::check($requests['password'], $admin->password)) {
            return redirect()->back()->with('error', 'Password salah');
        } 

        $position = Position::findOrFail($id);
        $delete = $position->delete();
        if($delete){
            return redirect('admin/position')->with('success', 'Data berhasil dihapus');
        }
        return redirect('admin/position')->with('error', 'Data gagal dihapus');
    }
}
