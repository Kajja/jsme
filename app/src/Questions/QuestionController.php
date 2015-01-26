<?php

namespace Kajja\Questions;

use \Anax\DI\IInjectionaware,
    \Anax\DI\TInjectable,
    \Kajja\Questions\Question as Question,
    \Kajja\QuestionTags\QuestionTag as QuestionTag; // Model for mapping between question and tags

/**
 * Controller class for questions
 *
 */
class QuestionController implements IInjectionaware
 {
    use TInjectable;
    
    /**
     * Initialize the controller
     *
     *
     */
    public function initialize()
    {
        // Create Question-model object
        $this->questionModel = new Question();
        $this->questionModel->setDI($this->di);

        // Set class on body-element for styling
        $this->theme->setVariable('bodyClasses', 'page-container');

        $this->theme->setTitle('Fråga');
    }


    /**
     * Method that handles the creation of questions.
     *
     */
    public function createAction()
    {
        // Check if logged in                          TODO: Flytta ut all login/logout funk
        if (!$this->session->has('logged_in_userid')) {

            $this->views->add('question/form', [
                'title'     => '',
                'content'   => 'Du måste vara inloggad för att kunna ställa en fråga']);

            return false;
        }

        $form = $this->questionForm();

        $form->check(function($form) {

                $now = date(DATE_RFC2822);
             
                // Save the question
                $this->questionModel->save([
                    'user_id'   => $this->session->get('logged_in_userid'),
                    'title'     => $form->Value('title'),
                    'text'      => $form->Value('question'),
                    'created'   => $now,
     //               'active'    => $now
                ]);

                //Save the tags related to the question
                $questionTagModel = new QuestionTag();
                $questionTagModel->setDI($this->di);

                $tagsChecked = $form['tags']['checked'];

                // Get the existing tags to get the ids
                                                        // Känns onödigt att behöva göra detta igen
                $tags = $this->dispatcher->forward([    // TODO: Dumt att det görs flera ggr
                    'controller' => 'tags',
                    'action'     => 'all',
                ]);

                foreach ($tagsChecked as $tagIndex) {
                    $questionTagModel->create([
                        'question_id'   => $this->questionModel->getProperties()['id'],
                        'tag_id'        => $tags['ids'][$tagIndex]
                    ]);
                }

                // Redirect to display the created user
//                $url = $this->url->create('question/id/' . $this->questionModel->id);
//                $this->response->redirect($url);
            },
            // If the check fails
            function($form) {
                $form->addOutput('Något gick fel');
            }
        );

        $this->views->add('question/form', [
            'title'     => 'Ställ din fråga',
            'content'   => $form->getHTML()]
        );

    }

    /**
     * Method that creates the question form.
     *
     */
    public function questionForm() 
    {
        // Turn on session, Form-service use it
        $this->session();

        // Get the existing tags
        $tags = $this->dispatcher->forward([ // TODO: Dumt att det görs flera ggr
            'controller' => 'tags',
            'action'     => 'all',
        ]);

        // Create the form
        $form = $this->form->create([], [
            'title' => [
                'type' => 'text',
                'label' => 'Rubrik:',
                'required' => true,
                'validation' => ['not_empty'],
                'value' => isset($values['title']) ? $values['title'] : ''          
            ], 
            'question' => [
                'type' => 'textarea',
                'label' => 'Fråga:',
                'required' => true,
                'validation' => ['not_empty'],
                'value' => isset($values['question']) ? $values['question'] : ''          
            ],
            'tags'  => [
                'type' => 'select-multiple',
                'label' => 'Taggar:',
                'required' => true,
                'options' => $tags['names'],
            ],
            'skapa' => [
                'type' => 'submit',
                'callback' => function($form) {
                    return true; // No extra validation
                }
            ]
        ]);

        return $form;
    }


    /**
     * Retrieve all stored questions and show in view
     *
     * @param string tag name
     */
    public function listAction($tagName = null) {

        // Use the model to get all stored questions
        $allQuestions = $this->questionModel->findAll(); // Om inte array?

        $this->display($allQuestions);
    }


    /**
     *  Retrieve the question with the specified id and display in view
     *  and display related tags, answers and comments.
     */
    public function idAction($id = null)
    {
        if (!isset($id)) {
            die('Missing id');
        }
        // Use the model to retrieve the specified user
        $question = $this->questionModel->find($id);

        $values = $question->getProperties();

        // Display question in view
        $this->display([$question]);

        // Show creator

        // List related tags
        $this->dispatcher->forward([
            'controller' => 'tags',
            'action'     => 'filter',
            'params'     => [$id]
        ]);

        // List question comments
        $this->dispatcher->forward([
            'controller' => 'comments',
            'action'     => 'list',
            'params'     => ['q', $id]
        ]);

        // List answers
        $this->dispatcher->forward([
            'controller' => 'answers',
            'action'     => 'list',
            'params'     => [$id]
        ]);

        // Show answer form
       /* Funkar inte hela databasen hänger sig
       $this->dispatcher->forward([
            'controller'    => 'answers',
            'action'        => 'create',
            'params'        => [$id]
        ]);
        */
    }

     /**
     *  Retrieve the questions with the specified tag and display in view
     *
     */
    public function tagAction($tag = null)
    {
        if (!isset($tag)) {
            die('Missing tag');
        }

        $questions = $this->questionModel->taggedQuestions($tag);
        $this->display($questions);
    }


    /**
     *  Show single question in abstract form
     *
     */
    public function showAction($id) {

         $question = $this->questionModel->find($id);
         $this->display([$question]);
    }

    /**
     *  Display only question(s) i.e. no tags, answers ...
     *
     */
    public function display($questions) {

        foreach ($questions as $question) {
            $values = $question->getProperties();
            $user = $this->dispatcher->forward([
                'controller'    => 'users',
                'action'        => 'userHTML',
                'params'        => [$values['user_id']]
            ]);

            $this->views->add('question/abstract', [
                'questionId'    => $values['id'],
                'title'         => $values['title'],
                'text'          => $this->textFilter->doFilter($values['text'], 'shortcode, markdown'),
                'user'          => $user,
                'created'       => $values['created']
            ]);
        }
    }

    /**
     *  Show the questions created by a certain user.
     *
     */
    public function userQuestionsAction($userId)
    {
        $questions = $this->questionModel->userQuestions($userId);
        $this->display($questions);
    }

    /**
     *  Show the questions answered by a certain user.
     *
     */
    public function answeredByAction($userId)
    {
        $questions = $this->questionModel->questionsAnsweredBy($userId);
//        var_dump($questions);
        $this->display($questions);
    }

    /**
     *  Latest questions.
     *
     */
    public function latestAction($numberOf)
    {
        $questions = $this->questionModel->latestQuestions($numberOf);
        $this->display($questions);
    }
}