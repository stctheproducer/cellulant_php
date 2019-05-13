<?php
namespace App\Controllers;

use App\Libraries\Controller;

/**
 * Pages Controller Class
 *
 * Returns views for requested pages
 * @author Chanda Mulenga <stconeten@gmail.com>
 */

class PagesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = $this->data;
        $this->view('pages/index', $data);
    }
}
