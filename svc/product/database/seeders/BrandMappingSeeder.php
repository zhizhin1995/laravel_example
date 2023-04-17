<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use stdClass;

/**
 * @class BrandMappingSeeder
 * @package Database\Seeders
 */
class BrandMappingSeeder extends Seeder
{
    /**
     * @const string
     */
    const TABLE_NAME = 'brand_name_mapping';

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

        $faw = $this->getBrand('FAW');

        $this->insertMapping($toyota, 'TOYOTA TEST');
        $this->insertMapping($toyota, 'TOYOTA TEST 2');

        $this->insertMapping($hyundai, 'HYUNDAI TEST');
        $this->insertMapping($hyundai, 'HYUNDAI TEST 2');

        $this->insertMapping($faw, 'FAW TEST');
        $this->insertMapping($faw, 'FAW TEST 2');
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
     * @param string $name
     * @return void
     */
    private function insertMapping(object $originalBrand, string $name): void
    {
        DB::table(self::TABLE_NAME)->insert([
            'original_brand_id' => $originalBrand->id,
            'mapping_name' => $name
        ]);
    }
}
