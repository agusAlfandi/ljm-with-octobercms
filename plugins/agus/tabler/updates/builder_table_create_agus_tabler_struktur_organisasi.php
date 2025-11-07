<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAgusTablerStrukturOrganisasi extends Migration
{
    public function up()
    {
        Schema::create('agus_tabler_struktur_organisasi', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('file', 225)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('agus_tabler_struktur_organisasi');
    }
}
