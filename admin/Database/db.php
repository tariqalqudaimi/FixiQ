<?php
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASSWORD = '';
const DB_NAME     = 'wamdha'; 

$dbcon = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);

// mysqli_set_charset($dbcon, "utf8mb4");
if($dbcon->connect_error){
	die("Database connection failed".$dbcon->connect_error);
}
?>