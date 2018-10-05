<?php
namespace App\Service\Alma;


class Set
{
    private $client;
    private $setName;

    public function __construct(Client $client, $setName)
    {
        $this->client = $client;
        $this->setName = $setName;
    }

    /**
     * Gets details about members in the set.
     *
     * Each yielded value should be an array consisting of id (bibid), description and link.
     *
     * @return \Generator
     */
    public function getMembers() : \Generator
    {
        $listSize   = 100;
        $offset     = 0;
        do {
            $setMemberResults   = $this->memberRequest($listSize, $offset);
            $total              = $setMemberResults["total_record_count"];
            $offset             = $offset + $listSize;

            foreach($setMemberResults["member"] as $member){
                yield $member;
            }
        } while(($total - $offset) >= $listSize);
    }


    /**
     * Gets the id of the set based on the configured name
     *
     * @return mixed
     */
    protected function getId()
    {
        $setResults = $this->client->request("get", "/conf/sets", array("q"=>"name~".$this->setName));
        $setId = $setResults["set"][0]["id"];
        return $setId;
    }

    /**
     * Makes a request to the set member endpoint.
     *
     * @param int $limit
     * @param int $offset
     * @return array|null
     */
    protected function memberRequest($limit = 100, $offset = 0)
    {
        $response = $this->client->request("get", "/conf/sets/".$this->getId($this->setName)."/members", [
            "offset"=> $offset,
            "limit" => $limit
        ]);
        return $response;
    }
}
