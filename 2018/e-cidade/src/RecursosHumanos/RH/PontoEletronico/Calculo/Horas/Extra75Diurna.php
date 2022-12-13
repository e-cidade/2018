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
 * Class Extra75Diurna
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Extra75Diurna extends Extra75 implements Horas {

  /**
   * @var \DateTime
   */
  private $oMarcacaoEntrada;

  /**
   * @var \DateTime
   */
  private $oMarcacaoSaida;

  private $oJornadaEntrada;

  private $oJornadaSaida = null;

  /**
   * Extra75Diurna constructor.
   * @param DiaTrabalho $oDiaTrabalho
   */
  public function __construct(DiaTrabalho $oDiaTrabalho) {

    parent::__construct($oDiaTrabalho);
    $this->setTipoHora(BaseHora::HORAS_EXTRA75);

    $this->oHoraExtraInicialDiurna  = $this->getHoraExtraInicialDiurna();
    $this->oHoraExtraFinalDiurna    = $this->getHoraExtraFinalDiurna();
    $this->oHoraExtraInicial        = $this->getHoraExtraInicial();

    if(    $this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada1()          != null
      && $this->getDiaTrabalho()->getMarcacoes()->getUltimaMarcacaoComRegistro() != null) {

      if(    !$this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada1()->hasMarcacaoLancada()
        && !$this->getDiaTrabalho()->getMarcacoes()->getUltimaMarcacaoComRegistro()->hasMarcacaoLancada()) {

        $this->oMarcacaoEntrada = $this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada1()->getMarcacao();
        $this->oMarcacaoSaida   = $this->getDiaTrabalho()->getMarcacoes()->getUltimaMarcacaoComRegistro()->getMarcacao();

        $aHorasJornada         = $this->getDiaTrabalho()->getJornada()->getHoras();
        $this->oJornadaEntrada = $aHorasJornada[0]->oHora;

        if(isset($aHorasJornada[1])) {
          $this->oJornadaSaida   = $aHorasJornada[1]->oHora;
        }

        if(isset($aHorasJornada[3])) {
          $this->oJornadaSaida   = $aHorasJornada[3]->oHora;
        }

        if(isset($aHorasJornada[5])) {
          $this->oJornadaSaida = $aHorasJornada[5]->oHora;
        }
      }
    }
  }

  /**
   * Calcula o número de horas extra 75% em determinado dia
   * @return \DateTime
   */
  public function calcular() {

    $this->atualizaMaximoExtra75();

    if(is_null($this->oMarcacaoEntrada) && is_null($this->oMarcacaoSaida)) {
      return $this->getHoraZerada();
    }

    /**
     * Não há hora extra 75% configurada
     * RETORNO: 00:00
     */
    if($this->isZero($this->getMaximoExtra75())) {
      return $this->getHoraZerada();
    }

    if($this->getDiaTrabalho()->getJornada()->isFolga()) {
      $this->totalHorasExtrasFeriadoFolga();
    }

    $oHorasExtras75 = $this->verificarExistenciaHorasExtras();

    if(is_null($oHorasExtras75)) {
      return $this->getHoraZerada();
    }

    /**
     * Não há horas extras 75%
     * RETORNO: 00:00
     */
    if($this->isZero($oHorasExtras75)) {
      return $this->getHoraZerada();
    }

    $oJornadaSaidaAlterada   = clone $this->oJornadaSaida;
    $lTemHoraExtraInicio     = !$this->isZero($this->getHoraExtraInicial());

    /**
     * Casos sem hora extra inicial
     */
    if(!$lTemHoraExtraInicio) {

      /**
       * A saída da jornada não é um período noturno. Neste caso, as horas extras começam pela diurna
       */
      if(!$this->horaEstaNoIntervalo($this->oJornadaSaida, $this->getInicioHoraExtraNoturna(), $this->getFinalHoraExtraNoturna())) {

        /**
         * Incrementa na hora de saída da jornada, as horas extras 50%
         */
        $oIntervalExtra50 = new \DateInterval("PT{$this->getHoraExtra50()->format('H')}H{$this->getHoraExtra50()->format('i')}M");
        $oJornadaSaidaAlterada->add($oIntervalExtra50);

        $oHoraExtra = $this->validacaoSaidaPeriodoNoturno($oJornadaSaidaAlterada);

        if($oHoraExtra != null) {
          return $oHoraExtra;
        }

        /**
         * Não há hora extra final noturna
         * RETORNO: total de horas extras 75%
         */
        if($this->isZero($this->getHoraExtraFinalNoturna())) {
          return $oHorasExtras75;
        }
      }
    }

    /**
     * Casos com hora extra inicial
     */
    if($lTemHoraExtraInicio) {

      $oHorasExtraInicial50Pos = new \DateTime(
        $this->getDiaTrabalho()->getData()->getDate() . ' ' .
        $this->getHoraExtraInicial()->diff($this->getHoraExtra50())->format('%H:%i')
      );

      /**
       * Hora extra inicial não excede o limite de hora extras 50% configurado
       */
      if(!$this->getHoraExtraInicial()->diff($this->getHoraExtra50())->invert) {

        /**
         * Incrementa na hora de saída da jornada, as horas extras 50% finais
         */
        $oIntervalExtra50Pos = new \DateInterval("PT{$oHorasExtraInicial50Pos->format('H')}H{$oHorasExtraInicial50Pos->format('i')}M");
        $oJornadaSaidaAlterada->add($oIntervalExtra50Pos);

        $oHoraExtra = $this->validacaoSaidaPeriodoNoturno($oJornadaSaidaAlterada);

        if($this->getDiaTrabalho()->getJornada()->isFolga()) {

          /**
           * Se as extras finais forem maiores que o limite de extras 75
           * então verifica também se o retorno do intervalo acrescido do limite
           * não está dentro do horário noturno, se não estiver retorna o limite
           *
           */
          if($this->getHoraExtraFinal()->diff($this->getMaximoExtra75())->invert) {

            $marcacoes                = $this->getDiaTrabalho()->getMarcacoes();
            $marcacaoEntrada2Ficticia = $marcacoes->getMarcacaoEntrada2();
            if(!empty($marcacaoEntrada2Ficticia) && $marcacaoEntrada2Ficticia->getMarcacao() != null) {

              $marcacaoEntrada2Ficticia = clone $marcacaoEntrada2Ficticia->getMarcacao();
              $marcacaoEntrada2Ficticia->add(new \DateInterval(
                "PT"
                . $this->getMaximoExtra75()->format('H') . 'H'
                . $this->getMaximoExtra75()->format('i') . 'M'
              ));

              if(!$this->horaEstaNoIntervalo($marcacaoEntrada2Ficticia, $this->getInicioHoraExtraNoturna(), $this->getFinalHoraExtraNoturna())) {

                if($oHoraExtra != null) {
                  return $this->getMaximoExtra75();
                }
              }
            }
          }
        }

        if($oHoraExtra != null) {
          return $oHoraExtra;
        }
      }

      /**
       * Há hora extra inicial após calcular as horas extras 50%
       */
      if(!$this->isZero($oHorasExtraInicial50Pos)) {

        /**
         * Horas extras que sobraram excedem o limite máximo de extras 75%
         * RETORNO: máximo de horas extras 75%
         */
        if($oHorasExtraInicial50Pos->getTimestamp() >= $this->getMaximoExtra75()->getTimestamp()) {

          if($this->getDiaTrabalho()->getJornada()->isFolga()) {
            /**
             * Verifica se a hora da marcação acrescido das extras 50 está no período noturno,
             * se estiver então deve retornar vazio as extras
             */
            $oMarcacaoEntradaFicticia = clone $this->oMarcacaoEntrada;
            $oMarcacaoEntradaFicticia->add(new \DateInterval("PT{$this->getHoraExtra50()->format('H')}H{$this->getHoraExtra50()->format('i')}M"));
            $oFinalHoraExtraNoturnaDiaAnterior = clone $this->getFinalHoraExtraNoturna();
            $oFinalHoraExtraNoturnaDiaAnterior->modify('-1 day');

            if($this->horaEstaNoIntervalo($oMarcacaoEntradaFicticia, $this->getInicioHoraExtraNoturna(), $this->getFinalHoraExtraNoturna()) || $oFinalHoraExtraNoturnaDiaAnterior->diff($oMarcacaoEntradaFicticia)->invert) {
              return $this->getHoraZerada();
            }
          }

          return $this->getMaximoExtra75();
        }

        /**
         * Verifica se ao retirar as horas que sobraram da diferença entre o as extras 50
         * com as iniciais das horas extras finais resta algo,
         * se restar algo e for menor que o limite 75 então retorna essa diferenca
         */
        $diferancaExtra50ExtraInicial                   = $this->getHoraExtraInicial()->diff($this->getHoraExtra50());
        $diferancaExtra50ExtraInicialDateTime           = \DateTime::createFromFormat('Y-m-d H:i',
          $this->getDiaTrabalho()->getData()->getDate() . ' '
          . $diferancaExtra50ExtraInicial->format('%H') . ':'
          . $diferancaExtra50ExtraInicial->format('%I')
        );

        $extraFinalDebitadoDiferencaExtra50ExtraInicial = clone $this->getHoraExtraFinal();
        $extraFinalDebitadoDiferencaExtra50ExtraInicial->setDate(
          $this->getDiaTrabalho()->getData()->getAno(),
          $this->getDiaTrabalho()->getData()->getMes(),
          $this->getDiaTrabalho()->getData()->getDia()
        );
        $extraFinalDebitadoDiferencaExtra50ExtraInicial->sub($diferancaExtra50ExtraInicial);

        /**
         * Caso seja um dia de folga, verifica se a diferença entre as extras 50 com as iniciais
         * é maior ou igual ao limite, caso sim verifica também se as extras 75 não estão no horário
         * noturno então retorna o limite
         */
        if($this->getDiaTrabalho()->getJornada()->isFolga()) {

          if($diferancaExtra50ExtraInicialDateTime->diff($this->getMaximoExtra75())->invert || $this->getMaximoExtra75()->format('H:i') == $diferancaExtra50ExtraInicial->format('%H:%I')) {

            $oMarcacaoEntradaFicticia = clone $this->oMarcacaoEntrada;
            $oMarcacaoEntradaFicticia->add(new \DateInterval("PT{$this->getMaximoExtra75()->format('H')}H{$this->getMaximoExtra75()->format('i')}M"));

            if(!$this->horaEstaNoIntervalo($oMarcacaoEntradaFicticia, $this->getInicioHoraExtraNoturna(), $this->getFinalHoraExtraNoturna())) {
              return $this->getMaximoExtra75();
            }
          }
        }

        if($this->getMaximoExtra75()->getTimestamp() >= $extraFinalDebitadoDiferencaExtra50ExtraInicial->getTimestamp()) {
          return $extraFinalDebitadoDiferencaExtra50ExtraInicial;
        }
      }

      /**
       * Há hora extra final
       */
      if(!$this->isZero($this->getHoraExtraFinal())) {

        /**
         * Nâo há hora extra final noturna
         */
        if($this->isZero($this->getHoraExtraFinalNoturna())) {

          /**
           * Hora extra final excede o limite de extra 75% configurado
           * RETORNO: máximo de horas extras 75%
           */
          if($this->getHoraExtraFinal()->diff($this->getMaximoExtra75())->invert) {
            return $this->getMaximoExtra75();
          }

          return $this->getHoraExtraFinal();
        }
      }
    }

    /**
     * Sem hora extra inicial e final
     * RETORNO: 00:00
     */
    if($this->isZero($this->oHoraExtraInicialDiurna) && $this->isZero($this->oHoraExtraFinalDiurna) && $this->isZero($this->oHoraExtraInicial)) {
      return $this->getHoraZerada();
    }

    return $this->getHoraZerada();
  }

  /**
   * Valida se a marcação de saída está dentro do período noturno
   * @param \DateTime $oJornadaSaidaAlterada
   * @return \DateTime|null
   */
  private function validacaoSaidaPeriodoNoturno(\DateTime $oJornadaSaidaAlterada) {

    /**
     * Marcação de saída está dentro do período noturno
     */
    if($this->horaEstaNoIntervalo($this->oMarcacaoSaida, $this->getInicioHoraExtraNoturna(), $this->getFinalHoraExtraNoturna())) {

      if(!$this->isZero($this->getHoraExtraFinalDiurna())) {

        $oDiferencaJornadaSaidaInicioNoturna = new \DateTime(
          $this->getDiaTrabalho()->getData()->getDate() . ' ' .
          $this->getInicioHoraExtraNoturna()->diff($oJornadaSaidaAlterada)->format('%H:%i')
        );

        /**
         * O número de horas extras sobrando excede o limite configurado para 75%
         * RETORNO: máximo de horas extras 75% configurado
         */
        if($oDiferencaJornadaSaidaInicioNoturna->diff($this->getMaximoExtra75())->invert) {
          return $this->getMaximoExtra75();
        }

        return $oDiferencaJornadaSaidaInicioNoturna;
      }
    }

    return null;
  }
}