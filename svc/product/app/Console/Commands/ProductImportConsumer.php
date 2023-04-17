<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Queues\Product\Import\ImportJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Kunnu\RabbitMQ\RabbitMQGenericMessageConsumer;
use Kunnu\RabbitMQ\RabbitMQIncomingMessage;
use Kunnu\RabbitMQ\RabbitMQManager;
use Kunnu\RabbitMQ\RabbitMQQueue;
use Kunnu\RabbitMQ\RabbitMQExchange;

/**
 * @class ProductImportConsumer
 * @package App\Console\Commands
 */
class ProductImportConsumer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:product-import {--queue} {--exchange} {--routingKey}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Product import consumer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * php artisan rabbitmq:product-import
     *
     *
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        /** @var RabbitMQManager $rabbitMQ */
        $rabbitMQ = app('rabbitmq');

        $messageConsumer = new RabbitMQGenericMessageConsumer(
            function (RabbitMQIncomingMessage $message) {
                /** @var ImportJob $job */
                $job = App::make(ImportJob::class);

                $data = $message->getStream();

                if ($job->process($message->getStream(), $this)) {
                    $message->getDelivery()->acknowledge();
                } else {
                    $message->getDelivery()->reject(true);
                    die($this->error($data . " failed")); // TODO alert and log
                }
            },
            $this
        );

        $routingKey = env('RABBITMQ_PRODUCT_IMPORT_ROUTING_KEY', '');
        $queue = new RabbitMQQueue(env('RABBITMQ_PRODUCT_IMPORT_QUEUE', ''));
        $exchange = new RabbitMQExchange(env('RABBITMQ_PRODUCT_IMPORT_EXCHANGE', ''));

        $messageConsumer
            ->setExchange($exchange)
            ->setQueue($queue);

        $rabbitMQ->consumer()->consume($messageConsumer, $routingKey);
    }
}