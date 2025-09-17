<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('acdmcs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('year');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('acdmc_semesters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('acdmc_id');
            $table->string('name');
            $table->boolean('open')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('acdmc_id')->references('id')->on('acdmcs')->onUpdate('cascade')->onDelete('cascade');
        });

        // Schema::table('user_roles', function (Blueprint $table) {
        //     $table->unsignedBigInteger('semester_id')->nullable();

        //     $table->dropForeign(['role_id']);
        //     $table->dropForeign(['user_id']);
        //     $table->dropPrimary();

        //     $table->primary(['user_id', 'semester_id']);
        //     $table->foreign('semester_id')->references('id')->on('acdmc_semesters')->onUpdate('cascade')->onDelete('cascade');
        // });


        Schema::create('acdmc_semester_metas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('semester_id');
            $table->string('key');
            $table->string('content')->nullable();
            $table->timestamps();

            $table->foreign('semester_id')->references('id')->on('acdmc_semesters')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('acdmc_calendar_ctgs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('acdmc_id');
            $table->string('name');

            $table->foreign('acdmc_id')->references('id')->on('acdmcs')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('acdmc_calendars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('acdmc_id');
            $table->date('date');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->boolean('holiday')->default(false);

            $table->foreign('acdmc_id')->references('id')->on('acdmcs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('acdmc_calendar_ctgs')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::create('acdmc_majors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('semester_id');
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('semester_id')->references('id')->on('acdmc_semesters')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('acdmc_superiors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('semester_id');
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('semester_id')->references('id')->on('acdmc_semesters')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('acdmc_classrooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('semester_id');
            $table->smallInteger('level_id');
            $table->string('name');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->unsignedBigInteger('major_id')->nullable();
            $table->unsignedBigInteger('superior_id')->nullable();
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('semester_id')->references('id')->on('acdmc_semesters')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('superior_id')->references('id')->on('acdmc_superiors')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('major_id')->references('id')->on('acdmc_majors')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('level_id')->references('id')->on('ref_grade_levels')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('sch_building_rooms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('supervisor_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('acdmc_classroom_presences', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('classroom_id');
            $table->date('presenced_at');
            $table->text('presence');
            $table->unsignedSmallInteger('presenced_by');

            $table->foreign('classroom_id')->references('id')->on('acdmc_classrooms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('presenced_by')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('acdmc_subject_ctgs', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::create('acdmc_subjects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kd');
            $table->string('name')->nullable();
            $table->unsignedBigInteger('semester_id');
            $table->smallInteger('level_id');
            $table->smallInteger('category_id')->nullable();
            $table->smallInteger('score_standard');
            $table->string('color_id', 10)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('semester_id')->references('id')->on('acdmc_semesters')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('level_id')->references('id')->on('ref_grade_levels')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('acdmc_subject_ctgs')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::create('acdmc_subject_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('assist_id')->nullable();
            $table->unsignedBigInteger('classroom_id')->nullable();
            $table->smallInteger('day')->nullable();
            $table->time('start_at')->nullable();
            $table->time('end_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('acdmc_subjects')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('empl_teachers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('assist_id')->references('id')->on('empl_teachers')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('acdmc_subject_comps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subject_id');
            $table->string('kd')->nullable();
            $table->string('name')->nullable();
            $table->text('indicators')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('acdmc_subjects')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('acdmc_subject_meets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('semester_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('classroom_id')->nullable();
            $table->text('props')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('semester_id')->references('id')->on('acdmc_semesters')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('acdmc_subjects')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('classroom_id')->references('id')->on('acdmc_classrooms')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('acdmc_subject_meet_metas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('meet_id');
            $table->string('key');
            $table->string('content')->nullable();
            $table->timestamps();

            $table->foreign('meet_id')->references('id')->on('acdmc_subject_meets')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('acdmc_subject_meet_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('meet_id');
            $table->smallInteger('az')->nullable();
            $table->date('plan_at')->nullable();
            $table->smallInteger('hour')->nullable();
            $table->timestamp('realized_at')->nullable();
            $table->unsignedBigInteger('comp_id')->nullable();
            $table->boolean('test')->default(false);
            $table->text('presence')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('meet_id')->references('id')->on('acdmc_subject_meets')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('comp_id')->references('id')->on('acdmc_subject_comps')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('acdmc_case_ctgs', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('acdmc_case_ctg_descs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('ctg_id');
            $table->string('name');
            $table->float('point')->nullable();
            $table->timestamps();

            $table->foreign('ctg_id')->references('id')->on('acdmc_case_ctgs')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('acdmc_counseling_ctgs', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acdmc_counseling_ctgs');
        Schema::dropIfExists('acdmc_case_ctg_descs');
        Schema::dropIfExists('acdmc_case_ctgs');
        Schema::dropIfExists('acdmc_subject_meet_plans');
        Schema::dropIfExists('acdmc_subject_meets');
        Schema::dropIfExists('acdmc_subject_comps');
        Schema::dropIfExists('acdmc_subject_schedules');
        Schema::dropIfExists('acdmc_subjects');
        Schema::dropIfExists('acdmc_subject_ctgs');
        Schema::dropIfExists('acdmc_classrooms');
        Schema::dropIfExists('acdmc_superiors');
        Schema::dropIfExists('acdmc_majors');
        Schema::dropIfExists('acdmc_calendars');
        Schema::dropIfExists('acdmc_calendar_ctgs');
        Schema::dropIfExists('acdmc_semester_metas');
        Schema::dropIfExists('acdmc_semesters');
        Schema::dropIfExists('acdmcs');
    }
}
