<?php

namespace Anax\Di;

use \Anax\DI\CDIFactoryDefault;

class CDIFactory extends CDIFactoryDefault 
{
    public function __construct() 
    {
        parent::__construct();

        $this->setShared('theme', function () {
            $themeEngine = new \Anax\ThemeEngine\CThemeBasic();
            $themeEngine->setDI($this);
            $themeEngine->configure(ANAX_APP_PATH . 'config/theme-kajja.php');
            return $themeEngine;
        });

        // Controller for Comments
        $this->set('RedovisningController', function() {
            $controller = new \Kajja\Redovisning\RedovisningController();
            $controller->setDI($this);
            return $controller;
        });
    }
}