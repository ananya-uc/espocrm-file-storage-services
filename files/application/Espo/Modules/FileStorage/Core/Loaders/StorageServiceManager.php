<?php

namespace Espo\Modules\FileStorage\Core\Loaders;

class StorageServiceManager
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;

        // Load if not already loaded
        $this->container->get('fileStorageComposerVendorDir');
    }

    /**
     * Loads the service into the dependency container.
     *
     * @author theBuzzyCoder
     *
     * @since 0.0.1
     *
     * @version 0.0.1
     *
     * @return Espo\Modules\FileStorage\Providers\Manager
     *
     * @throws BadRequest
     */
    public function load()
    {
        $className = '\\Espo\\Custom\\Providers\\Manager';
        if (!class_exists($className)) {
            $className = '\\Espo\\Modules\\FileStorage\\Providers\\Manager';
            if (!class_exists($className)) {
                throw new Error("{$className}: Not Found");
            }
        }

        return new $className($this->container);
    }
}
