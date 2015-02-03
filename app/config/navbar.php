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
        'Templates'  => [
            'text'  => 'Mallar',
            'url'   => 'mallar',
            'title' => 'Översikt frågor',
        ],
 
        // This is a menu item
        'Exercises ' => [
            'text'  =>'Uppgifter',
            'url'   => 'redovisning/kmom1',
            'title' => 'Uppgifter',
            'submenu' => [
                'items' => [

                    'item 1'  => [
                        'text'  => 'Kmom1',   
                        'url'   => $this->di->get('url')->create('redovisning/content/kmom1'),  
                        'title' => 'Kursmoment 1'
                    ],

                    'item 2'  => [
                        'text'  => 'Kmom2',   
                        'url'   => $this->di->get('url')->create('redovisning/content/kmom2'), 
                        'title' => 'Kursmoment 2'
                    ],

                    'item 3'  => [
                        'text'  => 'Kmom3',   
                        'url'   => $this->di->get('url')->create('redovisning/content/kmom3'),  
                        'title' => 'Kursmoment 3'
                    ],
                    'item 4'  => [
                        'text'  => 'Kmom4',   
                        'url'   => $this->di->get('url')->create('redovisning/content/kmom4'), 
                        'title' => 'Kursmoment 4'
                    ],
                    'item 5'  => [
                        'text'  => 'Kmom5',   
                        'url'   => $this->di->get('url')->create('redovisning/content/kmom5'),  
                        'title' => 'Kursmoment 5'
                    ],
                    'item 6'  => [
                        'text'  => 'Kmom6',   
                        'url'   => $this->di->get('url')->create('redovisning/content/kmom6'), 
                        'title' => 'Kursmoment 6'
                    ],
                    'item 7'  => [
                        'text'  => 'Projekt',   
                        'url'   => $this->di->get('url')->create('redovisning/content/proj'),  
                        'title' => 'Projekt'
                    ]
                ]
            ]
        ],

        // This is a menu item
        'About' => [
            'text'  =>'Om',
            'url'   => $this->di->get('url')->create('about'),
            'title' => 'Om'
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
