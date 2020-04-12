<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuleQuiz extends Model
{
  use SoftDeletes;
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'module_id',
    'title',
    'content',
  ];

  /**
   * Get the questions for the group.
   */
  public function questions()
  {
      return $this->hasMany('App\ModuleQuizQuestion')->select(
        'id',
        'module_quiz_id',
        'title',
        'content',
        'status'
      );
  }
}
