<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteAgusTablerHomePageHeader extends Migration
{
    public function up()
    {
        Schema::dropIfExists('agus_tabler_home_page_header');
    }
    
    public function down()
    {
        Schema::create('agus_tabler_home_page_header', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('image_1', 225)->nullable();
            $table->string('image_2', 225)->nullable();
            $table->string('image_3', 225)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
}
