<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use stdClass;

/**
 * @class BrandAnalogueSeeder
 * @package Database\Seeders
 */
class BrandAnalogueSeeder extends Seeder
{
    /**
     * @const string
     */
    const TABLE_NAME = 'brand_analogue';

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

        $toyota = $this->getBrand('TOYOTA');

        $hyundai = $this->getBrand('HYUNDAI');

        $this->insertMapping($toyota, $hyundai);
        $this->insertMapping($hyundai, $toyota);
    }

    /**
     * @param string $name
     * @return stdClass
     */
    private function getBrand(string $name): stdClass
    {
        return DB::query()
            ->newQuery()
            ->from(self::TABLE_BRAND)
            ->where('name', '=', $name)
            ->first();
    }

    /**
     * @param object $originalBrand
     * @param object $analogueBrand
     * @return void
     */
    private function insertMapping(object $originalBrand, object $analogueBrand): void
    {
        DB::table(self::TABLE_NAME)->insert([
            'original_id' => $originalBrand->id,
            'analogue_id' => $analogueBrand->id
        ]);
    }
}