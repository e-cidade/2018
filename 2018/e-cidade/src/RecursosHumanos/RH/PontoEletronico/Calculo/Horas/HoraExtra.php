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

use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto;

/**
 * Classe com métodos padrões para os tipos de hora exta
 * Class HoraExtra
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Renan Silva <renan.silva@dbseller.com.br>
 */
class HoraExtra extends BaseHora {

  /**
   * @var \DateTime
   */
  protected $oHoraExtraInicial;

  /**
   * @var \DateTime
   */
  protected $oHoraExtraInicialNoturna;

  /**
   * @var \DateTime
   */
  protected $oHoraExtraInicialDiurna;

  /**
   * @var \DateTime
   */
  protected $oHoraExtraFinalDiurna;

  /**
   * @var \DateTime
   */
  protected $oHoraExtraFinalNoturna;

  /**
   * @var \DateTime
   */
  protected $oHoraExtraFinal;

  /**
   * @var \DateTime
   */
  protected $oHoraExtraIntervalo;

  /**
   * @var \DateTime
   */
  protected $oHoraExtraIntervaloNoturna;

  /**
   * @var \DateTime
   */
  protected $oHoraExtraSomaInicialFinal;

  /**
   * @var \DateTime
   */
  protected $oHoraExtraTotal;

  /**
   * @var \DateTime
   */
  private $oInicioHoraExtraNoturna;

  /**
   * @var \DateTime
   */
  private $oFinalHoraExtraNoturna;

  /**
   * @var \DateTime
   */
  protected $oHoraExtraDiurna = null;

  /**
   * @var \DateTime
   */
  protected $oHoraExtraNoturna = null;

  /**
   * @var bool
   */
  private $lCalculouNoturnaInicial = false;

  /**
   * @var bool
   */
  private $lCalculouNoturnaFinal = false;

  /**
   * Total Calculado de Horas Extras para a Tolerancia em minutos
   * @var array;
   */
  private $totalCalculadoDeTolerancia = array();

  /**
   * Total de horas Calculadas
   * @var array
   */
  private $totalHorasCalculadas = array();

  /**
   * HoraExtra constructor.
   */
  public function __construct() {

    parent::__construct();

    $this->oInicioHoraExtraNoturna = new \DateTime("{$this->getDiaTrabalho()->getData()->getDate()} 22:00");
    $this->oFinalHoraExtraNoturna  = new \DateTime(
      ("{$this->getDiaTrabalho()->getData()->getAno()}-{$this->getDiaTrabalho()->getData()->getMes()}-". ($this->getDiaTrabalho()->getData()->getDia()))
      .' '.
      "05:00"
    );
    $this->oFinalHoraExtraNoturna->modify('+1 day');

    $this->totalHorasExtrasDiurnasNoturnas();
  }

  /**
   * @return \DateTime
   */
  public function getInicioHoraExtraNoturna() {
    return $this->oInicioHoraExtraNoturna;
  }

  /**
   * @return \DateTime
   */
  public function getFinalHoraExtraNoturna() {
    return $this->oFinalHoraExtraNoturna;
  }

  /**
   * Verifica o número de horas extras. Por padrão, compara a primeira marcação com base na primeira hora da jornada.
   * Se $lPrimeiraHoras for false, compara a última marcação com base na última hora da jornada
   * @param $oHoras
   * @param bool $lPrimeirasHoras
   * @return \DateTime
   * @throws \BusinessException
   */
  protected function verificaHorasExtras($oHoras, $lPrimeirasHoras = true) {

    $oHoraExtra = $this->getHoraZerada();

    if(!$this->oHoraExtraInicialNoturna instanceof \DateTime) {
      $this->oHoraExtraInicialNoturna  = $this->getHoraZerada();
    }

    if(!$this->oHoraExtraFinalNoturna instanceof \DateTime) {
      $this->oHoraExtraFinalNoturna = $this->getHoraZerada();
    }

    if($this->getConfiguracoesLotacao() == null) {
      throw new \BusinessException('Configurações de Lotação não realizadas.( RH > Procedimentos > Ponto Eletrônico > Configurações )');
    }

    $iTolerancia             = $this->getConfiguracoesLotacao()->getTolerancia();
    $oDataIntervalTolerancia = new \DateInterval("PT{$iTolerancia}M");

    /**
     * Regra da toleracia padrão (artigo 58 da CLT): 5 minutos
     * Caso a tolerância configurada seja menor, não é considerada essa regra
     */
    $iToleranciaLei          = $iTolerancia > 5 ? 5 : 0;
    $tempoToleranciaNaBatida = new \DateInterval("PT{$iToleranciaLei}M");

    if(is_null($oHoras->oHoraMarcacao)) {
      return $oHoraExtra;
    }

    if ($lPrimeirasHoras) {

      $oHoraJornadaPrimeirasHoras = clone $oHoras->oHoraJornada;
      if ($oHoraJornadaPrimeirasHoras->diff($oHoras->oHoraMarcacao)->invert) {

        $intervaloNaEntrada = $oHoras->oHoraJornada->diff($oHoras->oHoraMarcacao);
        $iHora   = $intervaloNaEntrada->h;
        $iMinuto = $intervaloNaEntrada->i;


        $this->totalCalculadoDeTolerancia["entrada"] = BaseHora::converterIntervaloEmMinutos($intervaloNaEntrada);
        $this->totalHorasCalculadas[] = BaseHora::converterIntervaloEmMinutos($intervaloNaEntrada);
        if ($iHora > 0 || $iMinuto > $tempoToleranciaNaBatida->format("%I")) {

          $oHoraExtra->setTime($iHora, $iMinuto);
          $this->totalCalculadoDeTolerancia["entrada"] = 0;
        }
        if(!$this->lCalculouNoturnaInicial) {

          $this->lCalculouNoturnaInicial  = true;
          $this->oHoraExtraInicialNoturna = $this->calculaHoraExtraNoturnaInicio($oHoras->oHoraMarcacao, $oHoras->oHoraJornada);
        }
      }
    }

    if(!$lPrimeirasHoras) {

      $oHoraJornadaUltimasHoras = clone $oHoras->oHoraJornada;
      if ($oHoras->oHoraMarcacao->diff($oHoraJornadaUltimasHoras)->invert) {

        $diferencaHoraExtra = $oHoras->oHoraMarcacao->diff($oHoras->oHoraJornada);
        $iHora              = $diferencaHoraExtra->h;
        $iMinuto            = $diferencaHoraExtra->i;

        /**
         * Adiciona no calculo de tolerancia a diferenca de hora extra.
         */
        $this->totalCalculadoDeTolerancia["saida"] = BaseHora::converterIntervaloEmMinutos($diferencaHoraExtra);
        $this->totalHorasCalculadas[] = BaseHora::converterIntervaloEmMinutos($diferencaHoraExtra);
        if ($iHora > 0 || $iMinuto > $tempoToleranciaNaBatida->format("%I")) {

          $oHoraExtra->setTime($iHora, $iMinuto);
          /**
           * a tolerancia foi computada como hora extra, não é necessário mais computar na tolerancia
           */
          $this->totalCalculadoDeTolerancia["saida"] = 0;
        }

        if(!$this->lCalculouNoturnaFinal) {

          $this->lCalculouNoturnaFinal  = true;
          $this->oHoraExtraFinalNoturna = $this->calculaHoraExtraNoturnaFim($oHoras->oHoraMarcacao, $oHoras->oHoraJornada);
        }
      }
    }

    /**
     * Acerta o total das horas Extra, conforme regra da toleracia (artigo 58 da CLT 5 minutos por batida nao ultrpassando 10 minutos Diários)
     */
    $minutosDeTolerancia  = $oDataIntervalTolerancia->i;
    $totalHorasTolerancia = array_sum($this->totalCalculadoDeTolerancia);
    $totalHorasExtras     = array_sum($this->totalHorasCalculadas);
    if ($totalHorasExtras > $minutosDeTolerancia || ($oHoraExtra->format('H') > 0  || $oHoraExtra->format('i') > $minutosDeTolerancia)) {
      $oHoraExtra->add(new \DateInterval("PT{$totalHorasTolerancia}M"));
    }
    return $oHoraExtra;
  }

  /**
   * @return \DateTime
   */
  protected function verificaHorasExtrasIntervalo() {

    $oHoraExtra = $this->getHoraZerada();

    if(!$this->getDiaTrabalho()->getJornada()->temIntervalo()) {
      return $oHoraExtra;
    }

    $aMarcacoes = $this->getDiaTrabalho()->getMarcacoes();
    if ($aMarcacoes->getMarcacaoSaida1() == null || $aMarcacoes->getMarcacaoEntrada2() == null) {
      return $oHoraExtra;
    }

    if ($aMarcacoes->getMarcacaoSaida1()->getJustificativa() != null || $aMarcacoes->getMarcacaoEntrada2()->getJustificativa() != null) {
      return $oHoraExtra;
    }

    $aHorasJornada     = $this->getDiaTrabalho()->getJornada()->getHoras();
    $oIntervaloJornada = $this->getHoraZerada();
    $oIntervaloJornada->add($aHorasJornada[1]->oHora->diff($aHorasJornada[2]->oHora));

    if (!$aMarcacoes->getMarcacaoSaida1()->hasMarcacaoLancada() && !$aMarcacoes->getMarcacaoEntrada2()->hasMarcacaoLancada()) {

      $oIntervaloMarcacoes = $this->getHoraZerada();
      $oIntervaloMarcacoes->add($aMarcacoes->getMarcacaoSaida1()->getMarcacao()->diff($aMarcacoes->getMarcacaoEntrada2()->getMarcacao()));

      if (!$oIntervaloMarcacoes->diff($oIntervaloJornada)->invert) {
        $oHoraExtra->add($oIntervaloMarcacoes->diff($oIntervaloJornada));
      }
    }

    return $oHoraExtra;
  }

  /**
   * Calcula o total de horas extras no dia
   * @param bool $lCalcula
   */
  protected function totalHorasExtras($lCalcula = true) {

    if($lCalcula) {

      $this->oHoraExtraInicial          = $this->verificaHorasExtras($this->getPrimeirasHoras());
      $this->oHoraExtraFinal            = $this->verificaHorasExtras($this->getUltimasHoras(), false);
      $this->oHoraExtraIntervalo        = $this->verificaHorasExtrasIntervalo();
      $this->oHoraExtraIntervaloNoturna = $this->calcularHoraExtraNoturnaIntervalo();
    }

    $this->oHoraExtraInicialDiurna    = \DateTime::createFromFormat('Y-m-d H:i',
      $this->getDiaTrabalho()->getData()->getDate()
      .' '.
      $this->oHoraExtraInicial->diff($this->oHoraExtraInicialNoturna)->format('%H:%I')
    );

    $this->oHoraExtraFinalDiurna      = \DateTime::createFromFormat('Y-m-d H:i',
      $this->getDiaTrabalho()->getData()->getDate()
      .' '.
      $this->oHoraExtraFinal->diff($this->oHoraExtraFinalNoturna)->format('%H:%I')
    );


    $this->oHoraExtraSomaInicialFinal = new \DateTime(
      $this->getDiaTrabalho()->getData()->getDate()
      .' '.
      $this->somaHoras($this->oHoraExtraInicial, $this->oHoraExtraFinal)->format('H:i')
    );

    $this->oHoraExtraTotal            = new \DateTime(
      $this->getDiaTrabalho()->getData()->getDate()
      .' '.
      $this->somaHoras($this->oHoraExtraSomaInicialFinal, $this->oHoraExtraIntervalo)->format('H:i')
    );
  }

  /**
   *
   */
  public function totalHorasExtrasFeriadoFolga() {

    $this->oHoraExtraIntervalo        = $this->getHoraZerada();
    $this->oHoraExtraIntervaloNoturna = $this->getHoraZerada();
    $this->oHoraExtraTotal            = $this->getHoraZerada();
    $this->oHoraExtraDiurna           = $this->getHoraZerada();
    $this->oHoraExtraNoturna          = $this->getHoraZerada();

    $this->oHoraExtraTotal->setTime($this->getHorasTrabalhadas()->format('H'), $this->getHorasTrabalhadas()->format('i'));

    $extrasFolgaFeriado = $this->calcularHorasExtrasFolgaFeriadoNoturna();

    $this->oHoraExtraNoturna->setTime($extrasFolgaFeriado->format('H'), $extrasFolgaFeriado->format('i'));
    $this->oHoraExtraDiurna->setTime($this->oHoraExtraTotal->diff($this->oHoraExtraNoturna)->h, $this->oHoraExtraTotal->diff($this->oHoraExtraNoturna)->i);
  }

  /**
   * Calcula o total de horas extras diurnas
   */
  private function totalHorasExtrasDiurnas() {

    if($this->isZero($this->oHoraExtraTotal)) {
      $this->oHoraExtraDiurna  = $this->getHoraZerada();
    }

    if(!$this->oHoraExtraDiurna instanceof \DateTime) {
      $this->oHoraExtraDiurna  = $this->getHoraZerada();
    }

    if(!$this->oHoraExtraNoturna instanceof \DateTime) {
      $this->oHoraExtraNoturna  = $this->getHoraZerada();
    }

    if($this->oHoraExtraNoturna->diff($this->oHoraExtraTotal) !== false) {

      $iHora   = $this->oHoraExtraNoturna->diff($this->oHoraExtraTotal)->h;
      $iMinuto = $this->oHoraExtraNoturna->diff($this->oHoraExtraTotal)->i;

      $this->oHoraExtraDiurna->setTime($iHora, $iMinuto);
    }
  }

  /**
   * Calcula o total de horas extras diurnas e noturnas
   */
  public function totalHorasExtrasDiurnasNoturnas($lCalcula = true) {

    $this->totalHorasExtras($lCalcula);
    $this->totalHorasExtrasDiurnas();
  }

  /**
   * @param      $oHoraMarcacaoEntrada
   * @param null $oHoraJornadaEntrada
   * @return \DateTime
   */
  protected function calculaHoraExtraNoturnaInicio($oHoraMarcacaoEntrada, $oHoraJornadaEntrada = null) {

    $oHoraExtraNoturnaInicio = $this->getHoraZerada();

    if(!$this->oHoraExtraNoturna instanceof \DateTime) {
      $this->oHoraExtraNoturna = $this->getHoraZerada();
    }

    /**
     * Se a hora da marcação de entrada for maior que a hora final do
     * período de extra noturna (05:00) então não tem hora extra no início
     */
    if($oHoraMarcacaoEntrada->diff($this->oFinalHoraExtraNoturna)->invert) {
      return $this->getHoraZerada();
    }

    // Se a hora da marcação de entrada for menor que a hora final do período de extra noturna (05:00)
    if(!$oHoraMarcacaoEntrada->diff($this->oFinalHoraExtraNoturna)->invert) {

      $iHora   = 0;
      $iMinuto = 0;

      /**
       * Se a hora da marcação de entrada for maior que a hora inicial do período de exta noturna (22:00)
       */
      if($oHoraMarcacaoEntrada->diff($this->oInicioHoraExtraNoturna)->invert) {

        /**
         * Se a hora da marcação for menor que a hora da de entrada da jornada
         */
        if($oHoraJornadaEntrada->diff($oHoraMarcacaoEntrada)->invert) {

          /**
           * Se a hora de entrada da jornada e menor que a hora final do período de extra noturna
           * então adiciona a diferença entre a hora de entrada da jornada e a hora da marcação
           */
          if($this->oFinalHoraExtraNoturna->diff($oHoraJornadaEntrada)->invert) {

            $iHora   = $oHoraMarcacaoEntrada->diff($oHoraJornadaEntrada)->h;
            $iMinuto = $oHoraMarcacaoEntrada->diff($oHoraJornadaEntrada)->i;
          }
        }
      }

      /**
       * Se a hora da marcação de entrada for menor que a hora inicial do período de extra noturna (22:00)
       */
      if(!$oHoraMarcacaoEntrada->diff($this->oInicioHoraExtraNoturna)->invert) {

        /**
         * Se a hora inicial da jornada for após o início de período extra noturna (22:00)
         * então faz a diferença entre o início do período de extra noturna (22:00) e o início da jornada
         */
        if($oHoraJornadaEntrada->diff($this->oInicioHoraExtraNoturna)->invert) {

          if(!empty($oHoraJornadaEntrada)) {

            $iHora   = $this->oInicioHoraExtraNoturna->diff($oHoraJornadaEntrada)->h;
            $iMinuto = $this->oInicioHoraExtraNoturna->diff($oHoraJornadaEntrada)->i;
          }
        }

        /**
         * Se o horário de entrada for antes das 05:00 do próprio dia, para isso
         * construimos nova varíavel com horário das 05:00 para fazer a diferença
         */
        $oHoraFinalExtraNoturnaNoDia = new \DateTime($this->getDiaTrabalho()->getData()->getDate() .' 05:00');

        if($oHoraFinalExtraNoturnaNoDia->diff($oHoraMarcacaoEntrada)->invert) {

          $iHora    = $oHoraFinalExtraNoturnaNoDia->diff($oHoraMarcacaoEntrada)->h;
          $iMinuto  = $oHoraFinalExtraNoturnaNoDia->diff($oHoraMarcacaoEntrada)->i;
        }
      }

      $this->oHoraExtraNoturna->setTime($iHora, $iMinuto);
      $oHoraExtraNoturnaInicio->setTime($iHora, $iMinuto);
    }

    return $oHoraExtraNoturnaInicio;
  }

  /**
   * @param      $oHoraMarcacaoSaida
   * @param null $oHoraJornadaSaida
   * @return \DateTime
   */
  protected function calculaHoraExtraNoturnaFim(\DateTime $oHoraMarcacaoSaida, $oHoraJornadaSaida = null) {

    $oHoraExtraNoturnaFim = $this->getHoraZerada();

    if(!$this->oHoraExtraNoturna instanceof \DateTime) {
      $this->oHoraExtraNoturna = $this->getHoraZerada();
    }

    /**
     * Se a hora da marcação da saída for menor que o início da extra noturna (22:00)
     * então não tem extra noturna no final do período de trabalho
     */
    if($oHoraMarcacaoSaida->getTimestamp() < $this->oInicioHoraExtraNoturna->getTimestamp()) {
      return $this->getHoraZerada();
    }

    /**
     * Se a hora da marcação da saída for maior ou igual que o início da extra noturna (22:00)
     */
    if($oHoraMarcacaoSaida->getTimeStamp() >= $this->oInicioHoraExtraNoturna->getTimestamp()) {

      $iHora   = 0;
      $iMinuto = 0;

      if(!empty($oHoraJornadaSaida)) {

        /**
         * Se a hora final do período de extra noturno (05:00) for maior que a hora da marcação
         * então faz a diferenca entre a hora da marcação e o início do período de extra noturno
         */
        if($this->oFinalHoraExtraNoturna->diff($oHoraMarcacaoSaida)->invert) {

          /**
           * Se a hora final da jornada for menor que a marcação então faz
           * a diferença entre a hora do início do período de extra noturna e a hora da marcação
           */
          if($oHoraMarcacaoSaida->diff($oHoraJornadaSaida)->invert) {

            $iHora   = $this->oInicioHoraExtraNoturna->diff($oHoraMarcacaoSaida)->h;
            $iMinuto = $this->oInicioHoraExtraNoturna->diff($oHoraMarcacaoSaida)->i;

            /**
             * Se a hora final da jornada estiver entre o período noturno (22:00) e (05:00)
             * a diferença entre a hora final da jornada e a hora da marcação
             */
            if($oHoraJornadaSaida->diff($this->oInicioHoraExtraNoturna)->invert) {

              $iHora   = $oHoraJornadaSaida->diff($oHoraMarcacaoSaida)->h;
              $iMinuto = $oHoraJornadaSaida->diff($oHoraMarcacaoSaida)->i;
            }
          }
        }

        /**
         * Se a hora final do período de extra noturno (05:00) for menor que a hora da marcação
         */
        if(!$this->oFinalHoraExtraNoturna->diff($oHoraMarcacaoSaida)->invert) {

          /**
           * Se a hora final da jornada for menor que a hora final do período de extra noturno (05:00)
           * então faz a difença entre o período final de extra noturno (05:00) e a hora final da jornada
           */
          if($this->oFinalHoraExtraNoturna->diff($oHoraJornadaSaida)->invert) {

            $iHora   = $this->oFinalHoraExtraNoturna->diff($oHoraJornadaSaida)->h;
            $iMinuto = $this->oFinalHoraExtraNoturna->diff($oHoraJornadaSaida)->i;
          }
        }
      }

      $this->oHoraExtraNoturna->add(new \DateInterval("PT{$iHora}H{$iMinuto}M"));

      $oHoraExtraNoturnaFim->setTime($iHora, $iMinuto);
    }

    return $oHoraExtraNoturnaFim;
  }

  private function calcularHoraExtraNoturnaIntervalo() {

    $oHoraExtraIntervalo = $this->getHoraZerada();
    $aMarcacoes          = $this->getDiaTrabalho()->getMarcacoes();

    if($aMarcacoes->getMarcacaoSaida1() != null && $aMarcacoes->getMarcacaoEntrada2() != null) {

      if(!$aMarcacoes->getMarcacaoSaida1()->hasMarcacaoLancada() && !$aMarcacoes->getMarcacaoEntrada2()->hasMarcacaoLancada()) {

        $aHorasJornada     = $this->getDiaTrabalho()->getJornada()->getHoras();
        $oIntervaloJornada = $this->getHoraZerada();
        $oIntervaloJornada->add($aHorasJornada[1]->oHora->diff($aHorasJornada[2]->oHora));

        if(!empty($aHorasJornada[3]) && !empty($aHorasJornada[4])) {
          $oIntervaloJornada->add($aHorasJornada[3]->oHora->diff($aHorasJornada[4]->oHora));
        }

        $oIntervaloMarcacoes = $this->getHoraZerada();
        $oMarcacaoSaida1     = clone $aMarcacoes->getMarcacaoSaida1()->getMarcacao();
        $oMarcacaoEntrada2   = clone $aMarcacoes->getMarcacaoEntrada2()->getMarcacao();

        $oIntervaloMarcacoes->add($this->verificarHorasExtrasIntervaloNoturno($oMarcacaoSaida1, $oMarcacaoEntrada2));

        if($aMarcacoes->getMarcacaoSaida2() != null && $aMarcacoes->getMarcacaoEntrada3() != null) {

          if(!$aMarcacoes->getMarcacaoSaida2()->hasMarcacaoLancada() && !$aMarcacoes->getMarcacaoEntrada3()->hasMarcacaoLancada()) {

            $oMarcacaoSaida2   = clone $aMarcacoes->getMarcacaoSaida2()->getMarcacao();
            $oMarcacaoEntrada3 = clone $aMarcacoes->getMarcacaoEntrada3()->getMarcacao();

            $oIntervaloMarcacoes->add($this->verificarHorasExtrasIntervaloNoturno($oMarcacaoSaida2, $oMarcacaoEntrada3));
          }
        }

        if(!$oIntervaloMarcacoes->diff($oIntervaloJornada)->invert) {

          if($oIntervaloMarcacoes->format('H:i') != '00:00') {
            $oHoraExtraIntervalo->add($oIntervaloMarcacoes->diff($oIntervaloJornada));
          }
        }
      }
    }

    $iHora   = $oHoraExtraIntervalo->format('H');
    $iMinuto = $oHoraExtraIntervalo->format('i');

    if(!$this->oHoraExtraNoturna instanceof \DateTime) {
      $this->oHoraExtraNoturna = $this->getHoraZerada();
    }

    $this->oHoraExtraNoturna->add(new \DateInterval("PT{$iHora}H{$iMinuto}M"));

    return $oHoraExtraIntervalo;
  }

  public function calcularHorasExtrasFolgaFeriadoNoturna() {

    $oHoraExtra            = $this->getHoraZerada();
    $marcacoes             = $this->getDiaTrabalho()->getMarcacoes()->getMarcacoes();
    $oInicioPeriodoNoturno = new \DateTime($this->getDiaTrabalho()->getData()->getDate().' 22:00');
    $oFimPeriodoNoturno    = new \DateTime($this->getDiaTrabalho()->getData()->getDate().' 05:00');
    $oFimPeriodoNoturno->modify('+1 day');
    $oFimPeriodoNoturnoAnterior = new \DateTime($this->getDiaTrabalho()->getData()->getDate().' 05:00');

    $lTemMarcacaoPeriodoNoturno = false;
    foreach ($marcacoes as $marcacao) {

      if($marcacao->getMarcacao() == null) {
        continue;
      }

      if($this->horaEstaNoIntervalo($marcacao->getMarcacao(), $oInicioPeriodoNoturno, $oFimPeriodoNoturno) || $oFimPeriodoNoturnoAnterior->diff($marcacao->getMarcacao())->invert) {

        $lTemMarcacaoPeriodoNoturno = true;

        switch ($marcacao->getTipo()) {

          case MarcacaoPonto::SAIDA_1:
          case MarcacaoPonto::SAIDA_2:
          case MarcacaoPonto::SAIDA_3:

            $marcacaoEntradaVinculada = $marcacao->getMarcacaoEntrada();

            if(    $marcacaoEntradaVinculada != null
              && !$this->horaEstaNoIntervalo($marcacaoEntradaVinculada, $oInicioPeriodoNoturno, $oFimPeriodoNoturno) && !$oFimPeriodoNoturnoAnterior->diff($marcacao->getMarcacao())->invert) {
              $marcacao->setMarcacaoEntrada(clone $oInicioPeriodoNoturno);
            }

            $intervaloTrabalhado = $marcacao->getHorarioTrabalhado();

            if(!empty($intervaloTrabalhado)) {

              $oHoraExtra->add($intervaloTrabalhado);

              if($this->getDiaTrabalho()->getJornada()->isFolga()) {
                if ($marcacao->getTipo() != MarcacaoPonto::SAIDA_1) {
                  $this->oHoraExtraFinalNoturna->add($intervaloTrabalhado);
                }

                if ($marcacao->getTipo() == MarcacaoPonto::SAIDA_1) {
                  $this->oHoraExtraInicialNoturna->setTime($intervaloTrabalhado->h, $intervaloTrabalhado->i);
                }
              }
            }

            $marcacao->setMarcacaoEntrada($marcacaoEntradaVinculada);
            break;

          default:

            $proximaMarcacao = $this->getDiaTrabalho()->getMarcacoes()->getMarcacao($marcacao->getTipo()+1);

            if(!empty($proximaMarcacao)) {

              if($marcacao->getMarcacao() == null || $proximaMarcacao->getMarcacao() == null) {
                break;
              }

              if($this->horaEstaNoIntervalo($marcacao->getMarcacao(), $oInicioPeriodoNoturno, $oFimPeriodoNoturno)) {

                if(!$this->horaEstaNoIntervalo($proximaMarcacao->getMarcacao(), $oInicioPeriodoNoturno, $oFimPeriodoNoturno)) {

                  $periodoExtraNoturno = $marcacao->getMarcacao()->diff($oFimPeriodoNoturno);

                  $oHoraExtra->add(new \DateInterval("PT"
                    . $periodoExtraNoturno->h . "H"
                    . $periodoExtraNoturno->i . "M"
                  ));

                  if($this->getDiaTrabalho()->getJornada()->isFolga()) {
                    if ($marcacao->getTipo() == MarcacaoPonto::ENTRADA_1) {
                      $this->oHoraExtraInicialNoturna->add($periodoExtraNoturno);
                    }

                    if ($marcacao->getTipo() != MarcacaoPonto::ENTRADA_1) {
                      $this->oHoraExtraFinalNoturna->add($periodoExtraNoturno);
                    }
                  }
                }
              }

              if(!$this->horaEstaNoIntervalo($marcacao->getMarcacao(), $oInicioPeriodoNoturno, $oFimPeriodoNoturno)) {

                if(!$oFimPeriodoNoturnoAnterior->diff($proximaMarcacao->getMarcacao())->invert) {

                  if($oFimPeriodoNoturnoAnterior->diff($marcacao->getMarcacao())->invert) {

                    $periodoExtraNoturno = $marcacao->getMarcacao()->diff($oFimPeriodoNoturnoAnterior);

                    $oHoraExtra->add(new \DateInterval("PT"
                      . $periodoExtraNoturno->h . "H"
                      . $periodoExtraNoturno->i . "M"
                    ));

                    if($this->getDiaTrabalho()->getJornada()->isFolga()) {
                      if ($marcacao->getTipo() == MarcacaoPonto::ENTRADA_1) {
                        $this->oHoraExtraInicialNoturna->add($periodoExtraNoturno);
                      }

                      if ($marcacao->getTipo() != MarcacaoPonto::ENTRADA_1) {
                        $this->oHoraExtraFinalNoturna->add($periodoExtraNoturno);
                      }
                    }
                  }
                }
              }
            }

            break;
        }
      }
    }

    if($lTemMarcacaoPeriodoNoturno === false) {
      $oHoraExtra->setTime(0, 0);
    }

    if($this->getDiaTrabalho()->getJornada()->isFolga()) {

      $marcacoesSaida = $this->getDiaTrabalho()->getMarcacoes()->getMarcacoesSaida();

      if(!empty($marcacoesSaida)) {

        $marcacaoSaida       = $marcacoesSaida[0];
        $periodoExtraInicial = $marcacaoSaida->getHorarioTrabalhado();

        if(!empty($periodoExtraInicial)) {
          $this->oHoraExtraInicial->setTime($periodoExtraInicial->h, $periodoExtraInicial->i);
        }
      }

      $periodoExtraFinal = $this->oHoraExtraInicial->diff($this->oHoraExtraTotal);
      $this->oHoraExtraFinal->setTime($periodoExtraFinal->h, $periodoExtraFinal->i);

      $this->oHoraExtraInicialDiurna = \DateTime::createFromFormat('Y-m-d H:i',
        $this->getDiaTrabalho()->getData()->getDate()
        .' '.
        $this->oHoraExtraInicial->diff($this->oHoraExtraInicialNoturna)->format('%H:%I')
      );

      $this->oHoraExtraFinalDiurna = \DateTime::createFromFormat('Y-m-d H:i',
        $this->getDiaTrabalho()->getData()->getDate()
        .' '.
        $this->oHoraExtraFinal->diff($this->oHoraExtraFinalNoturna)->format('%H:%I')
      );
    }

    return $oHoraExtra;
  }

  private function verificarHorasExtrasIntervaloNoturno($oMarcacaoSaida, $oMarcacaoEntrada) {

    $oIntervalo = $this->getHoraZerada();

    if($oMarcacaoEntrada instanceof \DateTime && $oMarcacaoSaida instanceof \DateTime) {

      if($this->horaEstaNoIntervalo($oMarcacaoEntrada, $this->oInicioHoraExtraNoturna, $this->oFinalHoraExtraNoturna)) {

        if(!$this->horaEstaNoIntervalo($oMarcacaoSaida, $this->oInicioHoraExtraNoturna, $this->oFinalHoraExtraNoturna)) {
          $oMarcacaoSaida->setTime($this->oInicioHoraExtraNoturna->format('H'), $this->oInicioHoraExtraNoturna->format('i'));
        }

      } else {

        if($this->horaEstaNoIntervalo($oMarcacaoSaida, $this->oInicioHoraExtraNoturna, $this->oFinalHoraExtraNoturna)) {
          $oMarcacaoEntrada->setTime($this->oFinalHoraExtraNoturna->format('H'), $this->oFinalHoraExtraNoturna->format('i'));
        }
      }

      if(   $this->horaEstaNoIntervalo($oMarcacaoEntrada, $this->oInicioHoraExtraNoturna, $this->oFinalHoraExtraNoturna)
        || $this->horaEstaNoIntervalo($oMarcacaoSaida, $this->oInicioHoraExtraNoturna, $this->oFinalHoraExtraNoturna))
      {

        $iHora   = $oMarcacaoEntrada->diff($oMarcacaoSaida)->h;
        $iMinuto = $oMarcacaoEntrada->diff($oMarcacaoSaida)->i;

        $oIntervalo->add(new \DateInterval("PT{$iHora}H{$iMinuto}M"));
      }
    }

    $iHora   = $oIntervalo->format('H');
    $iMinuto = $oIntervalo->format('i');

    return new \DateInterval("PT{$iHora}H{$iMinuto}M");
  }

  /**
   * Retorna as horas extras diurnas
   * @return \DateTime
   */
  public function getHorasExtrasDiurnas() {
    return $this->oHoraExtraDiurna;
  }

  /**
   * Retorna as horas extras noturnas
   * @return \DateTime
   */
  public function getHorasExtrasNoturnas() {
    return $this->oHoraExtraNoturna;
  }

  /**
   * Retorna o total de HoraExtraInicial
   * @return \DateTime
   */
  public function getHoraExtraInicial() {
    return $this->oHoraExtraInicial;
  }

  /**
   * Retorna a Hora Extra Inicial Noturna
   * @return \DateTime
   */
  public function getHoraExtraInicialNoturna() {
    return $this->oHoraExtraInicialNoturna;
  }

  /**
   * Retorna a Hora Extra Inicial Diurna
   * @return \DateTime
   */
  public function getHoraExtraInicialDiurna() {
    return $this->oHoraExtraInicialDiurna;
  }

  /**
   * Retorna a Hora Extra Final Diurna
   * @return \DateTime
   */
  public function getHoraExtraFinalDiurna() {
    return $this->oHoraExtraFinalDiurna;
  }

  /**
   * Retorna a Hora Extra Final Noturna
   * @return \DateTime
   */
  public function getHoraExtraFinalNoturna() {
    return $this->oHoraExtraFinalNoturna;
  }

  /**
   * Retorna o total de HoraExtraFinal
   * @return \DateTime
   */
  public function getHoraExtraFinal() {
    return $this->oHoraExtraFinal;
  }

  /**
   * Retorna o total de HoraExtraIntervalo
   * @return \DateTime
   */
  public function getHoraExtraIntervalo() {
    return $this->oHoraExtraIntervalo;
  }

  /**
   * Retorna o total de HoraExtraIntervaloNoturno
   * @return \DateTime
   */
  public function getHoraExtraIntervaloNoturno()
  {
    return $this->oHoraExtraIntervaloNoturna;
  }

  /**
   * Retorna o total de HoraExtraSomaInicialFinal
   * @return \DateTime
   */
  public function getHoraExtraSomaInicialFinal() {
    return $this->oHoraExtraSomaInicialFinal;
  }

  /**
   * Retorna o total de HoraExtraTotal
   * @return \DateTime
   */
  public function getHoraExtraTotal() {
    return $this->oHoraExtraTotal;
  }

  /**
   * @param \DateTime $oHora
   * @return bool
   */
  public function isZero(\DateTime $oHora) {
    return (int) $oHora->format('H') == 0 && (int) $oHora->format('i') == 0;
  }

  /**
   * Atualiza as marcações com base nas horas extras autorizadas
   */
  public function atualizaTotalizadoresEMarcacoes() {

    if($this->getDiaTrabalho()->getMarcacoes()->isEmpty()){
      return;
    }

    $oHorasAutorizadas    = $this->getDiaTrabalho()->getHorasExtrasAutorizadas();
    $oHorasExtrasSobrando = clone $oHorasAutorizadas;

    $aJornada        = $this->getDiaTrabalho()->getJornada()->getHoras();
    $oJornadaEntrada = $aJornada[0];
    $oJornadaSaida   = end($aJornada);

    $oPrimeiraMarcacao = clone $this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada1();
    $oUltimaMarcacao   = clone $this->getDiaTrabalho()->getMarcacoes()->getUltimaMarcacaoComRegistro();

    $oHoraExtraInicial = $this->getHoraZerada();

    if($oJornadaEntrada->oHora->getTimestamp() > $oPrimeiraMarcacao->getMarcacao()->getTimestamp()){

      $intervaloHoraExtraInicio = $this->getDiferencaHoras($oPrimeiraMarcacao->getMarcacao(), $oJornadaEntrada->oHora);
      $oHoraExtraInicial->add($intervaloHoraExtraInicio);
    }


    $oHoraExtraFinal = $this->getHoraZerada();

    if($oJornadaSaida->oHora->getTimestamp() < $oUltimaMarcacao->getMarcacao()->getTimestamp()) {

      $intervaloHoraExtraFinal  = $this->getDiferencaHoras($oJornadaSaida->oHora, $oUltimaMarcacao->getMarcacao());
      $oHoraExtraFinal->add($intervaloHoraExtraFinal);
    }

    $oSomaExtraInicialFinal = $this->somaHoras($oHoraExtraInicial, $oHoraExtraFinal);

    /**
     * Casos em que há hora extra inicial
     */
    if($oSomaExtraInicialFinal->getTimestamp() > $oHorasAutorizadas->getTimestamp()) {

      if(!$this->isZero($oHoraExtraInicial)) {

        if($oHoraExtraInicial->getTimestamp() > $oHorasExtrasSobrando->getTimestamp()){

          $oMarcacaoAtualizada  = clone $oJornadaEntrada->oHora;
          $oMarcacaoAtualizada->modify("- {$oHorasExtrasSobrando->format('H')} hour");
          $oMarcacaoAtualizada->modify("- {$oHorasExtrasSobrando->format('i')} minute");

          $oPrimeiraMarcacao->setMarcacao($oMarcacaoAtualizada);
          $this->getDiaTrabalho()->getMarcacoes()->atualizaMarcacao($oPrimeiraMarcacao);
          $oHorasExtrasSobrando = $this->getHoraZerada();
        }

        if($oHorasExtrasSobrando->getTimestamp() >= $oHoraExtraInicial->getTimestamp()){
          $oHorasExtrasSobrando->modify("- {$oHoraExtraInicial->format('H')} hour");
          $oHorasExtrasSobrando->modify("- {$oHoraExtraInicial->format('i')} minute");
        }
      }


      /**
       * Casos em que há hora extra no intervalo
       */
      $oHoraExtraIntervalo = $this->verificaHorasExtrasIntervalo();

      if(!$this->isZero($oHoraExtraIntervalo)){

        /**
         * Quando hora extra no intervalo ultrapassa as extras sobrando
         */
        if($oHoraExtraIntervalo->getTimestamp() > $oHorasExtrasSobrando->getTimestamp()){
          $oJornadaSaida1    = $aJornada[2];
          $oJornadaEntrada2  = $aJornada[3];
          $oSegundaMarcacao  = clone $this->getDiaTrabalho()->getMarcacoes()->getMarcacaoSaida1();
          $oTerceiraMarcacao = clone $this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada2();

          $oIntervaloExtraIntervaloSobrando = $this->getDiferencaHoras($oHorasExtrasSobrando, $oHoraExtraIntervalo);
          $oTerceiraMarcacao->getMarcacao()->add($oIntervaloExtraIntervaloSobrando);
          $this->getDiaTrabalho()->getMarcacoes()->atualizaMarcacao($oTerceiraMarcacao);

          $oHorasExtrasSobrando = $this->getHoraZerada();
        }

        /**
         * Quando a quantidade de horas extras do intervalo são menores que o valor de horas extras sobrando
         * Decrementa as extras do intervalo das horas extras sobrando
         */
        if($oHoraExtraIntervalo->getTimestamp() <= $oHorasExtrasSobrando->getTimestamp()){
          $oHorasExtrasSobrando->modify("- {$oHoraExtraIntervalo->format('H')} hour");
          $oHorasExtrasSobrando->modify("- {$oHoraExtraIntervalo->format('i')} minute");
        }

      }

      /**
       * Casos em que há hora extra final
       */
      if(!$this->isZero($oHoraExtraFinal)) {

        /**
         * Quando o total de hora extra final é maior que as horas extras liberadas(já atualizadas quando há extra inicial)
         * Atualiza a marcação de saída, limitando as horas extras que sobraram
         */
        if($oHoraExtraFinal->getTimestamp() > $oHorasExtrasSobrando->getTimestamp()) {

          $oMarcacaoAtualizada = $this->somaHoras($oJornadaSaida->oHora, $oHorasExtrasSobrando);
          $oUltimaMarcacao->setMarcacao($oMarcacaoAtualizada);
          $this->getDiaTrabalho()->getMarcacoes()->atualizaMarcacao($oUltimaMarcacao);
        }
      }
    }
  }
}
