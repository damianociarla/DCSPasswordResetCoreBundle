<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Command;

use DCS\PasswordReset\CoreBundle\Command\ResetPasswordCommand;
use DCS\PasswordReset\CoreBundle\Handler\ResetPasswordFromToken;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\DependencyInjection\Container;

class ResetPasswordCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigureMethod()
    {
        $container = new Container();

        $command = new ResetPasswordCommand();
        $command->setContainer($container);

        $this->assertEquals('dcs_user:password_reset:reset', $command->getName());

        $this->assertTrue($command->getDefinition()->hasArgument('token'));
        $this->assertTrue($command->getDefinition()->hasArgument('password'));

        $this->assertTrue($command->getDefinition()->getArgument('token')->isRequired());
        $this->assertTrue($command->getDefinition()->getArgument('password')->isRequired());
    }

    public function testExecuteMethod()
    {
        $input = new ArrayInput([
            'token' => 'abc',
            'password' => '123',
        ]);
        $output = new NullOutput();

        $handler = $this->getMockBuilder(ResetPasswordFromToken::class)->disableOriginalConstructor()->getMock();
        $handler->expects($this->once())->method('__invoke')->with('abc','123');

        $container = new Container();
        $container->set('dcs_password_reset.handler.reset_password_from_token', $handler);

        $command = new ResetPasswordCommand();
        $command->setContainer($container);
        $command->setApplication(new Application());
        $command->run($input, $output);
    }
}