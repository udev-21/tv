<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
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
        // 'password',
        'avatar',
        // 'active',
        'employee_id',
        'position_id',
        'department_id',
        'organization_id',
        'latin_name',
        'can_see_guard',
    ];

    protected $with = [
        'department',
        'organization',
        'position',
        'latestSession',
        'todaysSessions',
        'todaysFirstSession',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'active' => 'boolean',
        'can_see_guard' => 'boolean',
    ];

    protected $appends = [
        'active',
        'in_building_time',
        'last_in',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(UserLog::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    public function latestSession()
    {
        return $this->hasOne(Session::class)->latestOfMany();
    }

    public function todaysSessions()
    {
        return $this->hasMany(Session::class)
            ->whereDate('in', now())
            ->orWhereNull('out')        // bir necha kun oldin kirib hali chiqmagan bo'lsa
            ->orWhereDate('out', now()) // bir necha kun oldin kirib bugun chiqgan bo'lsa
            ->orderBy('in', 'desc');
    }

    public function todaysFirstSession()
    {
        return $this->hasOne(Session::class)
            ->whereDate('in', now())
            ->orWhereNull('out')        // bir necha kun oldin kirib hali chiqmagan bo'lsa
            ->orWhereDate('out', now()) // bir necha kun oldin kirib bugun chiqgan bo'lsa
            ->orderBy('in', 'asc');
    }

    public function latestNotFinishedSession()
    {
        return $this->hasOne(Session::class)
            ->whereNull('out')
            ->latestOfMany();
    }

    public function getActiveAttribute($value)
    {
        return $this->latestNotFinishedSession()->exists();
    }

    public function getInBuildingTimeAttribute():int
    {   
        return $this->todaysSessions->sum('duration') * 1000;
    }

    public function getUpdatedAtAttribute($value)
    {
        return ($this->latestNotFinishedSession->in ?? $this->latestSession->out ?? new Carbon($value))->format('Y-m-d H:i:s');
    }

    // public function getLastOutAttribute(){
    //     return ($this->latestSession->out ?? null) ? $this->latestSession->out->format('Y-m-d H:i:s') : '-';
    // }
    // public function getFirstInAttribute(){
    //     return ($this->todaysFirstSession->in ?? null) ? $this->todaysFirstSession->in->format('Y-m-d H:i:s') : '-';
    // }
    public function getLastInAttribute(){
        return ($this->latestSession->in ?? null) ? $this->latestSession->in->format('Y-m-d H:i:s') : '-';
    }
}
