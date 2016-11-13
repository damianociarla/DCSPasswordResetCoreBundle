<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Command;

use DCS\PasswordReset\CoreBundle\Command\CreateNewResetRequestCommand;
use DCS\PasswordReset\CoreBundle\Handler\CreateResetRequest;
use DCS\PasswordReset\CoreBundle\Model\ResetRequestInterface;
use DCS\User\CoreBundle\Model\UserInterface;
use DCS\User\CoreBundle\Repository\UserRepositoryInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\DependencyInjection\Container;

class CreateNewResetRequestCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigureMethod()
    {
        $container = new Container();

        $command = new CreateNewResetRequestCommand();
        $command->setContainer($container);

        $this->assertEquals('dcs_user:password_reset:create_new_request', $command->getName());

        $this->assertTrue($command->getDefinition()->hasOption('id'));
        $this->assertTrue($command->getDefinition()->hasOption('username'));

        $this->assertTrue($command->getDefinition()->getOption('id')->isValueOptional());
        $this->assertTrue($command->getDefinition()->getOption('username')->isValueOptional());
    }

    /**
     * @dataProvider executeMethodWithExceptionDataProvider
     */
    public function testExecuteMethodWithException($inputOptions, $exception, $exceptionMessageRegExp, $repositoryMethodCall)
    {
        $input = new ArrayInput($inputOptions);

        $output = new NullOutput();

        $this->expectException($exception);
        $this->expectExceptionMessageRegExp($exceptionMessageRegExp);

        $repository = $this->getMockBuilder(UserRepositoryInterface::class)->getMock();

        if (null !== $repositoryMethodCall) {
            $repository->expects($this->once())->method($repositoryMethodCall)->willReturn(null);
        }

        $container = new Container();
        $container->set('dcs_user.repository', $repository);

        $command = new CreateNewResetRequestCommand();
        $command->setContainer($container);
        $command->run($input, $output);
    }

    public function executeMethodWithExceptionDataProvider()
    {
        return [
            [
                [], \UnexpectedValueException::class, '/.*at least one option.*/', null
            ],
            [
                ['--id' => 1, '--username' => 'acme'], \UnexpectedValueException::class, '/.*only one option.*/', null
            ],
            [
                ['--id' => 1], \Exception::class, '/.*not found.*/', 'findOneById'
            ],
            [
                ['--username' => 'acme'], \Exception::class, '/.*not found.*/', 'findOneByUsername'
            ],
        ];
    }

    /**
     * @dataProvider completeCommandDataProvider
     */
    public function testCompleteCommand($inputOptions, $repositoryMethodCall)
    {
        $input = new ArrayInput($inputOptions);
        $output = new NullOutput();

        $user = $this->getMockBuilder(UserInterface::class)->getMock();

        $resetRequest = $this->getMockBuilder(ResetRequestInterface::class)->getMock();
        $resetRequest->expects($this->once())->method('getToken')->willReturn('token');

        $repository = $this->getMockBuilder(UserRepositoryInterface::class)->getMock();
        $repository->expects($this->once())->method($repositoryMethodCall)->willReturn($user);

        $handler = $this->getMockBuilder(CreateResetRequest::class)->disableOriginalConstructor()->getMock();
        $handler->expects($this->once())->method('__invoke')->with($this->isInstanceOf(UserInterface::class))->willReturn($resetRequest);

        $container = new Container();
        $container->set('dcs_user.repository', $repository);
        $container->set('dcs_password_reset.handler.create_reset_request', $handler);

        $command = new CreateNewResetRequestCommand();
        $command->setContainer($container);
        $command->setApplication(new Application());
        $command->run($input, $output);
    }

    public function completeCommandDataProvider()
    {
        return [
            [
                ['--id' => 1], 'findOneById'
            ],
            [
                ['--username' => 'acme'], 'findOneByUsername'
            ],
        ];
    }
}