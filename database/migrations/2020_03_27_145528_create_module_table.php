<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lesson_id');
            $table->unsignedBigInteger('quiz_id');
            $table->string('title');
            $table->string('description')->default('');
            $table->integer('order');
            $table->boolean('status')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('lesson_id')->references('id')->on('module_lessons');
            $table->foreign('quiz_id')->references('id')->on('module_quizzes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
