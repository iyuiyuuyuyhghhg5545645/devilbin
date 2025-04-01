<?php
    session_start();

    include 'classes/dbh.php';

    include 'html/ban_check.php';

    if (isset($_GET['uid'])){
      $id = $_GET['uid'];
      $stmt = $dbh->prepare("SELECT * FROM users WHERE users_id=:id");
      $stmt->execute(['id' => strip_tags($id)]);
      $user = $stmt->fetch();

      $stmt = $dbh->prepare("SELECT * FROM doxes WHERE unlisted = 0 ORDER BY `add` DESC LIMIT 100");
      $stmt->execute();
      $fff = $stmt->fetchAll();
      $countt = $stmt->rowCount();

      $stmtUnlis = $dbh->prepare("SELECT * FROM doxes WHERE unlisted = 1 ORDER BY `add` DESC LIMIT 100");
      $stmtUnlis->execute();
      $fffUnlis = $stmtUnlis->fetchAll();
      $counttUnlis = $stmtUnlis->rowCount();

      if (empty(strip_tags($user['users_uid']))) { // messy way of doing it, but it works :)
        header("Location: error.php?status=User not found");
        die();
      }
    }
    else {
        header("Location: error.php?status=Missing parameter.");
        die();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>DevilBin -  <?php echo strip_tags($user['users_uid']); ?>'s profile</title>
<?php include 'html/head.html' ?>
<?php
  if ($user['users_id'] == 1) {
    echo '
    <style>
    body {
      background-image: linear-gradient(rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.65)), url(https://i.imgur.com/W4yq61w.png);
    }
    </style>';
  }
  if ($user['users_id'] == 9) {
    echo '
    <style>
    body {
      background-image: linear-gradient(rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.3)), url(https://files.doxbin.gg/ZVpK1dFv.png);
    }
    </style>';
  }
  if ($user['users_id'] == 26) {
    echo '
    <style>
    body {
      background-image: linear-gradient(rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.65)), url(https://pomf2.lain.la/f/z9rncv9q.jpeg);
    }
    </style>';
  }
?>
</head>
<body>
    <?php include 'html/header.php' ?>
    <div>
      <div class="container">
      <div class="panel panel-default">
      <div class="panel-heading" style="background-color: #0D0D0D;">
      <?php // ik I could just use DB columns instead to echo the styled username but i CBA
            if (strip_tags($user['banned']) == 1) {
              echo '<p>Profile of <span class="" style="color: #808080 ;font-weight:bold;text-decoration:line-through;">[Banned] '.strip_tags($user['users_uid']).'</span></p>';
            }
            else if (strip_tags($user['users_rank']) == 2) {
              echo '<p>Profile of <span class="devious" style="color: #d90429;font-weight:bold;">[Devious] '.strip_tags($user['users_uid']).'</span></p>';
            }
            else if (strip_tags($user['users_rank']) == 1) {
              echo '<p>Profile of <span class="admin" style="color: #FCB517;font-weight:bold;">[Admin] '.strip_tags($user['users_uid']).'</span></p>';
            }
            else if (strip_tags($user['users_rank']) == 3) {
              echo '<p>Profile of <span class="asta" style="color: #d62643;font-weight:bold;">[Astaroth] '.strip_tags($user['users_uid']).'</span></p>';
            }
            else if (strip_tags($user['users_rank']) == 4) {
              echo '<p>Profile of <span class="devious" style="color: #88c0d0;font-weight:bold;">[Mod] '.strip_tags($user['users_uid']).'</span></p>';
            }
            else {
              echo '<p>Profile of <span class="" style="font-weight:bold;">'.strip_tags($user['users_uid']).'</span></p>';
            }
      ?>
      </div>
      <div class="panel-body">
        <div class="panel-profile">
          <img style="margin-bottom:12px" src="<?php echo strip_tags($user['profileimg']) ?>" width="120px" height="120px">
          <?php
          if (strip_tags($user['banned']) == 0) {
            echo '<p>'.strip_tags($user['bio']).'</p>';
          }
          else {
            echo '<p><b>Ban reason:</b> '.strip_tags($user['ban_reason']).'</p>';
            echo '<p><b>Banned by:</b> '.strip_tags($user['banned_by']).'</p>';
          }
          ?>
        </div>
      <div class="panel-information">
        <p><b>User ID: </b><?php echo strip_tags($user['users_id']) ?></p>
        <p><b>Account created: </b><?php echo strip_tags($user['joined']) ?></p>
        <p>
          <?php 
              if (strip_tags($user['banned']) == 1) {
                echo "<b>Rank:</b> Banned";
              }
              else if (strip_tags($user['users_rank']) == 1) {
                echo "<b>Rank:</b> Administrator";
              }
              else if (strip_tags($user['users_rank']) == 0) {
                echo "<b>Rank:</b> User";
              }
              else if (strip_tags($user['users_rank']) == 2) {
                echo "<b>Rank:</b> Devious";
              }
              else if (strip_tags($user['users_rank']) == 3) {
                echo "<b>Rank:</b> Astaroth";
              }
              else if (strip_tags($user['users_rank']) == 4) {
                echo "<b>Rank:</b> Moderator";
              }
          ?>
        </p>

        <?php 
          if ($_SESSION["rank"] == 1) {
            echo '<p><b>Email:</b> '.strip_tags($user["users_email"]).' (only admins can view this, mods cannot)</p>';
          }
          if ($_SESSION["rank"] == 1 || $_SESSION["rank"] == 4) {
            echo '<p><b></b> <a class="link" href="deleteac.php?uid='.strip_tags($user["users_id"]).'">Ban/unban user</a>';
          }
          if (strip_tags($user['users_id']) == $_SESSION['userid'] || $_SESSION["rank"] == 1 || $_SESSION['rank'] == 4) {
            echo ' 
            <p><b></b><a class="link" href="settings.php?uid='.strip_tags($user['users_id']).'">Edit account information</a></p>';
          }
      ?>
      </div>
      </div>
        </div>
        </div>
        <div class="container">
          <b><p><?php echo strip_tags($user['users_uid']) ?>'s pastes</p></b>
          <table class="table table-hover">
          <thead class="tb-highlight">
            <tr>
              <th>Title</th>
              <th>Date created</th>
            </tr>
          </thead>
          <tbody>
          <?php
            foreach($fff as $e) {
              if (strip_tags($user["users_id"]) == strip_tags($e['uid'])) {
                if (!strip_tags($e['private']) == 1) {
                  echo '
                  <tr class="tb-highlight" id="'.strip_tags($e['id']).'">
                  <td><a class="paste-link" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
                  <td>'.strip_tags($e['add']).'</td>
                  <tr>
                  ';
                }
              }
            }
          ?>
          </tbody>
          </table>
          <?php
            if (strip_tags($user["users_id"]) == strip_tags($_SESSION['userid'])) {
              echo '
              <b><p>Your private pastes</p></b>
              <table class="table">
              <thead class="tb-highlight">
                <tr>
                  <th>Title</th>
                  <th>Date created</th>
                </tr>
              </thead>
              <tbody>
              ';
            }
            foreach($fff as $e) {
              if (strip_tags($user["users_id"]) == strip_tags($e['uid']) && $_SESSION["userid"] == strip_tags($e['uid'])) {
                if (strip_tags($e['private']) == 1) {
                  echo '
                  <tr class="tb-highlight" id="'.strip_tags($e['id']).'">
                  <td><a class="paste-link" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
                  <td>'.strip_tags($e['add']).'</td>
                  <tr>
                  ';
                }
              }
            }
            if (strip_tags($user["users_id"]) == strip_tags($_SESSION['userid'])) {
              echo '
              </tbody>
              </table>
              ';
            }

            // UNLISTED

            if (strip_tags($user["users_id"]) == strip_tags($_SESSION['userid'])) {
              echo '
              <b><p>Your unlisted pastes</p></b>
              <table class="table">
              <thead class="tb-highlight">
                <tr>
                  <th>Title</th>
                  <th>Date created</th>
                </tr>
              </thead>
              <tbody>
              ';
            }
            foreach($fffUnlis as $eU) {
              if (strip_tags($user["users_id"]) == strip_tags($eU['uid']) && $_SESSION["userid"] == strip_tags($eU['uid'])) {
                if (strip_tags($eU['unlisted']) == 1) {
                  echo '
                  <tr class="tb-highlight" id="'.strip_tags($eU['id']).'">
                  <td><a class="paste-link" title="'.strip_tags($eU['title']).'" href="/viewpaste.php?id='.strip_tags($eU['id']).'">'.strip_tags($eU['title']).'</a></td>
                  <td>'.strip_tags($eU['add']).'</td>
                  <tr>
                  ';
                }
              }
            }
            if (strip_tags($user["users_id"]) == strip_tags($_SESSION['userid'])) {
              echo '
              </tbody>
              </table>
              ';
            }
            
          ?>

        </div>
    </div>
</body>
</html>