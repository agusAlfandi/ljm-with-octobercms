<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAgusTablerMonevSurveiKepuasan extends Migration
{
    public function up()
    {
        Schema::create('agus_tabler_monev_survei_kepuasan', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('title', 225);
            $table->string('file_name', 225);
            $table->string('category', 225);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('agus_tabler_monev_survei_kepuasan');
    }
}
