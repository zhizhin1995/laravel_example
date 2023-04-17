<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @const string
     */
    const TABLE_NAME = 'brand';

    /**
     * @const string
     */
    const REF_TABLE_COUNTRY = 'country';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(self::TABLE_NAME, function(Blueprint $table)
        {
            $table->integer('default_country_id')->nullable();

            $table->foreign('default_country_id', 'fk_brand_default_country_id')
                ->references('id')
                ->on(self::REF_TABLE_COUNTRY)
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
            $table->dropForeign('fk_brand_default_country_id');
            $table->dropColumn('default_country_id');
        });
    }
};
