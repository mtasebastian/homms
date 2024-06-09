<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialsPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financials_payment', function (Blueprint $table) {
            $table->id();
            $table->integer("financial_id");
            $table->string("reference_number", 50);
            $table->string("discount_type", 100)->nullable();
            $table->decimal("discount_amount", 20, 6)->default(0)->nullable();
            $table->string("mode_of_payment", 200);
            $table->decimal("payment", 20, 6)->default(0);
            $table->string("remarks")->nullable();
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
        Schema::dropIfExists('financials_payment');
    }
}
