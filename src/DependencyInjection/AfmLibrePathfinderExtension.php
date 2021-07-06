<?php

namespace AfmLibre\Pathfinder\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @see http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
final class AfmLibrePathfinderExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $containerBuilder): void
    {
        $phpFileLoader = new PhpFileLoader($containerBuilder, new FileLocator(__DIR__.'/../../config'));
        $phpFileLoader->load('services.php');
    }

    /**
     * Allow an extension to prepend the extension configurations.
     */
    public function prepend(ContainerBuilder $containerBuilder): void
    {
        // get all bundles
        $bundles = $containerBuilder->getParameter('kernel.bundles');

        if (isset($bundles['DoctrineBundle'])) {
            foreach (array_keys($containerBuilder->getExtensions()) as $name) {
                switch ($name) {
                    case 'doctrine':
                        $this->loadConfig($containerBuilder, 'doctrine');

                        break;
                    case 'twig':
                        $this->loadConfig($containerBuilder, 'twig');

                        break;
                }
            }
        }
    }

    protected function loadConfig(ContainerBuilder $containerBuilder, string $name): void
    {
        //https://symfony.com/doc/current/bundles/prepend_extension.html
        //$containerBuilder->prependExtensionConfig($name, $config);
        $configs = $this->loadConfigFile($containerBuilder);

        $configs->load($name.'.php');
    }

    protected function loadConfigFile(ContainerBuilder $containerBuilder): PhpFileLoader
    {
        return new PhpFileLoader(
            $containerBuilder,
            new FileLocator(__DIR__.'/../../config/packages/')
        );
    }
}
