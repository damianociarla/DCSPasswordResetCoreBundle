<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Checker;

use DCS\PasswordReset\CoreBundle\Checker\TimeHasPassedSinceLastResetRequest;
use DCS\PasswordReset\CoreBundle\Checker\UserCanCreateNewResetRequest;
use DCS\PasswordReset\CoreBundle\Repository\ResetRequestRepositoryInterface;
use DCS\PasswordReset\CoreBundle\Service\DateTimeGenerator\DateTimeGeneratorInterface;
use DCS\PasswordReset\CoreBundle\Tests\Helper\ResetRequest;
use DCS\User\CoreBundle\Model\UserInterface;

class UserCanCreateNewResetRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getDataProvider
     */
    public function test($dateTimeReferrer, $waitingTimeNewRequest, $returnRepository, $expected)
    {
        $dateTimeGenerator = $this->getMock(DateTimeGeneratorInterface::class);
        $dateTimeGenerator->expects($this->any())->method('generate')->willReturn($dateTimeReferrer);

        $checker = new TimeHasPassedSinceLastResetRequest($dateTimeGenerator, $waitingTimeNewRequest);
        $user = $this->getMock(UserInterface::class);

        $resetRequestRepository = $this->getMock(ResetRequestRepositoryInterface::class);
        $resetRequestRepository->expects($this->once())->method('findLatestNotUsedByUser')->with($user)->willReturn($returnRepository);

        $userCanCreateNewResetRequest = new UserCanCreateNewResetRequest($resetRequestRepository, $checker);

        $this->assertEquals($expected, $userCanCreateNewResetRequest($user));
    }

    public function getDataProvider()
    {
        $dateTimeReferrer = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-01-01 23:59:59');

        $resetRequest = new ResetRequest();
        $resetRequest->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', '2016-01-01 22:59:59'));

        $diff = $dateTimeReferrer->getTimestamp() - $resetRequest->getCreatedAt()->getTimestamp();

        return [
            [$dateTimeReferrer, $diff, $resetRequest, false],
            [$dateTimeReferrer, $diff+1, $resetRequest, false],
            [$dateTimeReferrer, $diff-1, $resetRequest, true],
            [$dateTimeReferrer, $diff, null, true],
            [$dateTimeReferrer, $diff+1, null, true],
            [$dateTimeReferrer, $diff-1, null, true],
        ];
    }
}