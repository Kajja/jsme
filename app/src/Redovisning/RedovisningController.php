<?php

namespace Kajja\Redovisning;

use \Anax\DI\IInjectionaware,
    \Anax\DI\TInjectable;


class RedovisningController implements IInjectionaware
{
    use TInjectable;
    
    public function contentAction($uppg)
    {

        $this->views->add('base/page', [
            'title' => '',
            'content' => $this->textFilter->doFilter($this->fileContent->get($uppg . '.md'), 'shortcode, markdown')
        ]);
    }
}