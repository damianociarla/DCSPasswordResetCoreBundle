<?php

namespace DCS\PasswordReset\CoreBundle\Tests;

use DCS\PasswordReset\CoreBundle\DCSPasswordResetCoreBundle;
use DCS\PasswordReset\CoreBundle\DependencyInjection\Compiler\RepositoryCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DCSPasswordResetCoreBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildAddsRepositoryCompilerPass()
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $containerBuilder->expects($this->atLeastOnce())
            ->method('addCompilerPass')
            ->with($this->isInstanceOf(RepositoryCompilerPass::class));

        $bundle = new DCSPasswordResetCoreBundle();
        $bundle->build($containerBuilder);
    }
}