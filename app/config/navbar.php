<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',
 
    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'Home'  => [
            'text'  => 'Start',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Första sidan'
        ],
 
        // This is a menu item
        'Questions'  => [
            'text'  => 'Frågor',
            'url'   => $this->di->get('url')->create('question/list'),
            'title' => 'Översikt frågor',
        ],
 
        // This is a menu item
        'Tags' => [
            'text'  =>'Taggar',
            'url'   => $this->di->get('url')->create('tags/list'),
            'title' => 'Alla taggar',
        ],

        // This is a menu item
        'Users' => [
            'text'  =>'Användare',
            'url'   => $this->di->get('url')->create('users/list'),
            'title' => 'Alla användare'
        ],
        // This is a menu item
        'Question' => [
            'text'  =>'Ställ fråga',
            'url'   => $this->di->get('url')->create('question/create'),
            'title' => 'Ställ en fråga'
        ],
        // This is a menu item
        'About' => [
            'text'  =>'Om oss',
            'url'   => $this->di->get('url')->create('about'),
            'title' => 'Om oss'
        ],
    ], 


    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) {
        if ($this->di->get('request')->getCurrentUrl($url) == $this->di->get('url')->create($url)) {
            return true;
        }
    },



    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },



   /**
     * Callback to create the url, if needed, else comment out.
     *
     */
   /*
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
    */
];
