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

        Schema::create('preference_company', function (Blueprint $table) {
            $table->integer('company_id')->primary();
            $table->string('company_name', 100)->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_phone', 20)->nullable();
            $table->string('company_email', 50)->nullable();
            $table->integer('data_state')->default(0);
            $table->timestamps();
            $table->integer('created_id')->nullable();
            $table->integer('updated_id')->nullable();
        });

        // Insert default company
        DB::table('preference_company')->insert([
            'company_id' => 1,
            'company_name' => 'Default Company',
            'company_address' => 'Default Address',
            'company_phone' => '1234567890',
            'company_email' => 'info@company.com',
            'data_state' => 0,
            'created_at' => now(),
            'updated_at' => now(),
            'created_id' => 1,
            'updated_id' => 1,
        ]);

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::dropIfExists('preference_company');

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
