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
 * Classe respons�vel pelo c�lculo de hora extra 50% noturna de um servidor em um dia de trabalho
 * Class Extra50Noturna
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author F�bio Esteves <fabio.esteves@dbseller.com.br>
 */
class Extra50Noturna extends Extra50 implements Horas {

  /**
   * Construtor da classe. Seta o tipo de hora instanciado
   */
  public function __construct(DiaTrabalho $oDiaTrabalho) {

    parent::__construct($oDiaTrabalho, BaseHora::HORAS_EXTRA50_NOTURNA);

    $this->oHoraExtraInicialNoturna = $this->getHoraExtraInicialNoturna();
    $this->oHoraExtraFinalNoturna   = $this->getHoraExtraFinalNoturna();
    $this->oHoraExtraInicial        = $this->getHoraExtraInicial();
  }

  /**
   * Calcula o n�mero de horas extra 50% noturna em determinado dia
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

    /**
     * Sem hora extra inicial e final
     * RETORNO: 00:00
     */
    if($this->isZero($this->oHoraExtraInicialNoturna) && $this->isZero($this->oHoraExtraFinalNoturna) && $this->isZero($this->oHoraExtraInicial)) {
      return $this->getHoraZerada();
    }

    return $this->calcularDiaTrabalhado();
  }

  /**
   * Calcula as horas extras 50% noturnas em dia trabalhado que � folga
   */
  public function calcularFolga() {

    $this->totalHorasExtrasFeriadoFolga();

    $oHoraExtra                 = $this->getHoraZerada();
    $oHoraExtraInicial          = $this->getHoraExtraInicial();
    $oHoraExtraInicialNoturna   = $this->getHoraExtraInicialNoturna();
    $oHoraExtraFinalNoturna     = $this->getHoraExtraFinalNoturna();

    $oHoraInicioPeriodoNoturno        = clone $this->getInicioHoraExtraNoturna();
    $oHoraFinalPeriodoNoturno         = clone $this->getFinalHoraExtraNoturna();
    $oHoraFinalPeriodoNoturnoAnterior = new \DateTime($this->getDiaTrabalho()->getData()->getDate() . ' 05:00');

    $oMaximoExtra50 = new \DateTime($this->getDiaTrabalho()->getData()->getDate() . $this->getConfiguracoesLotacao()->getHoraExtra50());

    /**
     * Se as extras iniciais forem iguais as extras iniciais noturnas
     */
    if($oHoraExtraInicial->format('H:i') == $oHoraExtraInicialNoturna->format('H:i')) {

      /**
       * Se as extras iniciais forem maiores ou iguais ou maiores
       * ao limite de extras 50 seta as extras com o limite
       */
      if($oHoraExtraInicialNoturna->diff($oMaximoExtra50)->invert) {
        $oHoraExtra->setTime($oMaximoExtra50->format('H'), $oMaximoExtra50->format('i'));
      }

      /**
       * Se as extras iniciais forem menores ent�o seta as extras com o inicial
       * e verifica o final para adicionar o restante, se houver, n�o excedendo o teto
       */
      if(!$oHoraExtraInicialNoturna->diff($oMaximoExtra50)->invert) {

        $saldoParaAtingirTeto         = $oHoraExtraInicialNoturna->diff($oMaximoExtra50);
        $saldoParaAtingirTeto->invert = 0;

        $oHoraExtra->setTime($oHoraExtraInicialNoturna->format('H'), $oHoraExtraInicialNoturna->format('i'));

        /**
         * Se houver horas extras finais noturnas ent�o verifica o quanto deve ser adicionado
         */
        if($oHoraExtraFinalNoturna->format('H') != 0 || $oHoraExtraFinalNoturna->format('i') != 0) {

          $marcacaoEntrada2 = $this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada2();

          if(!empty($marcacaoEntrada2)) {

            $marcacaoEntrada2 = clone $marcacaoEntrada2->getMarcacao();
            $marcacaoEntrada2->add($saldoParaAtingirTeto);

            if($this->horaEstaNoIntervalo($marcacaoEntrada2, $oHoraInicioPeriodoNoturno, $oHoraFinalPeriodoNoturno) || $oHoraFinalPeriodoNoturnoAnterior->diff($marcacaoEntrada2)->invert) {
              $oHoraExtra->add($saldoParaAtingirTeto);
            }
          }
        }
      }
    }

    if($oHoraExtraInicial->format('H:i') != $oHoraExtraInicialNoturna->format('H:i')) {

      $marcacaoEntrada1 = $this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada1();
      $marcacaoEntrada1 = $marcacaoEntrada1->getMarcacao();

      // Se as extras iniciais s�o MAIORES que o m�ximo de extra 50
      if($oHoraExtraInicial->diff($oMaximoExtra50)->invert) {

        $novaMarcacaoEntrada1 = clone $marcacaoEntrada1;
        $novaMarcacaoEntrada1->add(new \DateInterval("PT". $oMaximoExtra50->format('H') .'H'. $oMaximoExtra50->format('i') .'M'));

        if($marcacaoEntrada1->diff($oHoraInicioPeriodoNoturno)->invert || $oHoraFinalPeriodoNoturnoAnterior->diff($marcacaoEntrada1)->invert) {

          if($oHoraFinalPeriodoNoturnoAnterior->diff($marcacaoEntrada1)->invert) {

            $oHoraExtra->setTime(
              $marcacaoEntrada1->diff($oHoraFinalPeriodoNoturnoAnterior)->h,
              $marcacaoEntrada1->diff($oHoraFinalPeriodoNoturnoAnterior)->i
            );
          } else {

            $oHoraExtra->setTime(
              $marcacaoEntrada1->diff($oHoraInicioPeriodoNoturno)->h,
              $marcacaoEntrada1->diff($oHoraInicioPeriodoNoturno)->i
            );
          }
        }

        if(!$marcacaoEntrada1->diff($oHoraInicioPeriodoNoturno)->invert) {

          if($novaMarcacaoEntrada1->diff($oHoraInicioPeriodoNoturno)->invert) {

            $oHoraExtra->setTime(
              $novaMarcacaoEntrada1->diff($oHoraInicioPeriodoNoturno)->h,
              $novaMarcacaoEntrada1->diff($oHoraInicioPeriodoNoturno)->i
            );
          }
        }
      }
    }

    return $oHoraExtra;
  }

  /**
   * Calcula as horas extras 50% noturnas em dia trabalhado normal
   */
  public function calcularDiaTrabalhado() {

    if((int)$this->getHorasExtrasNoturnas()->format('H') == 0 && (int)$this->getHorasExtrasNoturnas()->format('i') == 0) {
      return $this->getHoraZerada();
    }

    $oHoraExtraNoturna          = $this->getHorasExtrasNoturnas();
    $oHoraExtraInicial          = $this->getHoraExtraInicial();
    $oHoraExtraInicialDiurna    = $this->getHoraExtraInicialDiurna();
    $oHoraExtraInicialNoturna   = $this->getHoraExtraInicialNoturna();
    $oHoraExtraFinalDiurna      = $this->getHoraExtraFinalDiurna();
    $oHoraExtraFinalNoturna     = $this->getHoraExtraFinalNoturna();
    $oHoraExtraIntervaloNoturno = $this->getHoraExtraIntervaloNoturno();
    $oHoraExtraTotal            = $this->getHoraExtraTotal();
    $oHoraInicioPeriodoNoturno  = clone $this->getInicioHoraExtraNoturna();
    $oHoraFinalPeriodoNoturno   = clone $this->getFinalHoraExtraNoturna();
    $aHorasJornada              = $this->getDiaTrabalho()->getJornada()->getHoras();
    $oHoraFinalJornada          = end($aHorasJornada);
    $oHoraFinalJornada          = $oHoraFinalJornada->oHora;

    /**
     * Verifica se o total de horas extras noturnas � igual ao total de horas extras
     */
    $oHoraExtra = $this->totalHorasExtraIgualTotal($oHoraExtraNoturna, $oHoraExtraTotal);

    if($oHoraExtra instanceof \DateTime) {
      return $oHoraExtra;
    }

    $oHoraExtra = $this->getHoraZerada();

    // Se o total de extras iniciais � MENOR que o m�ximo de extras 50%
    if($this->getMaximoHorasExtras50()->diff($oHoraExtraInicial)->invert) {

      $oHoraExtra->setTime(
        $oHoraExtraInicialNoturna->format('H'),
        $oHoraExtraInicialNoturna->format('i')
      );

      /**
       * Se o total de extras iniciais � igual �s extras iniciais noturnas
       * deve-se somar tamb�m as extras de intervalo noturno e extras finais noturnas
       */
      if($oHoraExtraInicial->format('H:i') == $oHoraExtraInicialNoturna->format('H:i')) {

        $oHoraExtra->add(new \DateInterval("PT{$oHoraExtraIntervaloNoturno->format('H')}H{$oHoraExtraIntervaloNoturno->format('i')}M"));

        /**
         * Se o total de extras n�o excedeu o m�ximo
         * deve somar as extras finais noturnas
         */
        if(!$oHoraExtra->diff($this->getMaximoHorasExtras50())->invert) {

          /**
           * Se a jornada termina no per�odo de extra noturna
           */
          if($oHoraFinalJornada instanceof \DateTime) {

            if($this->horaEstaNoIntervalo($oHoraFinalJornada, $oHoraInicioPeriodoNoturno, $oHoraFinalPeriodoNoturno)) {

              // Se as extras finais noturnas s�o maiores que as extras diurnas
              if($oHoraExtraFinalNoturna->diff($oHoraExtraFinalDiurna)->invert) {
                $oHoraExtra->add(new \DateInterval("PT{$oHoraExtraFinalNoturna->format('H')}H{$oHoraExtraFinalNoturna->format('i')}M"));
              }
            }
          }
        }

        /**
         * Se o total de extras excedeu o m�ximo,
         * ent�o reseta as extras para o m�ximo
         */
        if($oHoraExtra->diff($this->getMaximoHorasExtras50())->invert) {
          $oHoraExtra = clone $this->getMaximoHorasExtras50();
        }

        // Se o total de extras iniciais � diferente das extras iniciais noturnas
      } else {

        /**
         * Se as extras iniciais s�o menores que o m�ximo de extra 50%
         */
        if($this->getMaximoHorasExtras50()->diff($oHoraExtraInicial)->invert) {

          //Define o limite de extras noturnas
          $oLimiteExtraInicialNoturna = $this->getHoraZerada();
          $oLimiteExtraInicialNoturna->setTime(
            $this->getMaximoHorasExtras50()->diff($oHoraExtraInicialDiurna)->format('%H'),
            $this->getMaximoHorasExtras50()->diff($oHoraExtraInicialDiurna)->format('%I')
          );

          $oHoraExtra->add(new \DateInterval("PT{$oHoraExtraIntervaloNoturno->format('H')}H{$oHoraExtraIntervaloNoturno->format('i')}M"));

          /**
           * Se houver mais extras noturnas (finais) ent�o adiciona a diferen�a at� o m�ximo
           */
          if((int)$oHoraExtraFinalNoturna->format('H') != 0 || (int)$oHoraExtraFinalNoturna->format('i') != 0) {

            $oDiferencaLimiteExtraInicial = new \DateTime($this->getDiaTrabalho()->getData()->getDate() .' 00:00');
            $oDiferencaLimiteExtraInicial->setTime(
              $this->getMaximoHorasExtras50()->diff($oHoraExtraInicial)->format('%H'),
              $this->getMaximoHorasExtras50()->diff($oHoraExtraInicial)->format('%I')
            );

            /**
             * Se a jornada termina ap�s o in�cio do per�odo de extra noturna
             */
            if($oHoraFinalJornada->diff($oHoraInicioPeriodoNoturno)->invert || ($oHoraFinalJornada->format('H:i') == $oHoraInicioPeriodoNoturno->format('H:i'))) {

              // Se as horas extras noturnas finais forem  maiores que a diferen�a at� o limite ent�o adiciona a diferen�a
              if($oHoraExtraFinalNoturna->diff($oDiferencaLimiteExtraInicial)->invert) {
                $oHoraExtra->add(new \DateInterval("PT{$oDiferencaLimiteExtraInicial->format('H')}H{$oDiferencaLimiteExtraInicial->format('i')}M"));
              }

              // Se as horas extras noturnas finais forem menores que a diferen�a at� o limite ent�o adiciona as extras finais
              if(!$oHoraExtraFinalNoturna->diff($oDiferencaLimiteExtraInicial)->invert) {
                $oHoraExtra->add(new \DateInterval("PT{$oHoraExtraFinalNoturna->format('H')}H{$oHoraExtraFinalNoturna->format('i')}M"));
              }
            }
          }

          /**
           * Se o total de extras excedeu o m�ximo para as extras noturnas
           */
          if($oHoraExtra->diff($oLimiteExtraInicialNoturna)->invert) {
            $oHoraExtra = clone $oLimiteExtraInicialNoturna;
          }
        }
      }
    }

    // Se o total de extras iniciais � MAIOR que o m�ximo de extras 50%
    if(!$this->getMaximoHorasExtras50()->diff($oHoraExtraInicial)->invert) {

      $oMarcacaoEntrada              = $this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada1();
      $oHoraFinalPeriodoNoturnoNoDia = new \DateTime($this->getDiaTrabalho()->getData()->getDate() .' 05:00');

      if(!empty($oMarcacaoEntrada)) {

        /**
         * Se a marca��o de entrada come�a no per�odo noturno (22:00 as 05:00)
         * ent�o as extras iniciais come�am pelas noturnas
         */
        if($this->horaEstaNoIntervalo($oMarcacaoEntrada->getMarcacao(), $oHoraInicioPeriodoNoturno, $oHoraFinalPeriodoNoturno) || $oHoraFinalPeriodoNoturnoNoDia->diff($oMarcacaoEntrada->getMarcacao())->invert) {

          // Se as extras iniciais noturnas s�o menores que o m�ximo retorna como extras as iniciais noturnas
          if($this->getMaximoHorasExtras50()->diff($oHoraExtraInicialNoturna)->invert) {

            $oHoraExtra->setTime(
              $oHoraExtraInicialNoturna->format('H'),
              $oHoraExtraInicialNoturna->format('i')
            );
          }

          // Se as extras iniciais noturnas s�o MAIORES ou iguais ao m�ximo retorna o m�ximo
          if(!$this->getMaximoHorasExtras50()->diff($oHoraExtraInicialNoturna)->invert || ($this->getMaximoHorasExtras50()->format('H:i') == $oHoraExtraInicialNoturna->format('H:i')) ) {

            $oHoraExtra->setTime(
              $this->getMaximoHorasExtras50()->format('H'),
              $this->getMaximoHorasExtras50()->format('i')
            );
          }

          // Caso a marca��o de entrada n�o tenha ocorrido no per�odo noturno (22:00 as 05:00)
        } else {

          /**
           * Se as extras iniciais diurnas s�o maiores que o m�ximo j� passou o limite, logo n�o tem extras noturnas
           */
          if($oHoraExtraInicialDiurna->diff($this->getMaximoHorasExtras50())->invert) {
            $oHoraExtra->setTime(0, 0);
          }

          /**
           * Se as extras iniciais diurnas s�o MENORES que o m�ximo deve retornar a diferen�a
           * entre o m�ximo de extras e as extras iniciais diurnas
           */
          if(!$oHoraExtraInicialDiurna->diff($this->getMaximoHorasExtras50())->invert) {

            $oHoraExtra->setTime(
              $oHoraExtraInicialDiurna->diff($this->getMaximoHorasExtras50())->h,
              $oHoraExtraInicialDiurna->diff($this->getMaximoHorasExtras50())->i
            );
          }
        }
      }
    }

    return $oHoraExtra;
  }
}