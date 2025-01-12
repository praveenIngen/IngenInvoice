<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('seller_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0);
            $table->integer('vender_id')->default(0);
            $table->integer('customer_id')->default(0);
            $table->string('trade_name')->nullable();
            $table->string('vat_registration_number')->nullable();
            $table->string('business_registration_number')->nullable();
            $table->string('business_address')->nullable();
            $table->string('contact_number')->default(0);
            $table->string('ebs_counter_number')->default(0);
            $table->string('cashierid')->default(0);
            $table->integer('created_by')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_details');
    }
};
