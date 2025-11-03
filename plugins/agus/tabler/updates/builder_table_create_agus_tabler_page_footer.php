<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAgusTablerPageFooter extends Migration
{
    public function up()
    {
        Schema::create('agus_tabler_page_footer', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('image_1', 225);
            $table->string('image_2', 225);
            $table->string('image_3', 225);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('agus_tabler_page_footer');
    }
}
