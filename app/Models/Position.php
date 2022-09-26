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

    protected $hidden = [
        'created_at',
        'updated_at',
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

    // public function makeHidden($attributes)
    // {
    //     $this->fillable = array_diff($this->fillable, $attributes);
    //     return $this;
    // }

}
