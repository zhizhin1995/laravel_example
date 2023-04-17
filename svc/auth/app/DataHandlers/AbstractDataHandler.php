<?php

namespace App\DataHandlers;

use App\Models\DataSourceInterface;

/**
 * @class AbstractDataHandler
 * @package App\DataHandlers
 */
abstract class AbstractDataHandler
{
    /**
     * // TODO dataSource из конфига
     *
     * @var DataSourceInterface $dataSource Источник данных
     */
    public DataSourceInterface $dataSource;

    /**
     * @param DataSourceInterface $dataSource
     */
    public function __construct(DataSourceInterface $dataSource)
    {
        $this->dataSource = $dataSource;
    }
}