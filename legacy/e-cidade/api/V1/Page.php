<?php
namespace ECidade\Api\V1;

class Page {

  /**
   * Number of the page
   * @var integer
   */
  protected $number;

  /**
   * Size of the page
   * @var integer
   */
  protected $size;

  /**
   * @return int
   */
  public function getNumber() {

    return $this->number;
  }

  /**
   * @param int $number
   */
  public function setNumber($number) {

    $this->number = $number;
  }

  /**
   * @return int
   */
  public function getSize() {

    return $this->size;
  }

  /**
   * @param int $size
   */
  public function setSize($size) {

    $this->size = $size;
  }
  
  
}