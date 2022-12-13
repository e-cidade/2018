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
namespace ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model;

use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;

/**
 * Class HoraExtraArtefato
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model
 * @author John Lenon Reis <john.reis@dbseller.com.br>
 */
class HoraExtraArtefato {

  /**
   * Guarda o tipo de hora extra que inicia
   * @var int
   */
  private $iniciaEm = Extra::TIPO_DIURNA;

  /**
   * @var \DateTime
   */
  private $oValor;
  /**
   * @var \DateTime
   */
  private $oInicio;
  /**
   * @var \DateTime
   */
  private $oFinal;
  /**
   * @var \DateTime
   */
  private $oInicioHorarioNoturno;
  /**
   * @var \DateTime
   */
  private $oFinalHorarioNoturno;

  /**
   * Hora final do horario noturno no dia.
   * @var \DateTime
   */
  private $oFinalHorarioNoturnoNoDia;
  /**
   * @var \DateTime
   */
  private $oValorNoturno;

  /**
   * @var \DateTime
   */
  private $oValorDiurno;

  /**
   * Total de horas extras diurnas
   * @var \DateInterval
   */
  private $oTotalHorasDiurnas;

  /**
   * Total de horas extras noturnas
   * @var \DateInterval
   */
  private $oTotalHorasNoturnas;

  /**
   * @var int
   * Total de horas extras diurnas em minutos
   */
  private $iMinutosDiurnos;

  /**
   * @var int
   * Total de horas extras noturnas em minutos
   */
  private $iMinutosNoturnos;

  /**
   * @var \DateTime
   */
  private $oHoraZerada;

  /**
   * @return int
   */
  public function getIniciaEm()
  {
    return $this->iniciaEm;
  }

  /**
   * @param int $iniciaEm
   */
  public function setIniciaEm($iniciaEm)
  {
    $this->iniciaEm = $iniciaEm;
  }


  /**
   * @return \DateTime
   */
  public function getHoraZerada()
  {
    return clone $this->oHoraZerada;
  }

  /**
   * @param \DateTime $oHoraZerada
   */
  public function setHoraZerada($oHoraZerada)
  {
    $this->oHoraZerada = $oHoraZerada;
  }


  /**
   * @return \DateTime
   */
  public function getValorNoturno()
  {
    return $this->oValorNoturno;
  }

  /**
   * @param \DateTime $oValorNoturno
   */
  public function setValorNoturno($oValorNoturno)
  {
    $this->oValorNoturno = $oValorNoturno;
  }

  /**
   * @return \DateTime
   */
  public function getValorDiurno()
  {
    return $this->oValorDiurno;
  }

  /**
   * @param \DateTime $oValorDiurno
   */
  public function setValorDiurno($oValorDiurno)
  {
    $this->oValorDiurno = $oValorDiurno;
  }

  /**
   * @return \DateInterval
   */
  public function getTotalHorasDiurnas()
  {
    return $this->oTotalHorasDiurnas;
  }

  /**
   * @param \DateInterval $oTotalHorasDiurnas
   */
  public function setTotalHorasDiurnas($oTotalHorasDiurnas)
  {
    $this->oTotalHorasDiurnas = $oTotalHorasDiurnas;
  }

  /**
   * @return \DateInterval
   */
  public function getTotalHorasNoturnas()
  {
    return $this->oTotalHorasNoturnas;
  }

  /**
   * @param \DateInterval $oTotalHorasNoturnas
   */
  public function setTotalHorasNoturnas($oTotalHorasNoturnas)
  {
    $this->oTotalHorasNoturnas = $oTotalHorasNoturnas;
  }

  /**
   * @return \DateTime
   */
  public function getInicioHorarioNoturno()
  {
    return $this->oInicioHorarioNoturno;
  }

  /**
   * @param \DateTime $oInicioHorarioNoturno
   */
  public function setInicioHorarioNoturno($oInicioHorarioNoturno)
  {
    $this->oInicioHorarioNoturno = $oInicioHorarioNoturno;
  }

  /**
   * @return \DateTime
   */
  public function getFinalHorarioNoturno()
  {
    return $this->oFinalHorarioNoturno;
  }

  /**
   * @param \DateTime $oFinalHorarioNoturno
   */
  public function setFinalHorarioNoturno($oFinalHorarioNoturno)
  {
    $this->oFinalHorarioNoturno = $oFinalHorarioNoturno;
  }


  /**
   * @return \DateTime
   */
  public function getValor()
  {
    return $this->oValor;
  }

  /**
   * @param \DateTime $oValor
   */
  public function setValor($oValor)
  {
    $this->oValor = $oValor;
  }

  /**
   * @return \DateTime
   */
  public function getInicio()
  {
    return $this->oInicio;
  }

  /**
   * @param \DateTime $oInicio
   */
  public function setInicio($oInicio)
  {
    $this->oInicio = $oInicio;
  }

  /**
   * @return \DateTime
   */
  public function getFinal()
  {
    return $this->oFinal;
  }

  /**
   * @param \DateTime $oFinal
   */
  public function setFinal($oFinal)
  {
    $this->oFinal = $oFinal;
  }

  /**
   * @return int
   */
  public function getMinutosDiurnos()
  {
    return $this->iMinutosDiurnos;
  }

  /**
   * @param int $iMinutosDiurnos
   */
  public function setMinutosDiurnos($iMinutosDiurnos)
  {
    $this->iMinutosDiurnos = $iMinutosDiurnos;
  }

  /**
   * @return int
   */
  public function getMinutosNoturnos()
  {
    return $this->iMinutosNoturnos;
  }

  /**
   * @param int $iMinutosNoturnos
   */
  public function setMinutosNoturnos($iMinutosNoturnos)
  {
    $this->iMinutosNoturnos = $iMinutosNoturnos;
  }



  /**
   * HoraExtraArtefato constructor.
   * @param \DateTime $oInicioHorarioNoturno
   * @param \DateTime $oFinalHorarioNoturno
   * @param \DateTime $oValor
   * @param \DateTime $oInicio
   */
  public function __construct($oInicioHorarioNoturno, $oFinalHorarioNoturno, $oInicio, $oValor)
  {

    $this->setHoraZerada(new \DateTime($oInicioHorarioNoturno->format('Y-m-d 00:00')));

    $this->setInicioHorarioNoturno($oInicioHorarioNoturno);
    $this->setFinalHorarioNoturno($oFinalHorarioNoturno);

    $this->oValor = $oValor;
    $this->oInicio = $oInicio;
    $this->setFinal($this->somaHoras($oInicio, $oValor));

    $this->setValorDiurno($this->getHoraZerada());
    $this->setValorNoturno($this->getHoraZerada());

    if ($oInicio->getTimestamp() > $this->getFinalHorarioNoturno()->getTimestamp()){
      $this->getInicioHorarioNoturno()->modify('+1 day');
    }
    $this->oFinalHorarioNoturnoNoDia = clone $oFinalHorarioNoturno;
    $this->oFinalHorarioNoturnoNoDia->modify(' -1 day');
    $this->separaDiurnaNoturna();
    $this->converteHorasParaIntervalo();

  }

  /**
   * Soma as horas de 2 DateTime
   * @param \DateTime $oHora1 - DateTime que receberá o valor somado
   * @param \DateTime $oHora2 - DateTime com o valor a ser somado
   * @return \DateTime
   */
  protected function somaHoras(\DateTime $oHora1, \DateTime $oHora2) {

    $iHora   = $oHora2->format('H');
    $iMinuto = $oHora2->format('i');

    $oDateInterval   = new \DateInterval("PT{$iHora}H{$iMinuto}M");
    $oHoraExtraTotal = new \DateTime($oHora1->format('Y-m-d H:i'));

    return $oHoraExtraTotal->add($oDateInterval);
  }

  /**
   * @param \DateTime $oHora1
   * @param \DateTime $oHora2
   * @return bool|\DateInterval
   */
  public function getDiferencaHoras(\DateTime $oHora1, \DateTime $oHora2) {
    return $oHora1->diff($oHora2);
  }

  function separaDiurnaNoturna() {

    // se inicio >= 05:00 E final <= 22:00
    // >>>>> tudo diurna
    $oCloneInicio = clone $this->getInicio();
    $oCloneInicio->modify("+1 day");

    if($oCloneInicio->getTimestamp() >= $this->getFinalHorarioNoturno()->getTimestamp() && $this->getFinal()->getTimestamp() <= $this->getInicioHorarioNoturno()->getTimestamp()){
      $this->setValorDiurno($this->getValor());
      return;
    }

    // se inicio >= 22h e final <= 05:00
    // >>>>>> tudo noturna
    if($this->getInicio()->getTimestamp() >= $this->getInicioHorarioNoturno()->getTimestamp() && $this->getInicio()->getTimestamp() <= $this->getFinalHorarioNoturno()->getTimestamp()){
      $this->setValorNoturno($this->getValor());
      return;
    }

    // se inicio < 22h E final > 22:00
    if($this->getInicio()->getTimestamp() < $this->getInicioHorarioNoturno()->getTimestamp() && $this->getFinal()->getTimestamp() > $this->getInicioHorarioNoturno()->getTimestamp()){

      $oHoraExtraDiurna = $this->getDiferencaHoras($this->getInicioHorarioNoturno(), $this->getInicio());
      if ($oHoraExtraDiurna->invert) {

        $this->getValorDiurno()->modify("+ {$oHoraExtraDiurna->h} hour");
        $this->getValorDiurno()->modify("+ {$oHoraExtraDiurna->i} minute");
      }

      $oHoraExtraNoturna = $this->getDiferencaHoras($this->getFinal(), $this->getInicioHorarioNoturno());
      if ($oHoraExtraNoturna->invert) {
        $this->getValorNoturno()->modify("+ {$oHoraExtraNoturna->h} hour");
        $this->getValorNoturno()->modify("+ {$oHoraExtraNoturna->i} minute");

      }
      return;
    }


    // se inicio < 05:00 E final > 05:00

    if (($this->getInicio()->getTimestamp() < $this->oFinalHorarioNoturnoNoDia->getTimestamp()) ||( $this->getInicio()->getTimestamp() < $this->getFinalHorarioNoturno()->getTimestamp()
         && $this->getFinal()->getTimestamp() > $this->getFinalHorarioNoturno()->getTimestamp())) {

      $oHoraExtraNoturna = $this->getDiferencaHoras($this->getFinalHorarioNoturno(), $this->getInicio());
      if ($oHoraExtraNoturna->invert) {

        $this->getValorNoturno()->modify("+ {$oHoraExtraNoturna->h} hour");
        $this->getValorNoturno()->modify("+ {$oHoraExtraNoturna->i} minute");
      }

      $oHoraExtraDiurna = $this->getDiferencaHoras($this->getFinal(), $this->getFinalHorarioNoturno());

      if ($oHoraExtraDiurna->invert) {

        $this->getValorDiurno()->modify("+ {$oHoraExtraDiurna->h} hour");
        $this->getValorDiurno()->modify("+ {$oHoraExtraDiurna->i} minute");
      }
      /**
       * Somamos o resto das Horas, até o inicio do Turno.
       */
      $oHoraExtraDiurna2 = $this->getDiferencaHoras($this->getFinal(), $this->oFinalHorarioNoturnoNoDia);
      if ($oHoraExtraDiurna2) {
        $this->setIniciaEm(Extra::TIPO_NOTURNA);
        $this->getValorDiurno()->modify("+ {$oHoraExtraDiurna2->h} hour");
        $this->getValorDiurno()->modify("+ {$oHoraExtraDiurna2->i} minute");
      }
      return;
    }

  }

  /**
   * Converte o date time para um intervalo
   */
  protected function converteHorasParaIntervalo() {

    $intervalorDiurno = new \DateInterval("PT".$this->getValorDiurno()->format("H")."H".$this->getValorDiurno()->format("i")."M");
    $this->setTotalHorasDiurnas($intervalorDiurno);

    $iMinutosDiurnos= BaseHora::converterIntervaloEmMinutos($intervalorDiurno);
    $this->setMinutosDiurnos($iMinutosDiurnos);

    $intervaloNoturno = new \DateInterval("PT".$this->getValorNoturno()->format("H")."H".$this->getValorNoturno()->format("i")."M");
    $this->setTotalHorasDiurnas($intervaloNoturno);

    $iMinutosNoturnos = BaseHora::converterIntervaloEmMinutos($intervaloNoturno);
    $this->setMinutosNoturnos($iMinutosNoturnos);
  }

}
