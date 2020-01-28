<?php
namespace ECidade\Educacao\Escola\Censo\SituacaoAluno\Layout;

class Layout2016 extends Layout {

  const LAYOUT = 277;

  function __construct() {

    parent::__construct(self::LAYOUT);
  }

  static public function lerArquivo($sFilePath) {

    $oDbLayoutReader = new \DBLayoutReader(self::LAYOUT, $sFilePath, true, true, false);
    return $oDbLayoutReader->getLines();
  }
}