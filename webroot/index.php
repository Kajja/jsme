<?php 

// Bootstrapping (i.e. configurations, create framework object etc.)
require __DIR__.'/config_with_app.php'; 

$app->session();
$app->theme->setTitle('JS-kurs');

$app->router->add('', function() use ($app) {
 
    $app->theme->setVariable('htmlClasses', 'first');
    $app->theme->setVariable('bodyClasses', 'first');

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