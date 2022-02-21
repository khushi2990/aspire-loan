<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKycVerification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kyc_verification', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->enum('kyc_type',['passport', 'pan']);
            $table->string('number')->description('Passport number or pan number');
            $table->string('file');
            $table->smallInteger('verification_status');
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
        Schema::dropIfExists('kyc_verification');
    }
}
