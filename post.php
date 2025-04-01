<?php
  session_start();

  include 'classes/dbh.php';
  include 'html/ban_check.php';

  /*if (!isset($_SESSION['useruid'])) {
    header('Location: error.php?status=accountneeded');
    exit();
  }*/

  /*if (!isset($_SESSION['useruid'])) {
    echo 'fuck off pedo<br>';
    echo 'Anonymous posting has been disabled due to people posting CSAM links.';
    die();
  }*/

  if ($_POST) {
    $data = array (
      'secret' => "meow",
      'response' => $_POST['h-captcha-response']
    );

    $verify = curl_init();
    curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
    curl_setopt($verify, CURLOPT_POST, true);
    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($verify);
    $responseData = json_decode($response);
    if($responseData->success) {
      $pasteID = 'Untitled Paste '.uniqid();
      $dbID = rand(0,99999);
      //$pasteTitle = htmlentities($_POST['pasteTitle']);
      function RemoveSpecialChar($str){
        $res = preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$str);
        return $res;
      }
      $pasteTitle = RemoveSpecialChar($_POST['pasteTitle']);
      $contents = htmlentities($_POST['pasteContents']);
      //$contents = $_POST['pasteContents'];
      $username = $_SESSION['useruid'];
      if (isset($_SESSION['useruid'])) {
        $uib = $_SESSION['userid'];
      }
      else {
        $uib = "Anonymous";
      }
  
      if (!isset($_SESSION['useruid'])) {
        $username = "Anonymous-".rand(0,99999);
      }
  
      if (empty($_POST['pasteContents'])) {
        header("Location: /error.php?status=Paste is empty");
        die();
      }

      if (strlen($_POST['pasteContents']) < 50) {
        header("Location: /error.php?status=Paste is too short");
        die();
      }
  
      if (strlen($_POST['pasteTitle']) > 30) {
        header("Location: /error.php?status=Paste title is too long");
        die();
      }
  
      if (strlen($_POST['pasteTitle'] > 0)) {
        if(file_exists("pastes/".$pasteTitle.'.txt')) {
          header("Location: error.php?status=Paste title is already taken");
          die();
        }  
        $paste = fopen("pastes/".$pasteTitle.".txt", "w");
        chmod("pastes/".$pasteTitle.".txt", 0644); // marks paste as rw-r--r--
        header("Location: viewpaste.php?id=".$dbID);
      }
  
      if (!strlen($_POST['pasteTitle'] > 0)) {
        $paste = fopen("pastes/".$pasteID.".txt", "w");
        $pasteTitle = $pasteID;
        chmod("pastes/".$pasteID.".txt", 0644); // marks paste as rw-r--r--
        header("Location: viewpaste.php?id=".$dbID);
      }
  
      if (isset($_POST['privateCheckbox'])) {
        if ($_SESSION['rank'] == 2 || $_SESSION['rank'] == 1 || $_SESSION['rank'] == 4) {
          $sql = "INSERT INTO doxes (id, username, title, uid, private, pinned) VALUES (:id, :username, :title, :uib, :private, :pinned)"; 
          $result = $dbh->prepare($sql);
              $values = array(':id'           => $dbID,
                              ':username'     => $username,
                              ':title'        => $pasteTitle,
                              ':uib'          => $uib,
                              ':private'      => 1,
                              ':pinned'       => 0
                              );
              $res = $result->execute($values);
  
          $sql = "UPDATE users SET pastes = pastes + 1 WHERE users_id=:id"; 
          $result = $dbh->prepare($sql);
              $values = array(':id'           => $uib);
              $res = $result->execute($values);
        }
      }
      else {
        $sql = "INSERT INTO doxes (id, username, title, uid, private, pinned) VALUES (:id, :username, :title, :uib, :private, :pinned)"; 
        $result = $dbh->prepare($sql);
            $values = array(':id'           => $dbID,
                            ':username'     => $username,
                            ':title'        => $pasteTitle,
                            ':uib'          => $uib,
                            ':private'      => 0,
                            ':pinned'       => 0
                            );
            $res = $result->execute($values);
        if (isset($_SESSION['useruid'])) {
          $sql = "UPDATE users SET pastes = pastes + 1 WHERE users_id=:id"; 
          $result = $dbh->prepare($sql);
              $values = array(':id'           => $uib);
              $res = $result->execute($values);
        }
      }
      fwrite($paste, $contents);
      fclose($paste);
    } 
    else {
        header("Location: ../error.php?status=You did not solve the captcha.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>DevilBin - New Paste</title>
<?php include 'html/head.html' ?>
</head>
<body class="past">
  <form action="" method="post">
      <div class="bin-buttons">
        <p>Please do not include quotation marks in your title.</p>
        <input type="text" name="pasteTitle" placeholder="Paste Title (leave empty for random)">
        <div class="h-captcha" data-sitekey="51a73684-2376-48f4-814d-300916b065df"></div>
        <input type="submit" name="submit" value="Submit Paste" >
        <?php
          if ($_SESSION["rank"] == 2 || $_SESSION["rank"] == 1 || $_SESSION["rank"] == 4) {
            echo '
            <label for="privateCheckbox">Private paste: </label>
            <input type="checkbox" name="privateCheckbox" value="value1">
            ';
          }
        ?>
      </div>
      <textarea class="bin-text" name="pasteContents" placeholder="READ THE TERMS OF SERVICE BEFORE CREATING A PASTE!"></textarea>
  </form>
</body>
</html>