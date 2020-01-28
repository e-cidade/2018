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

use ECidade\Configuracao\Cadastro\Model\Feriado;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;

/**
 * Class Extra75Noturna
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Extra75Noturna extends Extra75 implements Horas {

  /**
   * Extra75Noturna constructor.
   * @param DiaTrabalho $oDiaTrabalho
   */
  public function __construct(DiaTrabalho $oDiaTrabalho) {

    parent::__construct($oDiaTrabalho);
    $this->setTipoHora(BaseHora::HORAS_EXTRA75_NOTURNA);
    $this->totalHorasExtrasDiurnasNoturnas();
    $this->oHoraExtraInicialNoturna  = $this->getHoraExtraInicialNoturna();
    $this->oHoraExtraFinalNoturna    = $this->getHoraExtraFinalNoturna();
    $this->oHoraExtraInicial         = $this->getHoraExtraInicial();
  }

  public function calcular() {

    $this->atualizaMaximoExtra75();

    $oHoraExtra = $this->getHoraZerada();

    if($this->getDiaTrabalho()->getFeriado() instanceof Feriado) {

      if($this->getDiaTrabalho()->getFeriado()->getData()->getDate() == $this->getDiaTrabalho()->getData()->getDate()) {
        return $this->calcularFeriadoDSR($oHoraExtra);
      }
    }

    if($this->getDiaTrabalho()->getJornada()->isDSR()) {
      return $this->calcularFeriadoDSR($oHoraExtra);
    }

    if($this->getDiaTrabalho()->getJornada()->isFolga()) {
      return $this->calcularFolga($oHoraExtra);
    }

    if($this->getHorasExtrasNoturnas()->format('H:i') == '00:00') {
      return $oHoraExtra;
    }

    /**
     * Sem hora extra inicial e final
     * RETORNO: 00:00
     */
    if($this->isZero($this->oHoraExtraInicialNoturna) && $this->isZero($this->oHoraExtraFinalNoturna) && $this->isZero($this->oHoraExtraInicial)) {
      return $this->getHoraZerada();
    }

    return $this->calcularDiaTrabalhado($oHoraExtra);
  }

  public function calcularFeriadoDSR($oHoraExtra) {
    return $oHoraExtra;
  }

  public function calcularFolga($oHoraExtra) {

    $this->totalHorasExtrasFeriadoFolga();

    $oMaximoExtra75 = $this->verificarExistenciaHorasExtras();

    /**
     * Se não restou saldo para calcular horas extras retorna zerado
     */
    if(empty($oMaximoExtra75)) {
      return $this->getHoraZerada();
    }

    $oHoraExtra = $this->calcularDiaTrabalhado($oHoraExtra);
    return $oHoraExtra;
  }

  public function calcularDiaTrabalhado($oHoraExtra) {

    $oMaximoExtra75 = $this->verificarExistenciaHorasExtras();

    // Se não restou saldo para calcular horas extras retorna zerado
    if(empty($oMaximoExtra75)) {
      return $this->getHoraZerada();
    }

    $oLimiteExtra75Noturna = new \DateTime($this->getDiaTrabalho()->getData()->getDate() .' '. $this->getConfiguracoesLotacao()->getHoraExtra75());
    $oHorasExtra50Noturnas = clone $this->getHoraExtra50Noturna();

    $this->verificarLimiteExtra75Noturna($oLimiteExtra75Noturna);

    // Se o limite de extra 75 noturna for zero então retorna zerado as extras 75
    if($oLimiteExtra75Noturna->format('H') == 0 && $oLimiteExtra75Noturna->format('i') == 0) {
      return $this->getHoraZerada();
    }

    $oHorasExtra50Noturnas->setDate(
      $this->getDiaTrabalho()->getData()->getAno(),
      $this->getDiaTrabalho()->getData()->getMes(),
      $this->getDiaTrabalho()->getData()->getDia()
    );

    // As extras 75% noturnas é a diferença do total de noturnas com as extras 50 noturnas
    $oHoraExtra->setTime(
      $oHorasExtra50Noturnas->diff($this->getHorasExtrasNoturnas())->h,
      $oHorasExtra50Noturnas->diff($this->getHorasExtrasNoturnas())->i
    );

    // Se as extras forem maiores que o limite de extra 75 noturnas então retorna o limite
    if($oHoraExtra->diff($oLimiteExtra75Noturna)->invert) {
      $oHoraExtra = clone $oLimiteExtra75Noturna;
    }

    return $oHoraExtra;
  }

  public function verificarLimiteExtra75Noturna($oLimiteExtra75Noturna) {

    $oHoraInicioPeriodoNoturno         = clone $this->getInicioHoraExtraNoturna();
    $oHoraFimPeriodoNoturno            = clone $this->getFinalHoraExtraNoturna();
    $oHoraFimPeriodoNoturnoDiaAnterior = clone $oHoraFimPeriodoNoturno;
    $aHorasJornada                     = $this->getDiaTrabalho()->getJornada()->getHoras();
    $oHoraJornadaFinal                 = end($aHorasJornada)->oHora;
    $oMaximoExtra75                    = new \DateTime(
      $this->getDiaTrabalho()->getData()->getDate() .' '. $this->getConfiguracoesLotacao()->getHoraExtra75()
    );

    /**
     * Se as extras iniciais são menores que as extras 50
     * então as extras 75 devem ser calculadas a partir
     * das extras do intervalo e as extras finais
     */
    if($this->getHoraExtra50()->getTimestamp() >= $this->getHoraExtraInicial()->getTimestamp()) {

      /**
       * Se for folga comportamento diferente
       */
      if($this->getDiaTrabalho()->getJornada()->isFolga()) {

        $saldoDescontarExtrasFinais = $this->getHoraExtra50()->diff($this->getHoraExtraInicial());
        $saldoDescontarExtrasFinais->invert = 0;
        $extrasFinais               = clone $this->getHoraExtraFinal();
        $extrasFinais->sub($saldoDescontarExtrasFinais);

        $marcacaoEntrada2 = $this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada2();
        if(!empty($marcacaoEntrada2) && $marcacaoEntrada2->getMarcacao() != null) {

          $marcacaoEntrada2 = clone $marcacaoEntrada2->getMarcacao();
          $marcacaoEntrada2->add($saldoDescontarExtrasFinais);
          if($this->horaEstaNoIntervalo($marcacaoEntrada2, $oHoraInicioPeriodoNoturno, $oHoraFimPeriodoNoturno) || $oHoraFimPeriodoNoturnoDiaAnterior->diff($marcacaoEntrada2)->invert) {

            if($extrasFinais->diff($oMaximoExtra75)->invert) {
              $oLimiteExtra75Noturna = clone $oMaximoExtra75;
            }

            if(!$extrasFinais->diff($oMaximoExtra75)->invert) {
              $oLimiteExtra75Noturna->setTime($extrasFinais->diff($oMaximoExtra75)->h, $extrasFinais->diff($oMaximoExtra75)->i);
            }
            return;
          }
        }

        $oLimiteExtra75Noturna->setTime(0,0);
        return;
      }

      $oComecoExtra75    = clone $oHoraJornadaFinal;
      $oComecoExtra75->add(new \DateInterval("PT". $this->getHoraExtra50()->format('H') ."H". $this->getHoraExtra50()->format('i') ."M"));
      $oComecoExtra75->add(new \DateInterval("PT". $oLimiteExtra75Noturna->format('H') ."H". $oLimiteExtra75Noturna->format('i') ."M"));
      $oComecoExtra75->sub(new \DateInterval("PT". $this->getHoraExtraInicial()->format('H') ."H". $this->getHoraExtraInicial()->format('i') ."M"));

      /**
       * Se o final da jornada acrescido das extras 50 e do limite de extra 75
       * estiverem fora do período noturno as extras 75 são zeradas
       */
      if(!$this->horaEstaNoIntervalo($oComecoExtra75, $oHoraInicioPeriodoNoturno, $oHoraFimPeriodoNoturno)) {
        $oLimiteExtra75Noturna->setTime(0,0);
      }

      /**
       * Se o final da jornada acrescido das extras 50 e do limite de extra 75
       * NÃO estiverem fora do período noturno as extras 75 devem ser calculadas
       * @todo verificar isto aqui
       */
      if($this->horaEstaNoIntervalo($oComecoExtra75, $oHoraInicioPeriodoNoturno, $oHoraFimPeriodoNoturno)) {

        $oComecoExtra75->sub(new \DateInterval("PT". $oLimiteExtra75Noturna->format('H') ."H". $oLimiteExtra75Noturna->format('i') ."M"));

        $iHora   = $oComecoExtra75->diff($oHoraFimPeriodoNoturno)->h;
        $iMinuto = $oComecoExtra75->diff($oHoraFimPeriodoNoturno)->i;

        $oLimiteExtra75Noturna->setTime($iHora, $iMinuto);

        if($oLimiteExtra75Noturna->diff($oMaximoExtra75)->invert) {
          $iHora   = $oMaximoExtra75->format('H');
          $iMinuto = $oMaximoExtra75->format('i');
        }

        $oLimiteExtra75Noturna->setTime($iHora, $iMinuto);
      }
    }

    /**
     * Se as extras iniciais são maiores que as extras 50
     * então as extras 75 são calculadas a partir das extras iniciais
     */
    if(!$this->getHoraExtra50()->diff($this->getHoraExtraInicial())->invert) {

      $oComecoExtra75 = clone $this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada1()->getMarcacao();
      $oComecoExtra75->add(new \DateInterval("PT". $this->getHoraExtra50()->format('H') ."H". $this->getHoraExtra50()->format('i') ."M"));
      $oComecoExtra75->add(new \DateInterval("PT". $oLimiteExtra75Noturna->format('H') ."H". $oLimiteExtra75Noturna->format('i') ."M"));

      if(!$this->horaEstaNoIntervalo($oComecoExtra75, $oHoraInicioPeriodoNoturno, $oHoraFimPeriodoNoturno)) {
        if(!$oHoraFimPeriodoNoturnoDiaAnterior->diff($oComecoExtra75)->invert) {
          $oLimiteExtra75Noturna->setTime(0,0);
        }
      }
    }
  }
}
