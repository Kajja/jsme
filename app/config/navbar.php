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
        'Texts' => [
            'text'  =>'Redovisningstexter',
            'url'   => $this->di->get('url')->create('redovisning/content/kmom1'),
            'title' => 'Redovisningstexter',
            'submenu' => [
                'items' => [

                    'item 1'  => [
                        'text'  => 'Kmom1',   
                        'url'   => $this->di->get('url')->create('redovisning/content/kmom1'),  
                        'title' => 'Kursmoment 1',
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
        'Execrises'  => [
            'text'  => 'Övningar',
            'url'   => $this->di->get('url')->create('ovningar/kmom1'),
            'title' => 'Övningar',
            'submenu' => [
                'items' => [
                    'item 1'  => [
                        'text' => 'Kmom1',
                        'url'   => $this->di->get('url')->create('ovningar/kmom1'),  
                        'title' => 'Kmom1',
                    ],
                    'item 2'  => [
                        'text' => 'Kmom2',
                        'url'   => $this->di->get('url')->create('ovningar/kmom2'),  
                        'title' => 'Flytta baddie',
                    ],
                    'item 3'  => [
                        'text' => 'Kmom3',
                        'url'   => $this->di->get('url')->create('ovningar/kmom3'),  
                        'title' => 'Transf',
                    ],
                    'item 4'  => [
                        'text' => 'Kmom4',
                        'url'   => $this->di->get('url')->create('ovningar/kmom4'),  
                        'title' => 'Flytta baddie',
                    ],
                ]
            ]
        ],
        'Templates' => [
            'text'  => 'Mallar',
            'url'   => $this->di->get('url')->create('u_change_size/'),
            'title' => 'Mallar',
            'submenu' => [
                'items' => [
                    'item 1'  => [
                        'text' => 'Mall',
                        'url'   => $this->di->get('url')->create('mall/'),  
                        'title' => 'Mall',
                    ],
                    'item 2'  => [
                        'text' => 'JS-fidle mall',
                        'url'   => 'http://jsfiddle.net/Kajja/7L1u8h02/',  
                        'title' => 'Mall',
                    ],
                ]
            ]
        ]  
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
