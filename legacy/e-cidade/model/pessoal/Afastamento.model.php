<?
/*
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

/**
 * Representação de um afastamento
 * 
 * @package Pessoal
 * @author Renan Silva <renan.silva@dbseller.com.br>
 * @version $Revision: 1.11 $
 *
 */
class Afastamento {

  /**
   * Situação do Afastamento
   */
  const AFASTADO_SEM_REMUNERACAO                 = 2;
  const AFASTADO_ACIDENTE_TRABALHO_MAIS_15_DIAS  = 3;
  const AFASTADO_SERVICO_MILITAR                 = 4;
  const AFASTADO_LICENCA_GESTANTE                = 5;
  const AFASTADO_DOENCA_MAIS_15_DIAS             = 6;
  const LICENCA_SEM_VENCIMENTO                   = 7;
  const AFASTADO_DOENCA_MAIS_30_DIAS             = 8;

  /**
   * Codigo do afastamento
   * 
   * @var Integer
   */
  private $iCodigoAfastamento;

  /**
   * Competencia do Afastamento
   * 
   * @var DBCompetencia
   */
  private $oCompetencia;

  /**
   * Servidor que está vinculado o afastamento
   * 
   * @var Servidor
   */
  private $oServidor;

  /**
   * Data de Início do Afastamento
   * 
   * @var DBDate
   */
  private $oDataAfastamento;

  /**
   * Data de Final do Afastamento
   * 
   * @var DBDate
   */
  private $oDataRetorno;

  /**
   * Código do tipo de Afastamento
   * 
   * @var Integer
   */
  private $iCodigoSituacao;

  /**
   * Data de inclusão na base do afastamento
   * 
   * @var DBDate
   */
  private $oDataLancamento;

  /**
   * Código SEFIP para afastamento
   * 
   * @var String
   */
  private $sCodigoAfastamentoSefip;

  /**
   * Código SEFIP para retorno do afastamento
   * 
   * @var String
   */
  private $sCodigoRetornoSefip;

  /**
   * Observação
   * 
   * @var String
   */
  private $sObservacao;

  /**
   * Construtor da classe
   * 
   * @param Integer $iCodigoAfastamento
   */
  public function __construct($iCodigoAfastamento = null) {

    if(!empty($iCodigoAfastamento)) {
      $this->iCodigoAfastamento = $iCodigoAfastamento;
    }
  }

  /**
   * Define o Código do afastamento
   * 
   * @param Integer $iCodigoAfastamento
   */
  public function setCodigoAfastamento($iCodigoAfastamento) {
    $this->iCodigoAfastamento = $iCodigoAfastamento;
  }

  /**
   * Define a Competência do afastamento
   * 
   * @param DBCompetencia $oCompetencia
   */
  public function setCompetencia($oCompetencia) {
    $this->oCompetencia = $oCompetencia;
  }

  /**
   * Define o Servidor do afastamento
   * 
   * @param Servidor $oServidor
   */
  public function setServidor($oServidor) {
    $this->oServidor = $oServidor;
  }

  /**
   * Define a data de início do afastamento
   * 
   * @param DBDate $oDataAfastamento
   */
  public function setDataAfastamento($oDataAfastamento) {
    $this->oDataAfastamento = $oDataAfastamento;
  }

  /**
   * Define a data final do afastamento
   * 
   * @param DBDate $oDataRetorno
   */
  public function setDataRetorno($oDataRetorno) {
    $this->oDataRetorno = $oDataRetorno;
  }

  /**
   * Define o código da situação do afastamento
   * 
   * @param Integer $iCodigoSituacao
   */
  public function setCodigoSituacao($iCodigoSituacao) {
    $this->iCodigoSituacao = $iCodigoSituacao;
  }

  /**
   * Define a data de lançamento do afastamento
   * 
   * @param DBDate $oDataLancamento
   */
  public function setDataLancamento($oDataLancamento) {
    $this->oDataLancamento = $oDataLancamento;
  }

  /**
   * Define o código SEFIP para o afastamento
   * 
   * @param String $sCodigoAfastamentoSefip
   */
  public function setCodigoAfastamentoSefip($sCodigoAfastamentoSefip) {
    $this->sCodigoAfastamentoSefip = $sCodigoAfastamentoSefip;
  }

  /**
   * Define o código SEFIP de retorno do afastamento
   * 
   * @param Integer $sCodigoRetornoSefip
   */
  public function setCodigoRetornoSefip($sCodigoRetornoSefip) {
    $this->sCodigoRetornoSefip = $sCodigoRetornoSefip;
  }

  /**
   * Define a observação do afastamento
   * 
   * @param String $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Retorna o Código do afastamento
   * 
   * @return Integer
   */
  public function getCodigoAfastamento() {
    return $this->iCodigoAfastamento;
  }

  /**
   * Retorna a Competência do afastamento
   * 
   * @return DBCompetencia
   */
  public function getCompetencia() {
    return $this->oCompetencia;
  }

  /**
   * Retorna o Servidor do afastamento
   * 
   * @return Servidor
   */
  public function getServidor() {
    return $this->oServidor;
  }

  /**
   * Retorna a data de início do afastamento
   * 
   * @return DBDate
   */
  public function getDataAfastamento() {
    return $this->oDataAfastamento;
  }

  /**
   * Retorna a data final do afastamento
   * 
   * @return DBDate
   */
  public function getDataRetorno() {
    return $this->oDataRetorno;
  }

  /**
   * Retorna o código da situação do afastamento
   * 
   * @return Integer
   */
  public function getCodigoSituacao() {
    return $this->iCodigoSituacao;
  }

  /**
   * Retorna a data de lançamento do afastamento
   * 
   * @return DBDate
   */
  public function getDataLancamento() {
    return $this->oDataLancamento;
  }

  /**
   * Retorna o código SEFIP para o afastamento
   * 
   * @return String
   */
  public function getCodigoAfastamentoSefip() {
    return $this->sCodigoAfastamentoSefip;
  }

  /**
   * Retorna o código de retorno do afastamento
   * 
   * @return Integer
   */
  public function getCodigoRetornoSefip() {
    return $this->sCodigoRetornoSefip;
  }

  /**
   * Retorna a observação do afastamento
   * 
   * @return String
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Retorna o número de dias de afastamento
   * 
   * @return Integer|Null
   */
  public function getPeriodoAfastamento() {

    $oDataAtual = new DBDate(date('d/m/Y'));

    if(empty($this->oDataRetorno)) {

      if(empty($this->oDataAfastamento)) {
        return null;
      }

      return (int)$oDataAtual->calculaIntervaloEntreDatas($oDataAtual, $this->oDataAfastamento, 'd')+1;
    }

    return round((int)($this->oDataRetorno->getTimeStamp() - $this->oDataAfastamento->getTimeStamp())/86400)+1;
  }

  /**
   * Retorna a quantidade de dias do afastamento na competência de 30 dias
   * @param \DBCompetencia $oCompetencia
   * @return int
   * @throws \BusinessException
   */
  public function getNumeroDeDiasNaCompetencia(DBCompetencia $oCompetencia) {

    $oDataInicio  = $this->getDataAfastamento();
    $iDiasAjuste = 1;
    if (empty($oDataInicio)) {
      $oDataInicio = $oCompetencia->getDataDeInicio();
    }
    $oDataTermino    = $this->getDataRetorno();
    
    if (empty($oDataTermino)) {      
      $oDataTermino    = $oCompetencia->getDataDeTermino();
     }
    if ($oDataInicio->getTimeStamp() < $oCompetencia->getDataDeInicio()->getTimeStamp()) {
      $oDataInicio = $oCompetencia->getDataDeInicio();
    }

    if ($oDataTermino->getTimeStamp() >= $oCompetencia->getDataDeTermino()->getTimeStamp()) {

      $iDataTerminoAfastamento = $oDataTermino->getDia();
      if ($this->getDataRetorno() == '' || ($oCompetencia->comparar($oDataTermino->getCompetencia(), DBCompetencia::COMPARACAO_MENOR))) {
        $iDataTerminoAfastamento = '';
      }

      $oDataTermino            = $oCompetencia->getDataDeTermino();
      $iUltimoDiaCompetencia   = $oCompetencia->getUltimoDia();
      
      
      /**
       * Caso o afastamentao nao termine no ultimo dia de fevereiro, devemos somar nos dias de ajustes a diferença
       * dos dias de fevereiro de 30 dias
       */
      if (in_array($iUltimoDiaCompetencia, array(28, 29)) && $iDataTerminoAfastamento != $iUltimoDiaCompetencia) {
        $iDiasAjuste += (30 - $oCompetencia->getUltimoDia());
      }

      if ($oCompetencia->getUltimoDia() == 31) {
        $iDiasAjuste = 0;
      }
    }

    $oDateTimeInicial = new DateTime($oDataInicio->getDate());
    $oDateTimeFinal   = new DateTime($oDataTermino->getDate());
    $oIntervalo       = $oDateTimeFinal->diff($oDateTimeInicial);
    $iDiasAfastadoNaCompetencia  = $oIntervalo->format('%a');
    $iDiasAfastadoNaCompetencia += $iDiasAjuste;
    if ($iDiasAfastadoNaCompetencia < 0 || $iDiasAfastadoNaCompetencia  > 30) {
      throw new BusinessException("Afastamento {$this->getCodigoSituacao()}  do Servidor {$this->getServidor()->getMatricula()} - {$this->getServidor()->getCgm()->getNome()} com dias ({$iDiasAfastadoNaCompetencia}) na competência inconsistente.");
    }
    return $iDiasAfastadoNaCompetencia;

  }
}