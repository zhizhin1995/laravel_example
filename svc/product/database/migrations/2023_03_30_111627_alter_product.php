<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @const string
     */
    const TABLE_NAME = 'product';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(self::TABLE_NAME, function(Blueprint $table)
        {
            $table->text('name_en')->nullable()->change();
            $table->text('name_ru')->nullable()->change();
            $table->float('weight', 6, 3)->nullable()->change();
            $table->float('volume', 6, 3)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table(self::TABLE_NAME, function(Blueprint $table)
        {
            $table->text('name_en')->change();
            $table->text('name_ru')->change();
            $table->float('weight', 6, 3)->change();
            $table->float('volume', 6, 3)->change();
        });
    }
};
