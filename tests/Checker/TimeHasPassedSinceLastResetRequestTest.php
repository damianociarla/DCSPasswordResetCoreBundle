<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Checker;

use DCS\PasswordReset\CoreBundle\Checker\TimeHasPassedSinceLastResetRequest;
use DCS\PasswordReset\CoreBundle\Service\DateTimeGenerator\DateTimeGeneratorInterface;
use DCS\PasswordReset\CoreBundle\Tests\Helper\FixedDateTimeGenerator;
use DCS\PasswordReset\CoreBundle\Tests\Helper\ResetRequest;

class TimeHasPassedSinceLastResetRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getDataProvider
     */
    public function test($dateTimeReferrer, $dateTimeRequest, $waitingTimeNewRequest, $expected)
    {
        $dateTimeGenerator = $this->getMock(DateTimeGeneratorInterface::class);
        $dateTimeGenerator->expects($this->any())->method('generate')->willReturn($dateTimeReferrer);
        
        $checker = new TimeHasPassedSinceLastResetRequest($dateTimeGenerator, $waitingTimeNewRequest);

        $resetRequest = new ResetRequest();
        $resetRequest->setCreatedAt($dateTimeRequest);

        $this->assertEquals($expected, $checker($resetRequest));
    }

    public function getDataProvider()
    {
        $dateTimeReferrer = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-01-01 23:59:59');
        $dateTimeRequest  = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-01-01 22:59:59');

        $diff = $dateTimeReferrer->getTimestamp() - $dateTimeRequest->getTimestamp();

        return [
            [$dateTimeReferrer, $dateTimeRequest, null, true],
            [$dateTimeReferrer, $dateTimeRequest, $diff, false],
            [$dateTimeReferrer, $dateTimeRequest, $diff+1, false],
            [$dateTimeReferrer, $dateTimeRequest, $diff-1, true],
        ];
    }
}