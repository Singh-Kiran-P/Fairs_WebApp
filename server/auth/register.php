<?php
session_start();

include '../classes/class.users.php';

$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$type = $_POST['type'];

$user = new Users();
$user->register($email,$password,$username,$type);

