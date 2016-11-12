<?php

namespace DCS\PasswordReset\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateNewResetRequestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('dcs_user:password_reset:create_new_request')
            ->setDescription('Create new reset password request')
            ->addOption('id', null, InputOption::VALUE_OPTIONAL)
            ->addOption('username', null, InputOption::VALUE_OPTIONAL)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getOption('id');
        $username = $input->getOption('username');

        if (null !== $id && null !== $username) {
            throw new \UnexpectedValueException('You can specify only one option between "id" and "username"');
        }

        if (null === $id && null === $username) {
            throw new \UnexpectedValueException('You can specify at least one option between "id" and "username"');
        }

        if (null !== $id) {
            $user = $this->getContainer()->get('dcs_user.repository')->findOneById($id);
        }

        if (null !== $username) {
            $user = $this->getContainer()->get('dcs_user.repository')->findOneByUsername($username);
        }

        if (null === $user) {
            throw new \Exception('User not found');
        }

        $resetRequest = $this->getContainer()->get('dcs_password_reset.handler.create_reset_request')->__invoke($user);

        $formatter = $this->getHelper('formatter');
        $formattedBlock = $formatter->formatBlock(['Success!', sprintf('The password change request was created correctly. The token to use is: %s', $resetRequest->getToken())], 'success',true);

        $style = new OutputFormatterStyle('white', 'green');
        $output->getFormatter()->setStyle('success', $style);
        $output->writeln('');
        $output->writeln($formattedBlock);
        $output->writeln('');
    }
}