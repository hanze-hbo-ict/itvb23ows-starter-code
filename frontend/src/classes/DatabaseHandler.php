<?php

namespace classes;

class DatabaseHandler
{
    private mysqli $conn;
    private string $hostname = 'localhost';
    private string $username = 'root';
    private string $password = 'root';
    private string $database = 'hive';
}