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
        Schema::table('promotions', function (Blueprint $table) {
            $table->foreign(['from_class'], 'promotions_ibfk_1')->references(['id'])->on('my_classes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['from_section'], 'promotions_ibfk_2')->references(['id'])->on('sections')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['to_class'], 'promotions_ibfk_3')->references(['id'])->on('my_classes')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['to_section'], 'promotions_ibfk_4')->references(['id'])->on('sections')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['student_id'], 'promotions_ibfk_5')->references(['id'])->on('student_records')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['user_id'], 'promotions_ibfk_6')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropForeign('promotions_ibfk_1');
            $table->dropForeign('promotions_ibfk_2');
            $table->dropForeign('promotions_ibfk_3');
            $table->dropForeign('promotions_ibfk_4');
            $table->dropForeign('promotions_ibfk_5');
            $table->dropForeign('promotions_ibfk_6');
        });
    }
};
