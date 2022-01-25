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
    ];
}
