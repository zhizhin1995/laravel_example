<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @const string
     */
    const TABLE_NAME = 'brand_name_mapping';

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
            $table->integer('original_brand_id');
            $table->string('mapping_name');
            $table->string('source')->nullable()->default('');

            $table->foreign('original_brand_id', 'fk_brand_mapping_brand')
                ->references('id')
                ->on(self::REF_TABLE_BRAND)
                ->onDelete('cascade');

            $table->index('original_brand_id', 'idx_brand_mapping_brand_id');
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
            $table->dropIndex('idx_brand_mapping_brand_id');
            $table->dropForeign('fk_brand_mapping_brand');
        });

        Schema::dropIfExists(self::TABLE_NAME);
    }
};
