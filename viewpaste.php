<?php
  session_start();

  include 'classes/dbh.php';
  include 'html/ban_check.php';

  if (isset($_GET['id'])){
    if(!intval($_GET['id'])){
        header("Location: error.php?status=Paste not found");
        die();
    }
    $id = $_GET['id'];
    $stmt = $dbh->prepare("SELECT * FROM doxes WHERE id=:id");
    $stmt->execute(['id' => strip_tags($id)]);
    $user = $stmt->fetch();

    $uid = strip_tags($user['uid']);
    $stmt2 = $dbh->prepare("SELECT * FROM users WHERE users_id=:uid");
    $stmt2->execute(['uid' => strip_tags($uid)]);
    $user5 = $stmt2->fetch();

    $stmtC = $dbh->prepare("SELECT * FROM comments WHERE com_paste=:id ORDER BY com_time DESC");
    $stmtC->execute(['id' => strip_tags($id)]);
    $fffC = $stmtC->fetchAll();
    $counttC = $stmtC->rowCount();
    
    if (strip_tags($user['private']) == 1) {
        if ($_SESSION["useruid"] != strip_tags($user['username'])) {
            header("Location: error.php?status=This paste is private");
            die();
        }
    }

    if ($_SESSION["hit_visted"] != strip_tags($user['id'])) {
        $sql = "UPDATE doxes SET hits = hits + 1 WHERE id=:id"; 
        $result = $dbh->prepare($sql);
            $values = array(':id'           => $id);
            $res = $result->execute($values);
            $_SESSION["hit_visted"] = strip_tags($user['id']);
    }

    if (empty(strip_tags($user['id']))) { // messy way of doing it, but it works :)
        header("Location: error.php?status=Paste not found");
        die();
      }
    }
    else {
        header("Location: error.php?status=Missing parameter");
        die();
    }
?>

<?php
    $comib = rand(0,99999);
    if (isset($_SESSION['useruid'])) {
        $usercom = $_SESSION['useruid'];
        $useruidcom = $_SESSION['userid'];
    }
    else {
        $usercom = "Anonymous";
        $useruidcom = 1361;
    }

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
            $sql = "INSERT INTO comments (com_author, com_text, com_id, com_paste, com_userid) VALUES (:username, :comtext, :comid, :compaste, :comuid)"; 
            $result = $dbh->prepare($sql);
                $values = array(':username'             => strip_tags($usercom),
                                ':comtext'              => htmlentities($_POST['comtextarea']),
                                ':comid'                => $comib,
                                ':compaste'             => strip_tags($user['id']),
                                ':comuid'               => strip_tags($useruidcom)
                                );
                $res = $result->execute($values);
            header('Location: viewpaste.php?id='.strip_tags($user['id']).'', true, 303);
        }
        else {
            header("Location: ../error.php?status=Captcha not solved");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>DevilBin - <?php echo strip_tags($user['title']); ?></title>
<?php include 'html/head.html' ?>
</head>
<body class="past">
    <a onclick="showHide()" href="#" class="sidebar-show"><i class="fa fa-arrow-right"></i></a>
    <script>
        function showHide() {
            var x = document.getElementById("bin-buttons");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
    <div class="bin-buttons" id="bin-buttons">
    <?php 
        $uuid = strip_tags($user['uid']);
        $stmtU = $dbh->prepare("SELECT users_uid FROM users WHERE users_id = :uuid");
        $stmtU->execute(['uuid' => strip_tags($uuid)]);
        $userU = $stmtU->fetch();

        if (empty(strip_tags($userU['users_uid']))) {
            $userU['users_uid'] = "Anonymous";
        }

        echo '<p><b>Paste title:</b> '.strip_tags($user['title']).'</p>';
        // this is very messy, but oh well.
        if (strip_tags($user5["banned"]) == 1) {
            echo '<p><b>Paste made by:</b> <a href="/profile.php?uid='.strip_tags($user['uid']).'"><span class="" style="color: #808080 ;font-weight:bold;text-decoration:line-through;">[Banned] </span><span class="" style="color: #808080 ;font-weight:bold;text-decoration:line-through;">'.strip_tags($userU['users_uid']).'</span></a></p>';
        }
        else if (strip_tags($user5["users_rank"]) == 2) {
            echo '<p><b>Paste made by:</b> <a href="/profile.php?uid='.strip_tags($user['uid']).'"><span class="devious" style="color: #d90429;font-weight:bold;">[Devious] </span><span class="devious" style="color: #d90429;font-weight:bold;">'.strip_tags($userU['users_uid']).'</span></a></p>';
        }
        else if (strip_tags($user5["users_rank"]) == 1) {
            echo '<p><b>Paste made by:</b> <a href="/profile.php?uid='.strip_tags($user['uid']).'"><span style="color: #FCB517;font-weight:bold;" class="admin">[Admin] </span><span class="admin" style="color: #FCB517;font-weight:bold;">'.strip_tags($userU['users_uid']).'</span></a></p>';
        }
        else if (strip_tags($user5["users_rank"]) == 3) {
            echo '<p><b>Paste made by:</b> <a href="/profile.php?uid='.strip_tags($user['uid']).'"><span style="color: #d62643;font-weight:bold;" class="asta">[Astaroth] </span><span class="admin" style="color: #d62643;font-weight:bold;">'.strip_tags($userU['users_uid']).'</span></a></p>';
        }
        else if (strip_tags($user5["users_rank"]) == 4) {
            echo '<p><b>Paste made by:</b> <a href="/profile.php?uid='.strip_tags($user['uid']).'"><span style="color: #88c0d0;font-weight:bold;" class="devious">[Mod] </span><span class="devious" style="color: #88c0d0;font-weight:bold;">'.strip_tags($userU['users_uid']).'</span></a></p>';
        }
        else {
            echo '<p><b>Paste made by:</b> <a href="/profile.php?uid='.strip_tags($user['uid']).'"> '.strip_tags($userU['users_uid']).'</a></p>';
        }
        if (strip_tags($user['private']) == 0) {
            echo '<p><b>Private:</b> No</p>';
        }
        else {
            echo '<p><b>Private:</b> Yes</p>';
        }
        if (strip_tags($user['unlisted']) == 0) {
            echo '<p><b>Unlisted:</b> No</p>';
        }
        else {
            echo '<p><b>Unlisted:</b> Yes</p>';
        }
        echo '<p><b>Hits:</b> '.strip_tags($user['hits']).'</p>';
    ?>
    
    <p><b>Paste created:</b> <?php echo strip_tags($user['add']); ?></p>
    <a target="_blank" href="post.php" class="btn">New Paste</a><br><br>
    <a target="_blank" href="raw.php?id=<?php echo strip_tags($user['id']); ?>" class="btn">View Raw</a><br><br>
    <?php
        if (strip_tags($user['username']) == $_SESSION['useruid'] || $_SESSION["rank"] == 1 || $_SESSION["rank"] == 4) {
            echo '<a class="btn" href="editpaste.php?pid='.strip_tags($user["id"]).'">Edit Paste</a><br><br>';
        }
        if ($_SESSION['rank'] == 2 || $_SESSION["rank"] == 1 || $_SESSION["rank"] == 4) {
            if (strip_tags($user['username']) == $_SESSION['useruid'] || $_SESSION["rank"] == 1 || $_SESSION["rank"] == 4) {
                echo '<a class="btn" href="delete.php?pid='.strip_tags($user["id"]).'">Delete Paste</a><br><br>';
            }
        }
        // comments
        ?>
            
        <form action="" method="post">
        <br>
        <textarea name="comtextarea" class="comment-text" placeholder="Enter comment"></textarea>
        <div class="h-captcha" data-sitekey="51a73684-2376-48f4-814d-300916b065df"></div>
        <input type="submit" name="submit" value="Post Comment">
        </form>
        <?php
            foreach($fffC as $eC) {
                $uuid = strip_tags($eC['com_userid']);
                $stmtU = $dbh->prepare("SELECT users_uid FROM users WHERE users_id = :uuid");
                $stmtU->execute(['uuid' => strip_tags($uuid)]);
                $userU = $stmtU->fetch();

                echo '<div class="comment">';
                if ($eC['com_author'] == "Anonymous") {
                    echo '<b>'.strip_tags($userU['users_uid']).'</a> | <i>'.strip_tags($eC['com_time']).'</i></b>';
                }
                else {
                    echo '<b><a target="_blank" href="/profile.php?uid='.strip_tags($eC['com_userid']).'">'.strip_tags($userU['users_uid']).'</a> | <i>'.strip_tags($eC['com_time']).'</i></b>';
                }
                
                if ($_SESSION['rank'] == 2 || $_SESSION['rank'] == 4 || $_SESSION['rank'] == 1) {
                    if ($_SESSION['useruid'] == strip_tags($eC['com_author']) || $_SESSION['rank'] == 4 || $_SESSION['rank'] == 1) {
                        echo '<a href="/includes/delcom.inc.php?cid='.strip_tags($eC['com_id']).'"> [DELETE]</a>';
                    }
                }
                echo '<p>'.strip_tags($eC['com_text']).'</p>';
                echo '</div>';
            }
        ?>
    </div>
    <div class="">
        <?php
            try {
                error_reporting(0);
                $uu = $user['title'];
                if(!file_exists("pastes/".$uu.".txt")) {
                    header("Location: error.php?status=Paste not found");
                    die();
                }
                
            } catch (Exception $e){
                echo "<h1>Issue</h1>";
                die();
            }
        ?>
        <pre class="view-paste"><?php include("pastes/".$uu.".txt"); ?></pre>
    </div>
</body>
</html>