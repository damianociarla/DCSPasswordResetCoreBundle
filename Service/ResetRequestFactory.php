<?php

namespace DCS\PasswordReset\CoreBundle\Service;

use DCS\User\CoreBundle\Model\UserInterface;
use DCS\PasswordReset\CoreBundle\Service\DateTimeGenerator\DateTimeGeneratorInterface;
use DCS\PasswordReset\CoreBundle\Service\TokenGenerator\TokenGeneratorInterface;

class ResetRequestFactory implements ResetRequestFactoryInterface
{
    /**
     * @var string
     */
    private $modelClass;

    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    /**
     * @var DateTimeGeneratorInterface
     */
    private $dateTimeGenerator;

    /**
     * ResetRequestFactory constructor.
     *
     * @param string $modelClass
     * @param TokenGeneratorInterface $tokenGenerator
     * @param DateTimeGeneratorInterface $dateTimeGenerator
     */
    public function __construct($modelClass, TokenGeneratorInterface $tokenGenerator, DateTimeGeneratorInterface $dateTimeGenerator)
    {
        $this->modelClass = $modelClass;
        $this->tokenGenerator = $tokenGenerator;
        $this->dateTimeGenerator = $dateTimeGenerator;
    }

    /**
     * @inheritDoc
     */
    public function createFromUser(UserInterface $user)
    {
        return new $this->modelClass(
            $user,
            $this->tokenGenerator->generate(),
            $this->dateTimeGenerator->generate()
        );
    }
}