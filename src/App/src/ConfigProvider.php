<?php

namespace App;
use Zend\Db\Adapter\AdapterAbstractServiceFactory;
/**
 * The configuration provider for the App module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            'invokables' => [
                Action\PingAction::class => Action\PingAction::class,
            ],
            'factories'  => [
                Action\HomePageAction::class => Action\HomePageFactory::class,
                Action\GetContractsAction::class => Action\GetContractsFactory::class,
                Action\CreateContractAction::class => Action\CreateContractFactory::class,
                Action\UpdateContractAction::class => Action\UpdateContractFactory::class,
                Action\DeleteContractAction::class => Action\DeleteContractFactory::class,
                Action\GetContractAction::class => Action\GetContractFactory::class,
                Model\ContractModel::class => Model\ContractModelFactory::class,
                'Application\Db\WriteAdapter' => AdapterAbstractServiceFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     *
     * @return array
     */
    public function getTemplates()
    {
        return [
            'paths' => [
                'app'    => [__DIR__ . '/../templates/app'],
                'error'  => [__DIR__ . '/../templates/error'],
                'layout' => [__DIR__ . '/../templates/layout'],
            ],
        ];
    }
}
