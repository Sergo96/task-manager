<?php

namespace ToDo\Models;

class AuthModel extends BaseModel
{
    /**
     * @var array
     */
    protected static $admin_data = [
        'login' => 'admin',
        'password' => '123',
    ];

    /**
     * @param \PDO $db
     */
    public function __construct(\PDO $db)
    {
        parent::__construct($db);
    }

    /**
     * @param null|string $login
     * @param null|string $password
     *
     * @return bool
     */
    public function login(?string $login = '', ?string $password = '') : bool
    {
        if (!empty($login) && !empty($password)) {
            $is_credentials_valid = ($login === self::$admin_data['login'] && $password === self::$admin_data['password']);

            if ($is_credentials_valid) {
                $_SESSION['auth'] = [
                    'key' => self::createAuthKey($login, $password),
                    'login' => $login,
                ];

                return true;
            }
        }

        $this->setNotification('Bad login data', BaseModel::NOTIFICATION_TYPE_ERROR);

        return false;
    }

    /**
     * @return bool
     */
    public static function isLoggedIn() : bool
    {
        $login = $_SESSION['auth']['login'] ?? '';
        $password = self::$admin_data['password'];
        $key = $_SESSION['auth']['key'] ?? '';

        return ($key === self::createAuthKey($login, $password));
    }

    /**
     * @param string $login
     * @param string $password
     *
     * @return string
     */
    protected static function createAuthKey(string $login, string $password) : string
    {
        return sha1($login . (strlen($login . $password) + 5) . $password);
    }
}
