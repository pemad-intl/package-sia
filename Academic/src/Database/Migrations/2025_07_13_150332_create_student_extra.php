<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentExtra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
         Schema::create('stdnt_smt_extras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedbigInteger('smt_id');
            $table->unsignedbigInteger('student_id');
            $table->unsignedBigInteger('classroom_id');
            $table->text('name');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('smt_id')->references('id')->on('stdnt_smts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('stdnts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('classroom_id')->references('id')->on('acdmc_classrooms')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('stdnt_smt_achievements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedbigInteger('smt_id');
            $table->unsignedbigInteger('student_id');
            $table->unsignedBigInteger('classroom_id');
            $table->text('name');
            $table->date('date');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('smt_id')->references('id')->on('stdnt_smts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('stdnts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('classroom_id')->references('id')->on('acdmc_classrooms')->onUpdate('cascade')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stdnt_smt_evaluation');
    }
}
