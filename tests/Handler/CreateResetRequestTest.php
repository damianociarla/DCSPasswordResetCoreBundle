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
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CreateResetRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getProvider
     */
    public function test($userCheckResult, $authorized)
    {
        $user = $this->createMock(UserInterface::class);

        $userCanCreateNewResetRequest = $this
            ->getMockBuilder(UserCanCreateNewResetRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userCanCreateNewResetRequest
            ->method('__invoke')
            ->willReturn($userCheckResult);

        $resetRequest = new ResetRequest($user);

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addListener(DCSPasswordResetCoreEvents::BEFORE_CREATE_RESET_REQUEST, function (UserCheckerEvent $event) use ($authorized) {
            if ($authorized) {
                $event->setAsAuthorized();
            } else {
                $event->setAsUnauthorized();
            }
        });

        $resetRequestFactory = $this->createMock(ResetRequestFactoryInterface::class);
        $resetRequestFactory
            ->expects($this->exactly($userCheckResult && $authorized ? 1 : 0))
            ->method('createFromUser')
            ->with($user)
            ->willReturn($resetRequest);

        $save = new Save($eventDispatcher);

        if (!$userCheckResult || !$authorized) {
            $this->expectException(UnauthorizedCreateNewResetRequestException::class);
        }

        $handler = new CreateResetRequest($userCanCreateNewResetRequest, $resetRequestFactory, $save, $eventDispatcher);
        $this->assertEquals($resetRequest, call_user_func($handler, $user));
    }

    public function getProvider()
    {
        return [
            [false, false],
            [false, true],
            [true, false],
            [true, true],
        ];
    }
}