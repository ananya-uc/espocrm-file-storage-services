<?php

class AfterInstall
{
    public function run($container)
    {
        if (!file_exists('application/Espo/Modules/FileStorage/Core/Loaders/vendor')) {
            define('EXTRACT_DIRECTORY', '/tmp/composer');
            mkdir(EXTRACT_DIRECTORY);

            $GLOBALS['log']->warning('Running shell_exec');
            if (!file_exists('composer.phar')) {
                shell_exec('wget https://getcomposer.org/download/1.9.3/composer.phar');
            }

            if (!file_exists(EXTRACT_DIRECTORY.'/vendor')) {
                $composerPhar = new Phar('composer.phar');
                $composerPhar->extractTo(EXTRACT_DIRECTORY);
            }

            require_once EXTRACT_DIRECTORY.'/vendor/autoload.php';

            $app = new \Composer\Console\Application();
            $application->setAutoExit(false);
            $input = new \Symfony\Component\Console\Input\ArrayInput(array('command' => 'install', '--working-dir' => 'application/Espo/Modules/FileStorage/Core/Loaders', '--no-dev' => true));
            $app->run($input);

            shell_exec('rm -rf '.EXTRACT_DIRECTORY);
            shell_exec('rm composer.phar');
            $GLOBALS['log']->warning('Done Installing composer packages');
        }
        $container->get('dataManager')->clearCache();
    }
}
