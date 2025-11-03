<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAgusTablerMediaPublikasiLjm extends Migration
{
    public function up()
    {
        Schema::create('agus_tabler_media_publikasi_ljm', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('title', 225);
            $table->text('description');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('agus_tabler_media_publikasi_ljm');
    }
}
