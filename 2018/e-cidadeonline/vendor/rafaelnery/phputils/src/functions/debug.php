<?php

function kill() {
  return call_user_func_array("\\PHP\\Utils::kill", func_get_args());
}

function dump_sql($sSql) {
  return \PHP\Utils::dump_sql($sSql);
}

function kill_sql($sSql) {

  \PHP\Utils::dump_sql($sSql);
  die("FIM SQL");
}

function debug_array(&$array) {
  return new \PHP\ArrayDebugger($array);
}
