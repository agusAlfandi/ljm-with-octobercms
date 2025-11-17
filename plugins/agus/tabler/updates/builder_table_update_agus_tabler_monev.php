<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAgusTablerMonev extends Migration
{
    public function up()
    {
        Schema::rename('agus_tabler_beban_bljr_mhs', 'agus_tabler_monev');
        Schema::table('agus_tabler_monev', function($table)
        {
            $table->string('category', 225)->nullable();
        });
    }
    
    public function down()
    {
        Schema::rename('agus_tabler_monev', 'agus_tabler_beban_bljr_mhs');
        Schema::table('agus_tabler_beban_bljr_mhs', function($table)
        {
            $table->dropColumn('category');
        });
    }
}
