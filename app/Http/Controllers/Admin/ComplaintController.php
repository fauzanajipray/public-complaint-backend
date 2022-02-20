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
    public function index(Request $request)
    {   
        $complaints = Complaint::with('users', 'position')->select('complaints.*');
        $complaints = $complaints->private()
            ->searchWithUsername()
            ->position()
            ->orderByDate()
            ->status()
            ->paginate(20)
            ->withQueryString();
        
        $complaints->map(function ($complaint) {
            $complaint->setAttribute('user_name', $complaint->users->name);
            $complaint->setAttribute('position_name', $complaint->position->name);
        });

        $data['complaints'] = $complaints;
        $data['positions'] = Position::all();
        $requests = $request;
        return view('admin.complaint.index', compact('data', 'requests'));
    }

    public function show($id)
    {
        $complaint = Complaint::with('comments', 'position', 'users')->find($id);
        $complaint->user_name = $complaint->users->name;
        $complaint->position_name = $complaint->position->name;
        $complaint->comments->map(function($comment) use($complaint) {
            $comment->setAttribute('user_name', ($comment->user_id) ? $comment->user->name : '-');
            $comment->setAttribute('position_id', $complaint->position_id);
            $comment->setAttribute('position_name', $complaint->position->name);
            $comment->setAttribute('user_role', $complaint->users->role_id);
            unset($comment->updated_at);
            unset($comment->user);
        });
        unset($complaint->users);
        unset($complaint->position);
        
        $data['complaint'] = $complaint;
        $data['positions'] = Position::all();
        return view('admin.complaint.show', compact('data'));
    }

    public function destroy($id)
    {
        //
    }

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
                $comment->from_role = "Admin";

                $comment->save();
                return redirect()->back()->with('success', 'Pengaduan berhasil diteruskan');
            }
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Error 505 : '.$e->getMessage());
        }
    }

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


