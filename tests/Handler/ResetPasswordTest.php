<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Handler;

use DCS\PasswordReset\CoreBundle\Checker\IsAvailableResetRequest;
use DCS\PasswordReset\CoreBundle\DCSPasswordResetCoreEvents;
use DCS\PasswordReset\CoreBundle\Event\ResetRequestCheckerEvent;
use DCS\PasswordReset\CoreBundle\Exception\ResetRequestAlreadyUsedException;
use DCS\PasswordReset\CoreBundle\Exception\TimeToLiveException;
use DCS\PasswordReset\CoreBundle\Exception\UnauthorizedResetPasswordException;
use DCS\PasswordReset\CoreBundle\Handler\ResetPassword;
use DCS\PasswordReset\CoreBundle\Manager\Save as ResetRequestSave;
use DCS\PasswordReset\CoreBundle\Service\DateTimeGenerator\DateTimeGeneratorInterface;
use DCS\PasswordReset\CoreBundle\Tests\Helper\ResetRequest;
use DCS\User\CoreBundle\Helper\PasswordHelperInterface;
use DCS\User\CoreBundle\Manager\Save as UserSave;
use DCS\User\CoreBundle\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ResetPasswordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getData
     */
    public function test($resetRequestUsed, $timeToLivePassed, $authorized)
    {
        $password = 'password';

        $isAvailableResetRequestChecker = $this->getMockBuilder(IsAvailableResetRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $isAvailableResetRequestChecker
            ->method('__invoke')
            ->willReturn(!$timeToLivePassed);

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addListener(DCSPasswordResetCoreEvents::BEFORE_RESET_PASSWORD, function (ResetRequestCheckerEvent $event) use ($authorized) {
            if ($authorized) {
                $event->setAsAuthorized();
            } else {
                $event->setAsUnauthorized();
            }
        });

        if ($resetRequestUsed) {
            $this->expectException(ResetRequestAlreadyUsedException::class);
        }

        if (!$resetRequestUsed && $timeToLivePassed) {
            $this->expectException(TimeToLiveException::class);
        }

        if (!$resetRequestUsed && !$timeToLivePassed && !$authorized) {
            $this->expectException(UnauthorizedResetPasswordException::class);
        }

        $passwordHelper = $this->createMock(PasswordHelperInterface::class);

        if ($resetRequestUsed || $timeToLivePassed || !$authorized) {
            $passwordHelper
                ->expects($this->exactly(0))
                ->method('updateUserPassword');
        } else {
            $passwordHelper
                ->expects($this->once())
                ->method('updateUserPassword')
                ->with(
                    $this->isInstanceOf(UserInterface::class),
                    $this->equalTo($password)
                );
        }

        $userSave = $this->getMockBuilder(UserSave::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userSave
            ->expects($this->exactly($resetRequestUsed || $timeToLivePassed || !$authorized ? 0 : 1))
            ->method('__invoke');

        $dateTimeGenerator = $this->createMock(DateTimeGeneratorInterface::class);
        $dateTimeGenerator
            ->expects($this->exactly($resetRequestUsed || $timeToLivePassed || !$authorized ? 0 : 1))
            ->method('generate');

        $resetRequestSave = $this->getMockBuilder(ResetRequestSave::class)
            ->disableOriginalConstructor()
            ->getMock();

        $resetRequestSave
            ->expects($this->exactly($resetRequestUsed || $timeToLivePassed || !$authorized ? 0 : 1))
            ->method('__invoke');

        $resetRequest = new ResetRequest($this->createMock(UserInterface::class));
        $resetRequest->setUsedAt($resetRequestUsed ? new \DateTime() : null);

        $resetPassword = new ResetPassword($isAvailableResetRequestChecker, $eventDispatcher, $userSave, $passwordHelper, $resetRequestSave, $dateTimeGenerator);
        $resetPassword($resetRequest, $password);
    }

    public function getData()
    {
        return [
            [false, false, true],
            [false, false, false],
            [false, true, true],
            [false, true, false],
            [true, true, true],
            [true, true, false],
            [true, false, true],
            [true, false, false],
        ];
    }
}