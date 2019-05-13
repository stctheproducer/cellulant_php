<?php
namespace App\Libraries;

use App\Helpers\Session;

/**
 * App Core Class
 *
 * Creates URLs and loads core controller
 * @author Chanda Mulenga <stconeten@gmail.com>
 */

class Core
{
    protected $currentController = 'App\Controllers\PagesController';
    protected $currentMethod     = 'index';
    protected $params            = [];

    public function __construct()
    {
        session_start();
        Session::terminate();

        $url = $this->getUrl();

        // Check for the right controller
        if (class_exists("App\\Controllers\\" . ucwords($url[0]) . "Controller")) {
            $this->currentController = "App\\Controllers\\" . ucwords($url[0]) . "Controller";
            unset($url[0]);
        }

        $this->currentController = new $this->currentController;

        // Check for controller methods
        if (isset($url[1])) {
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        // Get URL parameters
        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }
    /**
     * Returns the url after the domain from the browser as an array
     *
     * @return array
     */
    public function getUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }

        // if (isset($_SERVER['REQUEST_URI'])) {
        //     $url = trim($_SERVER['REQUEST_URI'], '/');
        //     $url = filter_var($url, FILTER_SANITIZE_URL);
        //     $url = explode('/', $url);
        //     return $url;
        // }
    }
}
