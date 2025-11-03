<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAgusTabler extends Migration
{
    public function up()
    {
        Schema::create('agus_tabler_', function($table)
        {
            $table->increments('id')->unsigned();
            $table->text('deskripsi')->nullable();
            $table->string('image')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('agus_tabler_');
    }
}
