<?php

namespace DCS\PasswordReset\CoreBundle\Tests\Manager;

use DCS\PasswordReset\CoreBundle\DCSPasswordResetCoreEvents;
use DCS\PasswordReset\CoreBundle\Manager\Save;
use DCS\PasswordReset\CoreBundle\Tests\Helper\ResetRequest;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SaveTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $dispatcher;
    /** @var Save */
    protected $save;

    public function setUp()
    {
        $this->dispatcher = $this->getMock(EventDispatcherInterface::class);
        $this->save = new Save($this->dispatcher);
    }

    public function testSave()
    {
        $this->dispatcher->expects($this->exactly(3))->method('dispatch');
        $this->dispatcher->expects($this->at(0))->method('dispatch')->with(DCSPasswordResetCoreEvents::BEFORE_SAVE_RESET_REQUEST);
        $this->dispatcher->expects($this->at(1))->method('dispatch')->with(DCSPasswordResetCoreEvents::SAVE_RESET_REQUEST);
        $this->dispatcher->expects($this->at(2))->method('dispatch')->with(DCSPasswordResetCoreEvents::AFTER_SAVE_RESET_REQUEST);

        call_user_func($this->save, new ResetRequest());
    }
}