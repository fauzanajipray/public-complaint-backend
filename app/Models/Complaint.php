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
        'status',
        'is_anonymous',
        'is_private',
    ];

    public static $rules = [
        'user_id' => 'required|integer',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'recipient_id' => 'required|integer',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'status' => 'string|max:255',
        'is_anonymous' => 'required|boolean',
        'is_private' => 'required|boolean',
    ];
    
    public function scopePrivate($query)
    {   
        if (isset(request()->private)) {
            return $query->where('is_private', request()->private);
        }
    }

    public function scopeAnonymous($query)
    {
        if (isset(request()->anonymous)) {
            return $query->where('is_anonymous', request()->anonymous);
        }
    }

    public function scopeUserId($query)
    {
        if (isset(request()->user_id)) {
            return $query->where('user_id', request()->user_id);
        }
    }

    public function scopeSearch($query)
    {
        if (isset(request()->search)) {
            return $query->where('title', 'like', '%'.request()->search.'%')
                        ->orWhere('description', 'like', '%'.request()->search.'%');
        }
    }

    public function scopeStatus($query)
    {
        if (isset(request()->status)) {
            return $query->where('status', request()->status);
        }
    }

    public function scopeRecipient($query)
    {
        if (isset(request()->recipient)) {
            return $query->where('recipient_id', request()->recipient);
        }
    }

    public function scopeOrderByDate($query)
    {
        if (isset(request()->order)) {
            return $query->orderBy('created_at', request()->order);
        }
    }

    public function scopeJoinUser($query)
    {
        return $query->join('users', 'users.id', '=', 'complaints.user_id')
                    ->select('complaints.*', 'users.name as user_name');
    }

    public function scopeJoinRecipient($query)
    {
        return $query->join('recipients', 'recipients.id', '=', 'complaints.recipient_id')
                    ->select('complaints.*', 'recipients.name as recipient_name');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
