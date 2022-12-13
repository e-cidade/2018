<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * Classe para regenciaperiodo. Controle a quantidade de aulas dadas por periodo de uma regencia
 * @package educacao
 * @author Fabio Esteves <fabio.esteves@dbseller.com.br>
 *
 */
class RegenciaPeriodo {

  /**
   * Codigo de regenciaperiodo
   * @var integer
   */
  private $iCodigo;

  /**
   * Instancia de Regencia
   * @var Regencia
   */
  private $oRegencia;

  /**
   * Instancia de AvaliacaoPeriodica
   * @var AvaliacaoPeriodica
   */
  private $oAvaliacaoPeriodica;

  /**
   * Aulas dadas no periodo
   * @var integer
   */
  private $iAulasDadas;

  /**
   * Construtor da classe. Caso seja informado $iCodigo, setamos os valores de cada propriedade
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoRegenciaPeriodo = db_utils::getDao("regenciaperiodo");
      $sSqlRegenciaPeriodo = $oDaoRegenciaPeriodo->sql_query_file($iCodigo);
      $rsRegenciaPeriodo   = $oDaoRegenciaPeriodo->sql_record($sSqlRegenciaPeriodo);

      if ($oDaoRegenciaPeriodo->numrows > 0) {

        $oDados                    = db_utils::fieldsMemory($rsRegenciaPeriodo, 0);
        $this->iCodigo             = $oDados->ed78_i_codigo;
        $this->oRegencia           = RegenciaRepository::getRegenciaByCodigo($oDados->ed78_i_regencia);
        $this->oAvaliacaoPeriodica = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo($oDados->ed78_i_procavaliacao);
        $this->iAulasDadas         = $oDados->ed78_i_aulasdadas;
        unset($oDados);
      }
    }
  }

  /**
   * Retorna o codigo de regenciaperiodo
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Seta o codigo de regenciaperiodo
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna uma instancia de Regencia
   * @return Regencia
   */
  public function getRegencia() {
    return $this->oRegencia;
  }

  /**
   * Seta uma instancia de Regencia
   * @param Regencia $oRegencia
   */
  public function setRegencia(Regencia $oRegencia) {
    $this->oRegencia = $oRegencia;
  }

  /**
   * Retorna uma instancia de AvaliacaoPeriodica
   * @return AvaliacaoPeriodica
   */
  public function getAvaliacaoPeriodica() {
    return $this->oAvaliacaoPeriodica;
  }

  /**
   * Seta uma instancia de AvaliacaoPeriodica
   * @param AvaliacaoPeriodica $oAvaliacaoPeriodica
   */
  public function setAvaliacaoPeriodica(AvaliacaoPeriodica $oAvaliacaoPeriodica) {
    $this->oAvaliacaoPeriodica = $oAvaliacaoPeriodica;
  }

  /**
   * Retorna o numero de aulas dadas
   * @return integer
   */
  public function getAulasDadas() {
    return $this->iAulasDadas;
  }

  /**
   * Seta o numero de aulas dadas
   * @param integer $iAulasDadas
   */
  public function setAulasDadas($iAulasDadas) {
    $this->iAulasDadas = $iAulasDadas;
  }

  /**
   * Salvamos alteracoes referentes ao periodo de uma regencia
   * @throws BusinessException
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Não Existe transação com o banco de dados ativa.");
    }

    if ($this->getRegencia() == null){
      throw new BusinessException("Regência não informada");
    }

    if ($this->getAvaliacaoPeriodica() == null){
      throw new BusinessException("Periodo de avaliacao nao informado");
    }

    $iNumeroDeFaltasLancadas = $this->getNumeroMaximoDeFaltas();
    if ($this->getAulasDadas() < $iNumeroDeFaltasLancadas) {

      $sMensagem  = "Total de aulas dadas no período {$this->getAvaliacaoPeriodica()->getDescricao()} para a ";
      $sMensagem .= "disciplina {$this->getRegencia()->getDisciplina()->getNomeDisciplina()}, é menor ";
      $sMensagem .= "que o número de faltas ({$iNumeroDeFaltasLancadas}) informadas ";
      $sMensagem .= "no diário de classe da turma.";
      throw new BusinessException($sMensagem);
    }
    $oDaoRegenciaPeriodo                       = db_utils::getDao("regenciaperiodo");
    $oDaoRegenciaPeriodo->ed78_i_regencia      = $this->getRegencia()->getCodigo();
    $oDaoRegenciaPeriodo->ed78_i_procavaliacao = $this->getAvaliacaoPeriodica()->getCodigo();
    $oDaoRegenciaPeriodo->ed78_i_aulasdadas    = $this->getAulasDadas();

    if ($this->getCodigo() == '') {

      $oDaoRegenciaPeriodo->incluir(null);
      $this->iCodigo = $oDaoRegenciaPeriodo->ed78_i_codigo;
    } else {

      $oDaoRegenciaPeriodo->ed78_i_codigo = $this->getCodigo();
      $oDaoRegenciaPeriodo->alterar($this->getCodigo());
    }

    if ($oDaoRegenciaPeriodo->erro_status == "0") {
      throw new BusinessException($oDaoRegenciaPeriodo->erro_msg);
    }
  }

  /**
   * Retorna o maior numero de Faltas no periodo, lancadas para a regencia
   * @return integer
   */
  public function getNumeroMaximoDeFaltas() {

    $iTotalFaltas        = 0;
    $oDaoDiarioAvaliacao = new cl_diarioavaliacao();

    $sWhere  = "ed95_i_regencia = {$this->getRegencia()->getCodigo()}";
    $sWhere .= " and ed72_i_procavaliacao = {$this->getAvaliacaoPeriodica()->getCodigo()}";

    $sSqlFaltas = $oDaoDiarioAvaliacao->sql_query_diario(null,
                                                         "coalesce(max(ed72_i_numfaltas), 0) as faltas",
                                                          null,
                                                          $sWhere
                                                        );

    $rsTotalFaltas = $oDaoDiarioAvaliacao->sql_record($sSqlFaltas);
    if (!$rsTotalFaltas) {
      throw new BusinessException("Erro ao retornar total de faltas do período");
    }
    $iTotalFaltas = db_Utils::fieldsMemory($rsTotalFaltas, 0)->faltas;
    return $iTotalFaltas;
  }
}