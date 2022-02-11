<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //index
    public function index()
    {
        $data['user'] = User::with('detail')->find(session('admin_id'));
        // dd($data);
        return view('admin.profile.index', compact('data'));
    }
}
