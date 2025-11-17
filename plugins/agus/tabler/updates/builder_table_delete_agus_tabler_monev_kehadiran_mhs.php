<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteAgusTablerMonevKehadiranMhs extends Migration
{
    public function up()
    {
        Schema::dropIfExists('agus_tabler_monev_kehadiran_mhs');
    }
    
    public function down()
    {
        Schema::create('agus_tabler_monev_kehadiran_mhs', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('file', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
}
