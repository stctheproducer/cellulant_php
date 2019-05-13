<?php

use App\Models\User;

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Instantiate User object
$user = new User;

$user->id = explode('/', $_GET['url'])[2];

// User query
$user->readOne();

// Create array
$user_arr = [
    'id'            => $user->id,
    'first_name'    => $user->first_name,
    'other_names'   => $user->other_names,
    'last_name'     => $user->last_name,
];

print_r(json_encode($user_arr));
