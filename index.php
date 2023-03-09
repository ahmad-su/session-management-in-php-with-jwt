<?php
//This is the server's entrypoint.
//First, we load all dependencies (we only use php-jwt from Firebase see: https://github.com/firebase/php-jwt)
//Second, we load session controller to be in scope
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/controller/session.php';

//Check if the user is logged in by aquiring session info
//Details can be found on the respective file (above)
//If the user has logged in, display index page. otherwise, redirect to login page
try {
  //unlike rust, this variable will live in the whole php execution (current file)
  //and outlive the current scope
  $session = session\SessionManager::get_session_info();

  //The try catch is similar to match on Result<T,E> error handling in rust
  //The Exception (class) is available and included by php automatically.
  //think about standard library in other language. welcome to PHP
} catch (Exception $e) {
  //header is same as Exception (available globally automatically)
  //it will add http header. we use it redirect to login file(page)
  header("location: /login.php");
  //exit function is used to terminate the whole php execution and display code or message
  exit(0);
}

//end of php block
?>


<html>

<head>

</head>

<body>
  <?
  //PHP can be inserted in the middle of html file like this 
  //AND ALL DATA (VARIABLES) IN THIS SINGLE PHP FILE IS CONNECTED
  //So you can use $session variable from above here
  //use ?= to print/append something from php block to html
  ?>
  <h1>Hello <?= $session->username ?></h1>
  <a href="/logout.php">logout</a>
</body>

</html>
