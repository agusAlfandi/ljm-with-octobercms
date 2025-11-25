<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAgusTablerSurveiKepuasan extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_survei_kepuasan', function($table)
        {
            if (!Schema::hasColumn('agus_tabler_survei_kepuasan', 'file_name')) {
                $table->string('file_name', 255)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('agus_tabler_survei_kepuasan', function($table)
        {
            $table->dropColumn('file_name');
        });
    }
}
