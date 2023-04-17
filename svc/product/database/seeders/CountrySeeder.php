<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * @class CountrySeeder
 * @package Database\Seeders
 */
class CountrySeeder extends Seeder
{
    /**
     * @const string
     */
    const TABLE_NAME = 'country';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table(self::TABLE_NAME)->truncate();

        DB::table(self::TABLE_NAME)->insert([
            'name' => 'Japan',
            'code' => 'JPN'
        ]);

        DB::table(self::TABLE_NAME)->insert([
            'name' => 'Korea',
            'code' => 'KR'
        ]);

        DB::table(self::TABLE_NAME)->insert([
            'name' => 'China',
            'code' => 'CN'
        ]);
    }
}
