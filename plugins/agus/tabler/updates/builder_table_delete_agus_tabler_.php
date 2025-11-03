<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteAgusTabler extends Migration
{
    public function up()
    {
        Schema::dropIfExists('agus_tabler_');
    }
    
    public function down()
    {
        Schema::create('agus_tabler_', function($table)
        {
            $table->increments('id')->unsigned();
            $table->text('deskripsi')->nullable();
            $table->string('image', 255)->nullable();
        });
    }
}
