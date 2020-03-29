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
    'lesson_id',
    'quiz_id',
    'title',
    'description',
    'order',
    'status',
  ];
}
