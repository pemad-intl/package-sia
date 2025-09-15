<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportEvalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('stdnt_smt_evaluation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('smt_id');
            $table->text('subject_note')->nullable();
            $table->text('recommendation_note')->nullable();
            $table->integer('grade')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('smt_id')->references('id')->on('stdnt_smts')->onUpdate('cascade')->onDelete('cascade');
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
