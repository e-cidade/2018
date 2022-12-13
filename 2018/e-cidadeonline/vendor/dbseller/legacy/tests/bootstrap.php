<?php
require_once __DIR__ . '/../vendor/autoload.php';

$versao = array(
  "5.3" => (version_compare(PHP_VERSION, '5.3') >= 0),
  "5.4" => (version_compare(PHP_VERSION, '5.4') >= 0),
  "5.5" => (version_compare(PHP_VERSION, '5.5') >= 0),
  "5.6" => (version_compare(PHP_VERSION, '5.6') >= 0),
  "7.0" => (version_compare(PHP_VERSION, '7.0') >= 0),
);

if (!$versao['7.0']) {
  ini_set("error_reporting", E_ALL & ~E_DEPRECATED);
}
