<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicsTypeEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('acdmc_smt_type_evaluations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->bigInteger('smt_id');
            $table->unsignedBigInteger('meet_id');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('smt_id')->references('id')->on('acdmc_subject_meet_plans')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('meet_id')->references('id')->on('acdmc_subject_meets')->onUpdate('cascade')->onDelete('cascade');
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
