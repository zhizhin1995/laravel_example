<?php declare(strict_types=1);

namespace App\Models\Dto\Currency;

use App\Models\Dto\AbstractDto;

/**
 * @class CurrencyDto
 * @package App\Models\Dto\Currency
 */
class CurrencyDto extends AbstractDto
{
    /**
     * @const string Дефолтная компания
     */
    const DEFAULT_COMPANY_NAME = 'common';

    /**
     * @const string Дефолтный проект
     */
    const DEFAULT_PROJECT = 'b2c';

    /**
     * @var int|null
     */
    public ?int $id;

    /**
     * @var string|null
     */
    public ?string $symbol;

    /**
     * @var string|null
     */
    public ?string $code;

    /**
     * @var string|null
     */
    public ?string $company = self::DEFAULT_COMPANY_NAME;

    /**
     * @var string|null
     */
    public ?string $project = self::DEFAULT_PROJECT;

    /**
     * @var string|null
     */
    public ?string $created_at;

    /**
     * @var string|null
     */
    public ?string $updated_at;
}
