<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteAgusTablerMonevDosen extends Migration
{
    public function up()
    {
        Schema::dropIfExists('agus_tabler_monev_dosen');
    }
    
    public function down()
    {
        Schema::create('agus_tabler_monev_dosen', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('file', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
}
