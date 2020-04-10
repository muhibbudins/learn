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
    'module_lesson_id',
    'module_quiz_id',
    'title',
    'description',
    'status',
  ];

  /**
   * Get the lessons for the module.
   */
  public function lessons()
  {
      return $this->hasMany('App\ModuleLesson');
  }

  /**
   * Get the quizzes for the module.
   */
  public function quizzes()
  {
      return $this->hasMany('App\ModuleQuiz');
  }
}
