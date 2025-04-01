<?php
  session_start();

  include 'classes/dbh.php';
  include 'html/ban_check.php';

  // everything below here is super messy so i wouldn't be surprised if there's some sort of vuln.

  if (!$_SESSION["rank"] == 1 || !$_SESSION["rank"] == 2 || !$_SESSION["rank"] == 4) {
    header("Location: error.php?status=Prohibited");
    die();
  }

  if (isset($_GET['pid'])){
    if(!intval($_GET['pid'])){
      header("Location: error.php?status=Paste not found");
      die();
    }
    $id = $_GET['pid'];
    $stmt = $dbh->prepare("SELECT * FROM doxes WHERE id=:id");
    $stmt->execute(['id' => strip_tags($id)]);
    $user = $stmt->fetch();
    $uib = strip_tags($user['uid']);
    if ($_SESSION["rank"] == 2 || $_SESSION["rank"] == 1 || $_SESSION["rank"] == 1 || $_SESSION["rank"] == 4) {
      if ($_SESSION["useruid"] == strip_tags($user['username']) || $_SESSION["rank"] == 1 || $_SESSION["rank"] == 4) {

      $stmt = $dbh->prepare("DELETE FROM doxes WHERE id=:id"); // delete dox from database
      $stmt->execute(['id' => strip_tags($id)]);

      $stmtCom = $dbh->prepare("DELETE FROM comments WHERE com_paste=:id"); // delete all comments on the dox
      $stmtCom->execute(['id' => strip_tags($id)]);

      // delete dox from file system

      $pasteDelete = 'pastes/'.strip_tags($user["title"]).'.txt';

      $sql = "UPDATE users SET pastes = pastes - 1 WHERE users_id=:id"; 
      $result = $dbh->prepare($sql);
          $values = array(':id'           => $uib);
          $res = $result->execute($values);
      }
      else {
        header("Location: error.php?status=Prohibited");
        die();
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>DevilBin - Deletion Panel</title>
<?php include 'html/head.html' ?>
</head>
<body>
    <div class="bin-buttons">
    </div>
    <div class="bin-text">
        <?php
            if ($_SESSION["rank"] == 2 || $_SESSION["rank"] == 1 || $_SESSION["rank"] == 4) {
              if ($_SESSION["useruid"] == strip_tags($user['username']) || $_SESSION["rank"] == 1 || $_SESSION["rank"] == 4) {
                if (!unlink($pasteDelete)) {
                  echo '<p>Paste deletion failed. '.$pasteDelete.'</p>';
                  echo '<a href="index.php">Homepage</a>';
                }
                else {
                    echo '<p>Paste deleted. '.$pasteDelete.'</p>';
                    echo '<a href="index.php">Homepage</a>';
                }
              }
              else {
                header("Location: error.php?status=Prohibited");
                die();
              }
            }
        ?>
    </div>
</body>
</html>