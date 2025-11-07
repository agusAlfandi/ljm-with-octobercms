<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAgusTablerPageFooter3 extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_page_footer', function($table)
        {
            $table->string('image_1', 225)->nullable();
            $table->string('image_2', 225)->nullable();
            $table->string('image_3', 225)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('agus_tabler_page_footer', function($table)
        {
            $table->dropColumn('image_1');
            $table->dropColumn('image_2');
            $table->dropColumn('image_3');
        });
    }
}
