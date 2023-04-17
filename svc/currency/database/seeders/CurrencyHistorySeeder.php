<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * @class CurrencyHistorySeeder
 * @package Database\Seeders
 */
class CurrencyHistorySeeder extends Seeder
{
    /**
     * @const string
     */
    const TABLE_NAME = 'currency_history';

    /**
     * @const string
     */
    const TABLE_CURRENCY = 'currency';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $currencyUSD = DB::query()
            ->newQuery()
            ->from(self::TABLE_CURRENCY)
            ->where('code', '=', 'USD')
            ->first();

        $currencyXXX = DB::query()
            ->newQuery()
            ->from(self::TABLE_CURRENCY)
            ->where('code', '=', 'XXX')
            ->first();

        $currencyYYY = DB::query()
            ->newQuery()
            ->from(self::TABLE_CURRENCY)
            ->where('code', '=', 'YYY')
            ->first();

        DB::table(self::TABLE_NAME)->insert([
            'currency_id' => $currencyUSD->id,
            'rate' => 1,
            'created_at' => '2022-11-24',
            'updated_at' => '2022-11-24',
        ]);

        DB::table(self::TABLE_NAME)->insert([
            'currency_id' => $currencyXXX->id,
            'rate' => 1.5,
            'created_at' => '2022-11-24',
            'updated_at' => '2022-11-24',
        ]);

        DB::table(self::TABLE_NAME)->insert([
            'currency_id' => $currencyYYY->id,
            'rate' => 2,
            'created_at' => '2022-11-24',
            'updated_at' => '2022-11-24',
        ]);
    }
}
