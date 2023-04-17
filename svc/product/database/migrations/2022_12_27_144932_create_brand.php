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
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('country_id');

            $table->foreign('country_id', 'fk_brand_country')
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
            $table->dropForeign('fk_brand_country');
        });

        Schema::dropIfExists(self::TABLE_NAME);
    }
};
