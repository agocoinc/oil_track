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
        Schema::create('equipment_details', function (Blueprint $table) {
            $table->id();

            $table->string('loc_name', 150)->nullable();
            $table->string('details_aname', 300)->nullable();
            $table->string('details_lname', 300)->nullable();
            $table->unsignedInteger('details_qty')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->string('note', 300)->nullable();

            $table->softDeletes();

            $table->foreignId('equipment_category_id')
                ->constrained('equipment_categories')
                ->after('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_details');
    }
};
