<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas;

use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;

/**
 * Classe responsável pelo cálculo de hora extra 75% de um servidor em um dia de trabalho
 * Class Extra75
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Extra75 extends HoraExtra {

  /**
   * @var \DateTime
   */
  private $oExtra50Diurna;

  /**
   * @var \DateTime
   */
  private $oExtra50Noturna;

  /**
   * @var \DateTime
   */
  private $oExtra50;

  /**
   * @var \DateTime
   */
  private $oMaximoExtra75;

  /**
   * Construtor da classe. Seta o tipo de hora instanciado
   */
  public function __construct(DiaTrabalho $oDiaTrabalho) {

    $this->setDiaTrabalho($oDiaTrabalho);
    parent::__construct();

    $this->oExtra50Diurna  = $this->getHoraZerada();
    $this->oExtra50Noturna = $this->getHoraZerada();
    $this->oExtra50        = $this->getHoraZerada();
    $this->oMaximoExtra75  = new \DateTime(
      $this->getDiaTrabalho()->getData()->getDate() .' '. $this->getConfiguracoesLotacao()->getHoraExtra75()
    );
  }

  /**
   * @return \DateTime
   */
  protected function getMaximoExtra75() {
    return $this->oMaximoExtra75;
  }

  public function setHoraExtra50Diurna(\DateTime $oExtra50Diurna) {
    $this->oExtra50Diurna = $oExtra50Diurna;
  }

  public function setHoraExtra50Noturna(\DateTime $oExtra50Noturna) {
    $this->oExtra50Noturna = $oExtra50Noturna;
  }

  public function setHoraExtra50(\DateTime $oExtra50) {
    $this->oExtra50 = $oExtra50;
  }

  public function getHoraExtra50Diurna() {
    return $this->oExtra50Diurna;
  }

  public function getHoraExtra50Noturna() {
    return $this->oExtra50Noturna;
  }

  public function getHoraExtra50() {
    return $this->oExtra50;
  }

  public function somarHorasExtra50() {
    return $this->somaHoras($this->oExtra50Diurna, $this->oExtra50Noturna);
  }

  /**
   * @return \DateTime|null
   */
  public function verificarExistenciaHorasExtras() {

    $oMaximoExtra50 = new \DateTime($this->getDiaTrabalho()->getData()->getDate() .' '. $this->getConfiguracoesLotacao()->getHoraExtra50());
    $oMaximoExtra75 = new \DateTime($this->getDiaTrabalho()->getData()->getDate() .' '. $this->getConfiguracoesLotacao()->getHoraExtra75());
    $oExtra75       = clone $this->getHoraExtraTotal();

    /**
     * Se as extras 50 não atingiram o limite então não há mais extras de 75 e 100
     */
    if($this->getHoraExtra50()->format('H:i') != $oMaximoExtra50->format('H:i')) {
      $oExtra75->setTime(0, 0);
    }

    if($this->getHoraExtra50()->diff($oMaximoExtra50)->invert || $this->getHoraExtra50()->format('H:i') == $oMaximoExtra50->format('H:i')) {

      $oExtra75->sub(new \DateInterval("PT". $this->getHoraExtra50()->format('H') ."H". $this->getHoraExtra50()->format('i') ."M"));

      if(empty($oMaximoExtra75)) {
        $oExtra75->setTime(0, 0);
      }
    }

    if($oExtra75->format('H') == 0 && $oExtra75->format('i') == 0) {
      return null;
    }

    return $oExtra75;
  }

  public function atualizaMaximoExtra75() {

    $this->setHoraExtra50($this->somarHorasExtra50());

    if(!$this->getDiaTrabalho()->isCalculaHoraExtra()) {
      return;
    }

    $oIntervaloDiferencaSomaTotal = $this->getDiferencaHoras($this->getHoraExtra50(), $this->getHoraExtraTotal());
    $oDiferencaSomaTotal          = $this->getHoraZerada();
    $oDiferencaSomaTotal->add($oIntervaloDiferencaSomaTotal);

    if(!$this->getDiferencaHoras($oDiferencaSomaTotal, $this->getMaximoExtra75())->invert) {
      $this->oMaximoExtra75 = clone $oDiferencaSomaTotal;
    }
  }
}