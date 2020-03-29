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
    'description',
    'order',
    'answer',
    'content',
    'status',
  ];
}
