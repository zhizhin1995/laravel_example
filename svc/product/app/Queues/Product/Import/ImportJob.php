<?php declare(strict_types=1);

namespace App\Queues\Product\Import;

use App\Services\Brand\BrandService;
use App\Services\Product\ProductService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Exception;
use Throwable;

/**
 * @class ImportJob
 * @package App\Queues\Product\Import
 *
 * @property ProductService $service
 */
class ImportJob
{
    /**
     * @var ProductService $service
     */
    private ProductService $service;

    public function __construct()
    {
        $this->service = App::make(ProductService::class);
    }

    /**
     * @param string $jobData
     * @param Command $consumer
     *
     * @return bool
     *
     * @throws Throwable
     */
    public function process(string $jobData, Command $consumer): bool
    {
        // ???
        ini_set('memory_limit', '3072M');

        $jobData = json_decode($jobData);

        $consumer->alert($jobData->hash . ' started');

        $callback = [
            new self(),
            'processChunk'
        ];

        $filename = '/project/versions/current/export.csv'; // TODO from $jobData

        $chunkSize = (int)env('PRODUCT_IMPORT_CHUNK_SIZE', 500);

        return $this->processByChunks($filename, $chunkSize, $callback);
    }

    /**
     * Обработка файла по частям
     *
     * @param string $filename
     * @param int $chunkSize
     * @param callable $callback
     *
     * @return bool
     *
     * @throws Exception
     */
    private function processByChunks(string $filename, int $chunkSize, callable $callback): bool
    {
        ini_set('auto_detect_line_endings', '1');

        $startingRow = 1;  // пропускаем заголовки

        $totalRows = $this->getRowCount($filename);

        $resultCount = 0;

        /** @var BrandService $brandService */
        $brandService = App::make(BrandService::class);

        $brands = $brandService->getBrandsWithAnalogues();



        var_dump($brands);die;

        try {
            while ($startingRow < $totalRows) {
                $result = call_user_func_array($callback, [
                    $filename,
                    $startingRow,
                    $chunkSize
                ]);

                $this->importChunk($result);
                $resultCount += count($result);
                var_dump("Done {$resultCount}" . PHP_EOL);
            }

             /*
                TODO LOG:
                $brokenRows = $totalRows - $resultCount;
                $logMessage = "Successfully imported {$resultCount} of {$totalRows}. {$brokenRows} could not be imported";
             */

            return true;
        } catch (Throwable $ex) {
            //TODO LOG: "Error occurred during import {$filename}: {$ex->getMessage()}";
            return false;
        }
    }

    /**
     * @param string $filename
     * @param int $start
     * @param int $desiredCount
     *
     * @return array|bool
     */
    function processChunk(string $filename, int $start, int $desiredCount): array|bool
    {
        $headings = [
            'id',
            'brand',
            'code',
            'country',
            'name_en',
            'name_ru',
            'weight',
            'volume',
            'min_lot',
        ];

        $row = 0;
        $count = 0;
        $rows = [];

        if (($handle = fopen($filename, "r")) === false) {
            return false;
        }

        while (($data = fgetcsv($handle, $desiredCount, ";")) !== false) {
            if ($row++ < $start) {
                continue;
            }

            try {
                if (count($data) == count($headings)) {
                    $product = array_combine($headings ?? [], $data);
                    $rows[] = $product;
                    $count++;
                }
            } catch (\Throwable $ex) {
                continue;
            }

            if ($count == $desiredCount) {
                return $rows;
            }
        }

        return $rows;
    }

    private function importChunk(array $chunk): void
    {
        $this->service->import($chunk);
    }

    /**
     * Получаем кол-во записей в csv, используя "линуксовые" инструменты
     *
     * @param string $filename
     * @return int
     * @throws Exception
     */
    private function getRowCount(string $filename): int
    {
        $result = exec("wc -l {$filename}");

        $result = trim(str_replace($filename, '', $result));

        if (!$result || !is_numeric($result)) {
            throw new Exception("Unable to open {$filename}");
        }

        return (int)$result;
    }
}