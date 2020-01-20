<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description');
            $table->float('value');
            $table->date('date');
            $table->text('note');
            $table->tinyInteger('type');
            $table->unsignedBigInteger('account_origin_id');
            $table->unsignedBigInteger('account_destiny_id')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            $table->foreign('account_origin_id')->references('id')->on('accounts');
            $table->foreign('account_destiny_id')->references('id')->on('accounts');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
