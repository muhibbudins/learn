<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_modules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('course_id');
            $table->integer('order')->default(0);
            $table->boolean('status')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('module_id')->references('id')->on('modules');
            $table->foreign('course_id')->references('id')->on('courses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_modules');
    }
}
