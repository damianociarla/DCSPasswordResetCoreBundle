<?php

namespace DCS\PasswordReset\CoreBundle\Model;

use DCS\User\CoreBundle\Model\UserInterface;

interface ResetRequestInterface
{
    /**
     * Get id
     *
     * @return mixed
     */
    public function getId();

    /**
     * Sets id
     *
     * @param mixed $id
     * @return ResetRequestInterface
     */
    public function setId($id);

    /**
     * Get user
     *
     * @return UserInterface
     */
    public function getUser();

    /**
     * Sets user
     *
     * @param UserInterface $user
     * @return ResetRequestInterface
     */
    public function setUser(UserInterface $user);

    /**
     * Get token
     *
     * @return string
     */
    public function getToken();

    /**
     * Sets token
     *
     * @param string $token
     * @return ResetRequestInterface
     */
    public function setToken($token);

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Sets createdAt
     *
     * @param \DateTime $createdAt
     * @return ResetRequestInterface
     */
    public function setCreatedAt(\DateTime $createdAt);

    /**
     * Get usedAt
     *
     * @return \DateTime
     */
    public function getUsedAt();

    /**
     * Sets usedAt
     *
     * @param \DateTime $usedAt
     * @return ResetRequestInterface
     */
    public function setUsedAt(\DateTime $usedAt = null);
}