<?php

use App\Models\User;

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Instantiate User object
$user = new User;

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Set ID to update
$user->id = explode('/', $_GET['url'])[3];

$user->first_name    = $data->first_name;
$user->other_names   = $data->other_names;
$user->last_name     = $data->last_name;

// Create user
if ($user->put()) {
    echo json_encode([
        'message' => 'User updated!',
    ]);
} else {
    echo json_encode([
        'message' => 'User not updated!',
    ]);
}
