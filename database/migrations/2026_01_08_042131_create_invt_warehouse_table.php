<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::create('invt_warehouse', function (Blueprint $table) {
            $table->integer('warehouse_id', true)->primary(); // Auto-incrementing primary key
            $table->integer('company_id')->nullable();
            $table->string('warehouse_code', 50)->nullable();
            $table->string('warehouse_name', 255)->nullable();
            $table->tinyInteger('data_state')->default(0);
            $table->bigInteger('branch_id')->nullable();
            $table->integer('created_id')->nullable();
            $table->integer('updated_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique('warehouse_id');
            $table->index(['company_id']);
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invt_warehouse');
    }
};
