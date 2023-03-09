<?php

namespace session {
  //you will want to see the helper.php file. It contains logger and some helpful function
  require_once __DIR__ . '/../../vendor/autoload.php';
  require_once __DIR__ . '/../helper.php';

  use Exception;
  use Firebase\JWT;
  use helper\logging;

  class Session
  {
    //This is some kind of magic, even if we didn't make a $username field implicitly,
    //if this session is instantiated (using new Session($username)) 
    //the Session will has $username field
    //(every parameter in this function will become object field)
    public function __construct(public string $username)
    {
    }
  }

  class SessionManager
  {
    //needed to sign and validate token
    public static string $SECRET_KEY = "djh3521kh123";

    public static function login(string $username, string $password): bool
    {
      //error logger from helper
      global $error_logger;
      //It's hardcoded now. later we will use db to test username & password
      //If the username & password match, 
      //we will create a token and assign it to user's browser cookie
      if ($username == "hendz" && $password == "ahmad") {
        //payload to encrypt
        $jwt_payload = ["username" => $username];
        //remember that it could fail. So we need to handle error case
        //We used Monolog logger here, to replace error_log() prelude function

        try {
          $jwt = JWT\JWT::encode($jwt_payload, SessionManager::$SECRET_KEY, 'HS256');
        } catch (Exception $e) {
          //will write to logs/error.log (config in php.ini)
          //we're not going to use this anymore so it's commented
          // error_log(logging\get_file_and_line() . " Can not generate JWT: " . $e->getMessage());

          //use new method to write to log file
          $error_logger->error(logging\get_file_and_line() . " Can not generate JWT: " . $e->getMessage());
          exit("Can not establish session: " . $e->getMessage() . ". Please contact administrator at: ahmad.suhae@gmail.com");
        }
        //set browser cookie. you know where this function comes from. std lib.
        setcookie("X-SESSION", $jwt);

        //we return boolean so the caller can use this function in if statement
        return true;
      } else {
        return false;
      }
    }

    //Here, we return a session object. it's not mandatory. you can even return bool.
    //Just anything you want to suit your logic and needs. 
    public static function get_session_info(): Session
    {
      //error logger from helper
      global $error_logger;
      //check if a cookie named "X-SESSION" is set
      if (isset($_COOKIE['X-SESSION'])) {
        //if it was, retrieve its value which is, the token
        $jwt = $_COOKIE['X-SESSION'];

        //We used Monolog logger here, to replace error_log() prelude function

        //decoding can fail.
        try {
          $payload = JWT\JWT::decode($jwt, new JWT\Key(SessionManager::$SECRET_KEY, 'HS256'));
          return new Session($payload->username);
        } catch (Exception $e) {
          //same here, we will use new logging method
          // error_log(logging\get_file_and_line() . $e->getMessage());
          $error_logger->error(logging\get_file_and_line() . $e->getMessage());

          //after we populate Exception, the current caller (index.php) will redirect to login page again
          throw new Exception(("Cannot get session info" . $e->getMessage()));
        }
      } else {
        //If no session stored in cookie, populate error
        //The caller must catch this
        throw new Exception("You are not logged in");
      }
    }
  }
}
