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
    const TABLE_NAME = 'service';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->date('created_at')->default(DB::raw('CURRENT_DATE'));
            $table->date('updated_at')->default(DB::raw('CURRENT_DATE'));
        });

        DB::table(self::TABLE_NAME)->insert([
            'name' => 'currency-service',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
