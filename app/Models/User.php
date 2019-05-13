<?php

namespace App\Models;

use App\Libraries\Database;

class User
{
    private $db;
    private $table = 'table_name';
    public $first_name;
    public $other_names;
    public $last_name;
    public $nrc;
    public $DOB;
    public $phone_number;
    public $email_address;
    public $password;
    public $department_id;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Register user
    public function register($data)
    {
        $this->db->query('INSERT INTO ' . $this->table . ' (first_name, other_names, last_name) VALUES (:first_name, :other_names, :last_name)');

        // Bind values
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':other_names', $data['other_names']);
        $this->db->bind(':last_name', $data['last_name']);

        // Execute SQL query
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Login user
    public function login($email_address, $password)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE email_address = :email_address');
        $this->db->bind(':email_address', $email_address);

        $row = $this->db->getSingleRecord();

        $hashed_password = $row->password;
        if (password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }

    // Delete User
    public function delete()
    {
        $this->db->query('DELETE FROM ' . $this->table . ' WHERE id = :id');

        // Sanitize data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $this->db->bind(':id', $this->id);

        // Execute query
        if ($this->db->execute()) {
            return true;
        }

        // Print error if occurs
        printf("Error: %s.\n", $this->db->error);

        return false;
    }

    // Get all users
    public function all()
    {
        $this->db->query('SELECT * FROM ' . $this->table);

        $rows = $this->db->getRecordSet();

        if ($this->db->rowCount() > 0) {
            return $rows;
        } else {
            return false;
        }
    }

    // Get number of users
    public function countRows()
    {
        $this->db->query('SELECT * FROM ' . $this->table);
        $this->db->execute();

        return $this->db->rowCount();
    }

    // Find user by email
    public function findUserByEmail($email_address)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE email_address = :email_address');

        // Bind value
        $this->db->bind(':email_address', $email_address);

        $row = $this->db->getSingleRecord();

        // Check row
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    // Find user by id
    public function get($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');

        // Bind value
        $this->db->bind(':id', $id);

        $row = $this->db->getSingleRecord();

        // Check row
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /* API CALLS */

    // Read all users
    public function read()
    {
        $this->db->query('SELECT * FROM ' . $this->table);
        $this->db->execute();

        if ($this->db->rowCount() > 0) {
            return $this->db->fetchAllAssoc();
        } else {
            return false;
        }
    }

    // Read single user
    public function readOne()
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id LIMIT 0,1');
        $this->db->bind(':id', $this->id);
        $this->db->execute();

        if ($this->db->rowCount() > 0) {
            $row = $this->db->fetchAssoc();

            $this->first_name    = $row['first_name'];
            $this->other_names   = $row['other_names'];
            $this->last_name     = $row['last_name'];
        } else {
            return 'User does not exist!';
        }
    }

    // Create User
    public function post()
    {
        $this->db->query('INSERT INTO ' . $this->table . ' (first_name, other_names, last_name) VALUES (:first_name, :other_names, :last_name)');

        // Sanitize data
        $this->first_name = ucwords(htmlspecialchars(strip_tags($this->first_name)));
        $this->other_names = ucwords(htmlspecialchars(strip_tags($this->other_names)));
        $this->last_name = ucwords(htmlspecialchars(strip_tags($this->last_name)));

        // Bind data
        $this->db->bind(':first_name', $this->first_name);
        $this->db->bind(':other_names', $this->other_names);
        $this->db->bind(':last_name', $this->last_name);

        // Execute query
        if ($this->db->execute()) {
            return true;
        }

        // Print error if occurs
        printf("Error: %s.\n", $this->db->error);

        return false;
    }

    // Update user
    public function put()
    {
        $this->db->query('UPDATE ' . $this->table . 'SET first_name = :first_name, other_names = :other_names, last_name = :last_name WHERE id = :id');

        // Sanitize data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->first_name = ucwords(htmlspecialchars(strip_tags($this->first_name)));
        $this->other_names = ucwords(htmlspecialchars(strip_tags($this->other_names)));
        $this->last_name = ucwords(htmlspecialchars(strip_tags($this->last_name)));

        // Bind data
        $this->db->bind(':id', $this->id);
        $this->db->bind(':first_name', $this->first_name);
        $this->db->bind(':other_names', $this->other_names);
        $this->db->bind(':last_name', $this->last_name);

        // Execute query
        if ($this->db->execute()) {
            return true;
        }

        // Print error if occurs
        printf("Error: %s.\n", $this->db->error);

        return false;
    }
}
