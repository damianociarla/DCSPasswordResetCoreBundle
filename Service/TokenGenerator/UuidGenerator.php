<?php

namespace DCS\PasswordReset\CoreBundle\Service\TokenGenerator;

use Ramsey\Uuid\Uuid;

class UuidGenerator implements TokenGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function generate()
    {
        return Uuid::uuid4();
    }
}