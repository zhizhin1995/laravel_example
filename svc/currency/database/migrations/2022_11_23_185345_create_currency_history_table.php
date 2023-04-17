<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * @const string Название талицы
     */
    const TABLE_NAME = 'currency_history';

    /**
     * @const string Название реф. таблицы
     */
    const REF_TABLE_CURRENCY = 'currency';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->integer('currency_id');
            $table->float('rate')->default(0);
            $table->date('created_at')->default(DB::raw('CURRENT_DATE'));
            $table->date('updated_at')->default(DB::raw('CURRENT_DATE'));

            $table->foreign('currency_id', 'fk_currency_history')
                ->references('id')
                ->on(self::REF_TABLE_CURRENCY)
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
