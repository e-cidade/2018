<?php

namespace DBSeller\Legacy\Tests\PHP56;
use DBSeller\Legacy\PHP56\Emulate;

class EmulateTest extends \PHPUnit_Framework_TestCase {

  function testExistence() {

    $o = new Emulate();
    $this->assertTrue(!!$o);
  }

  function testAssertSplit() {

    $testes = "OI:TUDO:BEM.COMO:VAI.";

    $this->assertTrue(function_exists("split"));
    $this->assertTrue(
      is_array( $array = split(":", $testes))
    );

    $this->assertEquals(4, count($array));
  }
}
