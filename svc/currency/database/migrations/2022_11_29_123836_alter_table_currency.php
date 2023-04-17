<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @const string
     */
    const TABLE_NAME = 'currency';

    /**
     * @const string
     */
    const UNIQUE_CURRENCY = 'unique_currency';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->addColumn('string', 'project')->default('b2c');
        });

        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->unique([
                'code',
                'company',
                'project',
            ], self::UNIQUE_CURRENCY);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropUnique(self::UNIQUE_CURRENCY);
        });

        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropColumn('project');
        });
    }
};
