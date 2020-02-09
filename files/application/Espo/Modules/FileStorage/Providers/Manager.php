<?php

namespace Espo\Modules\FileStorage\Providers;

use Espo\Core\Utils\Config;
use Espo\Core\Container;
use Espo\Core\ORM\EntityManager;
use Espo\Core\Exceptions\Error;
use Espo\Modules\FileStorage\Core\Utils\Storage\FlySystem;

final class Manager
{
    private $container = null;

    private $entity = null;

    private $adapterService = null;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $serviceName = $this->getConfig()->get('defaultStorageService');
        if (!$serviceName) {
            throw new Error('defaultStorageService not provided in config.php');
        }

        $this->adapterService = $serviceName;
        $this->loadIntegration();
    }

    private function getConfig(): Config
    {
        return $this->getContainer()->get('config');
    }

    private function getContainer(): Container
    {
        return $this->container;
    }

    private function loadIntegration(): void
    {
        if (is_null($this->entity)) {
            $this->entity = $this->getEntityManager()->getEntity('Integration', $this->adapterService);
        }
    }

    private function getEntityManager(): EntityManager
    {
        return $this->container->get('entityManager');
    }

    public function isEnabled(): bool
    {
        return $this->entity->get('enabled');
    }

    public function testConnection(): bool
    {
        $adapterClassName = FlySystem::STORAGE_CLIENT_MAPS[$this->entity->id];
        $adapter = new $adapterClassName($this->entity);

        return $adapter->testConnection();
    }
}
