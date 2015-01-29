<?php

namespace Kajja\Comments;

use \Anax\DI\IInjectionaware,
    \Anax\DI\TInjectable,
    \Kajja\Comments\Comment;

/**
 *
 *
 *
 */
class CommentsController implements IInjectionaware
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
        $this->commentModel = new Comment();
        $this->commentModel->setDI($this->di);
    }


    /**
     *
     *
     *
     */
    public function createAction($type, $objectId)
    {
      
        $this->theme->setTitle('Kommentera');
        $this->views->addString('<h3>Kommentera</h3><hr>');

        // Check if logged in                          TODO: Flytta ut all login/logout funk
        if (!$this->session->has('logged_in_userid')) {

            $this->views->add('base/page', [
                'title'     => '',
                'content'   => 'Du måste vara inloggad för att kunna kommentera'
            ]);
                return false;
        }

        $form = $this->commentForm($objectId);

        $form->check(
            // If the check is ok
            function($form) use ($type, $objectId) {
                $now = date(DATE_RFC2822);
                 
                // Save the comment
                $this->commentModel->save([
                    'user_id'       => $this->session->get('logged_in_userid'),
                    'type'          => $type,
                    'related_id'    => $objectId,
                    'text'          => $form->Value('comment'),
                    'created'       => $now,
                ]);

                // Get the related question id if commenting an answer
                if ($type === 'a') {
                    $answer = $this->dispatcher->forward([
                        'controller'    => 'answers',
                        'action'        => 'id',
                        'params'        => [$objectId]
                    ]);
                    $objectId = $answer->question_id;
                }

                $url = $this->url->create('question/id/' . $objectId);
                $this->response->redirect($url);
            },
            // If the check fails
            function($form) use ($type, $objectId) {
                $form->addOutput('Något gick fel');
                $url = $this->url->create('comments/create/'. $type . '/' . $objectId);
                $this->response->redirect($url);
            }
        );


        $this->views->add('base/page', [
            'title'     => '',
            'content'   => $form->getHTML()
        ]);
    }

    /**
     *
     *
     *
     */
    public function commentForm($objectId)
    {
        $form = $this->form->create([], [
            'comment' => [
                'type' => 'textarea',
                'label' => 'Kommentar:',
                'required' => true,
                'validation' => ['not_empty'],
                'value' => isset($values['comment']) ? $values['comment'] : ''          
            ],
            'Kommentera' => [
                'type' => 'submit',
                'callback' => function($form) {
                    return true; // No extra validation
                }
            ]
        ]);
        return $form;
    }

    /**
     *
     *
     *
     */
    public function listAction($type, $objectId)
    {
        $comments = $this->commentModel->comments($type, $objectId);

        foreach ($comments as $comment) {
            $values = $comment->getProperties();

            $user = $this->dispatcher->forward([
                'controller'    => 'users',
                'action'        => 'id',
                'params'        => [$values['user_id'], false]
            ]);

            $this->views->add('comment/comment', [
                'text'  => $this->textFilter->doFilter($values['text'], 'shortcode, markdown'),
                'user'  => $user->name,
                'userId' => $user->id,
                'created' => date('Y-m-d, H:i:s', strtotime($values['created']))
            ]);
        }
    }
}