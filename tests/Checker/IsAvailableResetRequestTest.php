<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Checker;

use DCS\PasswordReset\CoreBundle\Checker\IsAvailableResetRequest;
use DCS\PasswordReset\CoreBundle\Service\DateTimeGenerator\DateTimeGeneratorInterface;
use DCS\PasswordReset\CoreBundle\Tests\Helper\FixedDateTimeGenerator;
use DCS\PasswordReset\CoreBundle\Tests\Helper\ResetRequest;

class IsAvailableResetRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getDataProvider
     */
    public function test($dateTimeReferrer, $dateTimeRequest, $ttl, $expected)
    {
        $dateTimeGenerator = $this->createMock(DateTimeGeneratorInterface::class);
        $dateTimeGenerator->expects($this->any())->method('generate')->willReturn($dateTimeReferrer);

        $checker = new IsAvailableResetRequest($dateTimeGenerator, $ttl);

        $resetRequest = new ResetRequest();
        $resetRequest->setCreatedAt($dateTimeRequest);

        $this->assertEquals($expected, $checker($resetRequest));
    }

    public function getDataProvider()
    {
        $dateTimeReferrer = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-01-01 23:59:59');
        $dateTimeRequest  = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-01-01 22:59:59');

        return [
            [$dateTimeReferrer, $dateTimeRequest, null, true],
            [$dateTimeReferrer, $dateTimeRequest, 3601, true],
            [$dateTimeReferrer, $dateTimeRequest, 3600, true],
            [$dateTimeReferrer, $dateTimeRequest, 3599, false],
        ];
    }
}
