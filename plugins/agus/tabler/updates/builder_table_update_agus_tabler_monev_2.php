<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAgusTablerMonev2 extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_monev', function($table)
        {
            $table->dropColumn('file');
        });
    }
    
    public function down()
    {
        Schema::table('agus_tabler_monev', function($table)
        {
            $table->string('file', 225)->nullable();
        });
    }
}
