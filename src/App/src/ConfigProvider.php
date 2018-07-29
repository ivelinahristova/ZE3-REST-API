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
            ],
            'factories'  => [
                Action\Contracts\GetContractsAction::class => Action\Contracts\GetContractsFactory::class,
                Action\Contracts\CreateContractAction::class => Action\Contracts\CreateContractFactory::class,
                Action\Contracts\UpdateContractAction::class => Action\Contracts\UpdateContractFactory::class,
                Action\Contracts\DeleteContractAction::class => Action\Contracts\DeleteContractFactory::class,
                Action\Contracts\GetContractAction::class => Action\Contracts\GetContractFactory::class,
                Action\Contracts\GetPropertiesAction::class => Action\Contracts\GetPropertiesFactory::class,
                Model\ContractModel::class => Model\ContractModelFactory::class,
                Model\PropertyModel::class => Model\PropertyModelFactory::class,
                Action\Properties\GetPropertiesAction::class => Action\Properties\GetPropertiesFactory::class,
                Action\Properties\CreatePropertyAction::class => Action\Properties\CreatePropertyFactory::class,
                Action\Properties\GetPropertyAction::class => Action\Properties\GetPropertyFactory::class,
                Action\Properties\UpdatePropertyAction::class => Action\Properties\UpdatePropertyFactory::class,
                Action\Properties\DeletePropertyAction::class => Action\Properties\DeletePropertyFactory::class,

                Action\ContractProperties\CreateContractPropertiesAction::class => Action\ContractProperties\CreateContractPropertiesFactory::class,
                Action\ContractProperties\DeleteContractPropertiesAction::class => Action\ContractProperties\DeleteContractPropertiesFactory::class,

                Model\LandlordModel::class => Model\LandlordModelFactory::class,
                Action\Landlords\CreateLandlordAction::class => Action\Landlords\CreateLandlordFactory::class,
                Action\Landlords\GetLandlordAction::class => Action\Landlords\GetLandlordFactory::class,
                Action\Landlords\GetLandlordsAction::class => Action\Landlords\GetLandlordsFactory::class,
                Action\Landlords\DeleteLandlordAction::class => Action\Landlords\DeleteLandlordFactory::class,
                Action\Landlords\UpdateLandlordAction::class => Action\Landlords\UpdateLandlordFactory::class,

                Action\PropertyLandlords\CreatePropertyLandlordsAction::class => Action\PropertyLandlords\CreatePropertyLandlordsFactory::class,
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
