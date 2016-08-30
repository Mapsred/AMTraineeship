<?php
/**
 * Created by PhpStorm.
 * User: francois
 * Date: 30/08/16
 * Time: 11:48
 */

namespace AdminBundle\Command;


use AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserGeneratorCommand extends ContainerAwareCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName("admin:create_user")
            ->setDescription("Create user and encode password")
            ->addArgument('username', InputArgument::REQUIRED, "The username to add")
            ->addArgument('password', InputArgument::REQUIRED, "The password to encode")
            ->addArgument('admin', InputArgument::OPTIONAL, "User as admin")
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null|int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $encoder = $this->getContainer()->get("security.password_encoder");
        $roles = ['ROLE_USER'];
        if ($input->hasArgument('admin') && $input->getArgument('admin') == 'true') {
            $roles[] = 'ROLE_ADMIN';
        }
        $user = new User();
        $password = $encoder->encodePassword($user, $input->getArgument('password'));
        $user->setUsername($input->getArgument('username'))->setPassword($password)->setRoles($roles);
        $this->getContainer()->get('doctrine')->getManager()->persist($user);
        $this->getContainer()->get('doctrine')->getManager()->flush();
        $output->writeln(sprintf("User %s created", $user->getUsername()));

        return null;
    }


}