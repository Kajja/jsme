<?php

namespace Kajja\Answers;

use \Anax\MVC\CDatabaseModel;

/**
 * Model class for answers
 *
 */
class Answer extends CDatabaseModel
{

    public function answers($questionId)
    {
        return $this->query()->where('question_id = ?')->execute([$questionId]);
    }

}