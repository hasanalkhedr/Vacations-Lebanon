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
        Schema::create('departments_supervisors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('employee_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->timestamps();
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
            $table->dropConstrainedForeignId('department_id');
            $table->dropConstrainedForeignId('employee_id');
        });
        Schema::dropIfExists('departments_supervisors');
    }
};
