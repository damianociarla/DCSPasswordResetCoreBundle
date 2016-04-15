<?php

namespace DCS\PasswordReset\CoreBundle\Service;

use DCS\User\CoreBundle\Model\UserInterface;
use DCS\PasswordReset\CoreBundle\Model\ResetRequestInterface;

interface ResetRequestFactoryInterface
{
    /**
     * Create a new instance of ResetRequest
     *
     * @param UserInterface $user
     * @return ResetRequestInterface
     */
    public function createFromUser(UserInterface $user);
}