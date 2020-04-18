<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
  use SoftDeletes;
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'course_id',
    'title',
    'description',
    'status',
  ];

  /**
   * Get the lessons for the module.
   */
  public function lessons()
  {
      return $this->hasMany('App\ModuleLesson')->select(
        'id',
        'title',
        'description',
        'content'
      );
  }

  /**
   * Get the quizzes for the module.
   */
  public function quizzes()
  {
      return $this->hasMany('App\ModuleQuiz')->select(
        'id',
        'title',
        'description'
      );
  }
}
