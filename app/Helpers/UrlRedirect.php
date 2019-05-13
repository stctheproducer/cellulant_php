<?php

namespace App\Helpers;

use Dotenv\Dotenv;

class UrlRedirect
{
    private function getAppUrl()
    {
        $dotenv = new Dotenv(dirname(dirname(__DIR__)));
        $dotenv->load();
        return env('APP_URL');
    }

    // Simple page redirect
    public function redirect($page, $redirect_url = null)
    {
        if (empty($redirect_url)) {
            header('location: ' . self::getAppUrl() . '/' . $page);
        } else {
            header('location: ' . $redirect_url . '/' . $page);
        }
    }
}
