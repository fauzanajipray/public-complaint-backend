<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'recipient_id',
        'image',  
        'status_complaint_id',      
        'message_status',
        'is_private',
    ];

    public static $rules = [
        'user_id' => 'required|integer',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'recipient_id' => 'required|integer',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'status_complaint_id' => 'required|integer',
        'message_status' => 'string|max:255',
        'is_private' => 'required|boolean',
    ];
    
}
