<?php

namespace DCS\PasswordReset\CoreBundle\Checker;

use DCS\PasswordReset\CoreBundle\Model\ResetRequestInterface;
use DCS\PasswordReset\CoreBundle\Service\DateTimeGenerator\DateTimeGeneratorInterface;

class TimeHasPassedSinceLastResetRequest
{
    /**
     * @var DateTimeGeneratorInterface
     */
    private $dateTimeGenerator;

    /**
     * @var int|null
     */
    private $waitingTimeNewRequest;

    /**
     * TimeHasPassedSinceLastResetRequest constructor.
     *
     * @param DateTimeGeneratorInterface $dateTimeGenerator
     * @param int|null $waitingTimeNewRequest
     */
    public function __construct(DateTimeGeneratorInterface $dateTimeGenerator, $waitingTimeNewRequest = null)
    {
        $this->dateTimeGenerator = $dateTimeGenerator;
        $this->waitingTimeNewRequest = $waitingTimeNewRequest;
    }

    /**
     * Check if the creation date of the request is lower of the ttl parameter
     *
     * @param ResetRequestInterface $resetRequest
     * @return bool
     */
    public function __invoke(ResetRequestInterface $resetRequest)
    {
        if (null === $this->waitingTimeNewRequest) {
            return true;
        }

        $currentDateTime = $this->dateTimeGenerator->generate();
        $diff = $currentDateTime->getTimestamp() - $resetRequest->getCreatedAt()->getTimestamp();

        return $diff > $this->waitingTimeNewRequest;
    }
}