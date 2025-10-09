<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFreemopayColumnsToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Ajout des colonnes Freemopay à la table payments
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'freemopay_reference')) {
                $table->string('freemopay_reference')->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('payments', 'freemopay_status')) {
                $table->string('freemopay_status')->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('payments', 'freemopay_message')) {
                $table->text('freemopay_message')->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('payments', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('transaction_id');
            }
        });

        // Ajout des colonnes Freemopay à la table deposits
        Schema::table('deposits', function (Blueprint $table) {
            if (!Schema::hasColumn('deposits', 'freemopay_reference')) {
                $table->string('freemopay_reference')->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('deposits', 'freemopay_status')) {
                $table->string('freemopay_status')->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('deposits', 'freemopay_message')) {
                $table->text('freemopay_message')->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('deposits', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('transaction_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['freemopay_reference', 'freemopay_status', 'freemopay_message', 'payment_proof', 'payment_reference']);
        });

        Schema::table('deposits', function (Blueprint $table) {
            $table->dropColumn(['freemopay_reference', 'freemopay_status', 'freemopay_message', 'payment_proof', 'payment_reference']);
        });
    }
}
