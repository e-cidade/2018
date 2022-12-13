<?php 

namespace PHP;

class ArrayDebuggerTest extends \PHPUnit_Framework_TestCase 
{

  public $base = array(
    'hum'  => 1, 
    'dois' => 2, 
    'tres' => 3
  );

  public function testGetter() {

    $array = new ArrayDebugger($this->base);
    $t = $this;

    $array->setLogger(function($retorno) use($t) {
      $t->assertEquals($retorno['type'], ArrayDebugger::TYPE_GET);
    });

    $teste = $array['hum'];

    $array->setLogger(function($retorno) use($t) {
      $t->assertNotEquals($retorno['type'], ArrayDebugger::TYPE_GET);
    });

    $array['quatro'] = 4;

    /**
     * offsetExists
     */
    isset($array['hum']);
    /**
     * offsetUnset
     */
    unset($array['quatro']);
  }

  public function testSetter() {

    $array = new ArrayDebugger($this->base);

    $t = $this;
    $array->setLogger(function($retorno) use($t) {
      $t->assertEquals($retorno['type'], ArrayDebugger::TYPE_SET);
    });

    $array['quatro'] = 4;

    $array->setLogger(function($retorno) use($t) {
      $t->assertNotEquals($retorno['type'], ArrayDebugger::TYPE_SET);
    });

    $teste = $array['hum'];

    /**
     * offsetExists
     */
    isset($array['hum']);
    /**
     * offsetUnset
     */
    unset($array['quatro']);
  }

  public function testIsset() {

    $array = new ArrayDebugger($this->base);

    $t = $this;
    $array->setLogger(function($retorno) use($t) {
      $t->assertEquals($retorno['type'], ArrayDebugger::TYPE_EXISTS);
    });


    /**
     * offsetExists
     */
    isset($array['hum']);

    $array->setLogger(function($retorno) use($t) {
      $t->assertNotEquals($retorno['type'], ArrayDebugger::TYPE_EXISTS);
    });

    $array['quatro'] = 4;

    $teste = $array['hum'];

    /**
     * offsetUnset
     */
    unset($array['quatro']);
  }

  public function testUnset() {

    $array = new ArrayDebugger($this->base);
    $t = $this;

    $array->setLogger(function($retorno) use($t) {
      $t->assertEquals($retorno['type'], ArrayDebugger::TYPE_UNSET);
    });

    /**
     * offsetUnset
     */
    unset($array['dois']);

    $array->setLogger(function($retorno) use($t) {
      $t->assertNotEquals($retorno['type'], ArrayDebugger::TYPE_UNSET);
    });
    /**
     * offsetExists
     */
    isset($array['hum']);

    $array['quatro'] = 4;

    $teste = $array['hum'];

  }
}
