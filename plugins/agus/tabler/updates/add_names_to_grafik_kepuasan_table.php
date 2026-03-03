<?php
namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddNamesToGrafikKepuasanTable extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_grafik_kepuasan', function ($table) {
            if (!Schema::hasColumn('agus_tabler_grafik_kepuasan', 'periode_name')) {
                $table->string('periode_name')->nullable();
            }
            if (!Schema::hasColumn('agus_tabler_grafik_kepuasan', 'prodi_name')) {
                $table->string('prodi_name')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('agus_tabler_grafik_kepuasan', function ($table) {
            $table->dropColumn('periode_name');
            $table->dropColumn('prodi_name');
        });
    }
}
