<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAgusTablerHomePageHeader extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_home_page_header', function($table)
        {
            $table->string('image_1', 225)->nullable()->change();
            $table->string('image_2', 225)->nullable()->change();
            $table->string('image_3', 225)->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('agus_tabler_home_page_header', function($table)
        {
            $table->string('image_1', 225)->nullable(false)->change();
            $table->string('image_2', 225)->nullable(false)->change();
            $table->string('image_3', 225)->nullable(false)->change();
        });
    }
}
