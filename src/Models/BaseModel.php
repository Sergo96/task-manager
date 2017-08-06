<?php

namespace ToDo\Models;

abstract class BaseModel
{
    /**
     * @property \PDO
     */
    protected $db;

    protected $repository;

    /**
     * @param \PDO $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }
}
