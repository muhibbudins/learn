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
    'course_id',
    'user_id',
  ];

  /**
   * Get the course for the user course.
   */
  public function course()
  {
      return $this->belongsTo('App\Course');
  }

  /**
   * Get the user for the user course.
   */
  public function user()
  {
      return $this->belongsTo('App\User');
  }
}
