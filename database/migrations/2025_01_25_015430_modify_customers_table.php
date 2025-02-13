<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCustomersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Drop email and password columns
            $table->dropColumn(['email', 'password']);

            // Add new columns for address and ID number
            $table->string('address')->nullable()->after('name'); // Add after name
            $table->string('no_ktp')->unique()->after('address'); // Add after address
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Add back email and password columns
            $table->string('email')->unique()->after('name');
            $table->string('password')->after('email');

            // Drop address and ID number columns
            $table->dropColumn(['address', 'no_ktp']);
        });
    }
}
