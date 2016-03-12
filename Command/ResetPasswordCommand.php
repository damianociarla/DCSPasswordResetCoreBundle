<?php

namespace DCS\PasswordReset\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetPasswordCommand extends ContainerAwareCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('dcs_user:password_reset:reset')
            ->setDescription('Reset password from token')
            ->addArgument('token', InputArgument::REQUIRED)
            ->addArgument('password', InputArgument::REQUIRED)
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $token = $input->getArgument('token');
        $password = $input->getArgument('password');

        $passwordReset = $this->getContainer()->get('dcs_password_reset.handler.reset_password_from_token');
        $passwordReset($token, $password);

        $formatter = $this->getHelper('formatter');
        $formattedBlock = $formatter->formatBlock(['Success!', 'The password has been successfully updated'], 'success', true);

        $style = new OutputFormatterStyle('white', 'green');
        $output->getFormatter()->setStyle('success', $style);
        $output->writeln('');
        $output->writeln($formattedBlock);
        $output->writeln('');
    }
}