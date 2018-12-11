<?php namespace App\Database;

use Illuminate\Database\MySqlConnection as BaseConnection;
use App\Database\Query\Builder as QueryBuilder;

class MySqlConnection extends BaseConnection
{
    /**
     * Get a new query builder instance.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        return new QueryBuilder(
            $this,
            $this->getQueryGrammar(),
            $this->getPostProcessor()
        );
    }
}
