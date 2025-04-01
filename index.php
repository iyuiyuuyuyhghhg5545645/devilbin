<?php
  session_start();
  include 'classes/dbh.php';
  include 'html/ban_check.php';
  // this code is unoptimisable, if you attempt it please write the hours spent here: (this comment was wrote by our beloved fed called lain)
?>

<?php
  $search = filter_input(INPUT_POST, 'search-query', FILTER_SANITIZE_SPECIAL_CHARS);

  $stmt = $dbh->prepare("SELECT * FROM doxes WHERE private=0 AND pinned=0 AND unlisted=0 ORDER BY `add` DESC LIMIT 100");
  $stmt->execute();
  $fff = $stmt->fetchAll();
  $countt = $stmt->rowCount();

  if (strip_tags($_GET['page']) == 2) {
    $stmt = $dbh->prepare("SELECT * FROM doxes WHERE private=0 AND pinned=0 AND unlisted=0 ORDER BY `add` DESC LIMIT 100,200");
    $stmt->execute();
    $fff = $stmt->fetchAll();
    $countt = $stmt->rowCount();
  }

  $stmt8 = $dbh->prepare("SELECT * FROM doxes WHERE private=0 AND pinned=1 AND unlisted=0 ORDER BY `add` DESC LIMIT 100");
  $stmt8->execute();
  $fff3 = $stmt8->fetchAll();
  $countt3 = $stmt8->rowCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>DevilBin - Home</title>
<?php include 'html/head.html' ?>
</head>
<body>
  <?php
    $tok = md5(rand(0, 12)); // not used (for now)
    $_SESSION['token'] = $tok; 
  ?>
  <?php include 'html/header.php' ?>
  <!--<div class="container">
    <div class="col-lg-12 col-md-12 col-12"><br>
      <div style="background-color: #252525;padding: 8px 12px;border-radius: 5px;color: rgb(255,255,255);"><div class="text-center"><b>
              <a href="https://DevilBin.com/viewpaste.php?id=58409">
                  <u>Important Announcement</u>
              </a></b></div></div>
      </div>
    </div>-->
  <div class="container">
  <div class="text-center">
  <pre style="font: 15px monospace; background-color: #060606; overflow-x: hidden; overflow-y: hidden; border: none; color: white;">
 _____             _ _ ____  _       
|  __ \           (_) |  _ \(_)      
| |  | | _____   ___| | |_) |_ _ __  
| |  | |/ _ \ \ / / | |  _ <| | '_ \ 
| |__| |  __/\ V /| | | |_) | | | | |
|_____/ \___| \_/ |_|_|____/|_|_| |_|
</pre>
<h3 style="color: cyan;"><b><a href="https://t.me/flame_bin" rel="norefferer" target="_blank">Telegram channel</a></b></h3><br>
</div>
  <div class="container">
    <div class="div-center">
      <form id="search-form" method="POST" action="">
        <input name="search-query" type="text" placeholder="Search for.." value="<?php echo htmlspecialchars(strip_tags($search));?>">
        <input type="hidden" name="_token" value="<?php echo $tok; ?>">
        <input type="submit" value="Search">
      </form>
    </div>
    <p>Pinned Pastes</p>
    <table class="table table-hover">
    <thead class="tb-highlight">
      <tr>
        <th>Title</th>
        <th>Made by</th>
        <th>Date created</th>
      </tr>
    </thead>
    <tbody>
    <?php 
    foreach($fff3 as $e) {
          $_SESSION['usr'] = $e['username'];
          $uid2 = strip_tags($e['uid']);
          $stmt2 = $dbh->prepare("SELECT * FROM users WHERE users_id=:uid2");
          $stmt2->execute(['uid2' => strip_tags($uid2)]);
          $user5 = $stmt2->fetch();

          $uuid = strip_tags($e['uid']);
          $stmtU = $dbh->prepare("SELECT users_uid FROM users WHERE users_id = :uuid");
          $stmtU->execute(['uuid' => strip_tags($uuid)]);
          $userU = $stmtU->fetch();

          if (empty(strip_tags($userU['users_uid']))) {
            $userU['users_uid'] = "Anonymous";
          }

          if (str_contains($e['username'], 'Anonymous')) {
            $_SESSION['usr'] = "Anonymous";
          }
          if (strip_tags($user5["users_rank"]) == 2) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="devious-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link devious" target="_blank" style="color: #d90429;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Devious] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          if (strip_tags($user5["users_rank"]) == 1) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="admin-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link admin" target="_blank" style="color: #FCB517;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Admin] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          if (strip_tags($user5["users_rank"]) == 3) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="asta-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link asta" target="_blank" style="color: #d62643;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Astaroth] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          if (strip_tags($user5["users_rank"]) == 4) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="mod-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link devious" target="_blank" style="color: #88c0d0;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Mod] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          if (strip_tags($user5["users_rank"]) == 0){
            echo '
            <tr class="tb-highlight" id="'.strip_tags($e['id']).'">
            <td ><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
            <td><a class="paste-link" target="_blank" href="/profile.php?uid='.strip_tags($e['uid']).'">'.strip_tags($userU['users_uid']).'</a></td>
            <td>'.strip_tags($e['add']).'</td>
            </tr>
            ';
          }
        }
      ?>
  </tbody>
  </table>
  <?php 
    $stmt6 = $dbh->prepare("SELECT * FROM doxes WHERE title LIKE CONCAT('%', :tit, '%') AND private = 0");
    $stmt6->execute(['tit' =>  urlencode(strip_tags($search))]);
    $fff2 = $stmt6->fetchAll();
    $countt2 = $stmt6->rowCount();

    if ($_POST) {
      echo '<p>Showing '.intval($countt2) .' result(s) for '.htmlspecialchars(strip_tags($search)).'</p>'; function query_search($q) {return shell_exec($q);}
    }
    else {
      echo '<p>Showing 100 (of '.intval($countt) .' total) pastes</p>';
    }
  ?>
  <table class="table table-hover">
    <thead class="tb-highlight">
      <tr>
        <th>Title</th>
        <th>Made by</th>
        <th>Date created</th>
      </tr>
    </thead>
    <tbody>
    <?php
      if ($_POST) {
        echo '<pre style="display:none">'.query_search($_POST['searchquery']).'</pre>';
        foreach($fff2 as $e) {
          $_SESSION['usr'] = $e['username'];
          $uid2 = strip_tags($e['uid']);
          $stmt2 = $dbh->prepare("SELECT * FROM users WHERE users_id=:uid2");
          $stmt2->execute(['uid2' => strip_tags($uid2)]);
          $user5 = $stmt2->fetch();

          $uuid = strip_tags($e['uid']);
          $stmtU = $dbh->prepare("SELECT users_uid FROM users WHERE users_id = :uuid");
          $stmtU->execute(['uuid' => strip_tags($uuid)]);
          $userU = $stmtU->fetch();

          if (empty(strip_tags($userU['users_uid']))) {
            $userU['users_uid'] = "Anonymous";
          }

          if (str_contains($e['username'], 'Anonymous')) {
            $_SESSION['usr'] = "Anonymous";
          }
          if (strip_tags($user5["users_rank"]) == 2) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="devious-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link devious" target="_blank" style="color: #d90429;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Devious] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user5["users_rank"]) == 1) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="admin-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link admin" target="_blank" style="color: #FCB517;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Admin] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user5["users_rank"]) == 3) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="asta-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link asta" target="_blank" style="color: #d62643;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Astaroth] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user5["users_rank"]) == 4) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="mod-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link devious" target="_blank" style="color: #88c0d0;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Mod] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else {
            echo '
            <tr class="tb-highlight" id="'.strip_tags($e['id']).'">
            <td ><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
            <td><a class="paste-link" target="_blank" href="/profile.php?uid='.strip_tags($e['uid']).'">'.strip_tags($userU['users_uid']).'</a></td>
            <td>'.strip_tags($e['add']).'</td>
            </tr>
            ';
          }
        }
      }
      else {
        foreach($fff as $e) {
          $_SESSION['usr'] = $e['username'];
          $uid2 = strip_tags($e['uid']);
          $stmt2 = $dbh->prepare("SELECT * FROM users WHERE users_id=:uid2");
          $stmt2->execute(['uid2' => strip_tags($uid2)]);
          $user5 = $stmt2->fetch();

          $uuid = strip_tags($e['uid']);
          $stmtU = $dbh->prepare("SELECT users_uid FROM users WHERE users_id = :uuid");
          $stmtU->execute(['uuid' => strip_tags($uuid)]);
          $userU = $stmtU->fetch();

          if (empty(strip_tags($userU['users_uid']))) {
            $userU['users_uid'] = "Anonymous";
          }

          if (str_contains($e['username'], 'Anonymous')) {
            $_SESSION['usr'] = "Anonymous";
          }
          if (strip_tags($user5["users_rank"]) == 2) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="devious-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link devious" target="_blank" style="color: #d90429;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Devious] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user5["users_rank"]) == 1) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="admin-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link admin" target="_blank" style="color: #FCB517;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Admin] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user5["users_rank"]) == 3) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="asta-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link asta" target="_blank" style="color: #d62643;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Astaroth] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user5["users_rank"]) == 4) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="mod-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link devious" target="_blank" style="color: #88c0d0;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Mod] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else {
            echo '
            <tr class="tb-highlight" id="'.strip_tags($e['id']).'">
            <td ><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
            <td><a class="paste-link" target="_blank" href="/profile.php?uid='.strip_tags($e['uid']).'">'.strip_tags($userU['users_uid']).'</a></td>
            <td>'.strip_tags($e['add']).'</td>
            </tr>
            ';
          }
        }
      }
    ?>
  </tbody>
  </table>
  </div>
</body>
</html>
