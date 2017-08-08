<?php

namespace ToDo\Helpers;

class Base
{
    /**
     * @param null|string $image_hash
     * @param bool        $use_default
     *
     * @return string
     */
    public static function getImagePath(?string $image_hash = '', $use_default = true) : string
    {
        if (empty($image_hash)) {
            return $use_default ? '//placehold.it/450X300/EEEEEE/776C6C' : '';
        }

        return '/img/' . $image_hash . '.jpg';
    }

    /**
     * @param string $url
     * @param bool   $permanent
     */
    public static function redirectTo(string $url, bool $permanent = false) : void
    {
        $code = $permanent ? 301 : 302;
        header('Location: ' . $url, true, $code);
        exit;
    }
}
