<?php
namespace App\Controllers;

use App\Libraries\Controller;

/**
 * API Controller Class
 *
 * Handles API calls
 * @author Chanda Mulenga <stconeten@gmail.com>
 */

class ApiController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->view('api/index');
    }

    public function users($id = null)
    {
        try {
            if (empty($id)) {
                $this->view('api/users/read');
            } elseif (is_numeric($id)) {
                $this->view('api/users/read.single');
            } else {
                throw new \Exception('Please use a number for the id');
            }
        } catch (\Exception $e) {
            echo json_encode([
                'message' => $e->getMessage(),
            ]);
        }

    }

    public function user($action = null, $id = null)
    {
        try {
            if ($action == 'create') {
                $this->view('api/users/post');
            } elseif ($action == 'update' && is_numeric($id)) {
                $this->view('api/users/put');
            } elseif ($action == 'delete' && is_numeric($id)) {
                $this->view('api/users/delete');
            } else {
                throw new \Exception('Please use a specific action i.e (create, update/{id}, delete/{id})');
            }
        } catch (\Exception $e) {
            echo json_encode([
                'message' => $e->getMessage(),
            ]);
        }
    }
}
