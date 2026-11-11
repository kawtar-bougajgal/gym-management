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
        Schema::create('members', function (Blueprint $table) {
            $table->id();  // Adds an auto-incrementing primary key 'id'
            $table->string('name');  // Adds 'name' column (string type)
            $table->string('email')->unique();  // Adds 'email' column with unique constraint
            $table->string('phone');  // Adds 'phone' column
            $table->string('status')->default('active');  // Adds 'status' column with default value
            $table->timestamps();  // Adds 'created_at' and 'updated_at' timestamp columns

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
