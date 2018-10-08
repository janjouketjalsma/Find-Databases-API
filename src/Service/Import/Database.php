<?php
namespace App\Service\Import;

use App\Entity\Database as DatabaseEntity;
use App\Repository\DatabaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Scriptotek\Marc\Record;

class Database
{
    /**
     * @var DatabaseRepository
     */
    private $databaseRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(DatabaseRepository $databaseRepository, EntityManagerInterface $entityManager)
    {
        $this->databaseRepository = $databaseRepository;
        $this->entityManager = $entityManager;
    }

    public function importFromBibs(\Generator $bibs){
        foreach($bibs as $bib){
            $database = $this->entityFromBib($bib);

            $this->entityManager->persist($database);

            yield "Importing ".$database->getBibid();
        }
        $this->entityManager->flush();
    }

    protected function entityFromBib(array $bib):DatabaseEntity
    {
        $marcXML    = $bib['anies'][0];
        $marcXML    = str_replace ('encoding="UTF-16"', 'encoding="UTF-8"', $marcXML);//Fix incorrect UTF encoding reference
        $marcRecord = Record::fromString($marcXML);

        $database = $this->databaseRepository->findOneBy(['bibid' => $bib['mms_id']]);

        if(!($database instanceOf DatabaseEntity)){
            $database = new DatabaseEntity();
        }

        $database   ->setTitle($marcRecord->query('245$a')->text())
                    ->setTitleRemainder($marcRecord->query('245$b')->text())
                    ->setPartTitle($marcRecord->query('245$p')->text())
                    ->setTitleOrgScript($marcRecord->query('880$a{$6~\245-}')->text())
                    ->setSortingTitle($this->getMarcSortingTitle($marcRecord))
                    ->setPublisher($marcRecord->query('260$b')->text())
                    ->setPublisherWebsite($marcRecord->query('947$v')->text())
                    ->setPublisherWebsiteLinkText($marcRecord->query('947$z')->text())
                    ->setDescription($marcRecord->query('947$u')->text())
                    ->setUrl($marcRecord->query('946$u')->text())
                    ->setLastModified(new \DateTime($bib['last_modified_date']))
                    ->setBibid($bib['mms_id'])
                    ->setNeedsProxy($marcRecord->query('946$z')->text())
                    ->setDatabaseGuide($marcRecord->query('947$u')->text())
                    ->setDatabaseGuideLinkText($marcRecord->query('947$y')->text())
                    ->setAccessPolicy($marcRecord->query('944$a')->text());

        return $database;
    }

    public function getMarcSortingTitle(Record $marcRecord)
    {
        $nonFilingCharacters = (int) $marcRecord->query('245')->first()->getIndicator(2);

        return substr($marcRecord->query('245$a')->text(), $nonFilingCharacters);
    }
}
