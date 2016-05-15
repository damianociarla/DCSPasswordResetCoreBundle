<?php

namespace DCS\PasswordReset\CoreBundle\Tests;

use DCS\PasswordReset\CoreBundle\DCSPasswordResetCoreBundle;
use DCS\PasswordReset\CoreBundle\DependencyInjection\Compiler\RepositoryCompilerPass;
use Doctrine\Common\Util\Debug;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DCSPasswordResetCoreBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildMethod()
    {
        $container = new ContainerBuilder();

        $DCSPasswordResetCoreBundle = new DCSPasswordResetCoreBundle();
        $DCSPasswordResetCoreBundle->build($container);

        $passes = $container->getCompiler()->getPassConfig()->getBeforeOptimizationPasses();

        $this->assertCount(1, $passes);
        $this->assertInstanceOf(RepositoryCompilerPass::class, $passes[0]);
    }
}