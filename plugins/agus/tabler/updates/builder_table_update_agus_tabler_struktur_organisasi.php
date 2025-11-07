<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAgusTablerStrukturOrganisasi extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_struktur_organisasi', function($table)
        {
            $table->dropColumn('file');
        });
    }
    
    public function down()
    {
        Schema::table('agus_tabler_struktur_organisasi', function($table)
        {
            $table->string('file', 225)->nullable();
        });
    }
}
