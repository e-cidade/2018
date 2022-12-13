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
 * Class Extra50Diurna
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author F�bio Esteves <fabio.esteves@dbseller.com.br>
 */
class Extra50Diurna extends Extra50 implements Horas {

  private $oHoraExtra;
  private $aHorasJornada;
  private $lJornadaIniciaPeriodoNoturno;
  private $oMarcacaoEntrada;
  private $oMarcacaoSaida;
  private $oHorasDescontadas;

  /**
   * Extra50Diurna constructor.
   * @param DiaTrabalho $oDiaTrabalho
   */
  public function __construct(DiaTrabalho $oDiaTrabalho) {

    parent::__construct($oDiaTrabalho, BaseHora::HORAS_EXTRA50);

    $this->oHoraExtra                   = $this->getHoraZerada();
    $this->oHoraExtraDiurna             = $this->getHorasExtrasDiurnas();
    $this->oHoraExtraTotal              = $this->getHoraExtraTotal();
    $this->oHoraExtraInicial            = $this->getHoraExtraInicial();
    $this->oHoraExtraInicialDiurna      = $this->getHoraExtraInicialDiurna();
    $this->oHoraExtraInicialNoturna     = $this->getHoraExtraInicialNoturna();
    $this->oHoraExtraFinal              = $this->getHoraExtraFinal();
    $this->oHoraExtraFinalDiurna        = $this->getHoraExtraFinalDiurna();
    $this->oHoraExtraFinalNoturna       = $this->getHoraExtraFinalNoturna();
    $this->aHorasJornada                = $this->getDiaTrabalho()->getJornada()->getHoras();
    $this->lJornadaIniciaPeriodoNoturno = $this->horaEstaNoIntervalo(
      $this->aHorasJornada[0]->oHora, $this->getInicioHoraExtraNoturna(), $this->getFinalHoraExtraNoturna()
    );

    if(   $this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada1() != null
      && $this->getDiaTrabalho()->getMarcacoes()->getUltimaMarcacaoComRegistro() != null
    ) {

      if(    !$this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada1()->hasMarcacaoLancada()
        && !$this->getDiaTrabalho()->getMarcacoes()->getUltimaMarcacaoComRegistro()->hasMarcacaoLancada()) {

        $this->oMarcacaoEntrada = $this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada1()->getMarcacao();
        $this->oMarcacaoSaida   = $this->getDiaTrabalho()->getMarcacoes()->getUltimaMarcacaoComRegistro()->getMarcacao();
      }
    }
  }

  /**
   * Calcula o n�mero de horas extra 50% em determinado dia
   * @return \DateTime
   */
  public function calcular() {

    if($this->getDiaTrabalho()->getFeriado() instanceof Feriado) {

      if($this->getDiaTrabalho()->getFeriado()->getData()->getDate() == $this->getDiaTrabalho()->getData()->getDate()) {
        return $this->getHoraZerada();
      }
    }

    if($this->getDiaTrabalho()->getJornada()->isDSR()) {
      return $this->getHoraZerada();
    }

    if($this->getDiaTrabalho()->getJornada()->isFolga()) {
      return $this->calcularFolga();
    }

    if((int)$this->oHoraExtraDiurna->format('H') == 0 && (int)$this->oHoraExtraDiurna->format('i') == 0) {
      return $this->getHoraZerada();
    }

    /**
     * Sem hora extra inicial e final
     * RETORNO: 00:00
     */
    if($this->isZero($this->oHoraExtraInicialDiurna) && $this->isZero($this->oHoraExtraFinalDiurna) && $this->isZero($this->oHoraExtraInicial)) {
      return $this->getHoraZerada();
    }

    /**
     * Verifica se o total de horas extras diurnas � igual ao total de horas extras
     */
    $this->oHoraExtra = $this->totalHorasExtraIgualTotal($this->oHoraExtraDiurna, $this->oHoraExtraTotal);

    if($this->oHoraExtra instanceof \DateTime) {
      return $this->oHoraExtra;
    }

    /**
     * HORA EXTRA INICIAL TOTAL � MAIOR QUE O M�XIMO DE HORAS EXTRAS 50%
     */
    if(!$this->getMaximoHorasExtras50()->diff($this->oHoraExtraInicial)->invert) {
      $this->oHoraExtra = $this->validacoesTotalMaiorConfigurado();
    }

    /**
     * HORA EXTRA INICIAL TOTAL � MENOR QUE O M�XIMO DE HORAS EXTRAS 50%
     */
    if($this->getMaximoHorasExtras50()->diff($this->oHoraExtraInicial)->invert) {
      $this->oHoraExtra = $this->validacoesTotalMenorConfigurado();
    }

    return $this->oHoraExtra;
  }

  /**
   * @return \DateTime|null
   */
  private function validacoesTotalMaiorConfigurado() {

    if(!empty($this->oMarcacaoEntrada)) {

      $oHoraInicioPeriodoNoturnoNoDia = clone $this->getInicioHoraExtraNoturna();
      $oHoraFinalPeriodoNoturnoNoDia  = clone $this->getFinalHoraExtraNoturna();

      $oInicioHoraExtraNoturna = clone $this->getInicioHoraExtraNoturna();
      $oFinalHoraExtraNoturna  = clone $this->getFinalHoraExtraNoturna();

      $oInicioHoraExtraNoturna->modify('-1 day');
      $oFinalHoraExtraNoturna->modify('-1 day');

      /**
       * Marca��o de entrada est� dentro do per�odo noturno
       */
      if(    $this->horaEstaNoIntervalo($this->oMarcacaoEntrada, $this->getInicioHoraExtraNoturna(), $this->getFinalHoraExtraNoturna())
        || $oHoraFinalPeriodoNoturnoNoDia->diff($this->oMarcacaoEntrada)->invert
      ) {

        if(!$this->getMaximoHorasExtras50()->diff($this->oHoraExtraInicialNoturna)->invert) {
          return $this->oHoraExtra;
        }

        $this->oHorasDescontadas = new \DateTime(
          $this->getDiaTrabalho()->getData()->getDate() . ' ' .
          $this->oHoraExtraInicialNoturna->format('H:i')
        );
      }

      /**
       * Marca��o de entrada est� fora do per�odo noturno
       */
      if(!$this->horaEstaNoIntervalo($this->oMarcacaoEntrada, $oInicioHoraExtraNoturna, $oFinalHoraExtraNoturna)) {

        if(!$this->oMarcacaoEntrada->diff($oHoraInicioPeriodoNoturnoNoDia)->invert) {

          $oHoraDiurnaInicial = new \DateTime(
            $this->getDiaTrabalho()->getData()->getDate() . ' ' .
            $this->oMarcacaoEntrada->diff($oHoraInicioPeriodoNoturnoNoDia)->format('%H:%i')
          );

          if($oHoraDiurnaInicial->diff($this->getMaximoHorasExtras50())->invert) {
            return $this->getMaximoHorasExtras50();
          }

          return $oHoraDiurnaInicial;
        }
      }
    }

    /**
     * Jornada inicia fora do per�odo noturno
     */
    if(!$this->lJornadaIniciaPeriodoNoturno) {

      /**
       * Total de hora extra inicial diurna � igual ao m�ximo de horas extras 50%
       * RETORNO: valor m�ximo configurado
       */
      if($this->getMaximoHorasExtras50()->format('H:i') == $this->oHoraExtraInicialDiurna->format('H:i')) {
        return $this->calculaSemHorasDesconto($this->getMaximoHorasExtras50());
      }

      /**
       * Total de hora extra inicial diurna � maior que o m�ximo de horas extras 50%
       * RETORNO: valor m�ximo configurado
       */
      if(!$this->getMaximoHorasExtras50()->diff($this->oHoraExtraInicialDiurna)->invert) {
        return $this->calculaSemHorasDesconto($this->getMaximoHorasExtras50());
      }
    }

    /**
     * Jornada inicia dentro do per�odo noturno
     */
    if($this->lJornadaIniciaPeriodoNoturno) {

      /**
       * Total de horas extras iniciais noturnas � igual ou maior que o m�ximo de horas extras 50%
       * RETORNO: 00:00
       */
      if(!$this->getMaximoHorasExtras50()->diff($this->oHoraExtraInicialNoturna)->invert) {
        return $this->oHoraExtra;
      }

      $oHorasIniciaisSobrando = new \DateTime(
        $this->getDiaTrabalho()->getData()->getDate() . ' ' .
        $this->getMaximoHorasExtras50()->diff($this->oHoraExtraInicialNoturna)->format('%H:%i')
      );

      /**
       * Total de horas extras inicial diurna � maior do que o n�mero de horas extras que sobraram(descontadas horas
       * extras iniciais noturnas e calculando com base no m�ximo permitido para 50%)
       * RETORNO: n�mero de horas que sobraram
       */
      if($oHorasIniciaisSobrando->diff($this->oHoraExtraInicialDiurna)->invert) {
        return $oHorasIniciaisSobrando;
      }

      /**
       * Total de horas extras inicial diurna � menor do que o n�mero de horas extras que sobraram
       */
      if(!$oHorasIniciaisSobrando->diff($this->oHoraExtraInicialDiurna)->invert) {

        $oIntervalExtraFinalDiurna = new \DateInterval("PT{$this->oHoraExtraFinalDiurna->format('H')}H{$this->oHoraExtraFinalDiurna->format('i')}M");
        $oHoraDiurnaSomada         = clone $this->oHoraExtraInicialDiurna;
        $oHoraDiurnaSomada->add($oIntervalExtraFinalDiurna);

        /**
         * Total de horas extras inicial e final diurna � maior que o n�mero de horas extras que sobraram
         * RETORNO: n�mero de horas extras que sobraram
         */
        if($oHorasIniciaisSobrando->diff($oHoraDiurnaSomada)->invert) {
          return $oHorasIniciaisSobrando;
        }

        /**
         * RETORNO: Total de horas extras inicial e final diurna
         */
        return $oHoraDiurnaSomada;
      }
    }

    return $this->getHoraZerada();
  }

  /**
   * @return \DateTime|null
   */
  private function validacoesTotalMenorConfigurado() {

    $oIntervalFinalDiurna = new \DateInterval("PT{$this->oHoraExtraFinalDiurna->format('H')}H{$this->oHoraExtraFinalDiurna->format('i')}M");
    $oHoraDiurnaSomada    = clone $this->oHoraExtraInicialDiurna;
    $oHoraDiurnaSomada->add($oIntervalFinalDiurna);

    /**
     * N�o h� hora extra ap�s a jornada
     * RETORNO: hora extra inicial diurna
     */
    if($this->isZero($this->oHoraExtraFinal)) {
      return $this->oHoraExtraInicialDiurna;
    }

    /**
     * N�o h� hora extra inicial noturna
     */
    if($this->isZero($this->oHoraExtraInicialNoturna)) {

      /**
       * Total de horas extras inicial e final diurna � maior que o m�ximo de horas extras 50%
       * RETORNO: valor m�ximo configurado
       */
      if(!$this->getMaximoHorasExtras50()->diff($oHoraDiurnaSomada)->invert) {
        return $this->getMaximoHorasExtras50();
      }

      /**
       * Total de horas extras inicial e final diurna � menor que o m�ximo de horas extras 50%
       * RETORNO: total de horas extras inicial e final diurna
       */
      if($this->getMaximoHorasExtras50()->diff($oHoraDiurnaSomada)->invert) {
        return new \DateTime($this->getMaximoHorasExtras50()->diff($oHoraDiurnaSomada)->format('%H:%i'));
      }
    }


    $oMarcacaoEntrada         = clone $this->oMarcacaoEntrada;
    $oMarcacaoEntradaFicticia = clone $oMarcacaoEntrada;
    $oMarcacaoEntradaFicticia->add(new \DateInterval("PT". $this->getMaximoHorasExtras50()->format('H') .'H'. $this->getMaximoHorasExtras50()->format('i') .'M'));

    /**
     * Se a hora ficticia (marca��o mais configura��o de extra 50) for maior que o inicio do per�do noturno (22:00) ent�o devo verificar se a hora de entrada real fazer a diferen�a
     */
    if($oMarcacaoEntradaFicticia->diff($this->getInicioHoraExtraNoturna())->invert) {

      if($this->getInicioHoraExtraNoturna()->diff($oMarcacaoEntrada)->invert) {

        /**
         * Total de horas extras inicial(diurna + noturna), n�o excedeu o limite de 50% configurado
         */
        if(!$this->getHoraExtraInicial()->diff($this->getMaximoHorasExtras50())->invert) {

          $oDiferencaInicialComMaximo         = $this->getHoraExtraInicial()->diff($this->getMaximoHorasExtras50());
          $oDiferencaInicialComMaximo->invert = 0;

          if(!$this->getInicioHoraExtraNoturna()->diff($this->oMarcacaoSaida)->invert) {

            $this->oHoraExtra = clone $this->getHoraExtraInicialDiurna();
            $this->oHoraExtra->add($oDiferencaInicialComMaximo);
          }

          return $this->oHoraExtra;
        }
      }
    }

    /**
     * H� hora extra inicial noturna
     */
    $oHorasIniciaisSobrando    = new \DateTime($this->getDiaTrabalho()->getData()->getDate() . ' ' . $this->oHoraExtraInicial->format('H:i'));
    $oIntervalExtraFinalDiurna = new \DateInterval("PT{$this->oHoraExtraFinalDiurna->format('H')}H{$this->oHoraExtraFinalDiurna->format('i')}M");

    $oHorasIniciaisSobrando->add($oIntervalExtraFinalDiurna);

    /**
     * Soma das horas extras inicial(diurna + noturna) com a extra final diurna � maior que o m�ximo de horas extras 50%
     * RETORNO: valor m�ximo configurado
     */
    if(!$this->getMaximoHorasExtras50()->diff($oHorasIniciaisSobrando)->invert) {
      return $this->getMaximoHorasExtras50();
    }

    /**
     * Quando a jornada inicia dentro do per�odo noturno
     */
    if($this->lJornadaIniciaPeriodoNoturno) {

      $oDiferencaMaximoComInicialNoturna = new \DateTime(
        $this->getDiaTrabalho()->getData()->getDate() . ' ' .
        $this->getMaximoHorasExtras50()->diff($this->oHoraExtraInicialNoturna)->format('%H:%i')
      );

      /**
       * Total de horas extras diurnas(inicial e final) est� dentro do valor m�ximo para hora extra 50%
       * RETORNO: total de horas extras diurnas(inicial e final)
       */
      if($oDiferencaMaximoComInicialNoturna->diff($this->oHoraExtraDiurna)->invert) {
        return $this->oHoraExtraDiurna;
      }

      /**
       * Total de horas extras diurnas(inicial e final) � maior que o valor m�ximo para hora extra 50%
       * RETORNO: n�mero de horas dentro do limite
       */
      return new \DateTime(
        $this->getDiaTrabalho()->getData()->getDate() . ' ' .
        $oDiferencaMaximoComInicialNoturna->diff($this->oHoraExtraDiurna)->format('%H:%i')
      );
    }

    return $this->getHoraZerada();
  }

  /**
   * @param \DateTime $oHora
   * @return \DateTime
   */
  private function calculaSemHorasDesconto(\DateTime $oHora) {

    if(empty($this->oHorasDescontadas)) {
      return $oHora;
    }

    return new \DateTime(
      $this->getDiaTrabalho()->getData()->getDate() . ' ' .
      $oHora->diff($this->oHorasDescontadas)->format('%H:%i')
    );
  }

  /**
   * @return \DateTime
   */
  protected function calcularFolga() {

    if($this->oMarcacaoEntrada == null) {
      return $this->getHoraZerada();
    }

    $oTotalHorasTrabalhadas  = clone $this->getHorasTrabalhadas();
    $oInicioHoraExtraNoturna = clone $this->getInicioHoraExtraNoturna()->modify('-1 day');
    $oFinalHoraExtraNoturna  = clone $this->getFinalHoraExtraNoturna()->modify('-1 day');

    if($this->oMarcacaoEntrada->format('H:i') >= '22:00' && $this->oMarcacaoEntrada->format('H:i') <= '23:59') {

      $oInicioHoraExtraNoturna->modify('+1 day');
      $oFinalHoraExtraNoturna->modify('+1 day');
    }

    $lMarcacaoPeriodoNoturno = $this->horaEstaNoIntervalo($this->oMarcacaoEntrada, $oInicioHoraExtraNoturna, $oFinalHoraExtraNoturna);

    /**
     * Jornadas que n�o tem in�cio em um per�odo noturno
     */
    if(!$this->lJornadaIniciaPeriodoNoturno || $lMarcacaoPeriodoNoturno) {

      /**
       * Quando h� hora extra 50% configurada
       */
      if(!$this->isZero($this->getMaximoHorasExtras50())) {

        /**
         * O total de horas extras 50% n�o � maior que o total de horas trabalhdas
         */
        if(!$this->getMaximoHorasExtras50()->diff($oTotalHorasTrabalhadas)->invert) {

          $oMarcacaoEntrada = $this->getMarcacaoEntradaComMaximoExtra50();

          /**
           * Marca��o de entrada original est� dentro do per�odo noturno
           */
          if($lMarcacaoPeriodoNoturno) {

            if($this->horaEstaNoIntervalo($oMarcacaoEntrada, $oInicioHoraExtraNoturna, $oFinalHoraExtraNoturna)) {
              return $this->oHoraExtra;
            }

            $oIntervalExtrasNoturnas = $this->oMarcacaoEntrada->diff($oFinalHoraExtraNoturna);
            $oHorasExtrasNoturnas    = $this->getHoraZerada();
            $oHorasExtrasNoturnas->add($oIntervalExtrasNoturnas);

            $oMaximoExtras50 = clone $this->getMaximoHorasExtras50();

            if(!$oHorasExtrasNoturnas->diff($oMaximoExtras50)->invert) {

              $oMaximoExtras50->sub($oIntervalExtrasNoturnas);
              return $oMaximoExtras50;
            }
          }

          /**
           * Marca��o de entrada com o valor de horas extras n�o est� dentro do intervalo noturno
           * RETORNO: m�ximo de horas extras 50%
           */
          if(!$this->horaEstaNoIntervalo($oMarcacaoEntrada, $oInicioHoraExtraNoturna, $oFinalHoraExtraNoturna)) {
            return $this->getMaximoHorasExtras50();
          }
        }
      }
    }

    /**
     * Marca��o de entrada foi anterior ao in�cio da jornada
     */
    if(!$this->oMarcacaoEntrada->diff($this->aHorasJornada[0]->oHora)->invert) {

      $oMarcacaoEntrada = $this->getMarcacaoEntradaComMaximoExtra50();

      /**
       * Pega a diferen�a entre o in�cio da hora extra noturna com a marca��o de entrada
       */
      $oDiferencaMarcacaoEntradaNoturno = $this->getHoraZerada();
      $oDiferencaMarcacaoEntradaNoturno->add($this->getInicioHoraExtraNoturna()->diff($oMarcacaoEntrada));

      /**
       * Retira do total de horas extras, o quanto foi feito de hora noturna
       */
      $oMaximoHorasExtra50 = clone $this->getMaximoHorasExtras50();
      $oMaximoHorasExtra50->modify(
        "-{$oDiferencaMarcacaoEntradaNoturno->format('H')} hour -{$oDiferencaMarcacaoEntradaNoturno->format('i')} minutes"
      );

      return $oMaximoHorasExtra50;
    }

    if(!$oTotalHorasTrabalhadas->diff($this->getMaximoHorasExtras50())->invert) {
      return $oTotalHorasTrabalhadas;
    }

    return $this->oHoraExtra;
  }

  /**
   * @return \DateTime
   */
  private function getMarcacaoEntradaComMaximoExtra50() {

    $oIntervalMaximoExtras50 = new \DateInterval(
      "PT{$this->getMaximoHorasExtras50()->format('H')}H{$this->getMaximoHorasExtras50()->format('i')}M"
    );

    $oMarcacaoEntrada = clone $this->oMarcacaoEntrada;
    $oMarcacaoEntrada->add($oIntervalMaximoExtras50);

    return $oMarcacaoEntrada;
  }
}
