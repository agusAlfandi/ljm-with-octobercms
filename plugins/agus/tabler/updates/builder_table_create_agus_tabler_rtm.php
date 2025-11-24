<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAgusTablerRtm extends Migration
{
    public function up()
    {
        Schema::create('agus_tabler_rtm', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('title', 225)->nullable();
            $table->string('file_name', 225)->nullable();
            $table->string('category', 225)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('agus_tabler_rtm');
    }
}
