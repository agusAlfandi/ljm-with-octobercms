<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteAgusTablerMonev extends Migration
{
    public function up()
    {
        Schema::dropIfExists('agus_tabler_monev');
    }
    
    public function down()
    {
        Schema::create('agus_tabler_monev', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('title', 225)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('category', 225)->nullable();
        });
    }
}
