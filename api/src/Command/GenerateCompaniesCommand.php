<?php

namespace App\Command;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-companies',
    description: 'Add a short description for your command',
)]
class GenerateCompaniesCommand extends Command
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
        for ($i = 0; $i < 1000; $i++) {
            $company = new Company();
            $company->setName(bin2hex(random_bytes(5)));
            $company->setCityId(random_int(1, 4079));
            $company->setCountryId(random_int(1, 239));

            $this->entityManager->persist($company);
        }

        $this->entityManager->flush();

        $output->writeln('1000 companies generated successfully!');

        return Command::SUCCESS;
    }
}
