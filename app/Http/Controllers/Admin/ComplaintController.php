<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Position;
use App\Models\Comment;
use Illuminate\Database\QueryException;
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
        $complaints = Complaint::joinUser()->joinPosition()->select('complaints.*', 'users.name as user_name', 'positions.name as position_name');
        
        $data['complaints'] = $complaints->private()
                                        ->searchWithUsername()
                                        ->position()
                                        ->orderByDate()
                                        ->status()
                                        ->paginate(20)
                                        ->withQueryString();
                                        
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
        $data['complaint'] = Complaint::joinUser()->joinPosition()
                            ->select('complaints.*', 'users.name as user_name', 'positions.name as position_name')
                            ->find($id);
        $data['complaint']['comments'] = Comment::joinUser()->select('comments.*', 'users.name as user_name', 'users.role_id as user_role')
                                            ->where('complaint_id', $id)
                                            ->orderBy('id', 'asc')
                                            ->get();
        $data['positions'] = Position::all();
        return view('admin.complaint.show', compact('data'));
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

    /**
     * Confirm the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request, $id)
    {
        try {
            $complaint = Complaint::find($id);
            if($complaint){
                $complaint->status = "Diteruskan";
                $complaint->update();

                $comment = new Comment;
                $comment->user_id = session('admin_id');
                $comment->complaint_id = $complaint->id;
                $comment->body = $request->message;
                $comment->status = "Diteruskan";

                $comment->save();
                return redirect()->back()->with('success', 'Pengaduan berhasil diteruskan');
            }
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Error 505 : '.$e->getMessage());
        }
    }

    /**
     * Reject the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, $id)
    {
        try {
            $complaint = Complaint::find($id);
            if($complaint){
                $complaint->status = "Ditolak";
                $complaint->update();

                $comment = new Comment;
                $comment->user_id = session('admin_id');
                $comment->complaint_id = $complaint->id;
                $comment->body = $request->message;
                $comment->status = "Ditolak";

                $comment->save();
                return redirect()->back()->with('success', 'Pengaduan berhasil ditolak');
            }
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Error 505 : '.$e->getMessage());
        }
    }
}


