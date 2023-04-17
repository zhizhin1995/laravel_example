<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table(self::REF_TABLE_PRODUCT, function(Blueprint $table)
        {
            $table->string('code', 64)->change();

            $table->unique('code', 'unique_product_code');
        });

        Schema::table(self::TABLE_NAME, function(Blueprint $table)
        {
            $table->dropForeign('fk_product_analogue_original');
            $table->dropForeign('fk_product_analogue_analogue');

            $table->dropIndex('idx_product_analogue_original');
            $table->dropIndex('idx_product_analogue_analogue');

            $table->dropColumn('original_id');
            $table->dropColumn('analogue_id');


            $table->string('original_code', 64);
            $table->string('analogue_code', 64);

            $table->foreign('original_code', 'fk_product_analogue_original')
                ->references('code')
                ->on(self::REF_TABLE_PRODUCT)
                ->onDelete('cascade');

            $table->foreign('analogue_code', 'fk_product_analogue_analogue')
                ->references('code')
                ->on(self::REF_TABLE_PRODUCT)
                ->onDelete('cascade');

            $table->index('original_code', 'idx_product_analogue_original');
            $table->index('analogue_code', 'idx_product_analogue_analogue');
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

        Schema::table(self::REF_TABLE_PRODUCT, function(Blueprint $table)
        {
            $table->string('code', 255)->change();

            $table->dropUnique('unique_product_code');
        });

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
};
