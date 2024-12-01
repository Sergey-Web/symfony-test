<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\PdoClient;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use PDO;
use Random\RandomException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:generate-users',
    description: 'Generate fake users for the database.',
)]
class GenerateUsersCommand extends Command
{
    private PDO $pdo;
    private int $count = 1000000;
    private int $batchSize = 1000;

    public function __construct(PdoClient $pdo)
    {
        parent::__construct();
        $this->pdo = $pdo->getConnection();
    }

    /**
     * @throws RandomException
     * @throws \DateMalformedStringException
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $names = [
            "Alexander", "Olivia", "Ethan", "Sophia", "Liam", "Isabella", "Mason", "Mia",
            "Jacob", "Charlotte", "Michael", "Amelia", "Benjamin", "Harper", "Elijah", "Evelyn",
            "James", "Abigail", "Lucas", "Emily", "Henry", "Avery", "William", "Scarlett",
            "Noah", "Ella", "Daniel", "Madison", "Sebastian", "Lily", "Aiden", "Grace",
            "Matthew", "Chloe", "Joseph", "Aria", "Jackson", "Penelope", "Logan", "Riley",
            "David", "Layla", "Nathan", "Zoey", "Samuel", "Nora", "Gabriel", "Hannah",
            "Anthony", "Luna", "Andrew", "Mila", "Joshua", "Ellie", "Christopher", "Lillian",
            "Dylan", "Victoria", "Ryan", "Aurora", "Luke", "Camila", "Caleb", "Brooklyn",
            "Isaac", "Paisley", "Aaron", "Savannah", "Julian", "Skylar", "Hunter", "Claire",
            "Adrian", "Anna", "Jonathan", "Caroline", "Christian", "Genesis", "Carter", "Kennedy",
            "Owen", "Violet", "Levi", "Lucy", "Lincoln", "Stella", "Eli", "Samantha",
            "Connor", "Natalie", "Asher", "Zoe", "Isaiah", "Leah", "Thomas", "Hazel"
        ];

        $this->pdo->beginTransaction();
        $sql = "INSERT INTO users (first_name, last_name, age, gender, birthday, city_id, country_id, company_id) VALUES ";

        $insertValues = [];
        $counter = 0;

        for ($i = 0; $i < $this->count; $i++) {
            $age = random_int(18, 80);
            $name = $names[random_int(0, count($names) - 1)];
            $lastName = bin2hex(random_bytes(5));
            $gender = $age % 2 === 0 ? "male" : "female";
            $birthday = (new DateTimeImmutable())->modify("-{$age} years")->format('Y-m-d');
            $cityId = random_int(1, 4079);
            $countryId = random_int(1, 239);
            $companyId = random_int(1, 1000);

            $insertValues[] = "('$name', '$lastName', $age, '$gender', '$birthday', $cityId, $countryId, $companyId)";
            $counter++;

            if ($counter === $this->batchSize) {
                $this->executeInsert($sql, $insertValues);
                $output->writeln("Inserted {$this->batchSize} users successfully.");
                $insertValues = [];
                $counter = 0;
            }
        }

        if (!empty($insertValues)) {
            $this->executeInsert($sql, $insertValues);
        }

        $this->pdo->commit();
        $output->writeln("{$this->count} users generated successfully!");

        return Command::SUCCESS;
    }

    private function executeInsert(string $sql, array $values): void
    {
        $query = $sql . implode(', ', $values);
        $this->pdo->exec($query);
    }
}
