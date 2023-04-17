<?php

namespace Tests;

use Library\Exceptions\AuthException;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\CreatesTestAuthToken;
use Tests\Traits\CreatesAuthAssignment;
use Illuminate\Support\Facades\DB;
use Database\Seeders\DatabaseSeeder;

/**
 * @class TestCase
 * @package Tests
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @var mixed
     */
    public $app;

    /**
     * @var string|bool
     */
    public string|bool $token;

    /**
     * @var object|null $user
     */
    public object|null $user = null;

    use CreatesApplication, CreatesTestAuthToken, CreatesAuthAssignment;

    /**
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     * @throws AuthException
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $this->app = $this->createApplication();

        $this->token = $this->createToken($this->app);

        parent::__construct($name, $data, $dataName);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        self::truncate();
    }

    public static function tearDownAfterClass(): void
    {
        self::truncate();

        parent::tearDownAfterClass();
    }

    /**
     * @return void
     */
    private static function truncate(): void
    {
        DB::table('brand_analogue')->truncate();
        DB::table('brand_name_mapping')->truncate();
        DB::table('brand')->truncate();
        DB::table('country')->truncate();
    }

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        $seeder = $this->app->make(DatabaseSeeder::class);
        $seeder->run();

        parent::setUp();
    }
}
