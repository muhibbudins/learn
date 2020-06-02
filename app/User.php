<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
  use SoftDeletes;
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'firstname',
    'lastname',
    'address',
    'status',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
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
   * Get the courses for the user.
   */
  public function courses()
  {
      return $this->hasMany('App\UserCourse');
  }

  /**
   * JWT Identifier
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

   /**
    * JWT Claims
    */
  public function getJWTCustomClaims()
  {
    return [];
  }

  public static function boot() {
    parent::boot();

    static::deleting(function($user) { // before delete() method call this
         $user->courses()->delete();
    });
  }
}
