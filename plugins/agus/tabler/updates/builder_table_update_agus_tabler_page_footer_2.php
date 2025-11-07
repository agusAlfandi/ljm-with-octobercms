<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAgusTablerPageFooter2 extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_page_footer', function($table)
        {
            $table->dropColumn('image_1');
            $table->dropColumn('image_2');
            $table->dropColumn('image_3');
        });
    }
    
    public function down()
    {
        Schema::table('agus_tabler_page_footer', function($table)
        {
            $table->string('image_1', 255);
            $table->string('image_2', 255);
            $table->string('image_3', 255);
        });
    }
}
