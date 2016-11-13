<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Handler;

use DCS\PasswordReset\CoreBundle\Exception\ResetRequestNotFoundException;
use DCS\PasswordReset\CoreBundle\Handler\ResetPassword;
use DCS\PasswordReset\CoreBundle\Handler\ResetPasswordFromToken;
use DCS\PasswordReset\CoreBundle\Repository\ResetRequestRepositoryInterface;
use DCS\PasswordReset\CoreBundle\Tests\Helper\ResetRequest;

class ResetPasswordFromTokenTest extends \PHPUnit_Framework_TestCase
{
    public function testWithRepositoryMatchToken()
    {
        $resetPassword = new ResetRequest();
        $token = 'token';
        $password = 'password';

        $resetRequestRepository = $this->createMock(ResetRequestRepositoryInterface::class);
        $resetRequestRepository
            ->method('findOneByToken')->with($token)->willReturn($resetPassword);

        $resetPasswordHandler = $this->getMockBuilder(ResetPassword::class)->disableOriginalConstructor()->getMock();
        $resetPasswordHandler
            ->expects($this->exactly(1))
            ->method('__invoke')
            ->with($resetPassword, $password);

        $handler = new ResetPasswordFromToken($resetRequestRepository, $resetPasswordHandler);
        $handler($token, $password);
    }

    public function testWithRepositoryNotMatchToken()
    {
        $token = 'token';
        $password = 'password';

        $resetRequestRepository = $this->createMock(ResetRequestRepositoryInterface::class);
        $resetRequestRepository
            ->method('findOneByToken')->with($token)->willReturn(null);

        $resetPasswordHandler = $this->getMockBuilder(ResetPassword::class)->disableOriginalConstructor()->getMock();
        $resetPasswordHandler
            ->expects($this->exactly(0))
            ->method('__invoke');

        $this->expectException(ResetRequestNotFoundException::class);

        $handler = new ResetPasswordFromToken($resetRequestRepository, $resetPasswordHandler);
        $handler($token, $password);
    }
}