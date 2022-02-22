<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {   
        try {
            $complaints = Complaint::with('comments', 'users')->where('position_id' , $request->user()->position_id)
                ->orderByDate()
                ->status()
                ->search()
                ->anonymous()
                ->paginate(20)
                ->withQueryString();
            
            $complaints->map(function ($complaint) {
                $complaint->setAttribute('username', (!$complaint->anonymous) ? 'Anonymous' : $complaint->users->name); //TODO: Nanti tolong di cek apakah gak terbalik yg anonymous
                $complaint->setAttribute('comments_count', $complaint->comments->count());
                unset($complaint->comments);
                unset($complaint->users);
                unset($complaint->is_anonymous);
                unset($complaint->is_private);
                unset($complaint->user_id);
                unset($complaint->position_id);
                unset($complaint->updated_at);
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
    
    public function store(Request $request) {
        $complaint = $request->all();
        $validator = Validator::make($complaint, Complaint::$rules);

        if (!$validator->fails()) {
            if ($request->hasFile('image')) {

                $file = $request->file('image');
                $date = date('Ymd').'_'.date('His');
                $filename = $date . '_' . random_int(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $destinationPath = "storage/complaint/";
                $file->move($destinationPath, $filename);
                $complaint['image'] = url('/') . '/' . $destinationPath . $filename;
            }

            $complaint['message_status'] = 1 ;

            if(Complaint::create($complaint)){
                return response()->json([
                    'status' => 200,
                    'message' => 'SUCCESS',
                    'data' => $complaint,
                    'error' => null,
                ], 200);
            } 

            return response()->json([
                'status' => 500,
                'message' => 'FAILED',
                'data' => $complaint,
                'errors' => [
                    'message' => 'Internal Server Error',
                ],
            ], 500);
        }

        return response()->json([
            'message' => 'VALIDATION_ERROR',
            'status' => '401',
            'data' => null,
            'errors' => $validator->errors(),
        ], 401);
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

    public function edit($id)
    {
        
    }

    public function update(Request $request, $id)
    {    
        $complaint = $request->all();
        $validator = Validator::make($complaint, Complaint::$rules);

        if (!$validator->fails()) {

            try {
                $complaint = Complaint::find($id);

                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $date = date('Ymd').'_'.date('His');
                    $filename = $date . '_' . random_int(1000, 9999) . '.' . $file->getClientOriginalExtension();
                    $destinationPath = "storage/complaint/";
                    $file->move($destinationPath, $filename);
                    $complaint['image'] = url('/') . '/' . $destinationPath . $filename;
                }
    
                if($complaint->update($complaint)){
                    return response()->json([
                        'status' => 200,
                        'message' => 'SUCCESS',
                        'data' => $complaint,
                        'error' => null,
                    ], 200);
                } 

            } catch (QueryException $q) {
                if (!$complaint) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'NOT_FOUND',
                        'data' => null,
                        'errors' => [
                            'message' => $q->getMessage(),
                        ],
                    ], 404);
                }
            }
        }

        return response()->json([
            'message' => 'VALIDATION_ERROR',
            'status' => '401',
            'data' => null,
            'errors' => $validator->errors(),
        ], 401);
    }

    public function destroy($id)
    {
        try {
            $complaint = Complaint::find($id);
            if ($complaint) {
                $complaint->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'SUCCESS',
                    'data' => null,
                    'error' => null,
                ], 200);
            }
            return response()->json([
                'status' => 404,
                'message' => 'NOT_FOUND',
                'data' => null,
                'errors' => [
                    'message' => 'Data not found',
                ],
            ], 404);    
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'message' => 'ERROR',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    protected function getNameByRole($user){
        if ($user->role_id = 1) {
            return 'Admin';
        } else if ($user->role_id = 3) {
            return $user->position->name;
        } else {
            return $user->name;
        }
    } 
    
}