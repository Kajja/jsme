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
        /*
        // Shows the question <<-- Detta funkar inte
        $this->dispatcher->forward([
            'controller' => 'question',
            'action'     => 'show',
            'params'     => [$questionId]
        ]);
        */

        // Check if logged in                          TODO: Flytta ut all login/logout funk
        if (!$this->session->has('logged_in_userid')) {

        $this->views->add('base/page', [
            'title'     => 'Din kommentar',
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

            },
            // If the check fails
            function($form) {
                $form->addOutput('Något gick fel');
            }
        );


        $this->views->add('base/page', [
            'title'     => 'Din kommentar',
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
            $this->views->add('comment/comment', [
                'text'  => $this->textFilter->doFilter($values['text'], 'shortcode, markdown'),
                'created' => $values['created']
            ]);
        }
    }
}