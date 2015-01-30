<?php

namespace Anax\Users;

use \Anax\DI\IInjectionaware,
    \Anax\DI\TInjectable,
    \Anax\Users\User as User;


/**
 * Controller for users
 *
 */
 class UsersController implements IInjectionaware
 {
    use TInjectable;

    private $logInUser;

    /**
     * Initialize the controller
     *
     *
     */
    public function initialize()
    {
        // Create User-model object
        $this->userModel = new User();
        $this->userModel->setDI($this->di);

        // Set class on body-element for styling
        $this->theme->setVariable('bodyClasses', 'page-container');
    }


    /**
     * Login user
     *
     */
    public function loginAction() {

        // Check if logged in                          TODO: Flytta ut all login/logout funk
        if ($this->session->has('logged_in_userid')) {

            $this->views->add('base/page', [
                'title'     => '',
                'content'   => 'Du är redan inloggad']);

            return false;
        }

        $form = $this->loginForm();

        //Check if the email and password has a match
        $form->check(
            // If the check is successful, 
            function($form) {
                $this->session->set('logged_in_userid', $this->logInUser->id); //TODO: Inte så snyggt
                $this->session->set('logged_in_usermail', $this->logInUser->email); //TODO: Inte så snyggt
                $this->session->set('logged_in_username', $this->logInUser->name); //TODO: Inte så snyggt

                // Redirect to the user profile
                $url = $this->url->create('users/profile/' . $this->logInUser->id);
                $this->response->redirect($url);
            },
            // If the check fails
            function($form) {
                $form->addOutput('Fel email eller lösenord');
                $url = $this->url->create('users/login');
                $this->response->redirect($url);
            }
        );


        $this->theme->setTitle('Login');
        $this->views->addString('<h3>Login</h3><hr>');

        // Add the created form to a view
        $this->views->add('base/page', [
            'title'     => '',
            'content'   => $form->getHTML()
        ]);
    }

    /**
     *
     *
     */

    public function loginForm() {

        // Turn on session, Form-service use it
        $this->session();

        $form = $this->form->create([], [
            'mail' => [
                'type' => 'text',
                'label' => 'Email:',
                'required' => true,
                'validation' => ['not_empty'],
                'value' => isset($values['mail']) ? $values['mail'] : ''          
            ], 
            'password' => [
                'type' => 'password',
                'label' => 'Lösenord:',
                'required' => true,
                'validation' => ['not_empty'],
                'value' => isset($values['password']) ? $values['password'] : ''          
            ],
            'login' => [
                'type' => 'submit',
                'callback' => function($form) {

                    $matchedUser = $this->userModel->query()
                         ->where('email = ?')
                         ->execute([$form->Value('mail')]);

                    if (empty($matchedUser)) {
                        return false;
                    } else {
                        $this->logInUser = $matchedUser[0];
                        return password_verify($form->Value('password'), $matchedUser[0]->getProperties()['passw']);
                    }
                }
            ]
        ]);
        return $form;
    }

    /**
     * Logout action
     *
     */
    public function logoutAction() {

        // Clear the session variables
        $this->session->set('logged_in_userid', null); // null räknas som unset
        $this->session->set('logged_in_usermail', null); // null räknas som unset
        $this->session->set('logged_in_username', null); // null räknas som unset

        // Reload current page
//        $this->response->redirect($this->request->getPost('redirect'));
        $this->response->redirect($this->url->create());
    }

    /**
     * Retrieve all stored users and show in view
     *
     */
    public function listAction() {

        // Use the model to get all stored users
        $allUsers = $this->userModel->findAll('name ASC');


        $this->theme->setTitle('Användare');
        $this->views->addString('<h3>Användare</h3><hr>');
        foreach ($allUsers as $user) {
            $values = $user->getProperties();
            $this->views->add('user/abstract', [
                'user'   => $this->userHTMLAction($values)
            ]);

        }
    }


    /**
     *  Retrieve the user with the specified id and display in view
     *
     */
    public function idAction($id = null, $display = true)
    {
        if (!isset($id)) {
            die('Missing id');
        }
        // Use the model to retrieve the specified user
        $user = $this->userModel->find($id);

        if ($display) {
            // Display user information in view
            $this->views->add('user/user', [
                'userValues' => $user->getProperties()
            ]);
        }

        return $user;
    }

    /**
     *  Retrieve the user with the specified id and display in view
     *
     */
    public function profileAction($id = null)
    {
        if (!isset($id)) {
            die('Missing id - profileAction');
        }
        // Use the model to retrieve the specified user
        $user = $this->userModel->find($id);
        $values = $user->getProperties();

        // Display user information in view
        $this->theme->setTitle('Användare');
        $this->views->add('user/profile', [
            'userName' => $values['name'],
            'email' => $values['email'],
            'created' => date('Y-m-d', strtotime($values['created'])),
            'id' => $user->id
        ]);

        // List questions that the user has answered
        $this->views->addString('<h3>Besvarade frågor</h3><hr>', 'panel-col-1');
        $this->dispatcher->forward([
            'controller' => 'question',
            'action'     => 'answeredBy',
            'params'     => [$id, 'panel-col-1']
        ]);

        // List the users questions
        $this->views->addString('<h3>Ställda frågor</h3><hr>', 'panel-col-2');
        $this->dispatcher->forward([
            'controller' => 'question',
            'action'     => 'userQuestions',
            'params'     => [$id, 'panel-col-2']
        ]);


    }

    /**
     * Creates a HTML-representation of a users info
     *
     */
    public function userHTMLAction($values, $imgSize = '30')  // TODO: Kanske inte så bra idé, fast bra att samla på ett ställe
    {
        if (!is_array($values)) {
            // Use the model to retrieve the specified user
           $values = $this->userModel->find($values)->getProperties();
        }

        $html = '<a href="' . $this->url->create('users/profile/' . $values['id']) . '">' . 
                    '<div class="user-info">' .
                        "<img src='https://www.gravatar.com/avatar/" .
                            md5(strtolower(trim($values['email']))) . "?s={$imgSize}&d=identicon' alt='Profilbild'/>" .
                        '<span>' . 
                            $values['name'] . 
                        '</span>' .
                    '</div>' .
                '</a>';

        return $html;
    }


    /**
     * Add and store a new user
     *
     * @param acronym to be used for the new user
     */
    public function addAction()
    {

        // Create HTML form for user creation
        $form = $this->userForm();

        // Adds button and callback
        $form->create([], [
            'password' => [
                'type' => 'password',
                'label' => 'Lösenord:',
                'required' => true,
                'validation' => ['not_empty'],
                'value' => ''          
            ],
            'Skapa' => [
                'type' => 'submit',
                'callback' => function($form) {

                    // Check that the email has not already been registred
                    $matchedUsers = $this->userModel->query()
                        ->where('email = ?')
                        ->execute([$form->Value('mail')]);

                    if(!empty($matchedUsers)) {
                        return false;
//                        return $values['id'] === $matchedUsers[0]->id;
                    }
                    return true;
                }
            ]
        ]);

        // Form check: 
        //      not submitted => ($form->check = null)
        //      submitted + valid -> run button callback
        //          callback returns true  -> run first check callback   ($form->check = true)
        //          callback returns false -> run second check callback  ($form->check = false)
        //      submitted + not valid -> run second check callback       ($from->check = false)
        $form->check(
            // If the check is successful, save the new user
            function($form) {

                $now = date(DATE_RFC2822);
             
                $this->userModel->save([
                    'name'      => $form->Value('name'),
                    'email'     => $form->Value('mail'),
                    'passw'     => password_hash($form->Value('password'), PASSWORD_DEFAULT),
                    'created'   => $now,
     //               'active'    => $now
                ]);

                // Redirect to display the created user
                $url = $this->url->create('users/profile/' . $this->userModel->id);
                $this->response->redirect($url);
            },
            // If the check fails
            function($form) {
                $form->addOutput('Det fanns redan en användare registrerad med den emailen');
                $url = $this->url->create('users/add');
                $this->response->redirect($url);
            }
        );


        $this->theme->setTitle('Skapa användare');
        $this->views->addString('<h3>Skapa användare</h3><hr>');

        // Add the created form to a view
        $this->views->add('base/page', [
            'title'     => '',
            'content'   => $form->getHTML()
        ]);
    }

    /**
     * Update a user
     *
     * @param id of the user to update
     */
    public function updateAction($id = null) {

        $this->theme->setTitle('Uppdatera profil');
        if (!isset($id)) {
            die('Missing id');
        }

        // Check the right user is logged in
        if ($this->session->get('logged_in_userid') !== $id) {

            $this->views->add('base/page', [
                'title'     => '',
                'content'   => 'Du är inte inloggad som den användare du vill uppdatera.']);

            return false;
        }

        // The user-object, i.e. userModel is populated with data
        // if $id matched a row in the database.
        $this->userModel->find($id);

        // Create HTML-form and populate it with values from database
        $form = $this->userForm($this->userModel->getProperties());

        // Adds button and callback
        $form->create([], [
            'password' => [
                'type' => 'password',
                'label' => 'Lösenord:',
                'required' => false,
                'value' => ''          
            ],
            'Uppdatera' => [
                'type' => 'submit',
                'callback' => function($form) use ($id){

                    // Check that the email has not already been registred
                    $matchedUsers = $this->userModel->query()
                        ->where('email = ?')
                        ->execute([$form->Value('mail')]);

                    if(!empty($matchedUsers)) {
                        return $id === $matchedUsers[0]->id;
                    }
                    return true;
                }
            ]
        ]);

        $form->addOutput('Lämnar du lösenordsrutan tom så uppdateras inte lösenordet');

        $form->check(
            // If the check is successful, update user
            function($form) use ($id) {

                $now = date(DATE_RFC2822);
             
                $newValues = [
                    'name'      => $form->Value('name'),
                    'email'     => $form->Value('mail'),
                    'changed'   => $now
                ];

                // Update password if the password field is not empty
                if ($form->Value('password') !== '') {
                    $newValues['passw'] = password_hash($form->Value('password'), PASSWORD_DEFAULT);
                }

                // Save the new information
                $this->userModel->save($newValues);

                // Update the logged in information
                $this->session->set('logged_in_usermail', $form->Value('mail'));
                $this->session->set('logged_in_username', $form->Value('name'));

                // Redirect to display the updated user
                $url = $this->url->create('users/profile/' . $this->userModel->id);
                $this->response->redirect($url);
            },
            // If the check fails
            function($form) use ($id) {
                $form->addOutput('Det fanns redan en användare registrerad med den emailen');
                $url = $this->url->create('users/update/' . $id);
                $this->response->redirect($url);
            }
        );

        // Add the created form to a view
        $this->views->addString('<h3>Uppdatera profil</h3><hr>');
        $this->views->add('base/page', [
            'title'     => '',
            'content' => $form->getHTML()
        ]);
    }

    /**
     * Method that creates a HTML-form for registering
     * and updating Users
     *
     * @param array values to populate field with
     *
     * @return a form object
     */
    public function userForm($values = [])
    {
        // Turn on session, Form-service use it
        $this->session();

        $form = $this->form->create([], [
            'name' => [
                'type' => 'text',
                'label' => 'Namn:',
                'required' => true,
                'validation' => ['not_empty'],
                'value' => isset($values['name']) ? $values['name'] : ''
            ],
            'mail' => [
                'type' => 'text',
                'label' => 'Email:',
                'required' => true,
                'validation' => ['not_empty'],
                'value' => isset($values['email']) ? $values['email'] : ''          
            ],
        ]);
        return $form;
    }


    /**
     * Delete a user
     *
     * @param user id
     */
    public function deleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }
     
        $res = $this->userModel->delete($id);

        // Redirect to display users
        $url = $this->url->create('users/list');
        $this->response->redirect($url);
    }


    /**
     * Soft-delete, the user is not removed from database
     * only marked as deleted.
     *
     * @param id of the user
     *
     */
    public function softDeleteAction($id = null)
    {
        if (!isset($id)) {
            die('Missing id');
        }

        $now = date(DATE_RFC2822);

        $user = $this->userModel->find($id);
        $user->deleted = $now;
        $user->save();

        // Redirect to display user
        $url = $this->url->create('users/id/' . $id);
        $this->response->redirect($url);
    }


    /**
     * Undo a soft-delete
     *
     * @param id of the user
     *
     */
    public function undoDeleteAction($id)
    {
        if (!isset($id)) {
            die('Missing id');
        }

        $now = date(DATE_RFC2822);

        $user = $this->userModel->find($id);
        $user->deleted = null;
        $user->save();

        // Redirect to display user
        $url = $this->url->create('users/waste');
        $this->response->redirect($url);   
    }

    /**
     * Inactivate user
     * 
     * @param user id
     *
     */
    public function inactivateAction($id)
    {
        if (!isset($id)) {
            die('Missing id');
        }

        $user = $this->userModel->find($id);
        $user->active = null;
        $user->save();

        // Redirect to display user
        $url = $this->url->create('users/active');
        $this->response->redirect($url);
    }

    /**
     * Activate user
     * 
     * @param user id
     *
     */
    public function activateAction($id)
    {
        if (!isset($id)) {
            die('Missing id');
        }

        $now = date(DATE_RFC2822);

        $user = $this->userModel->find($id);
        $user->active = $now;
        $user->save();

        // Redirect to display user
        $url = $this->url->create('users/inactive');
        $this->response->redirect($url);
    }


    /**
     * Show the active users
     *
     */
    public function activeAction() 
    {
        $active = $this->userModel->query()
            ->where('active IS NOT NULL')
            ->andWhere('deleted is NULL')
            ->execute();

        $userInfo = $this->userInfoBuilder(['update', 'inactivate', 'soft-delete'], $active);

        $this->views->add('user/list-all', [
            'title' => 'Aktiva användare',
            'users' => $userInfo
        ]);
    }


    /**
     * Show inactive users 
     *
     */
    public function inactiveAction() {

        $inactive = $this->userModel->query()
            ->where('active IS NULL')
            ->execute();

        $userInfo = $this->userInfoBuilder(['update', 'activate', 'soft-delete'], $inactive);

        $this->views->add('user/list-all', [
            'title' => 'Inaktiva användare',
            'users' => $userInfo
        ]);
    }


    /**
     * Shows the objects that have been soft-deleted
     *
     */
    public function wasteAction()
    {

        $softDeleted = $this->userModel->query()
            ->where('deleted IS NOT NULL')
            ->execute();

        $userInfo = $this->userInfoBuilder(['hard-delete', 'undo-delete'], $softDeleted);

        $this->views->add('user/list-all', [
            'title' => 'Papperskorg',
            'users' => $userInfo
        ]);
    }

    /**
     * Shows the objects that have been soft-deleted
     *
     */
    public function activeUsersAction($numOf, $area = 'main')
    {
        $users = $this->userModel->activeUsers($numOf);
        foreach ($users as $user) {
            $values = $user->getProperties();
            $this->views->add('user/abstract', [
                'user'   => $this->userHTMLAction($values)
            ], $area);
        }
    }
 }