<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->dateTime('invoice_date');
            $table->string('billing_address', 70);
            $table->string('billing_city', 40);
            $table->string('billing_state', 40)->nullable();
            $table->string('billing_country', 40);
            $table->string('billing_postalcode', 10)->nullable();
            $table->decimal('total', 10, 2);
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice');
    }
}
