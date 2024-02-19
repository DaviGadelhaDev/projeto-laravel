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
        Schema::table('contas', function (Blueprint $table) {
           $table->unsignedBigInteger('status_conta_id')->default(2)->after('vencimento');

           $table->foreign('status_conta_id')->references('id')->on('status_contas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('status_contas', function (Blueprint $table) {
            $table->dropColumn('status_conta_id');
         });
    }
};
