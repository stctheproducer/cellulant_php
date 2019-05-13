<?php

namespace App\Helpers;

class Session
{
    // Initialize session
    public static function init()
    {
        // if (session_status() == PHP_SESSION_NONE) {
        session_start();
        // }
    }

    // Check for session value
    public static function check($key, $secondKey = false)
    {
        if ($secondKey == true) {
            if (isset($_SESSION[$key][$secondKey])) {
                return true;
            }
        } else {
            if (isset($_SESSION[$key])) {
                return true;
            }
        }

        return false;
    }

    // Check for duration of session
    public static function terminate()
    {
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
            session_unset();
            session_destroy();
        }
        // $_SESSION['LAST_ACTIVITY'] = time();
    }

    // Update last activity time of session
    public static function lastActivity()
    {
        if (self::isLoggedIn()) {
            $_SESSION['LAST_ACTIVITY'] = time();
        }
    }

    // Regenerate session ID
    public static function regenerate()
    {
        if (!isset($_SESSION['CREATED'])) {
            $_SESSION['CREATED'] = time();
        } else if (time() - $_SESSION['CREATED'] > 1800) {
            session_regenerate_id(true);
            $_SESSION['CREATED'] = time();
        }
    }

    // Check if user logged in
    public static function isLoggedIn()
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        } else {
            return false;
        }
    }

    // Set session value
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    // Get session value
    public static function get($key, $secondKey = false)
    {
        if ($secondKey == true) {
            if (isset($_SESSION[$key][$secondKey])) {
                return $_SESSION[$key][$secondKey];
            }
        } else {
            if (isset($_SESSION[$key])) {
                return $_SESSION[$key];
            }
        }

        return false;
    }

    // Display session values
    public static function display()
    {
        echo '<pre>';
        print_r($_SESSION);
        echo '</pre>';
    }

    // Destroy session values
    public static function destroy()
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    // Destroy all session values
    public static function destroyAll()
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }

    // Unset session value
    public static function del($key, $secondKey = false)
    {
        if ($secondKey == true) {
            if (isset($_SESSION[$key][$secondKey])) {
                unset($_SESSION[$key][$secondKey]);
            }
        } else {
            if (isset($_SESSION[$key])) {
                unset($_SESSION[$key]);
            }
        }

        return false;
    }

    // Unset many session values
    public static function delMany(array $keys)
    {
        foreach ($keys as $key) {
            if (isset($_SESSION[$key])) {
                unset($_SESSION[$key]);
            } else {
                return false;
            }
        }
    }

    // Flash message helper
    // public static function flash($name = '', $message = '', $class = 'notification is-success')
    // {
    //     if (!empty($name)) {
    //         if (!empty($message) && empty($_SESSION[$name])) {
    //             if (!empty($_SESSION[$name])) {
    //                 unset($_SESSION[$name]);
    //             }

    //             if (!empty($_SESSION[$name . '_class'])) {
    //                 unset($_SESSION[$name . '_class']);
    //             }

    //             $_SESSION[$name]            = $message;
    //             $_SESSION[$name . '_class'] = $class;
    //         } elseif (empty($message) && !empty($_SESSION[$name])) {
    //             $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
    //             $html_msg = '';
    //             echo $html_msg;
    //             unset($_SESSION[$name]);
    //             unset($_SESSION[$name . '_class']);
    //         }
    //     }
    // }
}
