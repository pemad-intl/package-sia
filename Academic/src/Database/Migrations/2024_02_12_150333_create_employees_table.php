<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Traits\Metable\MetableSchema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empls', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('kd')->nullable()->unique();
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('permanent_at')->nullable();
            $table->string('permanent_kd')->nullable();
            $table->string('permanent_sk')->nullable();
            $table->timestamp('exited_at')->nullable();
            $table->string('nip')->nullable();
            $table->string('group')->nullable();
            $table->string('sk_number')->nullable();
            $table->date('entered_at')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        MetableSchema::create('empl_meta', 'empl_id', 'empls', 'unsignedSmallInteger');

        Schema::create('empl_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('empls')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->string('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('empl_asmts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('empls')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->double('scale')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('empl_teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('empls')->onUpdate('cascade')->onDelete('cascade');
            $table->string('nuptk')->nullable();
            $table->date('teaching_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['employee_id']);
        });

        Schema::create('empl_teacher_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('empl_teachers')->onUpdate('cascade')->onDelete('cascade');
            $table->string('reason')->nullable();
            $table->timestamp('officiated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('empl_contracts', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('empl_id');
            $table->string('kd')->nullable();
            $table->unsignedTinyInteger('contract_id');
            $table->unsignedTinyInteger('work_location')->default(1);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique('kd');
            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('contract_id')->references('id')->on('cmp_contracts')->onUpdate('cascade')->onDelete('cascade');
        });

        MetableSchema::create('empl_contract_meta', 'contract_id', 'empl_contracts', 'unsignedSmallInteger');

        Schema::create('empl_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('empl_id');
            $table->date('start_at');
            $table->date('end_at');
            $table->jsonb('dates')->nullable();
            $table->unsignedTinyInteger('workdays_count')->default(0);
            $table->timestamps();

            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
            $table->unique(['empl_id', 'start_at', 'end_at']);
        });

        Schema::create('empl_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('empl_id');
            $table->unsignedSmallInteger('position_id');
            $table->unsignedSmallInteger('contract_id')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('cmp_positions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('contract_id')->references('id')->on('empl_contracts')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('empl_vacation_quotas', function (Blueprint $table) {
            $table->smallincrements('id');
            $table->unsignedSmallInteger('empl_id');
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->unsignedTinyInteger('ctg_id');
            $table->unsignedTinyInteger('quota')->nullable();
            $table->timestamp('visible_at');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ctg_id')->references('id')->on('cmp_vacation_ctgs')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('empl_vacations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('quota_id');
            $table->jsonb('dates')->nullable();
            $table->text('description')->nullable();
            $table->text('history')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('quota_id')->references('id')->on('empl_vacation_quotas')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('empl_leaves', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('empl_id');
            $table->unsignedTinyInteger('ctg_id');
            $table->jsonb('dates')->nullable();
            $table->text('description')->nullable();
            $table->string('attachment')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ctg_id')->references('id')->on('cmp_leave_ctgs')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('empl_overtimes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('empl_id');
            $table->unsignedSmallInteger('scheduled_by')->nullable();
            $table->string('name')->nullable();
            $table->text('schedules')->nullable();
            $table->text('dates')->nullable();
            $table->text('description')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('paidable_at')->nullable();
            $table->decimal('paid_amount', 20, 2)->nullable();
            $table->timestamp('paid_off_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('scheduled_by')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('empl_outworks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('empl_id');
            $table->unsignedTinyInteger('ctg_id');
            $table->string('name')->nullable();
            $table->text('dates')->nullable();
            $table->text('description')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamp('paidable_at')->nullable();
            $table->float('paid_amount', 9, 2)->nullable();
            $table->timestamp('paid_off_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('ctg_id')->references('id')->on('cmp_outwork_ctgs')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('empl_insurances', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kd')->nullable()->unique();
            $table->unsignedSmallInteger('empl_id');
            $table->unsignedSmallInteger('price_id');
            $table->double('cmp_price')->default(0);
            $table->double('empl_price')->default(0);
            $table->text('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('price_id')->references('id')->on('cmp_insurance_prices')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('empl_salary_templates', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('empl_id');
            $table->unsignedSmallInteger('cmp_template_id');
            $table->string('name')->nullable();
            $table->string('prefix')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('cmp_template_id')->references('id')->on('cmp_salary_templates')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('empl_salary_template_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('template_id');
            $table->unsignedSmallInteger('component_id');
            $table->unsignedTinyInteger('slip_az')->nullable();
            $table->string('slip_name')->nullable();
            $table->unsignedTinyInteger('ctg_az')->nullable();
            $table->string('ctg_name')->nullable();
            $table->unsignedTinyInteger('az')->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->double('amount')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('empl_salary_templates')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('component_id')->references('id')->on('cmp_salary_slip_cmpnts')->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::create('empl_salaries', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('empl_id');
            $table->string('name')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->text('components')->nullable();
            $table->double('amount', 12, 2)->nullable();
            $table->string('file')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->string('complain')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('empl_data_recaps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('empl_id');
            $table->unsignedTinyInteger('type');
            $table->date('start_at');
            $table->date('end_at');
            $table->jsonb('result')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('empl_taxs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('empl_id');
            $table->unsignedTinyInteger('type');
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->timestamp('released_at')->nullable();
            $table->text('meta')->nullable();
            $table->string('file')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('empl_loans', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('empl_id');
            $table->unsignedTinyInteger('ctg_id');
            $table->unsignedSmallInteger('parent_id');
            $table->text('description')->nullable();
            $table->double('amount_total', 12, 2)->nullable();
            $table->unsignedSmallInteger('tenor')->nullable();
            $table->unsignedTinyInteger('tenor_by')->nullable();
            $table->timestamp('submission_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->date('start_at')->nullable();
            $table->text('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('ctg_id')->references('id')->on('cmp_loan_ctgs')->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::create('empl_loan_installments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('loan_id');
            $table->timestamp('bill_at')->nullable();
            $table->timestamp('paid_off_at')->nullable();
            $table->double('amount')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('loan_id')->references('id')->on('empl_loans')->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::create('empl_loan_inst_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('installment_id');
            $table->unsignedTinyInteger('method')->nullable();
            $table->boolean('is_cash')->default(0);
            $table->double('amount')->nullable();
            $table->unsignedSmallInteger('payer_id')->nullable();
            $table->unsignedSmallInteger('recipient_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('payer_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('recipient_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('installment_id')->references('id')->on('empl_loan_installments')->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::create('empl_deductions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('empl_id');
            $table->unsignedTinyInteger('type');
            $table->unsignedSmallInteger('component_id');
            $table->float('amount', 9, 2);
            $table->text('description')->nullable();
            $table->timestamp('paid_at');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('component_id')->references('id')->on('cmp_salary_slip_cmpnts')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('empl_schedule_submissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('empl_id');
            $table->date('start_at');
            $table->date('end_at');
            $table->text('dates')->nullable();
            $table->unsignedTinyInteger('workdays_count')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('empl_recap_submissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('empl_id');
            $table->unsignedTinyInteger('type');
            $table->date('start_at');
            $table->date('end_at');
            $table->text('result')->nullable();
            $table->softDeletes();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('recaped_at')->nullable();
            $table->timestamps();

            $table->foreign('empl_id')->references('id')->on('empls')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {}
};
