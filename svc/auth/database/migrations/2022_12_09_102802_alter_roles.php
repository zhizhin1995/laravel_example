<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * @const string
     */
    const TABLE_NAME = 'roles';

    /**
     * @const string
     */
    const REF_TABLE_SERVICE = 'service';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->addColumn('integer', 'service_id')->nullable();
        });

        $service = DB::query()
            ->newQuery()
            ->from(self::REF_TABLE_SERVICE)
            ->where('name', '=', 'currency-service')
            ->first();

        DB::table(self::TABLE_NAME)->update(['service_id' => $service->id]);

        DB::statement("ALTER TABLE " . self::TABLE_NAME . " ALTER COLUMN service_id TYPE integer USING service_id::integer");

        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->foreign('service_id', 'fk_roles_service')
                ->references('id')
                ->on(self::REF_TABLE_SERVICE)
                ->onDelete('cascade');

            $table->index('service_id','idx_roles_service_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropIndex('idx_roles_service_id');
            $table->dropForeign('fk_roles_service');
            $table->dropColumn('service_id');
        });
    }
};
