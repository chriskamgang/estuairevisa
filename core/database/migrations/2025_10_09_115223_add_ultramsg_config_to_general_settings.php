<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUltramsgConfigToGeneralSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('general_settings', 'ultramsg_instance_id')) {
                $table->string('ultramsg_instance_id')->nullable();
            }
            if (!Schema::hasColumn('general_settings', 'ultramsg_token')) {
                $table->string('ultramsg_token')->nullable();
            }
            if (!Schema::hasColumn('general_settings', 'whatsapp_notification')) {
                $table->boolean('whatsapp_notification')->default(1);
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
        Schema::table('general_settings', function (Blueprint $table) {
            if (Schema::hasColumn('general_settings', 'ultramsg_instance_id')) {
                $table->dropColumn('ultramsg_instance_id');
            }
            if (Schema::hasColumn('general_settings', 'ultramsg_token')) {
                $table->dropColumn('ultramsg_token');
            }
            if (Schema::hasColumn('general_settings', 'whatsapp_notification')) {
                $table->dropColumn('whatsapp_notification');
            }
        });
    }
}
