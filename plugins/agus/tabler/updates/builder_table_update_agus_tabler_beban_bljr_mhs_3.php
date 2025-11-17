<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAgusTablerBebanBljrMhs3 extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_beban_bljr_mhs', function($table)
        {
            $table->dropColumn('file');
        });
    }
    
    public function down()
    {
        Schema::table('agus_tabler_beban_bljr_mhs', function($table)
        {
            $table->string('file', 225)->nullable();
        });
    }
}
