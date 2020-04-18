<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCourseModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_course_modules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_course_id');
            $table->unsignedBigInteger('module_lesson_id');
            $table->boolean('completed')->default(false)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_course_id')->references('id')->on('user_courses');
            $table->foreign('module_lesson_id')->references('id')->on('module_lessons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_course_module');
    }
}
