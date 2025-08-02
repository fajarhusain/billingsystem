<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Cek apakah tabel sudah ada
        if (!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('phone');
                $table->string('email')->nullable();
                $table->text('address');
                $table->foreignId('package_id')->constrained()->onDelete('cascade');
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
                $table->date('registration_date');
                $table->date('installation_date')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};