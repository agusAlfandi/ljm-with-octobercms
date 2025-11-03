<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAgusTablerAgendaLjm2 extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_agenda_ljm', function($table)
        {
            $table->boolean('is_active')->default(false);
        });
    }
    
    public function down()
    {
        Schema::table('agus_tabler_agenda_ljm', function($table)
        {
            $table->dropColumn('is_active');
        });
    }
}
