<?php

class AfterUninstall
{
    public function run($container)
    {
        $container->get('dataManager')->clearCache();
    }
}
