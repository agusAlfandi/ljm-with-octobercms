<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAgusTablerBebanBljrMhs extends Migration
{
    public function up()
    {
        Schema::create('agus_tabler_beban_bljr_mhs', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('file')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('agus_tabler_beban_bljr_mhs');
    }
}
