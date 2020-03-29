<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCourse extends Model
{
  use SoftDeletes;
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id',
    'course_id',
    'status',
  ];
}
