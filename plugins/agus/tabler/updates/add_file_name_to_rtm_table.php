<?php namespace Agus\Tabler\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddFileNameToRtmTable extends Migration
{
    public function up()
    {
        Schema::table('agus_tabler_rtm', function($table)
        {
            if (!Schema::hasColumn('agus_tabler_rtm', 'file_name')) {
                $table->string('file_name', 225)->nullable()->after('title');
            }
        });
    }

    public function down()
    {
        Schema::table('agus_tabler_rtm', function($table)
        {
            if (Schema::hasColumn('agus_tabler_rtm', 'file_name')) {
                $table->dropColumn('file_name');
            }
        });
    }
}
