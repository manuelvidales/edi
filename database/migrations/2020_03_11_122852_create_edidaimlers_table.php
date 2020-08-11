<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdidaimlersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edidaimlers', function (Blueprint $table) {
            $table->id();
            $table->string('filename',121)->nullable();
            $table->string('shipment_id',25)->nullable();
            $table->string('purpose_code',10)->nullable();
            $table->string('s5total',10)->nullable();
            $table->string('response',25)->nullable();
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
        Schema::dropIfExists('edidaimlers');
    }
}
