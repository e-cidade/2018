<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Manutencao;

use ECidade\RecursosHumanos\RH\Efetividade\Model\Periodo;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\ProcessamentoPontoEletronico;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Repository\DiaTrabalho as DiaTrabalhoRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho as DiaTrabalhoModel;
use ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Repository\Evento;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto;
use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosRepository;

/**
 * Classe para montagem dos dados do ponto de um servidor em uma data
 * Class EspelhoPonto
 * @package ECidade\RecursosHumanos\RH\PontoEletronico
 */
class EspelhoPonto {

  /**
   * @var \Servidor
   */
  private $oServidor;

  /**
   * @var Periodo
   */
  private $oPeriodoEfetividade;

  /**
   * @var \Instituicao
   */
  private $oInstituicao;

  /**
   * @var array
   */
  private $aDados;

  /**
   * @var bool
   */
  private $lTotalizadores = false;

  /**
   * @var bool
   */
  private $lTemTodasMarcacoes = true;

  /**
   * @var array
   */
  private $aObservacoes = array();

  /**
   * @var array
   */
  private $aControleObservacoes = array();

  /**
   * @var Periodo[]
   */
  private $aPeriodos = array();

  /**
   * EspelhoPonto constructor.
   * @param \Servidor $oServidor
   * @param Periodo $oPeriodo
   * @param \Instituicao $oInstituicao
   */
  public function __construct(\Servidor $oServidor, $aPeriodos, \Instituicao $oInstituicao) {

    $this->oServidor    = $oServidor;
    $this->aPeriodos    = $aPeriodos;
    $this->oInstituicao = $oInstituicao;

    $this->getEstruturaDadosBasico();
  }

  /**
   * Seta se os totalizadores devem ser calculados
   */
  public function calcularTotalizadores() {
    $this->lTotalizadores = true;
  }

  /**
   * Monta a estrutura básica dos dados
   */
  private function getEstruturaDadosBasico() {

    $this->aDados     = array(
      'dados'             => $this->getDadosServidor(),
      'dadosGerais'       => array(),
      'datas'             => array(),
      'datasSemMarcacao'  => array(),
      'aHorasJornada'     => array(),
      'observacoes'       => array(),
      'nTotalHorasNormais'  => array('0:00'),
      'nTotalHorasFaltas'   => array('0:00'),
      'nTotalHorasExt50diurnas'    => array('0:00'),
      'nTotalHorasExt50noturnas'   => array('0:00'),
      'nTotalHorasExt75diurnas'    => array('0:00'),
      'nTotalHorasExt75noturnas'   => array('0:00'),
      'nTotalHorasExt100diurnas'   => array('0:00'),
      'nTotalHorasExt100noturnas'  => array('0:00'),
      'nTotalHorasAdicional'=> array('0:00')
    );
  }

  /**
   * Retorna um array de todas as datas de um período de efetividade
   * @return \DBDate[]
   */
  private function getDatasEfetividade() {
    return \DBDate::getDatasNoIntervalo($this->oPeriodoEfetividade->getDataInicio(), $this->oPeriodoEfetividade->getDataFim());
  }

  /**
   * Retorna a escala do servidor em uma data
   * @param \DBDate $oDataEfetividade
   * @return \ECidade\RecursosHumanos\RH\Efetividade\Model\EscalaServidor
   * @throws \BusinessException
   */
  private function getEscalaServidorNaData(\DBDate $oDataEfetividade) {

    if($this->oServidor->getEscalas() == null) {

      $sMensagem  = "Servidor {$this->oServidor->getMatricula()} - {$this->oServidor->getCgm()->getNome()} não possui";
      $sMensagem .= " escala de trabalho configurada.";
      $sMensagem .= ' Para configurá-la, acesse o menu:';
      $sMensagem .= "\n- RH > Procedimentos > Efetividade > Manutenção da Escala de Funcionários";

      throw new \BusinessException($sMensagem);
    }

    $oEscala = ProcessamentoPontoEletronico::getEscalaNaData($this->oServidor->getEscalas(), $oDataEfetividade);

    if(is_null($oEscala)) {

      $sMensagem  = "Servidor {$this->oServidor->getMatricula()} - {$this->oServidor->getCgm()->getNome()} não possui";
      $sMensagem .= " escala de trabalho configurada no dia {$oDataEfetividade->getDate(\DBDate::DATA_PTBR)}.";
      $sMensagem .= ' Para verificar as escalas, acesse o menu:';
      $sMensagem .= "\n- RH > Procedimentos > Efetividade > Manutenção da Escala de Funcionários";

      throw new \BusinessException($sMensagem);
    }

    return $oEscala;
  }

  /**
   * Retorna uma instância de DiaTrabalho
   * @param $oDataEfetividade
   * @return DiaTrabalhoModel
   */
  private function getDiaTrabalho($oDataEfetividade) {

    $oDiaTrabalhoRepository = new DiaTrabalhoRepository();
    $oDiaTrabalhoRepository->setEscalaServidor($this->getEscalaServidorNaData($oDataEfetividade));
    $oDiaTrabalhoRepository->setBuscaJustificativaMarcacoes(true);

    return $oDiaTrabalhoRepository->getDiaTrabalhoProcessadoServidor($this->oServidor, $oDataEfetividade);
  }

  /**
   * Preenche os dados do servidor
   * @return object
   * @throws \BusinessException
   */
  private function getDadosServidor() {

    $oParametrosLotacao = ParametrosRepository::create()->getConfiguracoesLotacao($this->oServidor->getCodigoLotacao());

    if (empty($oParametrosLotacao)) {

      $sMensagem  = "A lotação ({$this->oServidor->getCodigoLotacao()}) do servidor: {$this->oServidor->getMatricula()} - {$this->oServidor->getCgm()->getNome()} não está configurada.\n";
      $sMensagem .= "Para o funcionamento correto do espelho ponto são necessárias as configurações de Tolerância e Horas Extras da lotação do servidor. \n";
      $sMensagem .= "Para configurá-las acesse:\nRH > Procedimentos > Ponto Eletrônico > Configurações > Lotação";      

      throw new \BusinessException($sMensagem);
    }

    return (object)array(
      'nome'       => $this->oServidor->getCgm()->getNome(),
      'matricula'  => $this->oServidor->getMatricula(),
      'admissao'   => $this->oServidor->getDataAdmissao()->getDate(\DBDate::DATA_PTBR),
      'supervisor' => $oParametrosLotacao->getSupervisor()->getCgm()->getNome()
    );
  }

  /**
   * @return object
   */
  private function getDadosGerais() {
    return (object) array('lTemTodasMarcacoes' => $this->lTemTodasMarcacoes);
  }

  /**
   * Preenche os valores referentes ao dia de trabalho
   * @param DiaTrabalhoModel $oDiaTrabalho
   * @return \stdClass
   */
  private function montarValoresGrade(DiaTrabalhoModel $oDiaTrabalho) {

    $diaSemana = new \DateTime($oDiaTrabalho->getData()->getDate());
    $oDados    = new \stdClass;
    $oDados->possuiEvento = false;
    $oDados->dadosEvento  = new \stdClass();
    $oDados->dadosEvento->descricao = '';

    $oJornada  = $oDiaTrabalho->getJornada();

    $oDados->oPeriodoEfetividade               = new \stdClass();
    $oDados->oPeriodoEfetividade->iExercicio   = $this->oPeriodoEfetividade->getExercicio();
    $oDados->oPeriodoEfetividade->iCompetencia = $this->oPeriodoEfetividade->getCompetencia();

    $oDados->codigo_data   = $oDiaTrabalho->getCodigo();
    $oDados->data          = $oDiaTrabalho->getData()->getDate(\DBDate::DATA_PTBR);
    $oDados->data_dia      = $oDiaTrabalho->getData()->getDate(\DBDate::DATA_PTBR) .' - '. $this->diaSemana($diaSemana->format('w'), true);
    $oDados->lTemMarcacoes = !$oDiaTrabalho->getMarcacoes()->isEmpty();
    $oDados->lFeriado      = $oDiaTrabalho->getFeriado() != null;
    $oDados->afastamento   = (object)array('isAfastado' => false);
    
    if($oDiaTrabalho->isAfastado()) {
      $oDados->afastamento->isAfastado  = true;
      $oDados->afastamento->descricao   = $oDiaTrabalho->getJustificativaAfastamento()->getDescricao();
      $oDados->afastamento->abreviacao  = $oDiaTrabalho->getJustificativaAfastamento()->getAbreviacao();

      if(empty($this->aControleObservacoes['afastamentos']) || !in_array($oDados->afastamento->abreviacao, $this->aControleObservacoes['afastamentos'])) {

        $sObservacao                  = "{$oDados->afastamento->abreviacao}: {$oDados->afastamento->descricao}";
        $this->aObservacoes[]         = $sObservacao;
        $this->aControleObservacoes['afastamentos'][] = $oDados->afastamento->abreviacao;
      }
    }

    $oDados->oJornada                 = new \stdClass();
    $oDados->oJornada->codigo         = $oJornada->getCodigo();
    $oDados->oJornada->descricao      = $oJornada->getDescricao();
    $oDados->oJornada->dsr_folga      = !$oJornada->isDiaTrabalhado();
    $oDados->oJornada->tipo_descricao = $oJornada->getTipoDescricao();

    $oEntradaSaida1 = new \stdClass();
    $oEntradaSaida2 = new \stdClass();
    $oEntradaSaida3 = new \stdClass();

    for($iContador = 1; $iContador <= 6; $iContador++) {

      $oMarcacao = $oDiaTrabalho->getMarcacoes()->getMarcacao($iContador);

      $oDadosMarcacao                 = new \stdClass();
      $oDadosMarcacao->codigo         = null;
      $oDadosMarcacao->hora           = '';
      $oDadosMarcacao->tipo           = $iContador;
      $oDadosMarcacao->manual         = false;
      $oDadosMarcacao->data           = '';
      $oDadosMarcacao->oJustificativa = null;

      if($oMarcacao != null) {

        $oDadosMarcacao->codigo = $oMarcacao->getCodigo();
        $oDadosMarcacao->hora   = $this->validaHoraZerada($oMarcacao->getMarcacao());
        $oDadosMarcacao->manual = $oMarcacao->isManual();
        $oDadosMarcacao->data   = $oMarcacao->getData()->getDate();

        if($oMarcacao->getJustificativa() != null) {

          $oDadosMarcacao->oJustificativa             = new \stdClass();
          $oDadosMarcacao->oJustificativa->codigo     = $oMarcacao->getJustificativa()->getCodigo();
          $oDadosMarcacao->oJustificativa->descricao  = $oMarcacao->getJustificativa()->getDescricao();
          $oDadosMarcacao->oJustificativa->abreviacao = $oMarcacao->getJustificativa()->getAbreviacao();

          if(empty($this->aControleObservacoes['justificativas']) || !in_array($oMarcacao->getJustificativa()->getCodigo(), $this->aControleObservacoes['justificativas'])) {

            $sObservacao = "{$oMarcacao->getJustificativa()->getAbreviacao()}: {$oMarcacao->getJustificativa()->getDescricao()}";

            $this->aObservacoes[]                           = $sObservacao;
            $this->aControleObservacoes['justificativas'][] = $oMarcacao->getJustificativa()->getCodigo();
          }
        }
      }

      switch($iContador) {

        case MarcacaoPonto::ENTRADA_1;

          $oEntradaSaida1->oEntrada = $oDadosMarcacao;
          break;

        case MarcacaoPonto::SAIDA_1;

          $oEntradaSaida1->oSaida = $oDadosMarcacao;
          break;

        case MarcacaoPonto::ENTRADA_2;

          $oEntradaSaida2->oEntrada = $oDadosMarcacao;
          break;

        case MarcacaoPonto::SAIDA_2;

          $oEntradaSaida2->oSaida = $oDadosMarcacao;
          break;
        case MarcacaoPonto::ENTRADA_3;

          $oEntradaSaida3->oEntrada = $oDadosMarcacao;
          break;

        case MarcacaoPonto::SAIDA_3;

          $oEntradaSaida3->oSaida = $oDadosMarcacao;
          break;
      }
    }

    $oDados->aMarcacoes     = array($oEntradaSaida1, $oEntradaSaida2, $oEntradaSaida3);
    $oDados->normais        = $this->validaHoraZerada($oDiaTrabalho->getHorasTrabalho()        != '' ? new \DateTime($oDiaTrabalho->getHorasTrabalho())        : '', true);
    $oDados->faltas         = $this->validaHoraZerada($oDiaTrabalho->getHorasFalta()           != '' ? new \DateTime($oDiaTrabalho->getHorasFalta())           : '', true);
    $oDados->ext50diurnas   = $this->validaHoraZerada($oDiaTrabalho->getHorasExtra50()         != '' ? new \DateTime($oDiaTrabalho->getHorasExtra50())         : '');
    $oDados->ext50noturnas  = $this->validaHoraZerada($oDiaTrabalho->getHorasExtra50Noturna()  != '' ? new \DateTime($oDiaTrabalho->getHorasExtra50Noturna())  : '');
    $oDados->ext75diurnas   = $this->validaHoraZerada($oDiaTrabalho->getHorasExtra75()         != '' ? new \DateTime($oDiaTrabalho->getHorasExtra75())         : '');
    $oDados->ext75noturnas  = $this->validaHoraZerada($oDiaTrabalho->getHorasExtra75Noturna()  != '' ? new \DateTime($oDiaTrabalho->getHorasExtra75Noturna())  : '');
    $oDados->ext100diurnas  = $this->validaHoraZerada($oDiaTrabalho->getHorasExtra100()        != '' ? new \DateTime($oDiaTrabalho->getHorasExtra100())        : '');
    $oDados->ext100noturnas = $this->validaHoraZerada($oDiaTrabalho->getHorasExtra100Noturna() != '' ? new \DateTime($oDiaTrabalho->getHorasExtra100Noturna()) : '');
    $oDados->ext50          = $this->validaHoraZerada(new \DateTime($this->somarTotalizador(array($oDados->ext50diurnas, $oDados->ext50noturnas))), true);
    $oDados->ext75          = $this->validaHoraZerada(new \DateTime($this->somarTotalizador(array($oDados->ext75diurnas, $oDados->ext75noturnas))), true);
    $oDados->ext100         = $this->validaHoraZerada(new \DateTime($this->somarTotalizador(array($oDados->ext100diurnas, $oDados->ext100noturnas))), true);
    $oDados->adicional      = $this->validaHoraZerada($oDiaTrabalho->getHorasAdicionalNoturno() != '' ? new \DateTime($oDiaTrabalho->getHorasAdicionalNoturno()) : '', true);   
    
    if(!$oDiaTrabalho->getMarcacoes()->temTodasMarcacoes() && !$oDiaTrabalho->getJornada()->isFixo() && $oDiaTrabalho->getFeriado() == null) {
      $this->lTemTodasMarcacoes = false;
    }

    $this->verificarExistenciaDeEvento($oDados);
    return $oDados;
  }

  /**
   * Seta os dados do evento para serem apresentados em tela
   * @param \stdClass $oDados
   * @return bool|\stdClass
   */
  private function verificarExistenciaDeEvento(\stdClass $oDados) {

    $dia = new \DBDate($oDados->data);
    $evento = Evento::getInstance()->possuiEventoNoDiaParaServidor($dia, $this->oServidor);
    if (!$evento) {
      return false;
    }

    $servidoresDoEvento = $evento->getServidores();
    if (array_key_exists($this->oServidor->getMatricula(), $servidoresDoEvento)) {

      $oDados->possuiEvento = true;
      $oDados->dadosEvento->descricao = $evento->getTitulo();
    }
    return $oDados;
  }

  /**
   * Retorna o dia da semana por extenso ou abreviado
   * @param $iNumeroDia
   * @param bool $lAbreviado
   * @return string
   */
  private function diaSemana($iNumeroDia, $lAbreviado = false) {

    switch ($iNumeroDia) {

      case 0:

        $diaSemanaAbreviado = "Dom";
        $diaSemanaExtenso   = "Domingo";
        break;

      case 1:

        $diaSemanaAbreviado = "Seg";
        $diaSemanaExtenso   = "Segunda-feira";
        break;

      case 2:

        $diaSemanaAbreviado = "Ter";
        $diaSemanaExtenso   = "Terça-feira";
        break;

      case 3:

        $diaSemanaAbreviado = "Qua";
        $diaSemanaExtenso   = "Quarta-feira";
        break;

      case 4:

        $diaSemanaAbreviado = "Qui";
        $diaSemanaExtenso   = "Quinta-feira";
        break;

      case 5:

        $diaSemanaAbreviado = "Sex";
        $diaSemanaExtenso   = "Sexta-feira";
        break;

      default:

        $diaSemanaAbreviado = "Sáb";
        $diaSemanaExtenso   = "Sábado";
        break;
    }

    return $lAbreviado ? $diaSemanaAbreviado : $diaSemanaExtenso;
  }

  /**
   * Atualiza os valores dos totalizadores
   * @param $oStdDiaTrabalho
   */
  private function getTotalizadores($oStdDiaTrabalho) {

    $this->aDados['nTotalHorasNormais'][]   = $oStdDiaTrabalho->normais;
    $this->aDados['nTotalHorasFaltas'][]    = $oStdDiaTrabalho->faltas;
    $this->aDados['nTotalHorasExt50diurnas'][]    = $oStdDiaTrabalho->ext50diurnas;
    $this->aDados['nTotalHorasExt50noturnas'][]   = $oStdDiaTrabalho->ext50noturnas;
    $this->aDados['nTotalHorasExt75diurnas'][]    = $oStdDiaTrabalho->ext75diurnas;
    $this->aDados['nTotalHorasExt75noturnas'][]   = $oStdDiaTrabalho->ext75noturnas;
    $this->aDados['nTotalHorasExt100diurnas'][]   = $oStdDiaTrabalho->ext100diurnas;
    $this->aDados['nTotalHorasExt100noturnas'][]  = $oStdDiaTrabalho->ext100noturnas;
    $this->aDados['nTotalHorasAdicional'][] = $oStdDiaTrabalho->adicional;
    if (empty($oStdDiaTrabalho->ext50noturnas)) {
      $oStdDiaTrabalho->ext50noturnas = '00:00';
    }
    if (empty($oStdDiaTrabalho->ext75noturnas)) {
      $oStdDiaTrabalho->ext75noturnas = '00:00';
    }
    if (empty($oStdDiaTrabalho->ext100noturnas)) {
      $oStdDiaTrabalho->ext100noturnas = '00:00';
    }
    if (empty($oStdDiaTrabalho->ext50diurnas)) {
      $oStdDiaTrabalho->ext50diurnas = '00:00';
    }
    if (empty($oStdDiaTrabalho->ext75diurnas)) {
      $oStdDiaTrabalho->ext75diurnas = '00:00';
    }
    if (empty($oStdDiaTrabalho->ext100diurnas)) {
      $oStdDiaTrabalho->ext100diurnas = '00:00';
    }

    $oExtra50Noturna  = new \DateTime($oStdDiaTrabalho->ext50noturnas);
    $oExtra75Noturna  = new \DateTime($oStdDiaTrabalho->ext75noturnas);
    $oExtra100Noturna = new \DateTime($oStdDiaTrabalho->ext100noturnas);

    $oInterval50  = new \DateInterval("PT{$oExtra50Noturna->format('H')}H{$oExtra50Noturna->format('i')}M");
    $oInterval75  = new \DateInterval("PT{$oExtra75Noturna->format('H')}H{$oExtra75Noturna->format('i')}M");
    $oInterval100 = new \DateInterval("PT{$oExtra100Noturna->format('H')}H{$oExtra100Noturna->format('i')}M");

    $oTotalExtra50  = new \DateTime($oStdDiaTrabalho->ext50diurnas);
    $oTotalExtra75  = new \DateTime($oStdDiaTrabalho->ext75diurnas);
    $oTotalExtra100 = new \DateTime($oStdDiaTrabalho->ext100diurnas);

    $oTotalExtra50->add($oInterval50);
    $oTotalExtra75->add($oInterval75);
    $oTotalExtra100->add($oInterval100);


    $this->aDados['nTotalHorasExt50'][]  = $oTotalExtra50->format('H:i');
    $this->aDados['nTotalHorasExt75'][]  = $oTotalExtra75->format('H:i');
    $this->aDados['nTotalHorasExt100'][] = $oTotalExtra100->format('H:i');
  }

  /**
   * Retorna a estrutura com as informações do ponto do servidor em um período de efetividade
   * @return array
   */
  public function retornaDados() {

    $aHorasJornada = array();

    foreach($this->aPeriodos as $oPeriodoEfetividade) {

      $this->oPeriodoEfetividade = $oPeriodoEfetividade;

      foreach($this->getDatasEfetividade() as $oDataEfetividade) {

        $oDiaTrabalhoRepository = new DiaTrabalhoRepository();
        $oDiaTrabalho           = $this->getDiaTrabalho($oDataEfetividade);
        $oJornada               = $oDiaTrabalho->getJornada();

        if($oDiaTrabalhoRepository->getCodigoData($oDiaTrabalho) === null) {
          $this->aDados['datasSemMarcacao'][] = $oDiaTrabalho->getData()->getDate();
        }

        $aHorasJornada[$oJornada->getCodigo()] = (object)array(
          'fixo'           => $oJornada->isFixo(),
          'folga'          => $oJornada->isFolga(),
          'DSR'            => $oJornada->isDSR(),
          'diaTrabalhado'  => $oJornada->isDiaTrabalhado(),
          'horas'          => $oJornada->getHoras()
        );

        $oStdDiaTrabalho               = $this->montarValoresGrade($oDiaTrabalho);
        $this->aDados['datas'][]       = $oStdDiaTrabalho;
        $this->aDados['aHorasJornada'] = $aHorasJornada;

        if ($this->lTotalizadores) {
           $this->getTotalizadores($oStdDiaTrabalho);
        }
      }
    }

    $this->aDados['dadosGerais'] = $this->getDadosGerais();
    $this->aDados['observacoes'] = $this->aObservacoes;

    return $this->aDados;
  }

  /**
   * Retorna o total de um totalizador
   * @return string
   */
  public static function somarTotalizador($aTotalizador) {

    $nTotalMinutos = 0;
    foreach ($aTotalizador as $horario) {

      if(empty($horario)) {
        continue;
      }

      list($iHora, $iMinute) = explode(':', $horario);
      $nTotalMinutos += $iHora * 60;
      $nTotalMinutos += $iMinute;
    }

    $iHoras = floor($nTotalMinutos / 60);
    $nTotalMinutos -= $iHoras * 60;

    return sprintf('%02d:%02d', $iHoras, $nTotalMinutos);
  }

  /**
   * @param $oHora
   * @return string
   */
  private function validaHoraZerada($oHora, $lTotalizador = false) {

    if($oHora === null || $oHora === '') {
      return '';
    }

    $sHora = $oHora->format('H:i');

    if($sHora == '00:00' && $lTotalizador) {
      return '';
    }

    return $sHora;
  }
}
