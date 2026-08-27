<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mpesa_p_s', function (Blueprint $table) {
            $table->id();
            $table->string('TransactionType');
            $table->string('trackingID');
            $table->string('TransAmount');
            $table->string('MpesaReceiptNumber');
            $table->string('TransactionDate');
            $table->string('PhoneNumber');
            $table->string('response');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_p_s');
    }
};
