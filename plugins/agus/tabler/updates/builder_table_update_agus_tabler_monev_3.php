<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAgusTablerMonev3 extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_monev', function($table)
        {
            $table->string('category')->change();
        });
    }
    
    public function down()
    {
        Schema::table('agus_tabler_monev', function($table)
        {
            $table->string('category', 225)->change();
        });
    }
}
