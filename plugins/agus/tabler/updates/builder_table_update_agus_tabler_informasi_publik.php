<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAgusTablerInformasiPublik extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_informasi_publik', function($table)
        {
            $table->string('tahun', 225)->nullable()->change();
            $table->string('title', 225)->nullable()->change();
            $table->string('file', 225)->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('agus_tabler_informasi_publik', function($table)
        {
            $table->string('tahun', 225)->nullable(false)->change();
            $table->string('title', 225)->nullable(false)->change();
            $table->string('file', 225)->nullable(false)->change();
        });
    }
}
