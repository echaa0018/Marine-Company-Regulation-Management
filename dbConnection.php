<?php
// Database configuration with Railway environment variables fallback to local development
$databaseHost = getenv('MYSQLHOST') ?: 'localhost';
$databaseName = getenv('MYSQLDATABASE') ?: 'railway';
$databaseUsername = getenv('MYSQLUSER') ?: 'mysql.railway.internal';
$databasePassword = getenv('MYSQLPASSWORD') ?: 'LfCjtchKeuAfkDZQsNEMhRTmsOgopUGL';
$databasePort = getenv('MYSQLPORT') ?: '3306';

// Open a new connection to the MySQL server
$mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName, $databasePort); 
