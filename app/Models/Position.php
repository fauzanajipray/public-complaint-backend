<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public static $rules = [
        'name' => 'required|string|max:255|unique:positions',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

}
