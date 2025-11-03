<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAgusTablerMediaPublikasiLjm extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_media_publikasi_ljm', function($table)
        {
            $table->string('title', 225)->nullable()->change();
            $table->text('description')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('agus_tabler_media_publikasi_ljm', function($table)
        {
            $table->string('title', 225)->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
        });
    }
}
