<?php

declare(strict_types= 1);

namespace Marshal\Authentication\Command;

use Marshal\Authentication\User\UserInterface;
use Marshal\Database\Hydrator\ItemInputHydrator;
use Marshal\Database\Query\Create;
use Marshal\Database\Schema\ContentManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class CreateUserCommand extends Command
{
    public function __construct()
    {
        parent::__construct("marshal:create-user");
    }

    protected function configure(): void
    {
        $this->setDescription("Create a user")
            ->setHelp('Create a user')
            ->addOption('credential', null, InputOption::VALUE_REQUIRED, 'The user\'s email address or phone number');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // get basic details
        $credential = $input->getOption('credential');
        if (empty($credential)) {
            $io->error("Please set an account credential using --credential=<credential>");
            return Command::FAILURE;
        }

        // set a password
        $password = $io->ask("Set a password for this account");
        if (empty($password) || \mb_strlen($password) < 6) {
            $io->error("Password must have a minimum of 6 characters");
            return Command::FAILURE;
        }

        $confirmPassword = $io->ask("Confirm the password");
        if ($password !== $confirmPassword) {
            $io->error("Confirm password did not match");
            return Command::FAILURE;
        }

        // @todo validate input as with form

        // create the user
        $user = ContentManager::get(UserInterface::class);
        $hydrator = new ItemInputHydrator();
        $hydrator->hydrate($user, [
            'credential' => $credential,
            'status' => 'unverified',
            'password' => \password_hash($password, PASSWORD_DEFAULT)
        ]);

        try {
            Create::target($user);
        } catch (\Throwable $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
