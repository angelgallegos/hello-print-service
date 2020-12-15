<?php

namespace App\Services;

use App\Clients\BrokerClient;
use App\Consumers\KafkaConsumer;
use App\Models\Request;
use Framework\DI\Facade;
use Framework\Utils\Configuration\ConfigurationInterface;
use Monolog\Logger;
use Throwable;

class RequestService implements ConsumerServiceInterface
{
    /**
     * @var KafkaConsumer
     */
    private KafkaConsumer $consumer;

    /**
     * @var BrokerClient
     */
    private BrokerClient $brokerClient;

    private static array $mutators = [
        "topic_a" => \App\Mutators\Request\TopicA\RequestMutator::class,
        "topic_b" => \App\Mutators\Request\TopicB\RequestMutator::class
    ];

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var ConfigurationInterface
     */
    private ConfigurationInterface $config;

    /**
     * RequestService constructor.
     * @param KafkaConsumer $consumer
     * @param BrokerClient $brokerClient
     * @param Logger $logger
     * @param ConfigurationInterface $configuration
     */
    public function __construct(
        KafkaConsumer $consumer,
        BrokerClient $brokerClient,
        Logger $logger,
        ConfigurationInterface $configuration
    ) {
        $this->consumer = $consumer;
        $this->brokerClient = $brokerClient;
        $this->config = $configuration;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function consume()
    {
        $this->consumer->listen(
            $this->process()
        );
    }

    /**
     * @return callable
     */
    public function process(): callable
    {
        return function ($message, $topic) {
            $request = Request::create(json_decode($message, true));

            $mutator = Facade::create(self::$mutators[$topic]);
            $request = $mutator->append($request);
            try {
                $this->brokerClient->update($request);
            } catch (Throwable $exception) {
                $this->logger->alert($exception->getMessage());
            }
        };
    }
}