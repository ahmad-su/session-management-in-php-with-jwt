<?php

//we bring session controller in scope
require_once __DIR__ . '/src/controller/session.php';

//message that will be showed when login failed 
//(see else case below and the if block in php block inserted in html body)
$message = "";
//If this file is hitted using POST method: (triggered by submit button)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //Try to login. this function will return bool
  //if successfull, redirect to index.php
  //if failed, display error message and do nothing
  if (session\SessionManager::login($_POST['username'], $_POST['password'])) {
    header('Location: /index.php');
    exit(0);
  } else {
    $message = "Login failed. username or password incorrect!";
  }
}

?>

<html>

<head>
  <title>Login</title>
</head>

<body>
  <?php if ($message) { ?>
    <p style='padding: .25rem 1rem; background-color: #ffcfcf'><?= $message ?> </p>
  <?php } ?>
  <h1>Login</h1>
  <form action="/login.php" method="post">
    <label>Username :
      <input type="text" name="username">
    </label>
    <br />
    <label>Password :
      <input type="password" name="password">
    </label>
    <br />
    <input type="submit" value="Login">
  </form>
</body>

</html>
