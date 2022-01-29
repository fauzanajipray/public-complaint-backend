<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Recipient;
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
        $complaints = Complaint::joinUser()->joinRecipient()->select('complaints.*', 'users.name as user_name', 'recipients.name as recipient_name')->orderBy('created_at', 'DESC');

        $data['complaints'] = $complaints->private()
                                        ->search()
                                        ->recipient()
                                        ->status()
                                        ->orderByDate()
                                        ->get();
        $data['recipients'] = Recipient::all();
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
