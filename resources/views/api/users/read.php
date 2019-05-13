<?php

use App\Models\User;

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Instantiate User object
$user = new User;

// User query
$result = $user->read();

// Get row count
$num = $user->countRows();

// Check for users
if ($num > 0) {
    // User array
    $users         = [];
    $users['data'] = [];

    foreach ($result as $row) {
        extract($row);

        $user_item = [
            'id'            => $id,
            'first_name'    => $first_name,
            'other_names'   => $other_names,
            'last_name'     => $last_name,
        ];

        // Push to'data'
        array_push($users['data'], $user_item);
    }

    // Turn to JSON
    echo json_encode($users);
} else {
    // No users
    json_encode([
        'message' => 'No users found!',
    ]);
}
