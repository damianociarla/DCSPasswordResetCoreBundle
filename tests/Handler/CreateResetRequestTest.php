<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Handler;

use DCS\PasswordReset\CoreBundle\Checker\UserCanCreateNewResetRequest;
use DCS\PasswordReset\CoreBundle\DCSPasswordResetCoreEvents;
use DCS\PasswordReset\CoreBundle\Event\UserCheckerEvent;
use DCS\PasswordReset\CoreBundle\Exception\UnauthorizedCreateNewResetRequestException;
use DCS\PasswordReset\CoreBundle\Handler\CreateResetRequest;
use DCS\PasswordReset\CoreBundle\Manager\Save;
use DCS\PasswordReset\CoreBundle\Service\ResetRequestFactoryInterface;
use DCS\PasswordReset\CoreBundle\Tests\Helper\ResetRequest;
use DCS\User\CoreBundle\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CreateResetRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getProvider
     */
    public function test($userCheckResult)
    {
        $user = $this->getMock(UserInterface::class);

        $userCanCreateNewResetRequest = $this
            ->getMockBuilder(UserCanCreateNewResetRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userCanCreateNewResetRequest
            ->method('__invoke')
            ->willReturn($userCheckResult);

        $resetRequest = new ResetRequest($user);

        $eventDispatcher = $this->getMock(EventDispatcherInterface::class);
        $eventDispatcher
            ->expects($this->exactly($userCheckResult ? 4 : 0))
            ->method('dispatch');

        $resetRequestFactory = $this->getMock(ResetRequestFactoryInterface::class);
        $resetRequestFactory
            ->expects($this->exactly($userCheckResult ? 1 : 0))
            ->method('createFromUser')
            ->with($user)
            ->willReturn($resetRequest);

        $save = new Save($eventDispatcher);

        if (!$userCheckResult) {
            $this->expectException(UnauthorizedCreateNewResetRequestException::class);
        } else {
            $eventDispatcher->expects($this->at(0))->method('dispatch')->with(
                DCSPasswordResetCoreEvents::BEFORE_CREATE_RESET_REQUEST,
                $this->isInstanceOf(UserCheckerEvent::class)
            );
        }

        $handler = new CreateResetRequest($userCanCreateNewResetRequest, $resetRequestFactory, $save, $eventDispatcher);
        $this->assertEquals($resetRequest, call_user_func($handler, $user));
    }

    public function getProvider()
    {
        return [
            [false],
            [true],
        ];
    }
}