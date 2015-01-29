<?php

namespace Anax\MVC;

use \Mos\Database\CDatabaseBasic;

/**
 *
 *
 */
class CDatabaseExtended extends CDatabaseBasic
{
    /**
     * Build the left join part.
     *
     * @param string $table     name of table.
     * @param string $condition to join.
     *
     * @return $this
     */
    public function leftJoin($table, $condition)
    {
        $this->join .= "LEFT JOIN " . $table
            . "\n\tON " . $condition . "\n";

        return $this;
    }
}