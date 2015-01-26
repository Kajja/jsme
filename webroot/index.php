<?php 

// Bootstrapping (i.e. configurations, create framework object etc.)
require __DIR__.'/config_with_app.php'; 


$app->router->add('', function() use ($app) {

    $app->theme->setTitle('Allt om cyklar');
    $app->theme->setVariable('htmlClasses', 'first');
    $app->theme->setVariable('bodyClasses', 'first');

    // The most popular tags
    $app->dispatcher->forward([
        'controller' => 'tags',
        'action'     => 'popularTags',
    ]);

    // The latest questions
    $app->dispatcher->forward([
        'controller' => 'question',
        'action'     => 'latest',
        'params'     => [5]
    ]);

    // The most active users
    
});

//Configuring how generated URLs vill look like
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);

// Handling request
$app->router->handle();

// Render the response using theme engine.
$app->theme->render();