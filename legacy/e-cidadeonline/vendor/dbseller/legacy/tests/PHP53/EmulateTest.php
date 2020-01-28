<?php

namespace DBSeller\Legacy\Tests\PHP53;
use DBSeller\Legacy\PHP53\Emulate;

class EmulateTest extends \PHPUnit_Framework_TestCase {

  function testExistence() {

    $o = new Emulate();
    $this->assertTrue(!!$o);
  }

  public function testSuperEnviroment() {

    Emulate::env();
    $this->assertTrue(isset($GLOBALS['_ENV']['PATH_INFO']));
  }

  public function testRegisterLongArray() {

    Emulate::registerLongArrays();
    $this->assertTrue(isset($GLOBALS['HTTP_ENV_VARS']));
    $this->assertTrue(isset($GLOBALS['HTTP_GET_VARS']));
    $this->assertTrue(isset($GLOBALS['HTTP_POST_VARS']));
    $this->assertTrue(isset($GLOBALS['HTTP_COOKIE_VARS']));
    $this->assertTrue(isset($GLOBALS['HTTP_SERVER_VARS']));
    $this->assertTrue(isset($GLOBALS['HTTP_FILES_VARS']));
  }

  public function testRegisterGlobals() {

    $_SERVER['oi'] = 'cara';
    Emulate::registerGlobals();

    global $oi;
    $this->assertEquals($oi, $_SERVER['oi']);
  }


  public function testMargicQuotesGPCR() {

    $original = "\\ '  \"";
    $_COOKIE['teste2'] = $_GET['teste1'] = $original;

    Emulate::magicQuotesGPCR("On");
    $this->assertEquals("\\\\ \\'  \\\"", $_COOKIE['teste2']);

    Emulate::magicQuotesGPCR("Off");
    $this->assertEquals($original, $_COOKIE['teste2']);
  }


  public function testSessionRegister() {

    session_register("TESTES_#1");

    $this->assertTrue(
      array_key_exists("TESTES_#1", $_SESSION)
    );
  }


  public function testSessionIsRegistered() {

    session_register("TESTES_#2");
    $this->assertTrue(session_is_registered("TESTES_#2"));
  }

  public function testSessionUnregister() {

    session_register("TESTES_#3");

    $this->assertTrue(session_is_registered("TESTES_#3"));
    session_unregister("TESTES_#3");
    $this->assertFalse(session_is_registered("TESTES_#3"));
  }

}
