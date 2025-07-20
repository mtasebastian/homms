<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFinancialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financials', function (Blueprint $table) {
            // Add
            $table->integer('bill_year')->after('resident_id');
            $table->integer('bill_month')->after('bill_year');
            $table->date('due_date')->after('bill_month');

            // Remove
            $table->dropColumn('bill_period');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Return removed field
            $table->date('bill_period');

            // Remove new fields
            $table->dropColumn(['bill_year', 'bill_month', 'due_date']);
        });
    }
}
