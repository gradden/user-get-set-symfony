<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-secret',
    description: 'Generate a new APP_SECRET',
)]
class GenerateAppSecretCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $chars = '1234567890abcdefg';
        $key = '';

        for ($i = 0; $i <= 32; $i++) {
            $key .= $chars[rand(0, (strlen($chars) - 1))];
        }

        $io->info('Your key is: ' . $key);
        $io->success('Your new secret has been successfully generated.');

        return Command::SUCCESS;
    }
}
