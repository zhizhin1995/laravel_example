<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    const TABLE_PERMISSIONS = 'permissions';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::table(self::TABLE_PERMISSIONS)->insert([
            'name' => 'can-see-product',
            'guard_name' => 'can-see-product'
        ]);

        DB::table(self::TABLE_PERMISSIONS)->insert([
            'name' => 'can-set-product',
            'guard_name' => 'can-set-product'
        ]);

        DB::table(self::TABLE_PERMISSIONS)->insert([
            'name' => 'can-create-product',
            'guard_name' => 'can-create-product'
        ]);

        DB::table(self::TABLE_PERMISSIONS)->insert([
            'name' => 'can-delete-product',
            'guard_name' => 'can-delete-product'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::table(self::TABLE_PERMISSIONS)->where('name', '=', 'can-see-product')->delete();
        DB::table(self::TABLE_PERMISSIONS)->where('name', '=', 'can-set-product')->delete();
        DB::table(self::TABLE_PERMISSIONS)->where('name', '=', 'can-create-product')->delete();
        DB::table(self::TABLE_PERMISSIONS)->where('name', '=', 'can-delete-product')->delete();
    }
};
