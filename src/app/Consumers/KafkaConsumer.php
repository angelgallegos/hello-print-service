<?php

namespace App\Consumers;

use Framework\Utils\Configuration\ConfigurationInterface;
use Kafka\Consumer;
use Kafka\ConsumerConfig;
use Monolog\Logger;

class KafkaConsumer
{
    /**
     * @var Consumer
     */
    private Consumer $consumer;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var ConfigurationInterface
     */
    private ConfigurationInterface $config;

    public function __construct(
        Logger $logger,
        ConfigurationInterface $config
    ) {
        $this->logger = $logger;
        $this->config = $config;

        $configConsumer = ConsumerConfig::getInstance();
        $configConsumer->setMetadataRefreshIntervalMs(10000);
        $this->logger->debug("=======================================================");
        $this->logger->debug("KAFKA _URL: {$this->config->get("KAFKA_URL")}:{$this->config->get("KAFKA_PORT")}");
        $configConsumer->setMetadataBrokerList("{$this->config->get("KAFKA_URL")}:{$this->config->get("KAFKA_PORT")}");
        $configConsumer->setGroupId($this->config->get("KAFKA_GROUP_ID"));
        $configConsumer->setBrokerVersion($this->config->get("KAFKA_BROKER_VERSION"));
        $topics = array_values(explode(",", $this->config->get("KAFKA_TOPICS")));
        $configConsumer->setTopics($topics);
        $this->logger->debug(json_encode($topics));

        $this->consumer = new Consumer();
        $this->consumer->setLogger($logger);
    }

    /**
     * @param callable $found
     * @return void
     */
    public function listen(callable $found): void {
        $this->consumer->start(function($topic, $part, $message) use ($found) {
            $found($message["message"]["value"], $topic);
        });
    }
}