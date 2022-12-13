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

use ECidade\Configuracao\Cadastro\Model\Feriado;
use ECidade\RecursosHumanos\RH\Efetividade\Model\Jornada as JornadaModel;
use ECidade\RecursosHumanos\RH\Efetividade\Model\Jornada;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Factory\TipoHora;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;
use ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Model\Evento;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacoesPontoCollection;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto;

/**
 * Classe com as informações referentes ao dia de trabalho de um servidor
 * Class DiaTrabalho
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class DiaTrabalho {

  /**
   * Código sequencial do dia dos registros
   * @var int
   */
  private $iCodigo;

  /**
   * Instância do servidor
   * @var \Servidor
   */
  private $oServidor;

  /**
   * Instância da jornada do dia do servidor
   * @var Jornada
   */
  private $oJornada;

  /**
   * Data do dia de trabalho
   * @var \DBDate
   */
  private $oData;

  /**
   * Marcações do servidor no dia
   * @var array
   */
  private $aMarcacoes = array();

  /**
   * Horas de trabalho
   * @var string
   */
  private $sHorasTrabalho;

  /**
   * Horas de Adicional Noturna
   * @var string
   */
  private $sHorasAdicionalNoturno;
  
  /**
   * Horas Extra50
   * @var string
   */
  private $sHorasExtra50;
  
  /**
   * Horas Extra75
   * @var string
   */
  private $sHorasExtra75;
  
  /**
   * Horas Extra100
   * @var string
   */
  private $sHorasExtra100;

  /**
   * Horas falta no dia
   * @var string
   */
  private $sHorasFalta;

  /**
   * Código do arquivo de marcações
   * @var Integer
   */
  private $iCodigoArquivo;

  /**
   * @var null|Feriado
   */
  private $oFeriado = null;

  /**
   * Configurações da lotação do servidor
   * @var \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosLotacao
   */
  private $oConfiguracoesLotacao;

  /**
   * Tempo de Tolerância
   * @var Integer
   */
  private $iTolerancia;

  /**
   * Horas Extra50Noturna
   * @var string
   */
  private $sHorasExtra50Noturna;

  /**
   * Horas Extra75Noturna
   * @var string
   */
  private $sHorasExtra75Noturna;

  /**
   * Horas Extra100Noturna
   * @var string
   */
  private $sHorasExtra100Noturna;

  /**
   * Se há afastamento no dia de trabalho à processar
   * @var boolean
   */
  private $lAfastado = false;

  /**
   * A justificativa para o afastamento no dia de trabalho à processar
   * @var \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Model\Justificativa
   */
  private $oJustificativaAfastamento;

  /**
   * Assentamento
   * var Assentamento
   */
  private $afastamento;

  /**
   * @var ParametrosGerais
   */
  private $oParametrosPontoEletronico;

  /**
   * @var bool
   */
  private $lCalculaHoraExtra = false;

  /**
   * @var null|\DateTime
   */
  private $oHorasExtrasAutorizadas = null;

  /**
   * @var Evento
   */
  private $evento = null;

  /**
   * @var \ECidade\RecursosHumanos\RH\Assentamento\AssentamentoHoraExtraManual []
   */
  private $assentamentosHoraExtraManual = array();

  /**
   * Construtor da classe
   *
   * @param \DBDate   $oData
   * @param \Servidor $oServidor
   */
  public function __construct($oData = null, $oServidor = null) {
    
    if(!empty($oData)) {
      $this->oData     = $oData;
    }
    
    if(!empty($oServidor)) {
      $this->oServidor = $oServidor;
    }

    $this->aMarcacoes = new MarcacoesPontoCollection();
  }

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o Servidor
   *
   * @return \Servidor
   */
  public function getServidor() {
    return $this->oServidor;
  }

  /**
   * Retorna a Jornada
   *
   * @return Jornada
   */
  public function getJornada() {
    return $this->oJornada;
  }

  /**
   * Retorna a Data
   *
   * @return \DBDate
   */
  public function getData() {
    return $this->oData;
  }

  /**
   * @return MarcacoesPontoCollection
   */
  public function getMarcacoes() {
    return $this->aMarcacoes;
  }

  /**
   * Retorna as Horas de trabalho
   *
   * @return string
   */
  public function getHorasTrabalho() {
    return $this->sHorasTrabalho;
  }

  /**
   * Retorna Horas de Adicional Noturna
   *
   * @return string
   */
  public function getHorasAdicionalNoturno() {
    return $this->sHorasAdicionalNoturno;
  }

  /**
   * Retorna Horas Extra50
   *
   * @return string
   */
  public function getHorasExtra50() {
    return $this->sHorasExtra50;
  }

  /**
   * Retorna Horas Extra75
   *
   * @return string
   */
  public function getHorasExtra75() {
    return $this->sHorasExtra75;
  }

  /**
   * Retorna Horas Extra100
   *
   * @return string
   */
  public function getHorasExtra100() {
    return $this->sHorasExtra100;
  }

  /**
   * Retorna Horas Extra50Noturna
   *
   * @return string
   */
  public function getHorasExtra50Noturna() {
    return $this->sHorasExtra50Noturna;
  }

  /**
   * Retorna Horas Extra75Noturna
   *
   * @return string
   */
  public function getHorasExtra75Noturna() {
    return $this->sHorasExtra75Noturna;
  }

  /**
   * Retorna Horas Extra100Noturna
   *
   * @return string
   */
  public function getHorasExtra100Noturna() {
    return $this->sHorasExtra100Noturna;
  }

  /**
   * Retorna as Horas de falta
   *
   * @return string
   */
  public function getHorasFalta() {
    return $this->sHorasFalta;
  }

  /**
   * Retorna o código do arquivo
   *
   * @return Integer
   */
  public function getCodigoArquivo() {
    return $this->iCodigoArquivo;
  }

  /**
   * @return Feriado
   */
  public function getFeriado() {
    return $this->oFeriado;
  }

  /**
   * Retorna as configurações da lotação do servidor
   *
   * @return \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosLotacao
   */
  public function getConfiguracoesLotacao() {
    return $this->oConfiguracoesLotacao;
  }

  /**
   * Retorna o tempo configurado para tolerância em minutos
   * 
   * @return integer [description]
   */
  public function getTolerancia() {
    return $this->iTolerancia;
  }

  /**
   * Retorna se no dia de trabalho em questão o servidor está afastado
   * @return boolean
   */
  public function isAfastado() {
    return $this->lAfastado;
  }

  /**
   * Retorna a justificativa do afastamento
   * @return \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Model\Justificativa
   */
  public function getJustificativaAfastamento() {
    return $this->oJustificativaAfastamento;
  }

  /**
   * @param int $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Define o Servidor
   *
   * @param \Servidor $oServidor
   */
  public function setServidor(\Servidor $oServidor) {
    $this->oServidor = $oServidor;
  }

  /**
   * Define a Jornada
   *
   * @param Jornada $oJornada
   */
  public function setJornada(JornadaModel $oJornada) {
    $this->oJornada = $oJornada;
  }

  /**
   * Define a Data
   *
   * @param \DBDate $oData
   */
  public function setData(\DBDate $oData) {
    $this->oData = $oData;
  }

  /**
   * Define as marcações
   * @param MarcacoesPontoCollection $aMarcacoes
   */
  public function setMarcacoes(MarcacoesPontoCollection $aMarcacoes) {
    $this->aMarcacoes = $aMarcacoes;
  }

  /**
   * Define as horas de trabalho
   *
   * @param string $sHorasTrabalho
   */
  public function setHorasTrabalho($sHorasTrabalho) {
    $this->sHorasTrabalho = $sHorasTrabalho;
  }

  /**
   * Define as Horas de Adicional Noturna
   *
   * @param string $sHorasAdicionalNoturno
   */
  public function setHorasAdicionalNoturno($sHorasAdicionalNoturno) {
    $this->sHorasAdicionalNoturno = $sHorasAdicionalNoturno;
  }

  /**
   * Define as Horas Extra50
   *
   * @param string $sHorasExtra50
   */
  public function setHorasExtra50($sHorasExtra50) {
    $this->sHorasExtra50 = $sHorasExtra50;
  }

  /**
   * Define as Horas Extra75
   *
   * @param string $sHorasExtra75
   */
  public function setHorasExtra75($sHorasExtra75) {
    $this->sHorasExtra75 = $sHorasExtra75;
  }

  /**
   * Define as Horas Extra100
   *
   * @param string $sHorasExtra100
   */
  public function setHorasExtra100($sHorasExtra100) {
    $this->sHorasExtra100 = $sHorasExtra100;
  }

  /**
   * Define as Horas Extra50Noturna
   *
   * @param string $sHorasExtra50Noturna
   */
  public function setHorasExtra50Noturna($sHorasExtra50Noturna) {
    $this->sHorasExtra50Noturna = $sHorasExtra50Noturna;
  }

  /**
   * Define as Horas Extra75Noturna
   *
   * @param string $sHorasExtra75Noturna
   */
  public function setHorasExtra75Noturna($sHorasExtra75Noturna) {
    $this->sHorasExtra75Noturna = $sHorasExtra75Noturna;
  }

  /**
   * Define as Horas Extra100Noturna
   *
   * @param string $sHorasExtra100Noturna
   */
  public function setHorasExtra100Noturna($sHorasExtra100Noturna) {
    $this->sHorasExtra100Noturna = $sHorasExtra100Noturna;
  }

  /**
   * Define as Horas de falta
   *
   * @param string $sHorasFalta
   */
  public function setHorasFalta($sHorasFalta) {
    $this->sHorasFalta = $sHorasFalta;
  }

  /**
   * Define o código do arquivo
   *
   * @param Integer $iCodigoArquivo
   */
  public function setCodigoArquivo($iCodigoArquivo) {
    $this->iCodigoArquivo = $iCodigoArquivo;
  }

  /**
   * @param Feriado $oFeriado
   */
  public function setFeriado(Feriado $oFeriado) {
    $this->oFeriado = $oFeriado;
  }

  /**
   * Define as configurações da lotação do servidor
   *
   * @param \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosLotacao $oConfiguracoesLotacao
   */
  public function setConfiguracoesLotacao($oConfiguracoesLotacao) {
    $this->oConfiguracoesLotacao = $oConfiguracoesLotacao;
  }

  /**
   * Define o tempo de tolerância em minutos
   * 
   * @param Integer $iTolerancia
   */
  public function setTolerancia($iTolerancia) {
    $this->iTolerancia = $iTolerancia;
  }

  /**
   * Define se o servidor está afastado
   * @param boolean $lAfastado
   * @return $this
   */
  public function setAfastado($lAfastado) {
    $this->lAfastado = $lAfastado;
    return $this;
  }

  /**
   * Define a justificativa do afastamento
   * @param \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Model\Justificativa $oJustificativaAfastamento
   * @return $this
   */
  public function setJustificativaAfastamento($oJustificativaAfastamento) {
    $this->oJustificativaAfastamento = $oJustificativaAfastamento;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getParametrosPontoEletronico() {
    return $this->oParametrosPontoEletronico;
  }

  /**
   * @param mixed $oParametrosPontoEletronico
   */
  public function setParametrosPontoEletronico($oParametrosPontoEletronico) {
    $this->oParametrosPontoEletronico = $oParametrosPontoEletronico;
  }

  /**
   * @return bool
   */
  public function isCalculaHoraExtra() {
    return $this->lCalculaHoraExtra;
  }

  /**
   * @param bool $lCalculaHoraExtra
   */
  public function setCalculaHoraExtra($lCalculaHoraExtra) {
    $this->lCalculaHoraExtra = $lCalculaHoraExtra;
  }

  /**
   * @return \DateTime|null
   */
  public function getHorasExtrasAutorizadas() {
    return $this->oHorasExtrasAutorizadas;
  }

  /**
   * @param \DateTime|null $oHorasExtrasAutorizadas
   */
  public function setHorasExtrasAutorizadas($oHorasExtrasAutorizadas) {
    $this->oHorasExtrasAutorizadas = $oHorasExtrasAutorizadas;
  }

  /**
   * @param \ECidade\RecursosHumanos\RH\Assentamento\AssentamentoHoraExtraManual[] $assentamentosHoraExtraManual
   */
  public function setAssentamentosHoraExtraManual($assentamentosHoraExtraManual) {
    $this->assentamentosHoraExtraManual = $assentamentosHoraExtraManual;
    return $this;
  }

  /**
   * @return \ECidade\RecursosHumanos\RH\Assentamento\AssentamentoHoraExtraManual[]
   */
  public function getAssentamentosHoraExtraManual() {
    return $this->assentamentosHoraExtraManual;
  }

  /**
   * Calcula as horas trabalhadas, extras, faltas e zaz
   */
  public function calcularHoras() {

    $this->setTolerancia($this->getConfiguracoesLotacao()->getTolerancia());

    $oHorasTrabalho        = TipoHora::getHora($this, BaseHora::HORAS_TRABALHO);
    $oHoraAdicionalNoturno = TipoHora::getHora($this, BaseHora::HORAS_ADICIONAL_NOTURNO);
    $oHoraFalta            = TipoHora::getHora($this, BaseHora::HORAS_FALTA);
    
    $this->sHorasTrabalho         = '';
    $this->sHorasFalta            = '';
    $this->sHorasAdicionalNoturno = '';
    $this->sHorasExtra50          = '';
    $this->sHorasExtra75          = '';
    $this->sHorasExtra100         = '';
    $this->sHorasExtra50Noturna   = '';
    $this->sHorasExtra75Noturna   = '';
    $this->sHorasExtra100Noturna  = '';

    if($this->getJornada()->isDiaTrabalhado()) {
    
      $oTotalHorasTrabalhadas = $oHorasTrabalho->calcular();
      $this->sHorasTrabalho   = $oTotalHorasTrabalhadas->format('H:i');

      $oHoraFalta->setHorasTrabalhadas($oTotalHorasTrabalhadas);
      $this->sHorasFalta = $oHoraFalta->calcular()->format('H:i');
    }

    /**
     * Se trabalhou (tem marcações), 
     * faz os cálculos de extras e adicional Noturna
     * também calcular horas de trabalho e falta se precisar
     */
    if(!$this->getMarcacoes()->isEmpty()) {

      if(!$this->getJornada()->isDiaTrabalhado()) {
        $oTotalHorasTrabalhadas = $oHorasTrabalho->calcular();
        $this->sHorasTrabalho   = $oTotalHorasTrabalhadas->format('H:i');

        $oHoraFalta->setHorasTrabalhadas($oTotalHorasTrabalhadas);
        $this->sHorasFalta = $oHoraFalta->calcular()->format('H:i');
      }

      if (!empty($this->evento)) {
        return $this->calcularHorasDiaComEvento();
      }

      $oHoraAdicionalNoturno->setHorasTrabalhadas($oTotalHorasTrabalhadas);
      $this->sHorasAdicionalNoturno = $oHoraAdicionalNoturno->calcular();

      /**
       * Se o parâmetro para calcular horas extras somente com autorização estiver como NÃO
       * ou
       * Se o parâmetro para calcular horas extras somente com autorização estiver como SIM e possuir assentamento de autorização
       */
      if (!$this->getParametrosPontoEletronico()->horaExtraSomenteComAutorizacao() || $this->isCalculaHoraExtra()) {

        $oHoraExtraCalculo     = TipoHora::getHora($this, BaseHora::HORAS_EXTRA_CALCULO);
        $oHoraExtraCalculo->calcular();
      }

    }
    $this->posCalcularHoras();
  }

  private function calcularHorasDiaComEvento() {

    $oHora  = \DateTime::createFromFormat('H:i', '0:00');
    $oHora->setDate(
      $this->getData()->getAno(),
      $this->getData()->getMes(),
      $this->getData()->getDia()
    );

    $oHorasExtra50         = clone $oHora;
    $oHorasExtra50Noturna  = clone $oHora;

    $oHorasExtra75         = clone $oHora;
    $oHorasExtra75Noturna  = clone $oHora;

    $oHorasExtra100        =  clone $oHora;
    $oHorasExtra100Noturna =  clone $oHora;
    
    $oHorasAdicionalNoturno =  clone $oHora;
    
    if(true) {
      $this->calcularHorasDiaComEventoComHorasEvento();
    } else {
      $this->calcularHorasDiaComEventoComHorasMarcacao();
    }

    $oHorasExtrasEvento = TipoHora::getHora($this, BaseHora::HORAS_EXTRAS_EVENTO);
    $oHorasExtrasEvento->calcular(
      $oHorasExtra50,
      $oHorasExtra50Noturna,
      $oHorasExtra75,
      $oHorasExtra75Noturna,
      $oHorasExtra100,
      $oHorasExtra100Noturna,
      $oHorasAdicionalNoturno
    );
    
    $this->sHorasExtra50          = $oHorasExtra50->format('H:i');
    $this->sHorasExtra50Noturna   = $oHorasExtra50Noturna->format('H:i');
    $this->sHorasExtra75          = $oHorasExtra75->format('H:i');
    $this->sHorasExtra75Noturna   = $oHorasExtra75Noturna->format('H:i');
    $this->sHorasExtra100         = $oHorasExtra100->format('H:i');
    $this->sHorasExtra100Noturna  = $oHorasExtra100Noturna->format('H:i');
    $this->sHorasAdicionalNoturno = $oHorasAdicionalNoturno->format('H:i');

    $this->posCalcularHoras();
  }

  private function calcularHorasDiaComEventoComHorasMarcacao() {
  }
  
  private function calcularHorasDiaComEventoComHorasEvento() {

    $marcacoes = $this->getMarcacoes()->getMarcacoes();

    foreach ($marcacoes as $tipo => $marcacao) {

      $horaMarcacao = null;

      switch ($tipo) {
        case MarcacaoPonto::ENTRADA_1:
          $horaMarcacao = clone $this->evento->getEntradaUm();
          break;

        case MarcacaoPonto::SAIDA_1:
          $horaMarcacao = clone $this->evento->getSaidaUm();
          break;

        case MarcacaoPonto::ENTRADA_2:
          $horaMarcacao = !is_null($this->evento->getEntradaDois()) ? clone $this->evento->getEntradaDois() : null;
          break;

        case MarcacaoPonto::SAIDA_2:
          $horaMarcacao = !is_null($this->evento->getSaidaDois()) ? clone $this->evento->getSaidaDois() : null;
          break;
      }

      $marcacao->limparHoraMarcacao();
      if(!empty($horaMarcacao)) {
        $marcacao->setMarcacao($horaMarcacao);
      }
    }
  }

  /**
   * Métodos a serem executados após o cálculo de horas
   */
  private function posCalcularHoras() {

    if ($this->evento) {
      $this->sHorasFalta = '00:00';
    }

    $this->sobrescreverHorasExtras();
  }

  /**
   * @param \Assentamento|null $assentamento
   */
  public function setAfastamento(\Assentamento $assentamento = null) {
    
    $this->afastamento = $assentamento;

    $this->lAfastado = false;
    if(!empty($assentamento)) {
      $this->lAfastado = true;
    }
  }

  /**
   * @return \Assentamento
   */
  public function getAfastamento() {
    return $this->afastamento;
  }

  /**
   * @param Evento $evento
   */
  public function setEvento(Evento $evento) {
    $this->evento = $evento;
  }

  /**
   * @return Evento
   */
  public function getEvento() {
    return $this->evento;
  }

  /**
   * Sobrescreve as horas extras existentes pelas horas cadastradas no assentamento do tipo HE Manual
   * @return bool
   */
  public function sobrescreverHorasExtras() {

    if (count($this->assentamentosHoraExtraManual) === 0) {
      return false;
    }

    foreach ($this->assentamentosHoraExtraManual as $assentamento) {

      $horasManuais = $assentamento->getHorasExtras();

      foreach ($horasManuais as $tipoHora => $horasExtras) {

        if(empty($horasExtras) || $horasExtras == ' ' || $horasExtras == '00:00' || $horasExtras == '0:00') {
          continue;
        }

        switch ($tipoHora) {

          case BaseHora::HORAS_EXTRA50:
            $this->sHorasExtra50 = $horasExtras;
            break;

          case BaseHora::HORAS_EXTRA75:
            $this->sHorasExtra75 = $horasExtras;
            break;

          case BaseHora::HORAS_EXTRA100:
            $this->sHorasExtra100 = $horasExtras;
            break;

          case BaseHora::HORAS_EXTRA50_NOTURNA:
            $this->sHorasExtra50Noturna = $horasExtras;
            break;

          case BaseHora::HORAS_EXTRA75_NOTURNA:
            $this->sHorasExtra75Noturna = $horasExtras;
            break;

          case BaseHora::HORAS_EXTRA100_NOTURNA:
            $this->sHorasExtra100Noturna = $horasExtras;
            break;
        }
      }
    }

    return true;
  }
}
