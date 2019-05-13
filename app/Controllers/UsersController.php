<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Helpers\UrlRedirect;
use App\Helpers\Validation;
use App\Libraries\Controller;
use App\Models\User;


/**
 * Users Controller Class
 *
 * Handles user authentication
 * @author Chanda Mulenga <stconeten@gmail.com>
 */
class UsersController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->userModel  = new User; // $this->model('User');
        $this->validation = new Validation;
    }

    public function register()
    {
        // Departments
        $departments = $this->deptModel->all();

        $numOfDept = $this->deptModel->countRows();

        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form

            // Sanitize input
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Initialize data
            $data = $this->data + [
                'page'                 => 'Register',
                'title'                => 'Create an Account',
                'first_name'           => ucfirst(trim($_POST['first_name'])),
                'other_names'          => ucfirst(trim($_POST['other_names'])),
                'last_name'            => ucfirst(trim($_POST['last_name'])),
                'nrc'                  => trim($_POST['nrc']),
                'DOB'                  => trim($_POST['DOB']),
                'phone_number'         => trim($_POST['phone_number']),
                'email_address'        => trim($_POST['email_address']),
                'password'             => trim($_POST['password']),
                'confirm_password'     => trim($_POST['confirm_password']),
                'departments'          => $departments,
                'department_id'        => trim($_POST['department_id']),
                'first_name_err'       => '',
                'other_names_err'      => '',
                'last_name_err'        => '',
                'nrc_err'              => '',
                'DOB_err'              => '',
                'phone_number_err'     => '',
                'email_address_err'    => '',
                'password_err'         => '',
                'confirm_password_err' => '',
                'department_id_err'    => '',
            ];

            // Validate email
            $data['email_address_err'] = Validation::validate('email address', $data['email_address']);
            // Check if email exists
            if ($this->userModel->findUserByEmail($data['email_address'])) {
                $data['email_address_err'] = 'That email address is already in use';
            }

            // Validate first name
            $data['first_name_err'] = Validation::validate('first name', $data['first_name']);

            // Validate other names
            $data['other_names_err'] = Validation::validate('other names', $data['other_names']);

            // Validate last name
            $data['last_name_err'] = Validation::validate('last name', $data['last_name']);

            // Validate NRC
            $data['nrc_err'] = Validation::validate('nrc', $data['nrc']);
            // Check if NRC number exists
            if ($this->userModel->findUserByNrc($data['nrc'])) {
                $data['nrc_err'] = 'That NRC number is already in use';
            }

            // Validate DOB
            $data['DOB_err'] = Validation::validate('DOB', $data['DOB']);

            // Validate phone number
            $data['phone_number_err'] = Validation::validate('phone number', $data['phone_number']);

            // Validate password
            $data['password_err'] = Validation::validate('password', $data['password']);

            // Validate password confirmation
            $data['confirm_password_err'] = Validation::validate('confirm password', $data['confirm_password'], $data['password']);

            // Validate department ID
            $data['department_id_err'] = Validation::validate('department_id', $data['department_id'], $numOfDept);

            // Make sure errors are empty
            if (empty($data['first_name_err']) && empty($data['other_names_err']) && empty($data['last_name_err']) && empty($data['nrc_err']) && empty($data['DOB_err']) && empty($data['phone_number_err']) && empty($data['department_id_err']) && empty($data['email_address_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Validated
                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Register user
                if ($this->userModel->register($data)) {
                    Session::flash('register_success', 'You are registered and can now login');
                    UrlRedirect::redirect('users/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('users/register', $data);
            }

        } else {
            // Initialize data
            $data = $this->data + [
                'page'                 => 'Register',
                'title'                => 'Create an Account',
                'first_name'           => '',
                'other_names'          => '',
                'last_name'            => '',
            ];

            // Load view
            $this->view('users/register', $data);
        }
    }

    public function login()
    {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            // Sanitize input
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Initialize data
            $data = $this->data + [
                'page'              => 'Login',
                'title'             => 'Log Into Account',
                'email_address'     => trim($_POST['email_address']),
                'password'          => trim($_POST['password']),
                'email_address_err' => '',
                'password_err'      => '',
            ];

            // Validate email
            $data['email_address_err'] = Validation::validate('email address', $data['email_address']);
            // Check if user/email exists
            if (!$this->userModel->findUserByEmail($data['email_address'])) {
                $data['email_address_err'] = 'No user found!';
            }
            // Validate password
            $data['password_err'] = Validation::validate('password', $data['password']);

            // Make sure errors are empty
            if (empty($data['email_address_err']) && empty($data['password_err'])) {
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email_address'], $data['password']);

                if ($loggedInUser) {
                    // Create session variables
                    $this->createUserSession($loggedInUser);
                    Session::regenerate();
                    Session::set('LAST_ACTIVITY', time());
                } else {
                    $data['password_err'] = 'Password incorrect';

                    $this->view('users/login', $data);
                }

            } else {
                $this->view('users/login', $data);
            }

        } else {
            // Initialize data
            $data = $this->data + [
                'page'              => 'Login',
                'title'             => 'Log Into Account',
                'email_address'     => '',
                'password'          => '',
                'email_address_err' => '',
                'password_err'      => '',
            ];

            // Load view
            $this->view('users/login', $data);
        }
    }

    public function createUserSession($user)
    {
        Session::set('user_id', $user->id);
        Session::set('user_email_address', $user->email_address);
        Session::set('user_first_name', $user->first_name);
        $user->other_names ? Session::set('user_other_names', $user->other_names) : null;
        Session::set('user_last_name', $user->last_name);
        Session::Set('user_department_id', $user->department_id);
        UrlRedirect::redirect('pages/index');
    }

    public function logout()
    {
        Session::delMany([
            'user_id',
            'user_email_address',
            'user_first_name',
            'user_last_name',
            'user_other_names',
            'user_department_id',
        ]);
        Session::destroy();
        UrlRedirect::redirect('users/login');
    }

    public function show($id)
    {}

    public function edit($id)
    {}

    public function update($id)
    {}

    public function destroy($id)
    {
        $this->userModel->id = $id;

        $this->userModel->delete();
    }
}
