<?php

namespace App\Command;

use App\Entity\User;
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

        for ($i = 0; $i < 10; $i++) { // Создаем 10 пользователей
            $user = new User();
            $user->setFirstName(random_int(0, count($names)));
            $user->setLastName();
            $user->setAge(random_int(18, 80));
            $user->setBirthday($faker->dateTimeBetween('-80 years', '-18 years'));

            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();

        $output->writeln('10 users generated successfully!');
        return Command::SUCCESS;
    }
}
