<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'post_id', 'body'];

    public static $rules = [
        'user_id' => 'required|integer',
        'post_id' => 'required|integer',
        'body' => 'required|min:5'
    ];
}
