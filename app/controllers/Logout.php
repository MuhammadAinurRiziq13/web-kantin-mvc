<?php
class Logout {
	public function index () {
		session_destroy();
		header('location:Login.php');
		exit;
	}
}
