<?php

namespace DCS\PasswordReset\CoreBundle\Model;

use DCS\User\CoreBundle\Model\UserInterface;

abstract class ResetRequest implements ResetRequestInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     */
    protected $usedAt;

    /**
     * ResetRequest constructor.
     *
     * @param UserInterface|null $user
     * @param string|null $token
     * @param \DateTime|null $createdAt
     */
    public function __construct(UserInterface $user = null, $token = null, \DateTime $createdAt = null)
    {
        $this->user = $user;
        $this->token = $token;
        $this->createdAt = $createdAt;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @inheritdoc
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @inheritdoc
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @inheritdoc
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getUsedAt()
    {
        return $this->usedAt;
    }

    /**
     * @inheritdoc
     */
    public function setUsedAt(\DateTime $usedAt = null)
    {
        $this->usedAt = $usedAt;
        return $this;
    }
}