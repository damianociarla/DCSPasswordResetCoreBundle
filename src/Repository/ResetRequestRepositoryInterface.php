<?php

namespace DCS\PasswordReset\CoreBundle\Repository;

use DCS\PasswordReset\CoreBundle\Model\ResetRequestInterface;

interface ResetRequestRepositoryInterface
{
    /**
     * Find all requests not used from the user
     *
     * @param mixed $user
     * @return array
     */
    public function findAllNotUsedByUser($user);

    /**
     * Find latest request not used from the user
     *
     * @param mixed $user
     * @return ResetRequestInterface|null
     */
    public function findLatestNotUsedByUser($user);

    /**
     * Find request from token
     *
     * @param string $token
     * @return null|ResetRequestInterface
     */
    public function findOneByToken($token);
}