<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAgusTablerInformasiPublik extends Migration
{
    public function up()
    {
        Schema::create('agus_tabler_informasi_publik', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('tahun', 225);
            $table->string('title', 225);
            $table->string('file', 225);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('agus_tabler_informasi_publik');
    }
}
