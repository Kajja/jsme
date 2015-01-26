<?php

namespace Kajja\Comments;

use \Anax\MVC\CDatabaseModel;

/**
 * Model class for comments
 *
 */
class Comment extends CDatabaseModel
{

    public function comments($type, $objectId)
    {
        return $this->query()->where('type = ?')
            ->andWhere('related_id = ?')->execute([$type, $objectId]);
    }
}