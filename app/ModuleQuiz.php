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
    'title',
    'content',
    'status',
  ];

  /**
   * Get the choices for the quiz.
   */
  public function choices()
  {
      return $this->hasMany('App\ModuleQuizChoices');
  }
}
