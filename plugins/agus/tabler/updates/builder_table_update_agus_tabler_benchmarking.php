<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAgusTablerBenchmarking extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_benchmarking', function($table)
        {
            $table->dropColumn('category');
        });
    }
    
    public function down()
    {
        Schema::table('agus_tabler_benchmarking', function($table)
        {
            $table->string('category', 225);
        });
    }
}
