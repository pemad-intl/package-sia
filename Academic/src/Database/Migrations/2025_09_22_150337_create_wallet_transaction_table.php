<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
         Schema::create('student_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                  ->constrained('stdnts')
                  ->cascadeOnDelete();
            $table->decimal('balance', 18, 2)->default(0);
            $table->string('currency', 10)->default('IDR');
            $table->unsignedBigInteger('last_transaction_id')->nullable();
            $table->timestamps();
        });

        Schema::create('student_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')
                  ->constrained('student_wallets')
                  ->cascadeOnDelete();

            //Kalau nambah saldo top up = credit
            //Membayar SPP, kantin = debit (pengurang)
            $table->enum('type', ['credit', 'debit']);
            $table->decimal('amount', 18, 2);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_type', 100)->nullable();
            $table->decimal('balance_after', 18, 2);
            $table->timestamps();
        });

        Schema::create('student_wallet_topups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')
                  ->constrained('student_wallets')
                  ->cascadeOnDelete();
            $table->decimal('amount', 18, 2);
            $table->string('method', 50)->nullable(); // ex: transfer, cash, qris

            //orang tua lalu -> pihak bank -> lalu pihak bank approve, keputusan saldo tersebut akan dikirim lewat API
            $table->string('status', 20)->default('pending');
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
        Schema::dropIfExists('student_wallets');
        Schema::dropIfExists('student_wallet_transactions');
        Schema::dropIfExists('student_wallet_topups');
    }
}
