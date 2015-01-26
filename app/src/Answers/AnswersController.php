<?php

namespace Kajja\Answers;

use \Anax\DI\IInjectionaware,
    \Anax\DI\TInjectable,
    \Kajja\Answers\Answer;

/**
 * Control class for answers
 *
 */
class AnswersController implements IInjectionaware
{
    use TInjectable;

    public function initialize()
    {
         // Create Answer-model object
        $this->answerModel = new Answer();
        $this->answerModel->setDI($this->di);
    }


    /**
     *
     *
     */
    public function createAction($questionId)
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
            'title'     => 'Ditt svar',
            'content'   => 'Du måste vara inloggad för att kunna svara'
        ]);

            return false;
        }

        $form = $this->answerForm($questionId);

        $form->check(
            // If the check is ok
            function($form) {
                $now = date(DATE_RFC2822);
                 
                // Save the answer
                $this->answerModel->save([
                    'user_id'       => $this->session->get('logged_in_userid'),
                    'question_id'   => $form->Value('questionId'),
                    'text'      => $form->Value('answer'),
                    'created'   => $now,
                ]);

            },
            // If the check fails
            function($form) {
                $form->addOutput('Något gick fel');
            }
        );


        $this->views->add('base/page', [
            'title'     => 'Ditt svar',
            'content'   => $form->getHTML()
        ]);

    }

    /**
     *
     *
     */

    public function answerForm($questionId)
    {
        $this->session();       // TODO: Inloggad för att kunna svara

        $form = $this->form->create([], [
            'answer' => [
                'type' => 'textarea',
                'label' => 'Svar:',
                'required' => true,
                'validation' => ['not_empty'],
                'value' => isset($values['answer']) ? $values['answer'] : ''          
            ],
            'questionId' => [
                'type' => 'hidden',
                'value' => $questionId
            ],
            'Svara' => [
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
    public function listAction($questionId)
    {
        $answers = $this->answerModel->answers($questionId);

        foreach ($answers as $answer) {
            $values = $answer->getProperties();
            $user = $this->dispatcher->forward([
                'controller'    => 'users',
                'action'        => 'userHTML',
                'params'        => [$values['user_id']]
            ]);
            $this->views->add('answer/answer', [
                'text'  => $this->textFilter->doFilter($values['text'], 'shortcode, markdown'),
                'answerId' => $values['id'],
                'user' => $user,
                'created' => $values['created']
            ]);

            $this->dispatcher->forward([
                'controller' => 'comments',
                'action'     => 'list',
                'params'     => ['a', $values['id']]
            ]);
        }
    }
}