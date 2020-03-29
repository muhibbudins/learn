<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuleQuizChoices extends Model
{
  use SoftDeletes;
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'quiz_id',
    'content',
    'order',
  ];
}
