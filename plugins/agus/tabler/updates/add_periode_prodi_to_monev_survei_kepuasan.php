<?php
namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddPeriodeProdiToMonevSurveiKepuasan extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_monev_survei_kepuasan', function ($table) {
            if (!Schema::hasColumn('agus_tabler_monev_survei_kepuasan', 'periode')) {
                $table->string('periode')->nullable();
            }
            if (!Schema::hasColumn('agus_tabler_monev_survei_kepuasan', 'prodi')) {
                $table->string('prodi')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('agus_tabler_monev_survei_kepuasan', function ($table) {
            $table->dropColumn('periode');
            $table->dropColumn('prodi');
        });
    }
}
