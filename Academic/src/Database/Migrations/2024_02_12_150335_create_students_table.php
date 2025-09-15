<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stdnts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('nis');
            $table->string('nisn')->nullable();
            $table->string('nik')->nullable();
            $table->integer('generation_id')->nullable();
            $table->date('entered_at')->nullable();
            $table->string('avatar')->nullable();
            $table->dateTime('graduated_at')->nullable();
            $table->string('graduated_avatar')->nullable();
            $table->foreignId('grade_id')->constrained('ref_grades')->onUpdate('cascade')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['user_id', 'nis']);

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('generation_id')->references('id')->on('acdmcs')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('stdnt_mutations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('student_id');
            $table->string('reason')->nullable();
            $table->timestamp('officiated_at')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('stdnts')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('stdnt_smts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('student_id');
            $table->bigInteger('semester_id');
            $table->bigInteger('classroom_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('stdnts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('semester_id')->references('id')->on('acdmc_semesters')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('classroom_id')->references('id')->on('acdmc_classrooms')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('stdnt_smt_asmts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('smt_id');
            $table->bigInteger('subject_id');
            $table->bigInteger('plan_id')->nullable();
            $table->smallInteger('type')->nullable();
            $table->string('description')->nullable();
            $table->float('value')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('smt_id')->references('id')->on('stdnt_smts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('acdmc_subjects')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('acdmc_subject_meet_plans')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('stdnt_smt_rprts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('smt_id');
            $table->bigInteger('subject_id');
            $table->float('value')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->integer('ki3_value')->nullable();
            $table->char('ki3_predicate', 1)->nullable();
            $table->text('ki3_description')->nullable();
            $table->integer('ki4_value')->nullable();
            $table->char('ki4_predicate', 1)->nullable();
            $table->text('ki4_description')->nullable();
            $table->text('comment')->nullable();
            $table->text('evaluation')->nullable();

            $table->foreign('smt_id')->references('id')->on('stdnt_smts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('acdmc_subjects')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('stdnt_smt_cases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('smt_id');
            $table->smallInteger('category_id');
            $table->string('witness')->nullable();
            $table->string('description')->nullable();
            $table->float('point')->nullable();
            $table->timestamp('break_at');
            $table->bigInteger('employee_id')->nullable();
            $table->timestamps();

            $table->foreign('smt_id')->references('id')->on('stdnt_smts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('acdmc_case_ctgs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('stdnt_smt_counselings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('smt_id');
            $table->smallInteger('category_id');
            $table->string('description')->nullable();
            $table->string('follow_up')->nullable();
            $table->bigInteger('employee_id');
            $table->timestamps();

            $table->foreign('smt_id')->references('id')->on('stdnt_smts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('acdmc_counseling_ctgs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('stdnt_package', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('student_id');
            $table->string('name');
            $table->tinyInteger('status');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('student_id')->references('id')->on('stdnts')->onUpdate('cascade')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stdnt_smt_counselings');
        Schema::dropIfExists('stdnt_smt_cases');
        Schema::dropIfExists('stdnt_smt_rprts');
        Schema::dropIfExists('stdnt_smt_asmts');
        Schema::dropIfExists('stdnt_smts');
        Schema::dropIfExists('stdnt_mutations');
        Schema::dropIfExists('stdnts');
    }
}
