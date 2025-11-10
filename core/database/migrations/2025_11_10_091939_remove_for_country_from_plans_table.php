<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveForCountryFromPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            if (Schema::hasColumn('plans', 'for_country')) {
                $table->dropColumn('for_country');
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
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'for_country')) {
                $table->integer('for_country')->default(0)->after('country_ids');
            }
        });
    }
}
