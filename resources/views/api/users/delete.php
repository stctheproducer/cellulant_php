<?php

use App\Models\User;

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Instantiate User object
$user = new User;

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

// Set ID to delete
$user->id = explode('/', $_GET['url'])[3];

// Delete user
if ($user->delete()) {
    echo json_encode([
        'message' => 'User deleted!',
    ]);
} else {
    echo json_encode([
        'message' => 'User not deleted!',
    ]);
}
