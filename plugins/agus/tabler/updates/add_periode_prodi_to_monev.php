<?php
namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddPeriodeProdiToMonev extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_monev', function ($table) {
            $table->string('periode')->nullable();
            $table->string('prodi')->nullable();
            $table->string('periode_name')->nullable();
            $table->string('prodi_name')->nullable();
        });
    }

    public function down()
    {
        Schema::table('agus_tabler_monev', function ($table) {
            $table->dropColumn('periode');
            $table->dropColumn('prodi');
            $table->dropColumn('periode_name');
            $table->dropColumn('prodi_name');
        });
    }
}
