<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @const string
     */
    const TABLE_NAME = 'brand_analogue';

    /**
     * @const string
     */
    const REF_TABLE_BRAND = 'brand';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->integer('original_id')->nullable(false);
            $table->integer('analogue_id')->nullable(false);

            $table->unique(['original_id', 'analogue_id'], 'unique_brand_analogue');

            $table->foreign('original_id', 'fk_brand_analogue_original_id')
                ->references('id')
                ->on(self::REF_TABLE_BRAND)
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');

            $table->foreign('analogue_id', 'fk_brand_analogue_analogue_id')
                ->references('id')
                ->on(self::REF_TABLE_BRAND)
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
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
            $table->dropUnique('unique_brand_analogue');
            $table->dropForeign('fk_brand_analogue_original_id');
            $table->dropForeign('fk_brand_analogue_analogue_id');
        });

        Schema::dropIfExists(self::TABLE_NAME);
    }
};
