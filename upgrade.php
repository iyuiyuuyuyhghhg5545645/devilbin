<?php
  session_start();
  include 'classes/dbh.php';
  include 'html/ban_check.php';
  /*if (!isset($_SESSION['useruid'])) {
    header("Location: error.php?status=prohibited");
    die();
  }*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>DevilBin - Upgrades</title>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 
<script src='https://www.hCaptcha.com/1/api.js' async defer></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- <script src="/assets/js/snow.js"></script> christmas is over >:) -->
<link rel="stylesheet" href="/css/uprgrade.css">
</head>
<body>
  	<?php include 'html/header.php' ?>
  	<h1 class="text-center">Upgrades</h1>
  	<div class="text-center" style="color: white; font-size: 18px;">
		<p>Usename preview: <span class="val" style="color: #d90429;font-weight:bold;"><span class="devious">[Devious] Anonymous</span></span></p>
		<p>Paste highlight color: <span class="val" style="color: #d90429;">Red</span></p>
		<p>More noticeable: Yes</p>
		<p>Private your own pastes: Yes</p>
		<p>Delete your own comments: Yes</p>
		<p>Ability to change your username: Yes</p>
	</div>
	<div class="container text-center">
		<a href="/includes/buyrank.inc.php" type="button" class="btn btn-success">Purchase with Bitcoin (10$)</a>
	</div>
</body>
</html>