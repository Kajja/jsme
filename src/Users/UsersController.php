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

        $this->theme->setTitle('Användare');
    }


    /**
     * Login user
     *
     */
    public function loginAction() {

        $form = $this->loginForm();

        //Check if the email and password has a match
        $form->check(
            // If the check is successful, 
            function($form) {
                $this->session->set('logged_in_userid', $this->logInUser->id); //TODO: Inte så snyggt
                $this->session->set('logged_in_usermail', $this->logInUser->email); //TODO: Inte så snyggt

                // Ladda om den sida som man var på när man klickade på login
                //$this->response->redirect($this->request->getPost('redirect'));
            },
            // If the check fails
            function($form) {
                $form->addOutput('Något gick fel');
            }
        );

        // Add the created form to a view
        $this->views->add('base/page', [
            'title'     => 'Login',
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
                'type' => 'text',
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
        $allUsers = $this->userModel->findAll();

        $colIndex = 1;
        $colMax = 4;                        // TODO: Inte bra att ha detta beroende här
        foreach ($allUsers as $user) {
            $values = $user->getProperties();
            $this->views->add('user/user', [
                'userValues' => $values,
                'userId' => $values['id']
            ], 'panel-col-' . $colIndex);

            // Lay out a users info in a panel, ordered in columns
            $colIndex === $colMax ? $colIndex = 1 : $colIndex++;
        }
    }


    /**
     *  Retrieve the user with the specified id and display in view
     *
     */
    public function idAction($id = null)
    {
        if (!isset($id)) {
            die('Missing id');
        }
        // Use the model to retrieve the specified user
        $user = $this->userModel->find($id);

        // Display user information in view
        $this->views->add('user/user', [
            'userValues' => $user->getProperties()
        ]);


    }

    /**
     *  Retrieve the user with the specified id and display in view
     *
     */
    public function profileAction($id = null)
    {
        if (!isset($id)) {
            die('Missing id');
        }
        // Use the model to retrieve the specified user
        $user = $this->userModel->find($id);

        // Display user information in view
        $this->views->add('user/profile', [
            'userValues' => $user->getProperties(),
            'id' => $user->id
        ]);

        // List the users questions
        $this->dispatcher->forward([
            'controller' => 'question',
            'action'     => 'userQuestions',
            'params'     => [$id]
        ]);

        // List questions that the user has answered
        $this->dispatcher->forward([
            'controller' => 'question',
            'action'     => 'answeredBy',
            'params'     => [$id]
        ]);
    }

    /**
     * Creates a HTML-representation of a users info
     *
     */
    public function userHTMLAction($id = null)  // TODO: Kanske inte så bra idé, fast bra att samla på ett ställe
    {
        if (!isset($id)) {
            die('Missing id');
        }

        // Use the model to retrieve the specified user
        $user = $this->userModel->find($id);

        $html = '<div>' .
                    '<a href="' . $this->url->create('users/profile/' . $user->id) . '">' .
                    "<img src='https://www.gravatar.com/avatar/" .
                     md5(strtolower(trim($user->email))) . "?s=30&d=identicon' alt='Profilbild'/>" .
                    $user->name . '</a></div>';

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
                $form->addOutput('Något gick fel');
            }
        );

        // Add the created form to a view
        $this->views->add('base/page', [
            'title'     => 'Skapa användare',
            'content'   => $form->getHTML()
        ]);
    }

    /**
     * Update a user
     *
     * @param id of the user to update
     */
    public function updateAction($id = null) {

        if (!isset($id)) {
            die('Missing id');
        }

        // The user-object, i.e. userModel is populated with data
        // if $id matched a row in the database.
        $this->userModel->find($id);

        // Create HTML-form and populate it with values from database
        $form = $this->userForm($this->userModel->getProperties());

        $form->check(
            // If the check is successful, update user
            function($form) {

                $now = date(DATE_RFC2822);
             
                $this->userModel->save([
                    'name'      => $form->Value('name'),
                    'email'     => $form->Value('mail'),
                    'changed'   => $now
                ]);

                // Redirect to display the created user
                $url = $this->url->create('users/profile/' . $this->userModel->id);
                $this->response->redirect($url);
            },
            // If the check fails
            function($form) {
                $form->addOutput('Något gick fel');
            }
        );

        // Add the created form to a view
        $this->views->add('base/page', [
            'title'     => 'Uppdatera profil',
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
            'password' => [
                'type' => 'password',
                'label' => 'Lösenord:',
                'required' => true,
                'validation' => ['not_empty'],
                'value' => isset($values['passw']) ? $values['passw'] : ''          
            ],
            'skapa/uppdatera' => [
                'type' => 'submit',
                'callback' => function($form) use ($values) {

                    // Check that the email has not already been registred
                    $matchedUsers = $this->userModel->query() //TODO: Funkar inte vid uppdatering
                        ->where('email = ?')
                        ->execute([$form->Value('mail')]);

                    if(!empty($matchedUsers)) {
                        return $values['id'] === $matchedUsers[0]->id;
                    }
                    return true;
                }
            ]
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
     * Builds user information to be displayed in list of users
     *
     * @param array which actions to display for every user
     * @param array with User-objects
     *
     * @return array with userinformation
     */
/*    public function userInfoBuilder($actions, $users)
    {
        $userInfo = [];
        foreach ($users as $key => $user) {

            $id = $user->id;

            $userInfo[$key]['id'] = $id;
            $userInfo[$key]['acronym'] = 
                "<a href=\"{$this->url->create('users/id/' . $id)}\">" . $user->acronym . '</a>';
            $userInfo[$key]['name'] = $user->name;

            // Adds action links to manipulate the user
            if (array_search('update', $actions) !== false) {
                $userInfo[$key][] = 
                    "<a href=\"{$this->url->create('users/update/' . $id)}\"  title='Uppdatera'><i class='fa fa-pencil'></i></a>";
            }
            if (array_search('inactivate', $actions) !== false) {
                $userInfo[$key][] = 
                    "<a href=\"{$this->url->create('users/inactivate/' . $id)}\" title='Inaktivera'><i class='fa fa-pause'></i></a>";
            }
            if (array_search('activate', $actions) !== false) {
                $userInfo[$key][] = 
                    "<a href=\"{$this->url->create('users/activate/' . $id)}\" title='Aktivera'><i class='fa fa-flash'></i></a>";
            }
            if (array_search('soft-delete', $actions) !== false) {
                $userInfo[$key][] = 
                    "<a href=\"{$this->url->create('users/softdelete/' . $id)}\" title='Släng'><i class='fa fa-trash'></i></a>";
            }
            if (array_search('undo-delete', $actions) !== false) {
                $userInfo[$key][] = 
                    "<a href=\"{$this->url->create('users/undodelete/' . $id)}\" title='Plocka upp ur papperskorg'><i class='fa fa-recycle'></i></a>";
            }
            if (array_search('hard-delete', $actions) !== false) {
                $userInfo[$key][] = 
                    "<a href=\"{$this->url->create('users/delete/' . $id)}\" title='Radera'><i class='fa fa-remove'></i></a>";
            }
        }
        return $userInfo;
    }
*/

 }