<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Position;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $data['complaint'] = null; 
        if($request->has('complaint_id')){
            $complaint = Complaint::find($request->complaint_id);
            dd($complaint);
        }

        $complaints = Complaint::joinUser()->joinPosition()->select('complaints.*', 'users.name as user_name', 'positions.name as position_name')->orderBy('created_at', 'DESC');

        $data['complaints'] = $complaints->private()
                                        ->search()
                                        ->position()
                                        ->status()
                                        ->orderByDate()
                                        ->get();
        $data['positions'] = Position::all();
        $requests = $request;
        return view('admin.complaint.index', compact('data', 'requests'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
