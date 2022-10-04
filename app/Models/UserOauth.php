<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOauth extends Model
{
    use HasFactory;

    protected $table = 'user_oauth';
    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
