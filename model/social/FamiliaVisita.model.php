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
 * Lista de telefone(s) de um cidadao
 * @author Andrio Costa
 * @package social
 * @version $Revision: 1.6 $
 */
class FamiliaVisita {
  
  /**
   * sequencial da cidadaofamiliavisita
   * @var integer
   */
  private $iCodigoSequencial;
   
  /**
   * data da visita 
   * @var string
   */
  private $dtVisita;
  
  /**
   * Hora da visita
   * @var string
   */
  private $sHoraVisita;
  
  /**
   * Codigo do profissional que fez a visita
   * @var integer
   */
  protected $iProfissionalVisita;
  
  /**
   * Codigo de cidadaofamilia
   * @var integer
   */
  protected $iCodigoCidadaoFamilia;
  
  /**
   * observaçao 
   * @var string 
   */
  private $sObservacao;
  
  /**
   * Instancia de VisitaTipo
   * @var VisitaTipo
   */
  private $oVisitaTipo;
  
  /**
   * Instancia do CGM para onde foi feito o encaminhamento
   * @var CGM
   */
  private $oCgmEncaminhamento = null;
  
  private $iCodigoEncaminhamento;
  
  public function __construct($iCodigoSequencial = null) {
    
    if (!empty($iCodigoSequencial)) {
      
      $oDaoVisita   = db_utils::getDao('cidadaofamiliavisita');
      $sSqlVisita   = $oDaoVisita->sql_query_visitaencaminhamento($iCodigoSequencial);
      $rsVisita     = $oDaoVisita->sql_record($sSqlVisita);
      
      if ($oDaoVisita->numrows > 0) {
      
        $oVisita   = db_utils::fieldsMemory($rsVisita, 0);
        
        $this->iCodigoSequencial     = $oVisita->as05_sequencial;
        $this->iCodigoCidadaoFamilia = $oVisita->as05_cidadaofamilia;
        $this->dtVisita              = db_formatar($oVisita->as05_datavisita, 'd');
        $this->sHoraVisita           = $oVisita->as05_horavisita;
        $this->sObservacao           = $oVisita->as05_observacao;
        $this->iProfissionalVisita   = $oVisita->as05_profissional;
        
        if (!empty($oVisita->as05_visitatipo)) {
          $this->oVisitaTipo = new VisitaTipo($oVisita->as05_visitatipo);
        }
        
        if (!empty($oVisita->as14_sequencial)) {
          
          $this->iCodigoEncaminhamento = $oVisita->as14_sequencial;
          $this->oCgmEncaminhamento    = CgmFactory::getInstanceByCgm($oVisita->as14_cgm);
        }
      }
    }
  }
  
  public function salvar($iCidadaoFamilia) {
    
    if (empty($iCidadaoFamilia)) {
      
      $sMsgErro = "Parâmetro código da Familia não informado";
      throw new ParameterException($sMsgErro);
    }
    
    $oDaoVisita                      = new cl_cidadaofamiliavisita();
    $oDaoVisita->as05_cidadaofamilia = $iCidadaoFamilia;
    $oDaoVisita->as05_datavisita     = implode("-", array_reverse(explode("/", $this->dtVisita)));
    $oDaoVisita->as05_observacao     = $this->sObservacao;
    $oDaoVisita->as05_profissional   = $this->getProfissionalVisita();
    $oDaoVisita->as05_horavisita     = $this->getHoraVisita();
    $oDaoVisita->as05_visitatipo     = $this->getVisitaTipo()->getCodigo();
    
    if (!empty($this->iCodigoSequencial)) {
      
      $oDaoVisita->as05_sequencial = $this->iCodigoSequencial;
      $oDaoVisita->alterar($this->iCodigoSequencial);
    } else {
      
      $oDaoVisita->incluir(null);
      $this->iCodigoSequencial = $oDaoVisita->as05_sequencial;
    }
    
    if ($oDaoVisita->erro_status == 0) {
    
      $sMsgErro  = "Erro ao incluir ou alterar:";
      $sMsgErro .= "\n\nErro técnico: {$oDaoVisita->erro_msg}";
      throw new BusinessException($sMsgErro);
    }
    
    $oDaoVisitaEncaminhamento = new cl_cidadaofamiliavisitaencaminhamento();
    $sWhereExcluir            = "as14_cidadaofamiliavisita = {$this->getCodigoSequencial()}";
    $oDaoVisitaEncaminhamento->excluir(null, $sWhereExcluir);
    if ($oDaoVisitaEncaminhamento->erro_status == 0) {
      throw BusinessException($oDaoVisitaEncaminhamento->erro_msg);
    }
      
    if (!empty($this->oCgmEncaminhamento)) {
      
      $oDaoVisitaEncaminhamento->as14_cidadaofamiliavisita = $this->getCodigoSequencial();
      $oDaoVisitaEncaminhamento->as14_cgm                  = $this->getCgmEncaminhamento()->getCodigo();
      $oDaoVisitaEncaminhamento->incluir(null);
      $this->iCodigoEncaminhamento = $oDaoVisitaEncaminhamento->as14_sequencial;
    }
    
    if ($oDaoVisitaEncaminhamento->erro_status == 0) {
    
      $sMsgErro  = "Erro ao incluir ou alterar:";
      $sMsgErro .= "\n\nErro técnico: {$oDaoVisitaEncaminhamento->erro_msg}";
      throw new BusinessException($sMsgErro);
    }
    unset($oDaoVisitaEncaminhamento);
    
    return true;
    
  }
  
  public function getCodigoSequencial() {
    
    return $this->iCodigoSequencial;
  }

  /**
   *  Define a data da visita da familia 
   *  formato d-m-Y
   * @param string $dtVisita
   */
  public function setDataVisita($dtVisita) {
  
    $this->dtVisita = $dtVisita;
  }
  /**
   *  Retorna a data da visita da familia
   *  formato d-m-Y
   * @param string $dtVisita
   */
  public function getDataVisita() {
  
    return $this->dtVisita;
  }

  /**
   * define uma observacao para a visita
   * @param string
   */
  public function setObservacao($sObservacao) {
  
    $this->sObservacao = $sObservacao;
  }

  /**
   * retorna uma observacao para a visita
   * @return string 
   */
  public function getObservacao() {
  
    return $this->sObservacao;
  }
  
  /**
   * 
   */
  static public function  getUltimaVisita($iCodigoFamilia) {
  	
  	$oDaoVisita   = db_utils::getDao('cidadaofamiliavisita');
  	$sWhere       = " as05_cidadaofamilia = {$iCodigoFamilia}";
  	$sCampos      = " max(as05_datavisita) as as05_datavisita ";
  	$sSqlVisita   = $oDaoVisita->sql_query_file(null, $sCampos, null, $sWhere);
  	$rsVisita     = $oDaoVisita->sql_record($sSqlVisita);
  	
  	$dtUltimaVisita = "";
  	
  	if ($oDaoVisita->numrows > 0) {
  		
  		$dtUltimaVisita = db_formatar(db_utils::fieldsMemory($rsVisita, 0)->as05_datavisita, "d");
  	}
  	return $dtUltimaVisita;
  }
  
  /**
   * Retorna a hora da visita
   * @return string
   */
  public function getHoraVisita() {
    return $this->sHoraVisita;
  }
  
  /**
   * Setamos a hora da visita
   * @param string $sHoraVisita
   */
  public function setHoraVisita($sHoraVisita) {
    $this->sHoraVisita = $sHoraVisita;
  }
  
  /**
   * Retorna o codigo do profissional que fez o contato com a familia
   * @return integer
   */
  public function getProfissionalVisita() {
    return $this->iProfissionalVisita;
  }
  
  /**
   * Seta o codigo do profissional que fez o contato com a familia
   * @param integer $iProfissionalContato
   */
  public function setProfissionalVisita($iProfissionalVisita) {
    $this->iProfissionalVisita = $iProfissionalVisita;
  }
  
  /**
   * Retorna o codigo de cidadaofamilia
   * @return integer
   */
  public function getCodigoCidadaoFamilia() {
    return $this->iCodigoCidadaoFamilia;
  }
  
  /**
   * Seta o codigo de cidadaofamilia
   * @param integer $iCidadaoFamilia
   */
  public function setCodigoCidadaoFamilia($iCidadaoFamilia) {
    $this->iCodigoCidadaoFamilia = $iCidadaoFamilia;
  }
  
  

  /**
   * Retorna uma instancia de VisitaTipo
   * @return VisitaTipo
   */
  public function getVisitaTipo() {
    return $this->oVisitaTipo;
  }

  /**
   * Seta uma instancia de VisitaTipo
   * @param VisitaTipo $oVisitaTipo
   */
  public function setVisitaTipo(VisitaTipo $oVisitaTipo) {
    $this->oVisitaTipo = $oVisitaTipo;
  }

  /**
   * Retorna uma instancia do CGM
   * @return CgmBase
   */
  public function getCgmEncaminhamento() {
    return $this->oCgmEncaminhamento;
  }

  /**
   * Seta uma instancia de CGM
   * @param $oCgmEncaminhamento
   */
  public function setCgmEncaminhamento($oCgmEncaminhamento) {
    $this->oCgmEncaminhamento = $oCgmEncaminhamento;
  }
  
  /**
   * Retorna o codigo de cidadaofamiliavisitaencaminhamento
   * @param integer
   */
  public function getCodigoEncaminhamento() {
    return $this->iCodigoEncaminhamento;
  }
  
  public function removeCgmEncaminhamento() {
    $this->oCgmEncaminhamento = null;
  }
}