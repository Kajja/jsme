<?php

namespace Kajja\Tags;

use \Anax\MVC\CDatabaseModel;

/**
 * Model class for tags
 *
 */
class Tag extends CDatabaseModel
{

    /**
     * Returns the tags associated with the question
     *
     * @param string question id
     */

    public function questionTags($questionId) 
    {

        $tagTable = $this->getSource(); 

        $tags = $this->join([$tagTable, 'questiontag'], "id = tag_id")
                     ->where('question_id = ?')
                     ->execute([$questionId]);

        return $tags;
    }

    /**
     * Returns the most popular tags sorted
     *
     * @return array most popular tags
     */
    public function popTags($numOf = 5)
    {
        $tagTable = $this->getSource();

        return $this->select("{$tagTable}.id, name, COUNT(question_id) AS num_questions")
            ->joinB($tagTable, "{$tagTable}.id = tag_id")->from('questiontag')->groupBy('name') //TODO: join/joinB
            ->orderBy('num_questions DESC')->limit($numOf)->execute();
    }

}