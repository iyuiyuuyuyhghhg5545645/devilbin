<?php
    session_start();

    include 'classes/dbh.php';
    include 'html/ban_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>DevilBin - Donate</title>
<?php include 'html/head.html' ?>
</head>
<body>
    <?php include 'html/header.php' ?>
      <h1 class="text-center">DevilBin</h1><br>
      <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center" style="background-color: #0D0D0D;">
                <b style="color: orange; font-size: 25px;">Bitcoin (BTC)</b>
            </div>
            <div class="panel-body text-center">
                <b style="color: white;">bc1qqyum5vytau6ppvjpyuu2ulta4fs4t5xzdx80ex</b>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading text-center" style="background-color: #0D0D0D;">
                <b style="color: lightblue; font-size: 25px;">Litecoin (LTC)</b>
            </div>
            <div class="panel-body text-center">
                <b style="color: white;">ltc1qrdtjy3s64gawsuc4pux8tkpfe0wvhqnz9h9kp3</b>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading text-center" style="background-color: #0D0D0D;">
                <b style="color: #ff6600; font-size: 25px;">Monero (XMR)</b>
            </div>
            <div class="panel-body text-center">
                <b style="color: white;">84HMSjJQQgkXZzjTSDGPKy7de6GQHJDQ8GeiPeyLAmtWjiCJhZhJU4ngk7afwGRLrc7jT34DomMijaXWChkCbk3DFQYcwYD</b>
            </div>
        </div>
        <h4 class="text-center">Contact us to be added to the donation leaderboard.</h4>
        <table class="table">
        <thead style="background-color: #121212;">
        <tr>
            <th>Donation Amount</th>
            <th>Donated By</th>
        </tr>
        </thead>
        <tbody>
            <tr class="tb-highlight">
                <td>50$</td>
                <td><a href="/profile.php?uid=1373" rel="noreferrer" target="_blank">KmetaNaEvropa</a></td>
            </tr>
            <tr class="tb-highlight">
                <td>40$</td>
                <td><a href="https://bf.hn/uid/179836" rel="noreferrer" target="_blank">ZeroHashx0</a></td>
            </tr>
        </tbody>
    </table>
    </div>
</body>
</html>