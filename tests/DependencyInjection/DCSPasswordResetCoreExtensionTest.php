<?php

namespace DCS\PasswordReset\CoreBundle\Tests\DependencyInjection;

use DCS\PasswordReset\CoreBundle\DependencyInjection\DCSPasswordResetCoreExtension;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DCSPasswordResetCoreExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getConfigurationForInvalidConfigurationException
     */
    public function testInvalidConfigurationException($configuration, $regex)
    {
        $container = new ContainerBuilder();

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessageRegExp($regex);

        $mock = $this->getMockBuilder(DCSPasswordResetCoreExtension::class)->setMethods(['processConfiguration'])->getMock();
        $mock->load(['dcs_password_reset_core' => $configuration], $container);
    }

    public function getConfigurationForInvalidConfigurationException()
    {
        $configuration[] = [
            [
                'model_class' => 'ACME',
                'repository_service' => null,
            ],
            '/.*"dcs_password_reset_core.repository_service".*/'
        ];

        $configuration[] = [
            [
                'model_class' => null,
                'repository_service' => 'ACME',
            ],
            '/.*"dcs_password_reset_core.model_class".*/'
        ];

        return $configuration;
    }

    public function testLoad()
    {
        $container = new ContainerBuilder();

        $mock = $this->getMockBuilder(DCSPasswordResetCoreExtension::class)->setMethods(['processConfiguration'])->getMock();
        $mock->load([
            'dcs_password_reset_core' => [
                'model_class' => 'ACME_MODEL',
                'repository_service' => 'ACME_REPOSITORY',
            ]
        ], $container);

        return $container;
    }

    /**
     * @depends testLoad
     */
    public function testContainsParameters(ContainerBuilder $container)
    {
        $this->assertTrue($container->hasParameter('dcs_password_reset.token_ttl'));
        $this->assertTrue($container->hasParameter('dcs_password_reset.waiting_time_new_request'));
        $this->assertTrue($container->hasParameter('dcs_password_reset.model_class'));
        $this->assertTrue($container->hasParameter('dcs_password_reset.repository_service'));

        $this->assertEquals(86400, $container->getParameter('dcs_password_reset.token_ttl'));
        $this->assertEquals(86400, $container->getParameter('dcs_password_reset.waiting_time_new_request'));
        $this->assertEquals('ACME_MODEL', $container->getParameter('dcs_password_reset.model_class'));
        $this->assertEquals('ACME_REPOSITORY', $container->getParameter('dcs_password_reset.repository_service'));
    }

    /**
     * @depends testLoad
     */
    public function testContainsAliases(ContainerBuilder $container)
    {
        $this->assertTrue($container->hasAlias('dcs_password_reset.service.date_time_generator'));
        $this->assertTrue($container->hasAlias('dcs_password_reset.service.token_generator'));

        $this->assertEquals('dcs_password_reset.service.date_time_generator.generic', $container->getAlias('dcs_password_reset.service.date_time_generator'));
        $this->assertEquals('dcs_password_reset.service.token_generator.random', $container->getAlias('dcs_password_reset.service.token_generator'));
    }

    /**
     * @depends testLoad
     */
    public function testContainsResources(ContainerBuilder $container)
    {
        $this->assertCount(4, $resources = $container->getResources());

        /** @var FileResource $resource */
        foreach ($resources as $resource) {
            $this->assertContains(pathinfo($resource->getResource(), PATHINFO_BASENAME), ['service.xml','checker.xml','handler.xml','manager.xml']);
        }
    }
}