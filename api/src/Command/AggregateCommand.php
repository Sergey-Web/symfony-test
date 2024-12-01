<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:aggregate',
    description: 'Add a short description for your command',
)]
class AggregateCommand extends Command
{
    /**
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $application = $this->getApplication();

        $memoryUsage = memory_get_usage(true);
        $output->writeln('Memory usage: ' . round($memoryUsage / 1024 / 1024, 2) . ' MB');
        $application->find('app:generate-users')->run($input, $output);
        $memoryUsage = memory_get_usage(true);
        $output->writeln('Memory usage: ' . round($memoryUsage / 1024 / 1024, 2) . ' MB');
        $application->find('app:generate-companies')->run($input, $output);
        $memoryUsage = memory_get_usage(true);
        $output->writeln('Memory usage: ' . round($memoryUsage / 1024 / 1024, 2) . ' MB');
        $application->find('app:generate-cinemas')->run($input, $output);
        $memoryUsage = memory_get_usage(true);
        $output->writeln('Memory usage: ' . round($memoryUsage / 1024 / 1024, 2) . ' MB');
        $application->find('app:generate-museums')->run($input, $output);
        $memoryUsage = memory_get_usage(true);
        $output->writeln('Memory usage: ' . round($memoryUsage / 1024 / 1024, 2) . ' MB');
        $application->find('app:generate-schools')->run($input, $output);
        $memoryUsage = memory_get_usage(true);
        $output->writeln('Memory usage: ' . round($memoryUsage / 1024 / 1024, 2) . ' MB');

        return Command::SUCCESS;
    }
}
