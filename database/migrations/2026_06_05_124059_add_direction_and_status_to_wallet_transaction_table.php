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
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->enum('direction', ['credit', 'debit'])
                ->default('credit')
                ->after('type');

            $table->enum('status', ['completed', 'failed', 'pending'])
                ->default('completed')
                ->after('balance_after');
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet_transaction', function (Blueprint $table) {
            $table->dropColumn(['direction', 'status']);
        });
    }
};
