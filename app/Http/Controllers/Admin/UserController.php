<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $requests, $id)
    {
        $data['user'] = User::find($id);

        $data['user']->setRelation('complaints', $data['user']->complaints()->joinPosition()->paginate(10));
        // $data['user']->setRelation('comments', $data['user']->comments()->paginate(10));
        // $category = Category::first();
        // $category->setRelation('apps', $category->apps()->paginate(10));
        // return view('example', compact('category');
        
        $data['positions'] = $data['user']->positions;


        // dd($data['user']);

        return view('admin.user.show', compact('data', 'requests'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
