<?php

namespace App\Command;

use App\Service\PdoClient;
use PDO;
use Random\RandomException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:generate-museums',
    description: 'Add a short description for your command',
)]
class GenerateMuseumsCommand extends Command
{
    private PDO $pdo;
    private int $count = 10000;
    private int $batchSize = 1000;

    public function __construct(PdoClient $pdo)
    {
        parent::__construct();
        $this->pdo = $pdo->getConnection();
    }

    /**
     * @throws RandomException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->pdo->beginTransaction();
        $table = 'museums';
        $sql = "INSERT INTO {$table} (country_id, city_id, name) VALUES ";

        $insertValues = [];
        $counter = 0;

        for ($i = 0; $i < $this->count; $i++) {
            $name = bin2hex(random_bytes(5));
            $cityId = random_int(1, 4079);
            $countryId = random_int(1, 239);

            $insertValues[] = "('$countryId', '$cityId', '$name')";

            if ($counter === $this->batchSize) {
                $this->executeInsert($sql, $insertValues);
                $output->writeln("Inserted {$this->batchSize} {$table} successfully.");
                $insertValues = [];
                $counter = 0;
            }
        }

        if (!empty($insertValues)) {
            $this->executeInsert($sql, $insertValues);
        }

        $this->pdo->commit();
        $output->writeln("{$this->count} {$table} generated successfully!");

        return Command::SUCCESS;
    }

    private function executeInsert(string $sql, array $values): void
    {
        $query = $sql . implode(', ', $values);
        $this->pdo->exec($query);
    }
}
