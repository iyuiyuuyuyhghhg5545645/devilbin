<?php
  session_start();
  include 'classes/dbh.php';
  include 'html/ban_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>DevilBin - Buy Sticky</title>
<?php include 'html/head.html' ?>
</head>
<body>
    <?php include 'html/header.php' ?>
    <h1 class="text-center">DevilBin</h1>
    <div class="container">
    <h4 class="text-center">Do you want your paste pinned? If so, then you've came to the right place. </h4>
    <h5 class="text-center">After making your decision please contact us via any of the methods <a href="/contact.php">here</a>.</h5><br>
    <table class="table">
        <thead style="background-color: #121212;">
        <tr>
            <th>Duration</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
            <tr class="tb-highlight">
                <td>7 days</td>
                <td>12$</td>
            </tr>
            <tr class="tb-highlight">
                <td>14 days</td>
                <td>23$</td>
            </tr>
            <tr class="tb-highlight">
                <td>30 days</td>
                <td>28$</td>
            </tr>
        </tbody>
    </table>
    </div>
</body>
</html>