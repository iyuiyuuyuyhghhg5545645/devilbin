<?php
  session_start();

  include 'classes/dbh.php';
  include 'html/ban_check.php';

  if (isset($_SESSION['useruid'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>DevilBin - Login</title>
<?php include 'html/head.html' ?>
</head>
<body>
    <?php include 'html/header.php' ?>
    <div class="text-center">
        <h1>Login</h1>
        <p>meow</p>
    </div>
    <div class='reglog-form'>
        <form action="includes/login.inc.php" method="post">
            <label for="username">Username</label>
            <br>
            <input type="text" name="uid" placeholder="Username">
            <label for="pwd">Password</label>
            <br>
            <input type="password" name="pwd" placeholder="Password">
            <br>
            <input type="submit" name="submit" value="Login">
            <?php
                if ($_GET["status"] == "success") {
                    echo '<p style="color: green;">Account registered, please log in.</p>';
                }
                if ($_GET["status"] == "banned") {
                    echo '<p style="color: red;">You have been banned from DevilBin.</p>';
                }
            ?>
        </form>
    </div>
</body>
</html>