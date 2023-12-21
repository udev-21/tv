<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Session
 * @property int $id
 * @property int $user_id
 * @property \Carbon\Carbon $in
 * @property \Carbon\Carbon $out
 * @property int $duration
 */
class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'in',
        'out',
    ];

    protected $casts = [
        'in' => 'datetime',
        'out' => 'datetime',
    ];

    protected $appends = [
        'duration',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDurationAttribute()
    {
        if ($this->out) {
            return $this->in->diffInSeconds($this->out);
        }

        return 0;
    }
}
