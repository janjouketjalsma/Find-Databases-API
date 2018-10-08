<?php

namespace App\Command;

use App\Service\Alma\Bibs;
use App\Service\Alma\Set;
use App\Service\Import\Database;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportDatabasesCommand extends Command
{
    protected static $defaultName = 'app:import-databases';

    private $almaSet;
    private $almaBibs;
    /**
     * @var Database
     */
    private $databaseImporter;

    public function __construct(Set $almaSet, Bibs $almaBibs, Database $databaseImporter)
    {
        parent::__construct();
        $this->almaSet = $almaSet;
        $this->almaBibs = $almaBibs;
        $this->databaseImporter = $databaseImporter;
    }

    protected function configure()
    {
        $this->setDescription('Imports databases');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        // Get members from the set (we need the Bib ids)
        $members = $this->almaSet->getMembers();

        // Get bibs
        $bibs = $this->getBibsForMembers($this->chunk($members, 100));

        // Import bibs
        foreach($this->databaseImporter->importFromBibs($bibs) as $importMessage){
            $io->text($importMessage);
        }

        $io->success("Finished importing databases");
    }

    /**
     * @param \Traversable $members
     * @return \Generator
     */
    protected function getBibsForMembers(\Traversable $members)
    {
        $bibIds = [];
        foreach($members as $member){
            $bibIds[] = $member['id'];
        }
        // Get the bib records
        $bibs = $this->almaBibs->getBibs($bibIds);

        // Yield the bib records
        foreach($bibs['bib'] as $bib){
            yield $bib;
        }
    }

    /**
     * Creates a generator that yields generators for chunks of a specified size
     *
     * @param \Generator $generator
     * @param $n
     * @return \Generator
     */
    protected function chunk(\Generator $generator, $n) {
        for ($i = 0; $generator->valid() && $i < $n; $generator->next(), ++$i) {
            yield $generator->current();
        }
    }

}
