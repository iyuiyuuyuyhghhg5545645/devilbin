<?php
    session_start();

    include 'classes/dbh.php';
    include 'html/ban_check.php';

    if (isset($_GET['uid'])) {
        if (isset($_SESSION['useruid'])){
            $id = $_GET['uid'];
            $stmt = $dbh->prepare("SELECT * FROM users WHERE users_id=:id");
            $stmt->execute(['id' => strip_tags($id)]);
            $user = $stmt->fetch();
        }
        else {
            header("Location: error.php?status=Prohibited");
            die();
        }
        if (empty(strip_tags($user['users_uid']))) { // messy way of doing it, but it works :)
            header("Location: error.php?status=User not found");
            die();
        }
    }
    else {
        header("Location: error.php?status=Missing parameter");
        die();
    }
?>

<?php
    if ($_SESSION["useruid"] == strip_tags($user['users_uid']) || $_SESSION["rank"] == 1 || $_SESSION['rank'] == 4) {
        if ($_POST) { // I should probably check if submit has been submitted.. Oh well!
            $bioText = htmlentities($_POST['bio']);
            if ($_SESSION["useruid"] == strip_tags($user['users_uid']) || $_SESSION["rank"] == 1 || $_SESSION['rank'] == 4) {
                if (strlen($bioText) > 50) {
                    header("Location: settings.php?status=toolong");
                    die();
                } 
                $sql = "UPDATE users SET bio = :biotxt WHERE users_id=:id"; 
                $result = $dbh->prepare($sql);
                    $values = array(':biotxt'           => $bioText,
                                    ':id'               => $id                        
                    );
                    $res = $result->execute($values);
                header('Location: settings.php?uid='.strip_tags($user['users_id']).'', true, 303);
            }
        }
    }
    else {
        header("Location: error.php?status=Prohibited");
        die();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>DevilBin - Settings</title>
<?php include 'html/head.html' ?>
</head>
<body>
    <?php include 'html/header.php' ?>
    <div>
      <h2 class="text-center" style="color: white;">Editing <b><?php echo strip_tags($user['users_uid']); ?></b></h2>
      <img class="img-center" style="margin-bottom:12px" src="<?php echo strip_tags($user['profileimg']) ?>" width="120px" height="120px">
      <div class='reglog-form'>
        <form action="/includes/pfp.inc.php?uid=<?php echo strip_tags($user['users_id']) ?>" method="post" enctype="multipart/form-data">
            <label for="img">Profile Picture (Max: 2MB)</label>
            <br>
            <input type="file" name="img" id="img">
            <div class="h-captcha" data-sitekey="51a73684-2376-48f4-814d-300916b065df"></div>
            <input type="submit" name="submit" value="Change profile picture">
            <?php
                if ($_GET["status"] == "success") {
                    echo '<p style="color: green;">Profile picture set.</p>';
                }
                else if ($_GET["status"] == "pfpsize") {
                    echo '<p style="color: red;">Profile picture must be under 2MB.</p>';
                }
                else if ($_GET["status"] == "pfperror") {
                    echo '<p style="color: red;">There was an error uploading your profile picture.</p>';
                }
                else if ($_GET["status"] == "pfpext") {
                    echo '<p style="color: red;">File must be a .jpg, .jpeg,. .gif or .png</p>';
                }
                if ($_GET["status"] == "captchafail") {
                    echo '<p style="color: red;">Captcha not solved.</p>';
                }
            ?>
        </form>
    </div>
    <hr width="500px">
      <div class='reglog-form'>
        <form action="" method="post">
            <label for="bio">Bio (Max: 50 characters)</label>
            <br>
            <input type="text" name="bio" value="<?php echo htmlspecialchars_decode($user['bio']) ?>">
            <input type="submit" name="submit" value="Edit bio">
            <?php
                if ($_GET["status"] == "toolong") {
                        echo '<p style="color: red;">Bio must be under 50 characters >:(</p>';
                }
            ?>
        </form>
    </div>
    <hr width="500px">
    <p class="text-center">You must be a Devious user to change your username.</p>
      <div class='reglog-form'>
        <form action="/includes/changeusr.inc.php?uid=<?php echo strip_tags($user['users_id']) ?>" method="post">
            <label for="username">New Username</label>
            <br>
            <input type="text" name="newusername" value="<?php echo htmlspecialchars_decode($user['users_uid']) ?>">
            <input type="submit" name="submit" value="Change username" <?php if ($_SESSION['rank'] == 0 || $_SESSION['rank'] == 3) { echo 'disabled'; } ?>>
            <?php
                if ($_GET["status"] == "toolongusr") {
                    echo '<p style="color: red;">Username must be under 15 characters >:(</p>';
                }
            ?>
        </form>
    </div>
    </div>
</body>
</html>