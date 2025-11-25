<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAgusTablerRtmKepuasan extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_rtm_kepuasan', function($table)
        {
            $table->string('file_name', 225)->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('agus_tabler_rtm_kepuasan', function($table)
        {
            $table->string('file_name', 225)->nullable(false)->change();
        });
    }
}
