<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolsTable extends Migration
{
    public function up()
    {
        Schema::create('sch_buildings', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('kd')->unique();
            $table->unsignedSmallInteger('grade_id');
            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('village')->nullable();
            $table->unsignedInteger('district_id')->nullable();
            $table->bigInteger('postal')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('district_id')
                ->references('id')->on('ref_province_regency_districts')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('sch_building_rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('building_id');
            $table->smallInteger('grade_id');
            $table->string('kd');
            $table->string('name');
            $table->smallInteger('capacity')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['building_id', 'kd']);
            $table->foreign('building_id')->references('id')->on('sch_buildings')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('sch_building_room_asset_ctgs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
        });

        Schema::create('sch_building_room_assets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('room_id');
            $table->string('name');
            $table->bigInteger('ctg_id')->nullable();
            $table->bigInteger('count')->nullable();
            $table->smallInteger('condition')->nullable();
            $table->boolean('viceable')->default(false);
            $table->timestamps();

            $table->foreign('room_id')->references('id')->on('sch_building_rooms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ctg_id')->references('id')->on('sch_building_room_asset_ctgs')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('sch_building_room_asset_brws', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('asset_id');
            $table->morphs('borrowable');
            $table->timestamps();

            $table->foreign('asset_id')->references('id')->on('sch_building_room_assets')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('sch_curriculas', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('kd')->unique();
            $table->unsignedSmallInteger('grade_id');
            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->year('year')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('sch_fclt_ctgs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
        });

        Schema::create('sch_fclts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kd')->unique();
            $table->string('name')->nullable();
            $table->bigInteger('ctg_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('ctg_id')->references('id')->on('sch_fclt_ctgs')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('sch_fclt_ops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('fclt_id');
            $table->bigInteger('user_id');
            $table->string('as')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('fclt_id')->references('id')->on('sch_fclts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('sch_fclt_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('fclt_id');
            $table->bigInteger('applicant_id');
            $table->string('purpose')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->bigInteger('accepter_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('fclt_id')->references('id')->on('sch_fclts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('applicant_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('accepter_id')->references('id')->on('sch_fclt_ops')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sch_fclt_requests');
        Schema::dropIfExists('sch_fclt_ops');
        Schema::dropIfExists('sch_fclts');
        Schema::dropIfExists('sch_fclt_ctgs');
        Schema::dropIfExists('sch_curriculas');
        Schema::dropIfExists('sch_building_room_asset_brws');
        Schema::dropIfExists('sch_building_room_assets');
        Schema::dropIfExists('sch_building_room_asset_ctgs');
        Schema::dropIfExists('sch_building_rooms');
        Schema::dropIfExists('sch_buildings');
    }
}
