<?php

namespace Vanier\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EmissionsController {
    private $conn;

    // Constructor - establishes connection to database
    public function __construct($host, $username, $password, $dbname) {
        $this->conn = new mysqli($host, $username, $password, $dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Destructor - closes connection to database
    public function __destruct() {
        $this->conn->close();
    }

    // Function to get all emissions
    public function getAllEmissions() {
        $sql = "SELECT * FROM emissions";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $emissions = array();
            while ($row = $result->fetch_assoc()) {
                $emissions[] = $row;
            }
            return $emissions;
        } else {
            return null;
        }
    }

    // Function to get emissions by id
    public function getEmissionsById($id) {
        $sql = "SELECT * FROM emissions WHERE id = $id";
        $result = $this->conn->query($sql);

        if ($result->num_rows == 1) {
            $emissions = $result->fetch_assoc();
            return $emissions;
        } else {
            return null;
        }
    }

    // Function to add new emissions
    public function addEmissions($type, $value) {
        $sql = "INSERT INTO emissions (type, value) VALUES ('$type', '$value')";

        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update emissions
    public function updateEmissions($id, $type, $value) {
        $sql = "UPDATE emissions SET type='$type', value='$value' WHERE id=$id";

        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    // Function to delete emissions
    public function deleteEmissions($id) {
        $sql = "DELETE FROM emissions WHERE id=$id";

        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
}
