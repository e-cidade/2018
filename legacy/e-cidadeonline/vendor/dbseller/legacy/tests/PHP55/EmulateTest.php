<?php

namespace DBSeller\Legacy\Tests\PHP55;
use DBSeller\Legacy\PHP55\Emulate;

class EmulateTest extends \PHPUnit_Framework_TestCase {

  function testExistence() {

    $o = new Emulate();
    $this->assertTrue(!!$o);
  }
}
