<?php
if (!defined('_IN_APP')) {
  header('Location: /');
  exit();
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
  <title>Zdrapka Lovelace</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="/css/style.css" rel="stylesheet">
</head>

<body>
  <div class="container">
    <h1 class="title">Zdrapka Lovelace</h1>

    <center>
      <?= $info ?><br />
      <a class="link" href="<?= $sarmacja->loginURL() ?>"> Login </a>
    </center>

    <div id="footer">
      Aplikacja stworzona przez <a href="https://stempel.org.pl/paszport/1098" class="link">Sheldon Anszlus Lovelace Romanow-Windsachen</a> na potrzeby zaliczenia studniów magisterskich w <a href="https://fc.sarmacja.org/viewforum.php?f=1182" class="link">SAN</a>
    </div>
  </div>
</body>

</html>