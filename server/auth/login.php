<?php
session_start();

include '../classes/class.users.php';

$password = $_POST['password'];
$email = $_POST['email'];


$user = new Users();
$user->login($email,$password);
