<?php

if (preg_match('/[^?]*api\/v\d\//', $_SERVER['REQUEST_URI'])) {
  return require_once 'api/api.php';
}

return require('app.php');