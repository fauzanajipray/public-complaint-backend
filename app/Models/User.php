<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'position_id',
        'is_email_verified',
        'otp',
        'otp_expired_at',
        'login_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'otp_expired_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relationship Table
     * 
     */
    
    public function detail(){
        return $this->hasOne(UserDetail::class);
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function position(){
        return $this->belongsTo(Position::class);
    }

    public function complaints(){
        return $this->hasMany(Complaint::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function oauth(){
        return $this->hasMany(UserOauth::class);
    }

    /**
     * Filter
     * 
     */

    
    public function scopeSearch($query)
    {
        if (isset(request()->search)) {
            return $query->where('name', 'like', '%'.request()->search.'%')
            ->orWhere('email', 'like', '%'.request()->search.'%');
        }
    }
    
    public function scopeOrderByDate($query)
    {
        if (isset(request()->order)) {
            return $query->orderBy('created_at', request()->order);
        } else {
            return $query->orderBy('created_at', 'desc');
        }
    }
    
    public function scopeRole($query)
    {
        if (isset(request()->role)) {
            return $query->where('role_id', request()->role);
        }
    }

    public function scopeStatus($query)
    {
        if (isset(request()->status)) {
            return $query->where('is_email_verified', request()->status);
        }
    }
}
