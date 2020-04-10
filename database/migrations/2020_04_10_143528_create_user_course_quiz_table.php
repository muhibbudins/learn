<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCourseQuizTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_course_quizzes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_course_id');
            $table->unsignedBigInteger('module_quiz_id');
            $table->unsignedBigInteger('module_quiz_choice_id')->nullable();
            $table->string('essay')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_course_id')->references('id')->on('user_courses');
            $table->foreign('module_quiz_id')->references('id')->on('module_quizzes');
            $table->foreign('module_quiz_choice_id')->references('id')->on('module_quiz_choices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_quiz_answer');
    }
}
