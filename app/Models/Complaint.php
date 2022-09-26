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
        'position_id',
        'image',    
        'status',
        'is_anonymous',
        'is_private',
    ];

    public static $rules = [
        'user_id' => 'required|integer',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'position_id' => 'required|integer',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'status' => 'string|max:255|in:Menunggu,Diteruskan,Diterima,Ditolak',
        'is_anonymous' => 'required|boolean',
        'is_private' => 'required|boolean',
    ];
    
    //* Filter *//
    
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

    public function scopeSearchWithUsername($query)
    {
        if (isset(request()->search)) {
            return $query->where('title', 'like', '%'.request()->search.'%')
                        ->orWhere('description', 'like', '%'.request()->search.'%')
                        ->orWhere('users.name', 'like', '%'.request()->search.'%');
        }
    }

    public function scopeStatus($query)
    {
        if (isset(request()->status)) {
            if (request()->status == 'Proses'){
                return $query->where('status', 'Menunggu')
                            ->orWhere('status', 'Diteruskan');
            } else if(request()->status == 'Selesai'){
                return $query->where('status', 'Diterima')
                            ->orWhere('status', 'Ditolak');
            } else {
                return $query->where('status', request()->status);
            }
        }
    }

    public function scopePosition($query, $id = null)
    {
        if (isset($id)) {
            return $query->where('position_id', $id);
        }
        else 
        if (isset(request()->position)) {
            return $query->where('complaints.position_id', request()->position);
        }
    }

    public function scopeOrderByDate($query, $order = 'desc')
    {
        if (isset(request()->order)) {
            return $query->orderBy('created_at', request()->order);
        } else {
            return $query->orderBy('created_at', $order);
        }
    }    

    //* Relationships *//

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
