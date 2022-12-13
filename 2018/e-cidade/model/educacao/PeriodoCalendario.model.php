<?php
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
 * Periodos de avaliacao de un calendario escolar
 * @author Iuri Guntchnigg <iuri at dbseller.com.br>
 */
class PeriodoCalendario {

  /**
   * Periodo de avaliacao do calendario
   * @var PeriodoAvaliacao
   */
  private $oPeriodo;
  
  /**
   * Data de Inicio do Periodo
   * @var DBDate
   */
  private $dtInicio;
  
  /**
   * data de Termino do periodo
   * @var DBDate
   */
  private $dtTermino;
  
  /**
   * Dias letivos do periodo de avaliacao
   * @var integer
   */
  private $iDiasLetivos;
  
  /**
   * Numero de semanas letivas do Calendario
   * @var integer
   */
  private $iSemanasLetivas;
  
  /**
   * Codigo do Periodo do Calendario
   * @var integer
   */
  private $iCodigoPeriodoCalendario;
  
  function __construct() {
    
  }
  /**
   * @return DBDate
   */
  public function getDataInicio() {

    return $this->dtInicio;
  }

  /**
   * Data de inicio do Periodo
   *
   * @param DBDate $dtInicio
   * @internal param DBDate $dataInicio
   */
  public function setDataInicio(DBDate $dtInicio) {

    $this->dtInicio = $dtInicio;
  }
  
  /**
   * Retorno a data de termino do periodo
   * @return DBDate
   */
  public function getDataTermino() {

    return $this->dtTermino;
  }
  
  /**
   * Data de termino do periodo 
   * @param DBDate $dtTermino formado
   */
  public function setDataTermino(DBDate $dtTermino) {

    $this->dtTermino = $dtTermino;
  }
  
  /**
   * Retorna o periodo de avaliacao que se refere o calendario
   * @return PeriodoAvaliacao
   */
  public function getPeriodoAvaliacao() {

    return $this->oPeriodo;
  }
  
  /**
   * Define o periodo de avaliacao do calendario
   * @param PeriodoAvaliacao $oPeriodo
   */
  public function setPeriodoAvaliacao($oPeriodo) {

    $this->oPeriodo = $oPeriodo;
  }
  
  /**
   * Atribui o numero de Dias letivos do Periodo
   * @param integer $iDiasLetivos
   */
  public function setDiasLetivos($iDiasLetivos) {
    
    $this->iDiasLetivos = $iDiasLetivos;
  }
  
  /**
   * Retorna o numero de dias letivos do periodo
   * @return integer
   */
  public function getDiasLetivos() {
    
    return $this->iDiasLetivos;
  }
  
  /**
   * Atribui o numero de semanas letivas do periodo
   * @param integer $iSemanasLetivas
   */
  public function setSemanasLetivas($iSemanasLetivas) {
    
    $this->iSemanasLetivas = $iSemanasLetivas;
  }
  
  /**
   * Retorna o numero de semanas letivas do periodo
   * @return integer
   */
  public function getSemanasLetivas() {
    
    return $this->iSemanasLetivas;
  }

  /**
   * Seta o codigo sequencial do periodocalendario
   * @param integer $iCodigoPeriodoCalendario
   */
  public function setCodigoPeriodoCalendario($iCodigoPeriodoCalendario) {
    
    $this->iCodigoPeriodoCalendario = $iCodigoPeriodoCalendario;
  }
  
  /**
   * Retorna o codigo do periodocalendario
   * @return integer
   */
  public function getCodigoPeriodoCalendario() {
    
    return $this->iCodigoPeriodoCalendario;
  }

  /**
   * Salva o Periodo do Calendario
   *
   * @param  Calendario $oCalendario
   * @throws BusinessException
   * @throws ParameterException
   * @throws DBException
   * @return boolean
   */
  public function salvar($oCalendario) {
    
    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transaçao ativa com o banco de dados");
    }
    
    if (empty($oCalendario)) {
      throw new ParameterException("Não foi definido o calendario.");
    }
    
    $oDaoPeriodoCalendario = db_utils::getDao("periodocalendario");
    
    $oDaoPeriodoCalendario->ed53_i_periodoavaliacao = $this->oPeriodo->getCodigo();
    $oDaoPeriodoCalendario->ed53_d_inicio           = $this->dtInicio->getDate(DBDate::DATA_EN);
    $oDaoPeriodoCalendario->ed53_d_fim              = $this->dtTermino->getDate(DBDate::DATA_EN);
    $oDaoPeriodoCalendario->ed53_i_diasletivos      = $this->iDiasLetivos;
    $oDaoPeriodoCalendario->ed53_i_semletivas       = $this->iSemanasLetivas;
    
    if (empty($this->iPeriodoCalendario)) {
      
      $oDaoPeriodoCalendario->ed53_i_codigo     = null;
      $oDaoPeriodoCalendario->ed53_i_calendario = $oCalendario->getCodigo();
      $oDaoPeriodoCalendario->incluir(null);
      $this->iCodigoPeriodoCalendario = $oDaoPeriodoCalendario->ed53_i_codigo; 
    } else {
      
      $oDaoPeriodoCalendario->ed53_i_codigo = $this->iCodigoPeriodoCalendario;
      $oDaoPeriodoCalendario->alterar($this->iCodigoPeriodoCalendario);
    }
    
    if ($oDaoPeriodoCalendario->erro_status == 0) {
    
      $sErroMsg  = "Erro ao incluir/alterar o Periodo do Calendario {$this->oPeriodo->getDescricao()}.";
      throw new BusinessException($sErroMsg);
    }
    return true;
  }
  
  
  public function __clone() {
    $this->iPeriodoCalendario = null;
  }
}
?>