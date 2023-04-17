<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @const string
     */
    const TABLE_NAME = 'product_analogue';

    /**
     * @const string
     */
    const REF_TABLE_PRODUCT = 'product';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->integer('original_id');
            $table->integer('analogue_id');

            $table->foreign('original_id', 'fk_product_analogue_original')
                ->references('id')
                ->on(self::REF_TABLE_PRODUCT)
                ->onDelete('cascade');

            $table->foreign('analogue_id', 'fk_product_analogue_analogue')
                ->references('id')
                ->on(self::REF_TABLE_PRODUCT)
                ->onDelete('cascade');

            $table->index('original_id', 'idx_product_analogue_original');
            $table->index('analogue_id', 'idx_product_analogue_analogue');
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
            $table->dropIndex('idx_product_analogue_original');
            $table->dropIndex('idx_product_analogue_analogue');
            $table->dropForeign('fk_product_analogue_original');
            $table->dropForeign('fk_product_analogue_analogue');
        });

        Schema::dropIfExists(self::TABLE_NAME);
    }
};
