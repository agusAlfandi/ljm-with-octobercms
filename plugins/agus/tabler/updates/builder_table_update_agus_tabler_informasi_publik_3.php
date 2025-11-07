<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAgusTablerInformasiPublik3 extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_informasi_publik', function($table)
        {
            $table->string('file', 225)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('agus_tabler_informasi_publik', function($table)
        {
            $table->dropColumn('file');
        });
    }
}
