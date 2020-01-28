<?php

namespace ECidade\V3\Modification\Parse\Operation;

use \ECidade\V3\Extension\Encode;

class Ignore {

  private $content;
  private $type;
  private $regex = false;
  private $flag;

  public function regex($regex = null) {

    if ($regex === null) {
      return $this->regex;
    }

    $this->regex = (boolean) $regex;
    return $this;
  }

  public function flag($flag = null) {

    if ($flag === null) {
      return $this->flag;
    }

    $this->flag = $flag;
  }

  public function type($type = null) {

    if ($type === null) {
      return $this->type;
    }

    $this->type = $type;
    return $this;
  }

  public function content($content = null) {

    if ($content === null) {
      return $this->content;
    }

    $this->content = $content;
    return $this;
  }

  public function match($needle) {

    if ($this->regex) {
      if (preg_match("/$this->content/$this->flag", $needle)) {
        return true;
      }
    } else if (strpos($needle, $this->content) !== false) {
      return true;
    }

    return false;
  }

}
