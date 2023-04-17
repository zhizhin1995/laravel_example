<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * @class ProductSeeder
 * @package Database\Seeders
 */
class ProductSeeder extends Seeder
{
    /**
     * @const string
     */
    const TABLE_NAME = 'product';

    /**
     * @const string
     */
    const TABLE_BRAND = 'brand';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table(self::TABLE_NAME)->truncate();

        $toyota = DB::query()
            ->newQuery()
            ->from(self::TABLE_BRAND)
            ->where('name', '=', 'TOYOTA')
            ->first();

        $faw = DB::query()
            ->newQuery()
            ->from(self::TABLE_BRAND)
            ->where('name', '=', 'FAW')
            ->first();

        $hyundai = DB::query()
            ->newQuery()
            ->from(self::TABLE_BRAND)
            ->where('name', '=', 'HYUNDAI')
            ->first();

        DB::table(self::TABLE_NAME)->insert([
            'brand_id' => $toyota->id,
            'code' => '14545TOYO453OF',
            'name_en' => 'OIL FILTER',
            'name_ru' => 'Oil filter',
            'weight' => 3.130,
            'volume' => 0.000,
            'min_lot' => 1,
        ]);

        DB::table(self::TABLE_NAME)->insert([
            'brand_id' => $faw->id,
            'code' => '1000FAW453OF',
            'name_en' => 'OIL FILTER',
            'name_ru' => 'Oil filter',
            'weight' => 3.150,
            'volume' => 0.000,
            'min_lot' => 1,
        ]);

        DB::table(self::TABLE_NAME)->insert([
            'brand_id' => $hyundai->id,
            'code' => '3666HYN453OF',
            'name_en' => 'OIL FILTER',
            'name_ru' => 'Oil filter',
            'weight' => 3.113,
            'volume' => 0.000,
            'min_lot' => 1,
        ]);
    }
}
