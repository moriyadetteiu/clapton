<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Traits\PrimaryKeyUuidUsable;
use App\Notifications\Passwords\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable, PrimaryKeyUuidUsable, HasApiTokens;

    // primary key でuuidを使うためにoverrideする
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_kana',
        'handle_name',
        'handle_name_kana',
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

    public static function encryptPassword(string $password): string
    {
        return Hash::make($password);
    }

    public function affiliateTeams(): HasMany
    {
        return $this->hasMany(UserAffiliationTeam::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
