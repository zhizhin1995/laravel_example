<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Dto\Currency\CurrencyDto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * @class CurrencySeeder
 * @package Database\Seeders
 */
class CurrencySeeder extends Seeder
{
    /**
     * @const string
     */
    const TABLE_NAME = 'currency';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table(self::TABLE_NAME)->insert([
            'symbol' => '$',
            'code' => 'USD',
            'company' => CurrencyDto::DEFAULT_COMPANY_NAME,
            'is_main' => 1,
        ]);

        DB::table(self::TABLE_NAME)->insert([
            'symbol' => 'X',
            'code' => 'XXX',
            'company' => CurrencyDto::DEFAULT_COMPANY_NAME,
        ]);

        DB::table(self::TABLE_NAME)->insert([
            'symbol' => 'Y',
            'code' => 'YYY',
            'company' => CurrencyDto::DEFAULT_COMPANY_NAME,
        ]);


    }
}
