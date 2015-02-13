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

$app->router->add('ovningar/kmom1', function() use ($app) {

    $app->views->add('assignments/base', [
        'title' => 'Kmom1: Uppgifter',
        'exercises' => [
            'Ändra storlek' => $app->url->create('u_change_size/'),
            'Flytta baddie' => $app->url->create('u_baddi_move/'),
            'Transforms och transistions' => $app->url->create('u_baddi_transitions/'),
            'Egen baddie' => $app->url->create('u_rutan_move/')
        ]
    ]);
});

$app->router->add('ovningar/kmom2', function() use ($app) {

    $app->views->add('assignments/base', [
        'title' => 'Kmom2: Uppgifter',
        'exercises' => [
            'Literaler' => $app->url->create('u2_literals/'),
            'Nummer' => $app->url->create('u2_numbers/'),
            'Strängar' => $app->url->create('u2_strings/'),
            'Tärning' => $app->url->create('u2_dice/'),
            'Boll' => $app->url->create('u2_ball/'),
            'Boulder Dash' => $app->url->create('u2_boulder_dash/'),
            'Datum' => $app->url->create('u2_date/'),
            'Reguljära uttryck' => $app->url->create('u2_regexp/'),
            'Felhantering' => $app->url->create('u2_errors/'),
            'Roulette' => $app->url->create('u2_roulette/'),
            'Eget JS-lib' => $app->url->create('mall/kajja.js'),
        ]
    ]);
});

// Configuring how generated URLs vill look like
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);

// Handling request
$app->router->handle();

// Render the response using theme engine.
$app->theme->render();