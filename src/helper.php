<?php

namespace helper\logging {
  require_once __DIR__ . '/../vendor/autoload.php';

  use Monolog\Logger;
  use Monolog\Handler;

  function get_file_and_line(): string
  {
    return __FILE__ . " [line " . __LINE__ . "]: ";
  }

  static $error_logger = new Logger("ErrorLogger");
  $error_logger->pushHandler(new Handler\StreamHandler(__DIR__ . '/../logs/error.log'));
}
