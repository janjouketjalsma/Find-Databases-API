<?php
namespace App\Service\Alma;

use Scriptotek\Marc\Record;

class Bibs
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Bibs constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getBib($bibId)
    {
        return $this->client->request("get", "/bibs/".$bibId);
    }

    public function getBibs(array $bibIds)
    {
        if(count($bibIds) > 100){
            throw new \InvalidArgumentException("getBibs accepts an array of up to 100 bibIds. An array of ".count($bibIds)." was given");
        }
        return $this->client->request("get", "/bibs", ['mms_id' => implode(',', $bibIds)]);
    }
}
