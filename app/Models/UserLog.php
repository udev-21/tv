<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type', // boolean: true - enter, false - exit
    ];

    protected $casts = [
        'type' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
