<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('my_classes', function (Blueprint $table) {
            $table->foreign(['class_type_id'], 'my_classes_ibfk_1')->references(['id'])->on('class_types')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('my_classes', function (Blueprint $table) {
            $table->dropForeign('my_classes_ibfk_1');
        });
    }
};
