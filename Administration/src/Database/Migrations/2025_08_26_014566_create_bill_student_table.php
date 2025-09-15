<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillStudentTable extends Migration
{
    public function up()
    {
        Schema::create('sch_bill_batchs', function(Blueprint $table){
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('grade_id');
            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('semester_id');
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('semester_id')->references('id')->on('acdmc_semesters')->onDelete('cascade');
        });

        Schema::create('sch_bill_references', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('batch_id');
            $table->string('kd')->unique();
            $table->string('name');
            $table->unsignedTinyInteger('type')->comment('1=Debit, 2=Credit');
            $table->unsignedTinyInteger('type_class')->comment('1=Smp, 2=Sma');
            $table->unsignedTinyInteger('payment_category');
            $table->unsignedTinyInteger('payment_cycle');
            $table->decimal('price', 20, 2);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('batch_id')->references('id')->on('sch_bill_batchs')->onDelete('cascade');
        });

        Schema::create('sch_bill_students', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('batch_id');
            $table->unsignedbigInteger('smt_id');
            $table->jsonb('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('smt_id')->references('id')->on('stdnt_smts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('batch_id')->references('id')->on('sch_bill_batchs')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down(){
        Schema::dropIfExists('sch_bill_batchs');
        Schema::dropIfExists('sch_bill_references');
        Schema::dropIfExists('sch_bill_students');
    }
};