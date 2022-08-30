<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('departments_supervisors', function (Blueprint $table) {
            $table->renameColumn('employee_id','supervisor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('departments_supervisors', function (Blueprint $table) {
            $table->renameColumn('supervisor_id','employee_id');
        });
    }
};
