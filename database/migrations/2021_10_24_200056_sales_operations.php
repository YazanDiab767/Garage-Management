<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SalesOperations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_operations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('username');
            $table->unsignedBigInteger('customer_id');
            $table->json('data'); // echo row has ( part id and quantity )
            $table->double('price');
            $table->text('note')->nullable(); // note for this operation
            $table->double('discount_add')->default(0); // discount or add on sale operation - or +
            $table->dateTime('paid_at')->nullable(); // is the customer padi the price or not
            $table->timestamps();
        });

        Schema::table('sales_operations', function($table) {
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_operations');
    }
}
