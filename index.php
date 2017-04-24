<?php 
include('connect.php');
session_start();
date_default_timezone_set('Asia/Almaty');
$URL_Path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$URL_Parts = explode('/', trim($URL_Path, ' /'));
$url = array_shift($URL_Parts);
if(!isset($_COOKIE['view']) or !isset($_SESSION['loginID'])) setcookie("view", "login");
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>Регистрация</title>
		<meta name="generator" content="Bootply" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/datatables.min.css" rel="stylesheet">
		<link href="css/styles.css" rel="stylesheet">
        <script src="js/jquery.js"></script>
	</head>
	<body>
<?php
include('views/head/header.php');
?>
<div class="container">
	<div class="no-gutter row">
	<?php
    if(isset($_COOKIE['view']) and isset($_SESSION['loginID'])){
        switch ($_COOKIE['view']){
            case 'login': include('./views/login/login.php');break;
            case 'vpz': include('./views/vpz/vpz.php');break;
        }
    }else{
        include './views/login/login.php';
    }
	?>
	</div>
</div>

</body>
<script src="js/bootstrap.min.js"></script>
<script src="js/datatables.min.js"></script>
<script src="js/func.js"></script>
</html>