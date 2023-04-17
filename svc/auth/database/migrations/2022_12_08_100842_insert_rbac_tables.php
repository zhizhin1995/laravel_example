<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * @const string Таблица roles
     */
    const TABLE_ROLES = 'roles';

    /**
     * @const string Таблица permissions
     */
    const TABLE_PERMISSIONS = 'permissions';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::table(self::TABLE_ROLES)->insert([
            'name' => 'admin',
            'guard_name' => 'admin'
        ]);

        DB::table(self::TABLE_ROLES)->insert([
            'name' => 'manager',
            'guard_name' => 'manager'
        ]);

        DB::table(self::TABLE_ROLES)->insert([
            'name' => 'user',
            'guard_name' => 'user'
        ]);

        DB::table(self::TABLE_PERMISSIONS)->insert([
            'name' => 'can-see-currency',
            'guard_name' => 'can-see-currency'
        ]);

        DB::table(self::TABLE_PERMISSIONS)->insert([
            'name' => 'can-set-currency-rate',
            'guard_name' => 'can-set-currency-rate'
        ]);

        DB::table(self::TABLE_PERMISSIONS)->insert([
            'name' => 'can-create-currency',
            'guard_name' => 'can-create-currency'
        ]);

        DB::table(self::TABLE_PERMISSIONS)->insert([
            'name' => 'can-delete-currency',
            'guard_name' => 'can-delete-currency'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::table(self::TABLE_ROLES)->truncate();
        DB::table(self::TABLE_PERMISSIONS)->truncate();
    }
};
