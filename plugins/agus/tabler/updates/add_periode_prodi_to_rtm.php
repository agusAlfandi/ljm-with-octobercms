<?php
namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddPeriodeProdiToRtm extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_rtm', function ($table) {
            $table->string('periode')->nullable();
            $table->string('prodi')->nullable();
        });
    }

    public function down()
    {
        Schema::table('agus_tabler_rtm', function ($table) {
            $table->dropColumn('periode');
            $table->dropColumn('prodi');
        });
    }
}
