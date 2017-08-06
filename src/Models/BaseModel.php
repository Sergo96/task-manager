<?php

namespace ToDo\Models;

abstract class BaseModel
{
    const NOTIFICATION_TYPE_SUCCESS = 'messages';
    const NOTIFICATION_TYPE_ERROR = 'errors';

    /**
     * @property \PDO
     */
    protected $db;

    protected $repository;

    /**
     * Stack of notifications descriptions (messages, errors)
     *
     * @var array
     */
    protected $notifications = [];

    /**
     * @param \PDO $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @return array
     */
    public function getNotifications() : array
    {
        return $this->notifications ?? [];
    }

    /**
     * @param string $text
     * @param string $type
     */
    public function setNotification(string $text, string $type = self::NOTIFICATION_TYPE_SUCCESS) : void
    {
        $this->notifications[$type][] = $text;
    }
}
