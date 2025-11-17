<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteAgusTablerBebanBljrMhs extends Migration
{
    public function up()
    {
        Schema::dropIfExists('agus_tabler_beban_bljr_mhs');
    }
    
    public function down()
    {
        Schema::create('agus_tabler_beban_bljr_mhs', function($table)
        {
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('title', 225)->nullable();
        });
    }
}
