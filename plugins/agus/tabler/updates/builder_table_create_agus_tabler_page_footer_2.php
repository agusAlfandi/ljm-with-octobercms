<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAgusTablerPageFooter2 extends Migration
{
    public function up()
    {
        Schema::create('agus_tabler_page_footer', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('image_1', 255);
            $table->string('image_2', 255);
            $table->string('image_3', 255);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('agus_tabler_page_footer');
    }
}
