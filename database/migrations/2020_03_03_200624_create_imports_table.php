<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->string('filename',191)->nullable();
            $table->string('st01',191)->nullable();
            $table->string('st02',191)->nullable();
            $table->string('b201',191)->nullable();
            $table->string('b202',191)->nullable();
            $table->string('b204',191)->nullable();
            $table->string('b206',191)->nullable();
            $table->string('b2a01',191)->nullable();
            $table->string('l1101',191)->nullable();
            $table->string('l1102',191)->nullable();
            $table->string('l11_01',191)->nullable();
            $table->string('l11_02',191)->nullable();
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
        Schema::dropIfExists('imports');
    }
}
