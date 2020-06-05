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
      return $this->belongsTo('App\Course')->select('id', 'title', 'status', 'description', 'created_at')->where('status', 1);
  }

  /**
   * Get the user for the user course.
   */
  public function user()
  {
      return $this->belongsTo('App\User')->select('id', 'name', 'email', 'role');
  }

  /**
   * Get the modules for the user course.
   */
  public function modules()
  {
      return $this->hasMany('App\UserCourseModule');
  }

  /**
   * Get the quizzes for the user course.
   */
  public function quizzes()
  {
      return $this->hasMany('App\UserCourseQuiz');
  }

  public static function boot() {
    parent::boot();

    static::deleting(function($userCourse) { // before delete() method call this
         $userCourse->modules()->delete();
         $userCourse->quizzes()->delete();
    });
  }
}
