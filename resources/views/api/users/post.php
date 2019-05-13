<?php

use App\Models\User;

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Instantiate User object
$user = new User;

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

$user->first_name    = $data->first_name;
$user->other_names   = $data->other_names;
$user->last_name     = $data->last_name;

// Create post
if ($user->post()) {
    echo json_encode([
        'message' => 'User created!',
    ]);
} else {
    echo json_encode([
        'message' => 'User not created!',
    ]);
}
