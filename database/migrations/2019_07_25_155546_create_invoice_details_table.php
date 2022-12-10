<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('invoice_date');
            $table->string('invoice_no');
            $table->string('order_no');
            $table->string('orderinc_no');
            $table->string('item_name');
            $table->string('sku');
            $table->string('hsn');
            $table->string('total_qty');
            $table->string('total_value');
            $table->string('taxable_val');
            $table->string('igst');
            $table->string('cgst');
            $table->string('sgst');
            $table->string('state');
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
        Schema::dropIfExists('invoice_details');
    }
}
