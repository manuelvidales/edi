<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdidaimlertracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edidaimlertracks', function (Blueprint $table) {
            $table->id();
            $table->string('code',10)->nullable();
            $table->string('filename',121)->nullable();
            $table->string('id_incremental',10)->nullable();
            $table->string('shipment_identification_number',25)->nullable();
            $table->string('alpha_code',10)->nullable();
            $table->string('status_code',10)->nullable();
            $table->string('reason_code',10)->nullable();
            $table->string('reference_identification',10)->nullable();
            $table->string('longitude',10)->nullable();
            $table->string('code_longitude',10)->nullable();
            $table->string('latitude',10)->nullable();
            $table->string('code_latitude',10)->nullable();
            $table->string('unidad',10)->nullable();
            $table->string('equipment',10)->nullable();
            $table->string('id_qualifier_sender',10)->nullable();
            $table->string('id_sender',20)->nullable();
            $table->string('id_qualifier_receiver',10)->nullable();
            $table->string('id_receiver',20)->nullable();
            $table->string('version_number',10)->nullable();
            $table->string('control_number',10)->nullable();
            $table->string('sender_code',15)->nullable();
            $table->string('agency_code',10)->nullable();
            $table->string('industry_identifier',10)->nullable();
            $table->string('date_time',30)->nullable();
            $table->string('idnew',10)->nullable();
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
        Schema::dropIfExists('edidaimlertracks');
    }
}
