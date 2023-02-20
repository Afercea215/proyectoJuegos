<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'user-admin',
    description: 'convierte un usuario en Admin',
)]
class UserAdminCommand extends Command
{
    private $ur;
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine, UserRepository $ur)
    {
        $this->ur=$ur;
        $this->doctrine=$doctrine;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('mail', InputArgument::REQUIRED, 'Correo electronico del usuario')
            //->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
            ->setHelp('Este comando convierte un usuario en Admin')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $em = $this->doctrine->getManager();
        $user = $this->ur->findOneBy(['email' => $input->getArgument('mail')]);

        $roles = $user->getRoles();
        $roles[] = 'ROLE_ADMIN';
        $user->setRoles($roles);

        $em->persist($user);
        $em->flush();

        $output->writeln('El usuario '.$user->getNombre().' es admin!');
        /* $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
 */
        return Command::SUCCESS;
    }
}
