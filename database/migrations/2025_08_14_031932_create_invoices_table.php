<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->foreignId('customer_id')->constrained()->onDelete('cascade');
        $table->decimal('amount', 10, 2);
        $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
        $table->date('payment_date')->nullable();
        $table->date('due_date');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('invoices');
}

};
