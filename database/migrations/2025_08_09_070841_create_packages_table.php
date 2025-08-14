<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_packages_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('home'); // home, business, corporate
            $table->integer('speed_mbps');
            $table->string('quota')->default('Unlimited');
            $table->decimal('price', 10, 2);
            $table->string('status')->default('active'); // active, inactive
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('packages');
    }
};
