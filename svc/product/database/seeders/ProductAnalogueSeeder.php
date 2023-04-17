<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * @class ProductAnalogueSeeder
 * @package Database\Seeders
 */
class ProductAnalogueSeeder extends Seeder
{
    /**
     * @const string
     */
    const TABLE_NAME = 'product_analogue';

    /**
     * @const string
     */
    const TABLE_PRODUCT = 'product';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table(self::TABLE_NAME)->truncate();

        $toyotaOF = DB::query()
            ->newQuery()
            ->from(self::TABLE_PRODUCT)
            ->where('code', '=', '14545TOYO453OF')
            ->first();

        $fawOF = DB::query()
            ->newQuery()
            ->from(self::TABLE_PRODUCT)
            ->where('code', '=', '1000FAW453OF')
            ->first();

        $hyundaiOF = DB::query()
            ->newQuery()
            ->from(self::TABLE_PRODUCT)
            ->where('code', '=', '3666HYN453OF')
            ->first();

        DB::table(self::TABLE_NAME)->insert([
            'analogue_code' => $toyotaOF->code,
            'original_code' => $fawOF->code,
        ]);

        DB::table(self::TABLE_NAME)->insert([
            'analogue_code' => $fawOF->code,
            'original_code' => $toyotaOF->code,
        ]);

        DB::table(self::TABLE_NAME)->insert([
            'analogue_code' => $hyundaiOF->code,
            'original_code' => $toyotaOF->code,
        ]);

        DB::table(self::TABLE_NAME)->insert([
            'analogue_code' => $hyundaiOF->code,
            'original_code' => $fawOF->code,
        ]);

    }
}
