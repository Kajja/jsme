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

/*
        // Creating a controller service for comments
        $this->set('CommentController', function() {
            $controller = new \Phpmvc\Comment\CommentController();
            $controller->setDI($this);
            return $controller;
        });

        // Creating a model service for comments
        $this->set('comments', function(){
//            $comments = new \Phpmvc\Comment\CommentsInSession();
            $comments = new \Phpmvc\Comment\Comment();
            $comments->setDI($this);
            return $comments;
        });
*/
        // Database service (from mos vendor)
        $this->set('db', function() {
            $db = new \Mos\Database\CDatabaseBasic();
            $db->setOptions(require ANAX_INSTALL_PATH . 'cdatabase_config_sqlite.php');
            $db->connect();
            return $db;
        });

        // Form service (from mos vendor)
        $this->set('form', '\Mos\HTMLForm\CForm');

        // Controller for forms
        $this->set('FormController', function() {
            $controller = new \Anax\HTMLForm\FormController();
            $controller->setDI($this);
            return $controller;
        });

        // Controller for User-actions
        $this->set('UsersController', function() {
            $controller = new \Anax\Users\UsersController();
            $controller->setDI($this);
            return $controller;
        });

        // Controller for Questions
        $this->set('QuestionController', function() {
            $controller = new \Kajja\Questions\QuestionController();
            $controller->setDI($this);
            return $controller;
        });

        // Controller for Tags
        $this->set('TagsController', function() {
            $controller = new \Kajja\Tags\TagsController();
            $controller->setDI($this);
            return $controller;
        });

        // Controller for Answers
        $this->set('AnswersController', function() {
            $controller = new \Kajja\Answers\AnswersController();
            $controller->setDI($this);
            return $controller;
        });

        // Controller for Comments
        $this->set('CommentsController', function() {
            $controller = new \Kajja\Comments\CommentsController();
            $controller->setDI($this);
            return $controller;
        });
/*
        // Request-recorder service
        $this->set('recorder', function() {
            $dbh = new \Kajja\Recorder\RequestDatabase();
            $dbh->setOptions([
                'dsn'           => 'sqlite:' . ANAX_APP_PATH . '.htphpmvc.sqlite',
                'fetch_mode'    => \PDO::FETCH_ASSOC
                ]);
            $dbh->connect();
            $formatter = new \Kajja\Recorder\HTMLFormatter();
            $recorder = new \Kajja\Recorder\RequestRecordAnax($dbh, $formatter, $this);
            return $recorder;
        });
        */
    }
}