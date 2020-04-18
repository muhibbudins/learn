<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleQuizQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_quiz_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('module_quiz_id');
            $table->string('title');
            $table->string('description')->default('')->nullable();
            $table->tinyInteger('status')->default(1)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('module_quiz_id')->references('id')->on('module_quizzes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_quiz_questions');
    }
}
