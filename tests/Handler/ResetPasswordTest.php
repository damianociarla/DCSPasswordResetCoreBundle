<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Handler;

use DCS\PasswordReset\CoreBundle\Checker\IsAvailableResetRequest;
use DCS\PasswordReset\CoreBundle\Exception\ResetRequestAlreadyUsedException;
use DCS\PasswordReset\CoreBundle\Exception\TimeToLiveException;
use DCS\PasswordReset\CoreBundle\Handler\ResetPassword;
use DCS\PasswordReset\CoreBundle\Service\DateTimeGenerator\DateTimeGeneratorInterface;
use DCS\PasswordReset\CoreBundle\Tests\Helper\ResetRequest;
use DCS\User\CoreBundle\Helper\PasswordHelperInterface;
use DCS\User\CoreBundle\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use DCS\User\CoreBundle\Manager\Save as UserSave;
use DCS\PasswordReset\CoreBundle\Manager\Save as ResetRequestSave;

class ResetPasswordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getData
     */
    public function test($resetRequestUsed, $timeToLivePassed)
    {
        $password = 'password';

        $isAvailableResetRequestChecker = $this->getMockBuilder(IsAvailableResetRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $isAvailableResetRequestChecker
            ->method('__invoke')
            ->willReturn(!$timeToLivePassed);

        $eventDispatcher = $this->getMock(EventDispatcherInterface::class);
        $eventDispatcher
            ->expects($this->exactly($resetRequestUsed || $timeToLivePassed ? 0 : 1))
            ->method('dispatch');

        if ($resetRequestUsed) {
            $this->expectException(ResetRequestAlreadyUsedException::class);
        }

        if (!$resetRequestUsed && $timeToLivePassed) {
            $this->expectException(TimeToLiveException::class);
        }

        $passwordHelper = $this->getMock(PasswordHelperInterface::class);

        if ($resetRequestUsed || $timeToLivePassed) {
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
            ->expects($this->exactly($resetRequestUsed || $timeToLivePassed ? 0 : 1))
            ->method('__invoke');

        $dateTimeGenerator = $this->getMock(DateTimeGeneratorInterface::class);
        $dateTimeGenerator
            ->expects($this->exactly($resetRequestUsed || $timeToLivePassed ? 0 : 1))
            ->method('generate');

        $resetRequestSave = $this->getMockBuilder(ResetRequestSave::class)
            ->disableOriginalConstructor()
            ->getMock();

        $resetRequestSave
            ->expects($this->exactly($resetRequestUsed || $timeToLivePassed ? 0 : 1))
            ->method('__invoke');

        $resetRequest = new ResetRequest($this->getMock(UserInterface::class));
        $resetRequest->setUsedAt($resetRequestUsed ? new \DateTime() : null);

        $resetPassword = new ResetPassword($isAvailableResetRequestChecker, $eventDispatcher, $userSave, $passwordHelper, $resetRequestSave, $dateTimeGenerator);
        $resetPassword($resetRequest, $password);
    }

    public function getData()
    {
        return [
            [false, false],
            [false, true],
            [true, true],
            [true, false],
        ];
    }
}