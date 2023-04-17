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
            $table->integer('brand_id');
            $table->string('code');
            $table->text('name_en');
            $table->text('name_ru');
            $table->float('weight', 6, 3);
            $table->float('volume', 6, 3);
            $table->integer('min_lot');
            $table->json('photo')->nullable()->default(null);

            $table->index('brand_id', 'idx_product_brand_id');

            $table->foreign('brand_id', 'fk_product_brand')
                ->references('id')
                ->on(self::REF_TABLE_BRAND)
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
        Schema::table(self::TABLE_NAME, function(Blueprint $table)
        {
            $table->dropIndex('idx_product_brand_id');
            $table->dropForeign('fk_product_brand');
        });

        Schema::dropIfExists(self::TABLE_NAME);
    }
};
