<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use App\Service\UserService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Exception;

#[AsCommand(
    name: 'app:create-admin-user',
    description: 'Creates a new admin user.',
    hidden: false,
    aliases: ['app:add-user']
)]
class CreateAdminUserCommand extends Command
{
    public function __construct(protected UserService $userService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'The email of the user.');
        $this->addArgument('password', InputArgument::REQUIRED, 'User password');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        try {

            $data = array(
                'email' => $input->getArgument('email'),
                'password' => $input->getArgument('password'),
                'roles' => array('ROLE_ADMIN')
            );

            $user = $this->userService->create($data);

            $output->writeln([
                'Created admin user:',
                'Email:' . $user['email']
            ]);

            return Command::SUCCESS;
        } catch (Exception $ex) {
            $output->writeln([
                'Error: ' . $ex->getMessage()
            ]);

            return Command::FAILURE;
        }
    }
}
