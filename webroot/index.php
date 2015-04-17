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

$app->router->add('ovningar/kmom3', function() use ($app) {

    $app->views->add('assignments/base', [
        'title' => 'Kmom3: Uppgifter',
        'exercises' => [
            'Övningar 1-8' => $app->url->create('u3_ex/'),
            'Egen jQuery-plugin' => $app->url->create('u3_plugin/')
        ]
    ]);
});

$app->router->add('u3_plugin', function() use ($app) {

    $app->theme->addJavaScript('../webroot/js/jquery.ezyread.js');
    $app->theme->addJavaScript('../webroot/js/pluginpres.js');

    $app->views->add('base/page', [
        'title' => '',
        'content' => $app->textFilter->doFilter($app->fileContent->get('plugin.md'), 'shortcode, markdown')
    ]);
});

$app->router->add('ovningar/kmom4', function() use ($app) {

    $app->views->add('assignments/base', [
        'title' => 'Kmom4: Uppgifter',
        'exercises' => [
            'Marvin' => $app->url->create('u4_marvin/'),
            'Login' => $app->url->create('u4_login/'),
            'Kundvagn' => $app->url->create('u4_cart/'),
            'Betalning' => $app->url->create('u4_checkout/'),
            'Webbshop' => $app->url->create('u4_shop/')
        ]
    ]);
});

$app->router->add('ovningar/kmom5', function() use ($app) {

    $app->views->add('assignments/base', [
        'title' => 'Kmom5: Uppgifter',
        'exercises' => [
            'Spelet Ketchup' => $app->url->create('u5_game/')
        ]
    ]);
});

$app->router->add('ovningar/kmom6', function() use ($app) {

    $app->views->add('assignments/base', [
        'title' => 'Kmom6: Uppgifter',
        'exercises' => [
            'Echo och broadcast' => $app->url->create('echo-broadcast/'),
            'Chatt' => $app->url->create('chat/'),
            'Chatt-server (använder samma websocket-modul som i övningen)' => $app->url->create('u6_chat/chatserver.js')
        ]
    ]);
});

$app->router->add('echo-broadcast', function() use ($app) {

    $app->theme->addStylesheet('css/stylephp/style.php?file=../../u6_websocket/echo-broadcast');
    $app->theme->addJavaScript('js/less.min.js');
    $app->theme->addJavaScript('u6_websocket/main.js');
    $app->views->add('websocket/echo-broadcast', [
        'path' => 'u6_websocket'
    ]);
});

$app->router->add('chat', function() use ($app) {

    $app->theme->addStylesheet('css/stylephp/style.php?file=../../u6_chat/chat');
    $app->theme->addJavaScript('js/less.min.js');
    $app->theme->addJavaScript('u6_chat/main.js');
    $app->views->add('websocket/chat', [
        'path' => 'u6_chat'
    ]);
});


// Configuring how generated URLs vill look like
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);

// Handling request
$app->router->handle();

// Render the response using theme engine.
$app->theme->render();