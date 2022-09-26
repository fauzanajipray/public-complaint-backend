<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        try {
            $positions = Position::get();

            $positions = $positions->makeHidden(['created_at', 'updated_at']);

            return response()->json([
                'message' => 'SUCCESS',
                'status' => '200',
                'data' => $positions,
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
}
