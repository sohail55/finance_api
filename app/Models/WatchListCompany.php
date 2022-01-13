<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;


class WatchListCompany extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_watch_list_id',
        'company_id',
        ];

    public function WatchListCompany() {
        return $this->hasMany(WatchListCompany::class, 'user_watch_list_id', 'id')->pluck('id');
    }

    public function UserCompany() {
        return $this->hasMany(Company::class, 'id', 'company_id');
    }



}
