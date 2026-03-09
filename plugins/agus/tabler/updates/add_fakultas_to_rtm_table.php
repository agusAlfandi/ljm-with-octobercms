<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddFakultasToRtmTable extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_rtm', function ($table) {
            $table->string('fakultas')->nullable()->after('level');
        });
    }

    public function down()
    {
        Schema::table('agus_tabler_rtm', function ($table) {
            $table->dropColumn('fakultas');
        });
    }
}
