<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleQuizChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_quiz_choices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('module_quiz_question_id');
            $table->string('title');
            $table->string('description')->default('')->nullable();
            $table->boolean('answer')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('module_quiz_question_id')->references('id')->on('module_quiz_questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_quiz_choices');
    }
}
