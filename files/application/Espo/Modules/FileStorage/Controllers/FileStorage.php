<?php

namespace Espo\Modules\FileStorage\Controllers;

use Espo\Core\Controllers\Base;
use Slim\Http\Request;
use Espo\Core\Exceptions\BadRequest;

class FileStorage extends Base
{
    const MANAGER = 'StorageServiceManager';

    /*
     * Test Connection After Settings are set
     *
     * @author theBuzzyCoder
     *
     * @since 0.0.1
     *
     * @version 0.0.1
     *
     * @param array             $params
     * @param stdClass          $data
     * @param Slim\Http\Request $request
     *
     * @return bool
     *
     * @throws BadRequest
     */
    public function getActionTestConnection(array $params, $data, Request $request): bool
    {
        if (!$request->isGet()) {
            throw new BadRequest();
        }

        return $this->getContainer()->get(self::MANAGER)->testConnection();
    }
}
