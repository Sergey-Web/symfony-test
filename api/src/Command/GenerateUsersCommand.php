<?php

namespace App\Command;

use App\Entity\Company;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Random\RandomException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand(
    name: 'app:generate-users',
    description: 'Generate fake users for the database.',
)]
class GenerateUsersCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    /**
     * @throws RandomException
     * @throws \DateMalformedStringException
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connection = $this->entityManager->getConnection();
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

        $batchSize = 1000;
        $insertValues = [];
        $counter = 0;

        for ($i = 0; $i < 1000000; $i++) {
            $age = random_int(18, 80);
            $name = $names[random_int(0, count($names) - 1)];
            $lastName = bin2hex(random_bytes(5));
            $gender = $age % 2 === 0 ? "male" : "female";
            $birthday = (new \DateTimeImmutable())->modify("-{$age} years")->format('Y-m-d');
            $cityId = random_int(1, 4079);
            $countryId = random_int(1, 239);
            $companyId = random_int(1, 1000);

            $insertValues[] = "('$name', '$lastName', $age, '$gender', '$birthday', $cityId, $countryId, $companyId)";
            $counter++;

            $memoryUsage = memory_get_usage(true); // Потребление памяти в байтах (округлённое до ближайшего блока)
            $output->writeln('Memory usage: ' . round($memoryUsage / 1024 / 1024, 2) . ' MB');

            if ($counter === $batchSize) {
                $sql = "INSERT INTO users (first_name, last_name, age, gender, birthday, city_id, country_id, company_id) VALUES " . implode(', ', $insertValues);
                $connection->executeStatement($sql);

                $insertValues = [];
                $counter = 0;

                $output->writeln("Inserted 1000 users successfully.");
            }
        }

        if (!empty($insertValues)) {
            $sql = "INSERT INTO users (first_name, last_name, age, gender, birthday, city_id, country_id, company_id) VALUES " . implode(', ', $insertValues);
            $connection->executeStatement($sql);
            $output->writeln("Inserted remaining users successfully.");
        }

        $output->writeln("1000000 users generated successfully!");

        return Command::SUCCESS;
    }
}
