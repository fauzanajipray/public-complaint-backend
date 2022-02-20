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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status' => '500',
                'message' => 'ERROR',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // TODO: Benerin ini
    public function show($id)
    {
        function getNameByRole($user){
            if ($user->role_id = 1) {
                return 'Admin';
            } else if ($user->role_id = 3) {
                return $user->position->name;
            } else {
                return $user->name;
            }
        } 

        $complaint = Complaint::with('comments')->find($id);

        $complaint->comments->map(function ($comment) use ($complaint) {  
            // return [
            //     'id' => $comment->id,
            //     'complaint_id' => $comment->complaint_id,
            //     'user_id' => $comment->user_id,
            //     'body' => $comment->body,
            //     'status' => $comment->status,
            //     'name' => getNameByRole($comment->user),
            //     'created_at' => $comment->created_at,
            //     'created_at_human' => $comment->created_at->diffForHumans(),
            //     'position' => $complaint->position->name,
            // ];
            $comment->setAttribute('name', getNameByRole($comment->user));
            $comment->setAttribute('position', $complaint->position->name);
            unset($comment->user);
        });

        if ($complaint) {
            return response()->json([
                'status' => 200,
                'message' => 'SUCCESS',
                'data' => $complaint,
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
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
    
}