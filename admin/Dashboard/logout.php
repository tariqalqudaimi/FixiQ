<?php 
	session_start();
	session_destroy();
	header('location: ../Dashboard/logout_page.php');


?>