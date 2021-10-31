<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The comments that belong to the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The lessons that a user has access to.
     */
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class);
    }

    /**
     * The lessons that a user has watched.
     */
    public function watched()
    {
        return $this->belongsToMany(Lesson::class)->wherePivot('watched', true);
    }

     /**
     * The achievements awarded to a user.
     */
    public function achievements()
    {
        return $this->belongsToMany(Achievement::class);
        // ->wherePivot('is_completed', true);
    }

    /**
     * The achievements awarded to a user.
     */
    public function completedAchievements()
    {
        return $this->belongsToMany(Achievement::class)
        ->wherePivot('is_completed', true);
    }
     /**
     * The next achievement for a user to attain.
     */
    public function nextAchievement()
    {
        return $this->belongsToMany(Achievement::class)
        ->withPivot('current_step', 'no_of_steps_required')
        ->wherePivot('is_completed', false);
    }

    /**
     * The badges of a user.
     */
    public function badges()
    {
        return $this->belongsToMany(Badge::class)
        ->wherePivot('is_completed', true);
    }

    /**
     * The next badges of a user.
     */
    public function nextBadges()
    {
        return $this->belongsToMany(Badge::class)
        ->withPivot('no_of_current_achievements', 'no_of_required_achievements')
        ->wherePivot('is_completed', false);
    }
}
