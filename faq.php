<?php
  session_start();
  include 'classes/dbh.php';
  include 'html/ban_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>DevilBin - FAQ</title>
<?php include 'html/head.html' ?>
</head>
<body>
    <?php include 'html/header.php' ?>
    <h1 class="text-center">DevilBin</h1>
    <h4 style="margin-left: 50px">Is this site inspired by Doxbin and/or Skidbin?</h4> <!-- question -->
    <h5 style="margin-left: 70px">Yes, it is.</h5> <!-- answer -->
    <h4 style="margin-left: 50px">Has any code been used from Doxbin in DevilBin?</h4> <!-- question -->
    <h5 style="margin-left: 70px">No, though this site does use some Doxbin CSS.</h5> <!-- answer -->
    <h4 style="margin-left: 50px">How do I delete/edit/private my pastes?</h4> <!-- question -->
    <h5 style="margin-left: 70px">Buy an upgrade <a href="/upgrade.php" target="_blank">here</a>.</h5> <!-- answer -->
    <h4 style="margin-left: 50px">What is DevilBin?</h4> <!-- question -->
    <h5 style="margin-left: 70px">DevilBin is a website for sharing text. You're free to share anything as long as it doesn't violate our ToS.</h5> <!-- answer -->
    <h4 style="margin-left: 50px">What information do you collect?</h4> <!-- question -->
    <h5 style="margin-left: 70px">We collect your IP address, user agent and referrer URL in our access logs which are cleared 3 hours.</h5> <!-- answer -->
    <h4 style="margin-left: 50px">How do I get my paste pinned?</h4> <!-- question -->
    <h5 style="margin-left: 70px">You can buy a sticky <a href="/sticky.php">here</a>.</h5> <!-- answer -->
    <h4 style="margin-left: 50px">I want to have something added/changed, where can I contact you?</h4> <!-- question -->
    <h5 style="margin-left: 70px">We're open to feature requests, please contact us <a href="/contact.php">here</a>.</h5> <!-- answer -->
</body>
</html>