<?php

namespace Anax\Users;

use \Anax\MVC\CDatabaseModel;

/** 
 * Model for users
 *
 */
 class User extends CDatabaseModel
 {
    /**
     * Gets the user with the most questions,
     * answers and comments.
     */
    public function activeUsers($numOf)
    {
         return $this->executeRaw("SELECT *, SUM(qsum + asum + csum) as activity FROM" . 
            " user LEFT JOIN (" .
                        "(SELECT user.id as q_uid, count(user_id) as qsum " .
                            "FROM user LEFT JOIN question ON user.id = user_id GROUP BY user.id) " .                         
                        "LEFT JOIN " . 
                            "(SELECT * FROM " .
                                "(SELECT user.id as a_uid, COUNT(user_id) as asum " .
                                    "FROM user LEFT JOIN answer ON user.id = user_id GROUP BY user.id" .
                                ") ".
                                "LEFT JOIN " .
                                "(SELECT user.id as c_uid, COUNT(user_id) as csum " . 
                                    "FROM user LEFT JOIN comment ON user.id = user_id GROUP BY user.id" .
                                ") " .
                            "ON a_uid = c_uid) " .
                        "ON q_uid = a_uid" .
                    ") ON user.id = q_uid GROUP BY user.id ORDER BY activity DESC LIMIT {$numOf}", []);
    }
 }