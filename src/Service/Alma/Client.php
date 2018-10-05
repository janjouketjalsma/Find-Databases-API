<?php
namespace App\Service\Alma;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;
use GuzzleHttp\ClientInterface;

class Client
{
    private $logger;
    private $client;
    private $host;
    private $apiKey;


    public function __construct(LoggerInterface $logger, ClientInterface $client, $host, $apiKey)
    {
        $this->logger = $logger;
        $this->client = $client;
        $this->host = $host;
        $this->apiKey = $apiKey;
    }

    /**
     * @param $method
     * @param $uri
     * @param array $params
     * @param null $entity
     * @return array|null
     */
    public function request($method, $uri, $params = array(), $entity = null): array
    {
        try {
            // Build the URI
            $uri = rtrim($this->host, '/').'/almaws/v1/'.ltrim($uri, '/');

            // Add API key to params
            $params['apikey'] = $this->apiKey;

            // Request JSON format
            $params['format'] = 'json';

            // Build options array
            $options = [ 'query' => array_filter($params) ];
            if ($entity) {
                $options['json'] = $entity;
            }
            $options['verify'] = false; // Disable SSL verification

            // Send request
            $response = $this->client->request($method, $uri, $options);

            // return response
            return json_decode($response->getBody(), true);

        } catch (RequestException $e) {
            $this->logger->critical('ALMAclient request exception', json_decode($e->getResponse()->getBody()));
        } catch (GuzzleException $e) {
            $this->logger->critical('Guzzle exception', json_decode($e->getResponse()->getBody()));
        }
    }
}
