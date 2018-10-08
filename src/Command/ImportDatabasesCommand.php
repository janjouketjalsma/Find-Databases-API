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

    /**
     * Import databases from the default Alma set
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        // Get members from the set (we need the Bib ids)
        $members = $this->almaSet->getMembers();

        foreach($this->chunk($members, 100) as $members){
            // Get bibs for this chunk
            $bibs = $this->getBibsForMembers($members);

            // Import bibs
            foreach($this->databaseImporter->importFromBibs($bibs) as $importMessage){
                $io->text($importMessage);
            }
        }
        $io->success("Finished importing databases");
    }

    /**
     * Get bib records for a set of members
     *
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
        do{
            yield (function($generator, $n) {
                for ($i = 0; $generator->valid() && $i < $n; $generator->next(), ++$i) {
                    yield $generator->current();
                }
            })($generator, $n);
        } while ($generator->valid());

    }

}
