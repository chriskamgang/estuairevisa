<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeeplSettingsToGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->string('deepl_api_key')->nullable()->after('whatsapp_notification');
            $table->enum('deepl_api_type', ['free', 'pro'])->default('free')->after('deepl_api_key');
            $table->boolean('deepl_status')->default(false)->after('deepl_api_type');
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
            $table->dropColumn(['deepl_api_key', 'deepl_api_type', 'deepl_status']);
        });
    }
}
