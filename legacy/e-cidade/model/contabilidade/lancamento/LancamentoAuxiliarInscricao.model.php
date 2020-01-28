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

require_once ("interfaces/ILancamentoAuxiliar.interface.php");
require_once ("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php");
/**
 * Executa os lancamentos auxiliares para documentos de inscri��o
 * @author Andrio Costa / Matheus Felini
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.7 $ 
 */
class LancamentoAuxiliarInscricao extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {
  
	/**
	 * C�digo da Inscri��o
	 * @var integer
	 */
  private $iInscricao;
  
  /**
   * Valor total da inscri��o
   * @var float
   */
  private $nValorTotalInscricao;
  
  /**
   * C�digo do Hist�rico
   * @var integer
   */
  private $iHistorico;
  
  /**
   * Ano do elemento
   * @var integer
   */
  private $iAnoElemento;
  
  
  /**
   * Executa Lan�amento nas tabelas auxili�res da inscri��o
   * @see ILancamentoAuxiliar::executaLancamentoAuxiliar()
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento) {
    
    /**
     * Grava o vinculo da Inscri��o com o Lan�amento
     */
    $oDaoConLanCamInscricaoPassivo                       = db_utils::getDao('conlancaminscricaopassivo');
    $oDaoConLanCamInscricaoPassivo->c37_sequencial       = null;
    $oDaoConLanCamInscricaoPassivo->c37_inscricaopassivo = $this->getInscricao();
    $oDaoConLanCamInscricaoPassivo->c37_instit           = db_getsession('DB_instit');
    $oDaoConLanCamInscricaoPassivo->c37_data             = $dtLancamento;
    $oDaoConLanCamInscricaoPassivo->c37_conlancam        = $iCodigoLancamento; 
    $oDaoConLanCamInscricaoPassivo->incluir(null);
    
    if ($oDaoConLanCamInscricaoPassivo->erro_status == 0) {
      
      $sErroMsg  = "N�o foi poss�vel incluir vinculo do lan�amento cont�bil com a inscri��o.";
      $sErroMsg .= "\n\nErro T�cnico:{$oDaoConLanCamInscricaoPassivo->erro_msg}";
      throw new BusinessException($sErroMsg);
    }
    
    /**
     * Grava o desdobramento da inscri��o. 
     */
    $oDaoConLancamEle             = db_utils::getDao('conlancamele');
    $oDaoConLancamEle->c67_codlan = $iCodigoLancamento;
    $oDaoConLancamEle->c67_codele = $this->getCodigoElemento();
    $oDaoConLancamEle->incluir($iCodigoLancamento);
    
    if ($oDaoConLancamEle->erro_status == 0) {
      
      $sErroMsg  = "N�o foi poss�vel incluir o v�nculo com o elemento.\n\n";
      $sErroMsg .= "Erro T�cnico: {$oDaoConLancamEle->erro_msg}";
      throw new BusinessException($sErroMsg);
    }

    /**
     * Incluindo vinculo do Lan�amento com o Complemento (observa��o do hist�rico [conhist])
     */
    $oDaoConLanCamCompl = db_utils::getDao('conlancamcompl');
    $oDaoConLanCamCompl->c72_codlan  = $iCodigoLancamento;
    $oDaoConLanCamCompl->c72_complem = $this->getObservacaoHistorico();
    $oDaoConLanCamCompl->incluir($iCodigoLancamento);
    
    if ($oDaoConLanCamCompl->erro_status == 0) {
      
      $sErroMsg  = "N�o foi poss�vel incluir o complemento do lan�amento.\n\n";
      $sErroMsg .= "Erro T�cnico: {$oDaoConLanCamCompl->erro_msg}";
      throw new BusinessException($sErroMsg);
    }
    
    /**
     * Incluindo vinculo do Lan�amento com Favorecido 
     */
    $oDaoConLanCamCgm = db_utils::getDao('conlancamcgm');
    $oDaoConLanCamCgm->c76_codlan = $iCodigoLancamento;
    $oDaoConLanCamCgm->c76_numcgm = $this->iFavorecido;
    $oDaoConLanCamCgm->c76_data   = $dtLancamento;
    $oDaoConLanCamCgm->incluir($iCodigoLancamento);
    
    if ($oDaoConLanCamCgm->erro_status == 0) {
    
      $sErroMsg  = "N�o foi poss�vel incluir vinculo do lan�amento com o Favorecido.\n\n";
      $sErroMsg .= "Erro T�cnico: {$oDaoConLanCamCompl->erro_msg}";
      throw new BusinessException($sErroMsg);
    }
    
    return true; 
  }    
  
  public function getInscricao () {
    return $this->iInscricao;
  }
  
  /**
   * Retorna o valor total
   * @see ILancamentoAuxiliar::getValorTotal()
   */
  public function getValorTotal() {
    return $this->nValorTotalInscricao;
  }
  
  public function getCodigoElemento() {
    return $this->iCodigoElemento;
  }

  /**
   * @see ILancamentoAuxiliar::getHistorico()
   */
  public function getHistorico() {
    return $this->iHistorico;
  }
  
  public function getAnoElemento() {
    return $this->iAnoElemento;
  }
  
  /**
   * @see ILancamentoAuxiliar::getObservacaoHistorico()
   */  
  public function getObservacaoHistorico() {
    return $this->sObservacaoHistorico;
  }
  
  public function getFavorecido() {
    return $this->iFavorecido;
  } 
  
  public function setInscricao ($iInscricao) {
    $this->iInscricao = $iInscricao;
  }
  
  /**
   * Seta o valor total
   * @see ILancamentoAuxiliar::setValorTotal()
   */
  public function setValorTotal($nValorTotal) {
    $this->nValorTotalInscricao = $nValorTotal;
  }
  
  public function setCodigoElemento($iCodigoElemento) {
    $this->iCodigoElemento = $iCodigoElemento;
  }

  /**
   * @see ILancamentoAuxiliar::setHistorico()
   */
  public function setHistorico($iHistorico) {
    $this->iHistorico = $iHistorico;
  }

  /**
   * @see ILancamentoAuxiliar::setObservacaoHistorico()
   */
  public function setObservacaoHistorico($sObservacaoHistorico) {
    $this->sObservacaoHistorico = $sObservacaoHistorico;
  }
  
  public function setAnoElemento($iAnoElemento) {
    $this->iAnoElemento = $iAnoElemento;
  }
  
  public function setFavorecido($iFavorecido) {
    $this->iFavorecido = $iFavorecido;
  }
  
  /**
   * ir� construir um lan�amento auxiliar do tipo inscri��o
   * @param integer $iCodigoLancamento (codlan)
   * @return object $oLancamentoAuxiliar
   */
  public static function getInstance($iCodigoLancamento){
  	
    $oDaoConlancamInscricaoPassivo = db_utils::getDao("conlancaminscricaopassivo");
  	//buscamos dados da inscri��o passiva
  	$sSqlInscricao = $oDaoConlancamInscricaoPassivo->sql_query_dadoslancamento(null, "*", null, "c37_conlancam = {$iCodigoLancamento}");
  	$rsInscricao   = $oDaoConlancamInscricaoPassivo->sql_record($sSqlInscricao);
  	if ($oDaoConlancamInscricaoPassivo->numrows == 0) {
  		throw new BusinessException("V�nculo do lan�amento {$iCodigoLancamento} com a inscri��o n�o encontrado");
  	}
  	$oDadosInscricao   = db_utils::fieldsMemory($rsInscricao, 0);
  	$oInscricaoPassivo = new InscricaoPassivoOrcamento($oDadosInscricao->c37_inscricaopassivo);
  	
  	$oLancamentoAuxiliarInscricao = new LancamentoAuxiliarInscricao();
  	$oLancamentoAuxiliarInscricao->setAnoElemento($oInscricaoPassivo->getAnoElemento());
  	$oLancamentoAuxiliarInscricao->setCodigoElemento($oInscricaoPassivo->getCodigoElemento());
  	$oLancamentoAuxiliarInscricao->setFavorecido($oInscricaoPassivo->getFavorecido()->getCodigo());
  	$oLancamentoAuxiliarInscricao->setHistorico($oInscricaoPassivo->getCodigoHistorico());
  	$oLancamentoAuxiliarInscricao->setInscricao($oInscricaoPassivo->getSequencial());
  	$oLancamentoAuxiliarInscricao->setObservacaoHistorico(addslashes(db_stdClass::normalizeStringJson($oDadosInscricao->c72_complem)));
  	$oLancamentoAuxiliarInscricao->setValorTotal($oDadosInscricao->c70_valor);
  	return $oLancamentoAuxiliarInscricao;
  	
  }
  
  
  
}