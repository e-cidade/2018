<?php

namespace DBSeller\Legacy\Tests\PHP54;
use DBSeller\Legacy\PHP54\Emulate;

class EmulateTest extends \PHPUnit_Framework_TestCase {

  function testExistence() {

    $o = new Emulate();
    $this->assertTrue(!!$o);
  }
}
