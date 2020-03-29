<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseModule extends Model
{
  use SoftDeletes;
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'module_id',
    'course_id',
    'order',
    'status',
  ];
}
