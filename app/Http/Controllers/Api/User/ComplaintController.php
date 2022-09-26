<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    private function changeNameImage($imageUrl){
        // $imageUrlExplode = explode('/', $imageUrl);
        // $imageName = $imageUrlExplode[count($imageUrlExplode)-1];
        // $fullUrl = 'https://68aa-45-126-187-15.ngrok.io'.'/storage/complaint/'.$imageName;
        // if(isset($imageUrlExplode[2])){
        //     if ($imageUrlExplode[2] == 'via.placeholder.com'){
        //         return $imageUrl;
        //     }
        // }
        // else {
        //     return $fullUrl;
        // }
    }

    public function index()
    {   
        try {
            $complaints = Complaint::private()
                ->orderByDate()
                ->anonymous()
                ->search()
                ->userId()
                ->status()
                ->with('comments')
                ->paginate(20)
                ->withQueryString();

            $complaints->map(function ($complaint) {
                $complaint->setAttribute('user_name', ($complaint->is_anonymous == 1) ? 'Anonymous' : $complaint->users->name); //TODO: Nanti tolong di cek apakah gak terbalik yg anonymous
                $complaint->setAttribute('user_image', ($complaint->is_anonymous == 1) ? null : $complaint->users->detail->avatar);
                $complaint->position_name = $complaint->position->name;
                $complaint->setAttribute('comments_count', $complaint->comments->count());
                // $complaint->setAttribute('image', $this->changeNameImage($complaint->image));
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
                'message' => 'Internal Server Error',
                'data' => null,
                'errors' => [
                    'message' => $e->getMessage(),
                ],
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

            $complaint['status'] = 'Menunggu' ;

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
            // $complaint->setAttribute('image', $this->changeNameImage($complaint->image));
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
    
    public function whereStatus(Request $request)
    {
        try {
            dd($request->all());
            $status = 1;
            $complaints = Complaint::where('status', $status)->get();
            if ($complaints) {
                return response()->json([
                    'status' => 200,
                    'message' => 'SUCCESS',
                    'data' => $complaints,
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