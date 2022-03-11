<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Complaint;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {   
        try {
            $complaints = Complaint::where('position_id' , $request->user()->position_id)
                ->orderByDate()
                ->status()
                ->search()
                ->anonymous()
                ->paginate(20)
                ->withQueryString();
            
            $complaints->map(function ($complaint) {
                $complaint->setAttribute('user_name', ($complaint->is_anonymous == 1) ? 'Anonymous' : $complaint->users->name); //TODO: Nanti tolong di cek apakah gak terbalik yg anonymous
                $complaint->setAttribute('user_image', ($complaint->is_anonymous == 1) ? null : $complaint->users->detail->avatar);
                $complaint->position_name = $complaint->position->name;
                $complaint->setAttribute('comments_count', $complaint->comments->count());
                unset($complaint->comments);
                unset($complaint->users);
                unset($complaint->is_anonymous);
                unset($complaint->is_private);
                unset($complaint->position_id);
                unset($complaint->updated_at);
                unset($complaint->position);
                return $complaint;
            });

            return response()->json([
                'message' => 'SUCCESS',
                'status' => '200',
                'data' => $complaints,
                'errors' => null,
            ], 200);

        } catch (QueryException $e) {
            return response()->json([
                'status' => '500',
                'message' => 'ERROR',
                'data' => null,
                'errors' => 'Internal Server Error',
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $complaint = Complaint::with('comments', 'users')->where('id', $id)->first();
            if (!$complaint){
                return response()->json([
                    'status' => 404,
                    'message' => 'NOT_FOUND',
                    'data' => null,
                    'errors' => [
                        'message' => 'Data not found',
                    ],
                ], 404);
            }

            $complaint->setAttribute('username', (!$complaint->anonymous) ? 'Anonymous' : $complaint->users->name); 
            $complaint->position_name = $complaint->position->name;
            $complaint->comments->map(function ($comment) use ($complaint) {  
                $comment->setAttribute('name', $this->getNameByRole($comment->user));
                $comment->setAttribute('position', $complaint->position->name);
                unset($comment->user);
                unset($comment->updated_at);
            });
            unset($complaint->updated_at);
            unset($complaint->users);
            unset($complaint->position);

            return response()->json([
                'status' => '200',
                'message' => 'SUCCESS',
                'data' => $complaint,
                'errors' => null,
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status' => '500',
                'message' => 'ERROR',
                'data' => null,
                'errors' => 'Internal Server Error',
            ], 500);
        }
    }

    public function confirm(Request $request, $id)
    {
        try {
            $requests = $request->all();
            $validator = Validator::make($requests, [
                'status' => 'required|in:Diterima,Ditolak',
                'body' => 'required|string',
            ]); 
            
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'VALIDATION_ERROR',
                    'status' => '401',
                    'data' => null,
                    'errors' => $validator->errors(),
                ], 401);
            }

            $complaint = Complaint::find($id);

            if(!$complaint){
                return response()->json([
                    'status' => 404,
                    'message' => 'NOT_FOUND',
                    'data' => null,
                    'errors' => [
                        'message' => 'Data not found',
                    ],
                ], 404);
            }

            if($complaint->status == 'Diteruskan'){
                if ($complaint->position_id != $request->user()->position_id){
                    return response()->json([
                        'status' => 403,
                        'message' => 'FORBIDDEN',
                        'data' => null,
                        'errors' => [
                            'message' => 'Forbidden',
                        ],
                    ], 403);
                }
                $comment = new Comment;
                $comment->user_id = $request->user()->id;
                $comment->complaint_id = $complaint->id;
                $comment->body = $request->body;
                $comment->status = $request->status;
                $comment->from_role = 'staff';
                $comment->save();
                $complaint->status = $request->status;
                $complaint->update();

                $complaint->setAttribute('username', (!$complaint->anonymous) ? 'Anonymous' : $complaint->users->name); 
                $complaint->position_name = $complaint->position->name;
                $complaint->comments->map(function ($comment) use ($complaint) {  
                    $comment->setAttribute('user_name', $this->getNameByRole($comment->user)); //TODO: Cek ini kenapa retunnya selalu admine
                    $comment->setAttribute('position_name', $complaint->position->name);
                    unset($comment->user);
                    unset($comment->updated_at);
                });
                unset($complaint->updated_at);
                unset($complaint->users);
                unset($complaint->position);

                return response()->json([
                    'status' => '200',
                    'message' => 'SUCCESsS',
                    'data' => $complaint,
                    'errors' => null,
                ], 200);
            } else {
                return response()->json([
                    'status' => '400',
                    'message' => 'BAD_REQUEST',
                    'errors' => [
                        'message' => 'Bad Request',
                    ],
                ], 400);
            }

            
        } catch (QueryException $e) {
            return response()->json([
                'status' => '500',
                'message' => 'ERROR',
                'errors' => 'Internal Server Error',
            ], 500);
        }
    }

    protected function getNameByRole($user) 
    {
        if ($user->role_id == 1) {
            return 'Admin';
        } else if ($user->role_id == 3) {
            return $user->position->name;
        } else {
            return $user->name;
        }
    } 
 
}