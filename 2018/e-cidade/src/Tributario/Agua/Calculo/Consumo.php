<?php

namespace ECidade\Tributario\Agua\Calculo;

use BusinessException;

class Consumo {

  /**
   * @var \AguaHidrometro
   */
  private $oHidrometro;

  /**
   * @var bool
   */
  private $lHidrometroVirou;

  /**
   * @return \AguaHidrometro
   */
  public function getHidrometro() {
    return $this->oHidrometro;
  }

  /**
   * @param \AguaHidrometro $oHidrometro
   */
  public function setHidrometro(\AguaHidrometro $oHidrometro) {
    $this->oHidrometro = $oHidrometro;
  }

  /**
   * @return bool
   */
  public function hidrometroVirou() {
    return $this->lHidrometroVirou;
  }

  /**
   * @param $iLeituraAtual
   * @param $iLeituraAnterior
   *
   * @return int
   * @throws BusinessException
   */
  public function calcular($iLeituraAtual, $iLeituraAnterior) {

    if (!$this->oHidrometro) {
      throw new BusinessException('Hidrômetro não informado.');
    }

    $this->lHidrometroVirou = $iLeituraAtual < $iLeituraAnterior;
    $iConsumo = $iLeituraAtual - $iLeituraAnterior;

    if ($this->lHidrometroVirou) {
      $iLeituraMaximaHidrometro = str_repeat('9', $this->oHidrometro->getQuantidadeDigitos());
      $iConsumo = $iLeituraMaximaHidrometro - $iLeituraAnterior + $iLeituraAtual;
    }

    return $iConsumo;
  }
}

