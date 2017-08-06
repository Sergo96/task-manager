<?php

namespace ToDo\Repositories;

abstract class BaseRepository
{
    /**
     * @property \PDO
     */
    protected $db;

    /**
     * @param \PDO $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }
}
