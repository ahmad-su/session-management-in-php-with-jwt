<?php

namespace helper\logging {
  function get_file_and_line(): string
  {
    return __FILE__ . "line " . __LINE__ . ": ";
  }
}
