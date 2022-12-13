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
use ECidade\Configuracao\Cadastro\Model\Feriado;

/**
 * Classe responsável pelo cálculo de hora extra 100% de um servidor em um dia de trabalho
 * Class Extra100
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Extra100 extends HoraExtra {

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
  private $oExtra75Diurna;

  /**
   * @var \DateTime
   */
  private $oExtra75Noturna;

  /**
   * @var \DateTime
   */
  private $oExtra75;


  /**
   * @var \DateTime
   */
  private $oMaximoExtra100;

  /**
   * Construtor da classe. Seta o tipo de hora instanciado
   */
  public function __construct(DiaTrabalho $oDiaTrabalho) {

    $this->setDiaTrabalho($oDiaTrabalho);
    parent::__construct();

    $this->oExtra50Diurna  = $this->getHoraZerada();
    $this->oExtra50Noturna = $this->getHoraZerada();
    $this->oExtra50        = $this->getHoraZerada();

    $this->oExtra75Diurna  = $this->getHoraZerada();
    $this->oExtra75Noturna = $this->getHoraZerada();
    $this->oExtra75        = $this->getHoraZerada();

    $this->oMaximoExtra100 = new \DateTime(
      $this->getDiaTrabalho()->getData()->getDate() .' '. $this->getConfiguracoesLotacao()->getHoraExtra100()
    );
  }

  public function atualizaMaximoExtra100() {

    $this->setHoraExtra50($this->somarHorasExtra50());
    $this->setHoraExtra75($this->somarHorasExtra75());

    if(!$this->getDiaTrabalho()->isCalculaHoraExtra()) {
      return;
    }

    $oSomaExtra50Extra75          = $this->somaHoras($this->getHoraExtra50(), $this->getHoraExtra75());
    $oIntervaloDiferencaSomaTotal = $this->getDiferencaHoras($oSomaExtra50Extra75, $this->getHoraExtraTotal());
    $oDiferencaSomaTotal          = $this->getHoraZerada();
    $oDiferencaSomaTotal->add($oIntervaloDiferencaSomaTotal);

    if(!$this->getDiferencaHoras($oDiferencaSomaTotal, $this->getMaximoExtra100())->invert) {
      $this->oMaximoExtra100 = clone $oDiferencaSomaTotal;
    }
  }

  public function calcular() {

    $this->atualizaMaximoExtra100();

    $oMaximoHorasExtra75 = new \DateTime($this->getDiaTrabalho()->getData()->getDate() .' '. $this->getConfiguracoesLotacao()->getHoraExtra75());
    $oHorasTrabalhadas   = clone $this->getHorasTrabalhadas();

    if($this->getDiaTrabalho()->getFeriado() instanceof Feriado) {

      if($this->getDiaTrabalho()->getFeriado()->getData()->getDate() == $this->getDiaTrabalho()->getData()->getDate()) {
        return $oHorasTrabalhadas;
      }
    }

    if($this->getDiaTrabalho()->getJornada()->isDSR()) {
      return $oHorasTrabalhadas;
    }

    if($this->getDiaTrabalho()->getJornada()->isFolga()) {

      $oMaximoHorasExtra50Folga = new \DateTime($this->getDiaTrabalho()->getData()->getDate() .' 02:00');

      if(!$oHorasTrabalhadas->diff($oMaximoHorasExtra50Folga)->invert) {
        return $this->getHoraZerada();
      }

      $oHorasTrabalhadas->modify('-'. $oMaximoHorasExtra50Folga->format('H') .'hours');
      $oHorasTrabalhadas->modify('-'. $oMaximoHorasExtra50Folga->format('i') .'minutes');

      $oHoraInicioExtra100Folga = new \DateTime($this->getDiaTrabalho()->getData()->getDate().' 22:00');
      $oIntervalDiferencaUltimaMarcaoInicioExtra100Folga = $this->getUltimasHoras()->oHoraMarcacao->diff($oHoraInicioExtra100Folga);

      /**
       * Se a última marcação não passou das 22:00 então retorna 00:00
       */
      if(!$oIntervalDiferencaUltimaMarcaoInicioExtra100Folga->invert) {
        return new \DateTime('00:00');
      }

      /**
       * Constrói um objeto DateTime com a diferença após as 22:00
       * e retorna
       */
      return new \DateTime($this->getDiaTrabalho()->getData()->getDate().' '.$oIntervalDiferencaUltimaMarcaoInicioExtra100Folga->format('%H:%i'));
    }

    $oMaximoHorasExtraTotal = new \DateTime($this->getDiaTrabalho()->getData()->getDate() .' '. $this->getConfiguracoesLotacao()->getHoraExtra50());
    $oMaximoHorasExtraTotal->modify("+ {$oMaximoHorasExtra75->format('H')} hour + {$oMaximoHorasExtra75->format('i')} minutes");

    $oHoraExtraTotal = $this->getHoraZerada();

    if($this->totalHorasExtras() instanceof \DateTime) {

      /**
       * Quando o número de horas extra 50% e 75% não for excedido,
       * significa que não há hora extra 100%, retornando um DateTime zerado
       */
      if($this->getDiferencaHoras($oMaximoHorasExtraTotal, $this->totalHorasExtras())->invert) {
        return $this->getHoraZerada();
      }

      /**
       * Tendo sido atingido o limite de horas extra 50% e 75%,
       * é subtraído a soma dessas horas, do total de horas extras
       * do dia, para verificar o quanto de extra 100% há
       */
      $oHoraExtraTotal       = $this->totalHorasExtras();
      $oDateIntervalSubtrair = new \DateInterval("PT{$oMaximoHorasExtraTotal->format('H')}H");
      $oHoraExtraTotal->sub($oDateIntervalSubtrair);
    }

    return new \DateTime($oHoraExtraTotal->format('H:i'));
  }

  public function verificarExistenciaHorasExtras(\DateTime $oHoraExtra, $tipo = BaseHora::HORAS_EXTRA100_NOTURNA) {

    $oMaximoExtra100 = clone $this->getHoraExtraTotal();

    if($this->getDiaTrabalho()->getJornada()->isFolga()) {

      $oMaximoExtra100 = clone $this->getHorasExtrasDiurnas();

      if($tipo == BaseHora::HORAS_EXTRA100_NOTURNA) {
        $oMaximoExtra100 = clone $this->getHorasExtrasNoturnas();
      }
    }

    if($this->getHoraExtra50()->diff($this->getHoraExtra50())->invert || $this->getHoraExtra50()->format('H:i') == $this->getHoraExtra50()->format('H:i')) {

      $oMaximoExtra100->sub(new \DateInterval("PT". $this->getHoraExtra50()->format('H') ."H". $this->getHoraExtra50()->format('i') ."M"));

      if($this->getHoraExtra75() != null) {

        if($this->getHoraExtra75()->diff($this->getHoraExtra75())->invert || $this->getHoraExtra75()->format('H:i') == $this->getHoraExtra75()->format('H:i')) {
          $oMaximoExtra100->sub(new \DateInterval("PT". $this->getHoraExtra75()->format('H') ."H". $this->getHoraExtra75()->format('i') ."M"));
        }
      }
    }

    if($oMaximoExtra100->format('H') == 0 && $oMaximoExtra100->format('i') == 0) {
      return null;
    }

    return $oMaximoExtra100;
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

  public function somarHorasExtra50() {
    return $this->somaHoras($this->oExtra50Diurna, $this->oExtra50Noturna);
  }

  public function somarHorasExtra50E75Diurnas() {
    return $this->somaHoras($this->oExtra50Diurna, $this->oExtra75Diurna);
  }

  public function setHoraExtra75Diurna(\DateTime $oExtra75Diurna) {
    $this->oExtra75Diurna = $oExtra75Diurna;
  }

  public function setHoraExtra75Noturna(\DateTime $oExtra75Noturna) {
    $this->oExtra75Noturna = $oExtra75Noturna;
  }

  public function setHoraExtra75(\DateTime $oExtra75) {
    $this->oExtra75 = $oExtra75;
  }

  public function somarHorasExtra75() {
    return $this->somaHoras($this->oExtra75Diurna, $this->oExtra75Noturna);
  }

  public function somarHorasExtra50E75Noturnas() {
    return $this->somaHoras($this->oExtra50Noturna, $this->oExtra75Noturna);
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

  public function getHoraExtra75Diurna() {
    return $this->oExtra75Diurna;
  }

  public function getHoraExtra75Noturna() {
    return $this->oExtra75Noturna;
  }

  public function getHoraExtra75() {
    return $this->oExtra75;
  }

  /**
   * @return \DateTime
   */
  public function getMaximoExtra100() {
    return $this->oMaximoExtra100;
  }

  /**
   * @param \DateTime $oMaximoExtra100
   */
  public function setMaximoExtra100($oMaximoExtra100) {
    $this->oMaximoExtra100 = $oMaximoExtra100;
  }
}
