<?php

namespace App\Clients;

use App\Models\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Monolog\Logger;

class BrokerClient
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var string
     */
    private string $url;

    /**
     * @var Logger
     */
    private Logger $logger;

    public function __construct(
        string $baseUrl,
        Logger $logger
    ) {
        $this->client = new Client();

        $this->url = $baseUrl;
        $this->logger = $logger;
    }

    /**
     * Updates a Request to the broker service
     *
     * @param Request $request
     * @return Request|null
     * @throws GuzzleException
     */
    public function update(Request $request): ?Request
    {
        try {
            $response = $this->client->put(
                $this->url."/request/update",
                ["body" => json_encode($request)]
            );

            return Request::create(json_decode($response->getBody(), true));
        } catch (ClientException | ServerException $e) {
            $this->logger->alert("The next error occurred while sending the request: ".$e->getMessage());
            return null;
        }
    }
}