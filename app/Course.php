<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
  use SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'title',
    'description',
    'content',
    'status',
  ];

  /**
   * Get the modules for the course.
   */
  public function modules()
  {
      return $this->hasMany('App\Module');
  }

  /**
   * Get the user for the courses.
   */
  public function users()
  {
      return $this->hasMany('App\UserCourse');
  }
}
