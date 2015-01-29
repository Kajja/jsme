<?php 

// Bootstrapping (i.e. configurations, create framework object etc.)
require __DIR__.'/config_with_app.php'; 


$app->router->add('', function() use ($app) {

    $app->theme->setTitle('Allt om cyklar');
    $app->theme->setVariable('htmlClasses', 'first');
    $app->theme->setVariable('bodyClasses', 'first');

    // The latest questions
    $app->views->addString('<h3 class="">Senaste frågorna</h3><hr>', 'panel-col-1');
    $app->dispatcher->forward([
        'controller' => 'question',
        'action'     => 'latest',
        'params'     => [5, 'panel-col-1']
    ]);

    // The most popular tags
    $app->views->addString('<h3 class="">Populäraste taggarna</h3><hr>', 'panel-col-2');
    $app->dispatcher->forward([
        'controller' => 'tags',
        'action'     => 'popularTags',
        'params'     => ['panel-col-2']
    ]);

    // The most active users
    $app->views->addString('<h3 class="">Aktiva användare</h3><hr>', 'panel-col-2');
    $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'activeUsers',
        'params'     => [5, 'panel-col-2']
    ]);
});

// About-page
$app->router->add('about', function() use ($app) {

    $app->theme->setTitle('Om oss');
    $app->views->addString('<h3>Om</h3><hr>');
    $app->views->add('base/page', [
        'title' => '',
        'content' => $app->textFilter->doFilter($app->fileContent->get('about.md'), 'shortcode, markdown')
    ]);
});

// Configuring how generated URLs vill look like
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);

// Handling request
$app->router->handle();

// Render the response using theme engine.
$app->theme->render();