<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * @class BrandSeeder
 * @package Database\Seeders
 */
class BrandSeeder extends Seeder
{
    /**
     * @const string
     */
    const TABLE_NAME = 'brand';

    /**
     * @const string
     */
    const TABLE_COUNTRY = 'country';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table(self::TABLE_NAME)->truncate();

        $japan = DB::query()
            ->newQuery()
            ->from(self::TABLE_COUNTRY)
            ->where('code', '=', 'JPN')
            ->first();

        $china = DB::query()
            ->newQuery()
            ->from(self::TABLE_COUNTRY)
            ->where('code', '=', 'CN')
            ->first();

        $korea = DB::query()
            ->newQuery()
            ->from(self::TABLE_COUNTRY)
            ->where('code', '=', 'KR')
            ->first();

        DB::table(self::TABLE_NAME)->insert([
            'country_id' => $japan->id,
            'name' => 'TOYOTA',
        ]);

        DB::table(self::TABLE_NAME)->insert([
            'country_id' => $korea->id,
            'name' => 'HYUNDAI',
        ]);

        DB::table(self::TABLE_NAME)->insert([
            'country_id' => $china->id,
            'name' => 'FAW',
        ]);
    }
}
