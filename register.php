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
<title>DevilBin - Register</title>
<?php include 'html/head.html' ?>
</head>
<body>
    <?php include 'html/header.php' ?>
    <div class="text-center">
        <h1>Register</h1>
        <p>Please use a randomly generated password.</p>
    </div>
    <div class='reglog-form'>
        <form action="includes/signup.inc.php" method="post">
            <label for="username">Username</label>
            <br>
            <input type="text" name="uid" placeholder="Username">
            <label for="email">Email/XMPP</label>
            <br>
            <input type="text" name="email" placeholder="Email/XMPP">
            <label for="pwd">Password</label>
            <br>
            <input type="password" name="pwd" placeholder="Password">
            <br>
            <label for="confirmpwd">Repeat Password</label>
            <br>
            <input type="password" name="confirmpwd" placeholder="Repeat Password">
            <br>
            <div class="h-captcha" data-sitekey="51a73684-2376-48f4-814d-300916b065df"></div>
            <input type="submit" name="submit" value="Register" >
            <?php
                if ($_GET["error"] == "captchafail") {
                    echo '<p style="color: red;">Captcha not solved.</p>';
                }
            ?>
            <p>Please make sure your username does not contain any special characters or spaces.</p>
            <p>We do no validation on emails/XMPP addresses, so feel free to enter whatever you want.</p>
        </form>
    </div>
</body>
</html>