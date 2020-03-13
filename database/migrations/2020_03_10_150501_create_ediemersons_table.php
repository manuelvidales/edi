<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdiemersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('ediemersons', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('id_qualifier_sender',35)->nullable();
        //     $table->string('id_sender',35)->nullable();
        //     $table->string('id_qualifier_receiver',35)->nullable();
        //     $table->string('id_receiver',35)->nullable();
        //     $table->string('version_number',35)->nullable();
        //     $table->string('control_number',35)->nullable();
        //     $table->string('sender_code',35)->nullable();
        //     $table->string('agency_code',35)->nullable();
        //     $table->string('industry_identifier',35)->nullable();
        //     $table->string('alpha_code',35)->nullable();
        //     $table->string('shipment_identification_number',35)->nullable();
        //     $table->string('method_payment',35)->nullable();
        //     $table->string('reference_identification',35)->nullable();
        //     $table->string('reference_identification_qualifier',35)->nullable();
        //     $table->string('trailer',35)->nullable();
        //     $table->string('reference_identification_qualifier_shipper',35)->nullable();
        //     $table->string('client',66)->nullable();
        //     $table->string('equipment_number',35)->nullable();
        //     $table->string('stop_number_load',35)->nullable();
        //     $table->string('stop_reason_code_load',35)->nullable();
        //     $table->string('weight_load',35)->nullable();
        //     $table->string('weight_units_load',35)->nullable();
        //     $table->string('quantity_load',35)->nullable();
        //     $table->string('unit_for_measurement_load',35)->nullable();
        //     $table->string('volume_load',35)->nullable();
        //     $table->string('volume_unit_qualifier_load',35)->nullable();
        //     $table->string('load_date_qualifier_1',35)->nullable();
        //     $table->string('load_date_1',35)->nullable();
        //     $table->string('load_time_qualifier_1',35)->nullable();
        //     $table->string('load_time_1',35)->nullable();
        //     $table->string('load_date_qualifier_2',35)->nullable();
        //     $table->string('load_date_2',35)->nullable();
        //     $table->string('load_time_qualifier_2',35)->nullable();
        //     $table->string('load_time_2',35)->nullable();
        //     $table->string('origin',35)->nullable();
        //     $table->string('addres_origin',35)->nullable();
        //     $table->string('city_origin',35)->nullable();
        //     $table->string('state_origin',35)->nullable();
        //     $table->string('postal_code_origin',35)->nullable();
        //     $table->string('country_origin',35)->nullable();
        //     $table->string('oid_reference_load',35)->nullable();
        //     $table->string('oid_purchase_load',35)->nullable();
        //     $table->string('oid_unit_for_measurement_load',35)->nullable();
        //     $table->string('oid_quantity_load',35)->nullable();
        //     $table->string('oid_weight_unit_code_load',35)->nullable();
        //     $table->string('oid_weight_load',35)->nullable();
        //     $table->string('oid_volume_unit_qualifier_load',35)->nullable();
        //     $table->string('oid_volume_load',35)->nullable();
        //     $table->string('stop_number_stop1',35)->nullable();
        //     $table->string('stop_reason_code_stop1',35)->nullable();
        //     $table->string('weight_stop1',35)->nullable();
        //     $table->string('weight_units_stop1',35)->nullable();
        //     $table->string('quantity_stop1',35)->nullable();
        //     $table->string('unit_for_measurement_stop1',35)->nullable();
        //     $table->string('volume_stop1',35)->nullable();
        //     $table->string('volume_unit_qualifier_stop1',35)->nullable();
        //     $table->string('stop1_date_qualifier',35)->nullable();
        //     $table->string('stop1_date',35)->nullable();
        //     $table->string('stop1_time_qualifier',35)->nullable();
        //     $table->string('stop1_time',35)->nullable();
        //     $table->string('stop1_time_code',35)->nullable();
        //     $table->string('stop1',35)->nullable();
        //     $table->string('addres_stop1',66)->nullable();
        //     $table->string('city_stop1',35)->nullable();
        //     $table->string('state_stop1',35)->nullable();
        //     $table->string('postal_code_stop1',35)->nullable();
        //     $table->string('country_stop1',35)->nullable();
        //     $table->string('oid_reference_stop1',35)->nullable();
        //     $table->string('oid_purchase_stop1',35)->nullable();
        //     $table->string('oid_unit_for_measurement_stop1',35)->nullable();
        //     $table->string('oid_quantity_stop1',35)->nullable();
        //     $table->string('oid_weight_unit_code_stop1',35)->nullable();
        //     $table->string('oid_weight_stop1',35)->nullable();
        //     $table->string('oid_volume_unit_qualifier_stop1',35)->nullable();
        //     $table->string('oid_volume_stop1',35)->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ediemersons');
    }
}
