<?php

namespace Kajja\Questions;

use \Anax\MVC\CDatabaseModel;

/**
 * Model class for questions
 *
 */
class Question extends CDatabaseModel
{

    /**
     * Retrieves questions with the specified tag
     *
     */
    public function taggedQuestions($tag) {

        $table = $this->getSource(); 

        $questions = $this->join([$table, 'questiontag'], "{$table}.id = question_id")
                     ->where('tag_id = ?')
                     ->execute([$tag]);

        return $questions;
    }

    /**
     * Retrieves questions created by user
     *
     */
    public function userQuestions($userId)
    {
        return $this->query()->where('user_id = ?')->execute([$userId]);
    }

    /**
     * Retrieves the questions answered by the user
     *
     */
    public function questionsAnsweredBy($userId)
    {
        $table = $this->getSource();
    /*    
        return $this->join(['answer', $table], "{$table}.id = question_id")
            ->where('answer.user_id = ?')->execute([$userId]);
            
    */
        return $this->select("*, MIN('{$table}.id')")->from('answer')
            ->joinB($table, "{$table}.id = question_id")
            ->where('answer.user_id = ?')->groupBy("{$table}.id")->execute([$userId]);
    }

    /**
     * Retrieves the questions most recently created
     *
     */
    public function latestQuestions($numberOf)
    {
        return $this->query()->orderBy('id DESC')->limit($numberOf)->execute();
    }

}