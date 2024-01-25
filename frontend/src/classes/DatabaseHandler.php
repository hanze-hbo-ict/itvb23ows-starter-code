<?php

namespace classes;

class DatabaseHandler
{
    private mysqli $conn;
    private string $hostname = 'localhost';
    private string $username = 'root';
    private string $password = 'root';
    private string $database = 'hive';

    /**
     * Restarts the Hive game by creating a new game instance in the database.
     *
     * @return int The ID of the newly created game instance.
     */
    public function restartGame(): int {
        $db = $this->getConnection();
        $stmt = $db->prepare("INSERT INTO games () VALUES ();");
        $stmt->execute();

        return $db->insert_id;
    }

    /**
     * Gets the database connection for the Hive game.
     *
     * @return mysqli The MySQLi database connection.
     */
    private function getConnection(): mysqli {
        if (!isset($this->conn)) {
            $conn = new mysqli($this->hostname, $this->username, $this->password, $this->database);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $this->conn = $conn;
        }
        return $this->conn;
    }
}