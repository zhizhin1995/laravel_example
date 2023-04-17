<?php declare(strict_types=1);

namespace App\Models\Dto\Currency;

use App\Models\Dto\AbstractDto;

/**
 * @class CurrencyHistoryDto
 * @package App\Models\Dto
 */
class CurrencyHistoryDto extends AbstractDto
{
    /**
     * @var int|null
     */
    public ?int $id;

    /**
     * @var int|null
     */
    public ?int $currency_id;

    /**
     * @var float|null
     */
    public ?float $rate;

    /**
     * @var string|null
     */
    public ?string $created_at;

    /**
     * @var string|null
     */
    public ?string $updated_at;

    /**
     * @var CurrencyDto|null
     */
    public ?CurrencyDto $currency;
}
