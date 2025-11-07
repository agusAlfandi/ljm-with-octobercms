<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteAgusTablerInformasiPublik2 extends Migration
{
    public function up()
    {
        Schema::dropIfExists('agus_tabler_informasi_publik');
    }
    
    public function down()
    {
        Schema::create('agus_tabler_informasi_publik', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('title', 225)->nullable();
            $table->string('sub_title', 225)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('file', 225)->nullable();
        });
    }
}
