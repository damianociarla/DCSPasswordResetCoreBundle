<?php

namespace DCS\PasswordReset\CoreBundle\Checker;

use DCS\PasswordReset\CoreBundle\Model\ResetRequestInterface;
use DCS\PasswordReset\CoreBundle\Service\DateTimeGenerator\DateTimeGeneratorInterface;

class IsAvailableResetRequest
{
    /**
     * @var DateTimeGeneratorInterface
     */
    private $dateTimeGenerator;

    /**
     * @var int
     */
    private $tokenTtl;

    /**
     * Ttl constructor.
     *
     * @param DateTimeGeneratorInterface $dateTimeGenerator
     * @param int|null $tokenTtl
     */
    public function __construct(DateTimeGeneratorInterface $dateTimeGenerator, $tokenTtl = null)
    {
        $this->dateTimeGenerator = $dateTimeGenerator;
        $this->tokenTtl = $tokenTtl;
    }

    /**
     * Check if the creation date of the request is lower of the ttl parameter
     *
     * @param ResetRequestInterface $resetRequest
     * @return bool
     */
    public function __invoke(ResetRequestInterface $resetRequest)
    {
        if (null === $this->tokenTtl) {
            return true;
        }

        $currentDateTime = $this->dateTimeGenerator->generate();
        $diff = $currentDateTime->getTimestamp() - $resetRequest->getCreatedAt()->getTimestamp();

        return $diff <= $this->tokenTtl;
    }
}