<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('original_number')->nullable();
            $table->string('description')->nullable();
            $table->string('number')->nullable(); // number of part
            $table->string('name')->nullable();
            $table->integer('count'); // count of parts
            $table->unsignedBigInteger('supplier_id'); // supplier who supply this part
            $table->string('qr_code')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('place_id'); // place of part
            $table->double('orignal_price');
            $table->double('selling_price');
            $table->timestamps();
        });

        Schema::table('parts', function($table) {
            $table->foreign('supplier_id')
            ->references('id')
            ->on('suppliers')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('place_id')
            ->references('id')
            ->on('places')
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
        Schema::dropIfExists('parts');
    }
}
