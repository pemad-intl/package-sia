<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardingTable extends Migration
{
    public function up()
    {
        // pemilihan tempat asrama
        Schema::create('sch_boarding_student_buildings', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('empl_id');
            $table->unsignedSmallInteger('building_id');
            $table->bigInteger('student_id');
            $table->bigInteger('room_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('room_id')->references('id')->on('sch_building_rooms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('stdnts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('building_id')->references('id')->on('sch_buildings')->onUpdate('cascade')->onDelete('cascade');
        });

        // ijin siswa
        Schema::create('sch_boarding_stdnts_leaves', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('student_id');
            $table->unsignedTinyInteger('ctg_id');
            $table->jsonb('dates')->nullable();
            $table->text('description')->nullable();
            $table->string('attachment')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('stdnts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ctg_id')->references('id')->on('cmp_leave_stdnts_ctgs')->onUpdate('cascade')->onDelete('cascade');
        });


        // kegiatan
        Schema::create('sch_boarding_event', function(Blueprint $table){
            $table->smallIncrements('id');
            $table->smallInteger('type');
            $table->string('name');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('in')->nullable();
            $table->time('out')->nullable();
            $table->smallInteger('type_participant');
            $table->softDeletes();
            $table->timestamps();
        });

         Schema::create('sch_boarding_student_event', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->bigInteger('student_id');
            $table->morphs('modelable');
            $table->unsignedSmallInteger('teacher_id')->nullable();
            $table->unsignedSmallInteger('supervisor_id')->nullable();
            //$table->unsignedSmallInteger('empl_id');
            $table->unsignedSmallInteger('event_id');
            $table->softDeletes();
            $table->timestamps();

            //$table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('sch_boarding_event')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('supervisor_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('sch_boarding_approvable', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('modelable');
            $table->morphs('userable');
            $table->unsignedTinyInteger('level')->default(1);
            $table->unsignedTinyInteger('cancelable')->default(0);
            $table->unsignedTinyInteger('result')->default(0);
            $table->text('reason')->nullable();
            $table->text('history')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sch_boarding_student_buildings');
        Schema::dropIfExists('sch_boarding_stdnts_leaves');
        Schema::dropIfExists('sch_boarding_event');
        Schema::dropIfExists('sch_boarding_student_event');
    }
}
