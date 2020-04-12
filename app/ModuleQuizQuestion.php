<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuleQuizQuestion extends Model
{
  use SoftDeletes;
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'module_quiz_id',
    'title',
    'content',
    'status',
  ];

  /**
   * Get the choices for the question.
   */
  public function choices()
  {
      return $this->hasMany('App\ModuleQuizChoices')->select(
        'id',
        'module_quiz_question_id',
        'content'
      );
  }
}
