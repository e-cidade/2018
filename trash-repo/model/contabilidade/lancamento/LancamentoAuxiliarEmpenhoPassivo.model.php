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
class LancamentoAuxiliarEmpenhoPassivo extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {
  
  /** propriedades usadas nos m�todos getters e setters da interface **/
  
  /**
   * Valor Total do Lancamento
   * @var float
   */
  private $nValorTotal;
  
  /**
   * C�digo do Hist�rico
   * @var integer
   */
  private $iHistorico;
  
  /**
   * Observa��o do Hist�rico
   * @var string
   */
  private $sObservacaoHistorico;
  
  /** propriedades  espec�ficas do Lan�amentoAuxiliarEmpenho **/
  
  /**
   * C�digo Favorecido
   * @var integer
   */
  private $iCodigoFavorecido;

  
  /**
   * C�digo da Inscri��o
   * @var integer
   */
  private $iInscricao;
  
  /**
   * Complemento do Lan�amento
   * @var string
   */
  private $sComplemento;
  
  /**
   * Caracter�stica Peculiar
   * @var string
   */
  private $sCaracteristicaPeculiar;
   
 
  /**
   * Implementa m�todo da Interface
   * Executa Lan�amento, efetuando v�nculo nas tabelas auxiliares da inscri��o
   **/
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento) {

    /**
     *  Grava o vinculo da Inscri��o  com o Lan�amento
     */
    $oDaoConLanCamInscricaoPassivo                       = db_utils::getDao('conlancaminscricaopassivo');
    $oDaoConLanCamInscricaoPassivo->c37_sequencial       = null;
    $oDaoConLanCamInscricaoPassivo->c37_inscricaopassivo = $this->iInscricao;
    $oDaoConLanCamInscricaoPassivo->c37_data             = $dtLancamento;
    $oDaoConLanCamInscricaoPassivo->c37_instit           = db_getsession('DB_instit');
    $oDaoConLanCamInscricaoPassivo->c37_conlancam        = $iCodigoLancamento;
    $oDaoConLanCamInscricaoPassivo->incluir(null);
    if ($oDaoConLanCamInscricaoPassivo->erro_status == 0) {
      
      $sErroMsg  = "N�o foi poss�vel incluir o v�nculo com o inscri��o passivo do lan�amento.\n\n";
      $sErroMsg .= "Erro T�cnico:{$oDaoConLanCamInscricaoPassivo->erro_msg}";
      throw new BusinessException($sErroMsg);
      
    }
    unset($oDaoConLanCamInscricaoPassivo);
    
    /**
     *  Inlcuindo v�nculo do lan�amento com o Elemento [comlancamele]
     */
    $oDaoConLanCamEle = db_utils::getDao('conlancamele');
    $oDaoConLanCamEle->c67_codlan = $iCodigoLancamento;
    $oDaoConLanCamEle->c67_codele = $this->iCodigoElemento;
    $oDaoConLanCamEle->incluir($iCodigoLancamento);
    
    if ($oDaoConLanCamEle->erro_status == 0) {
      
      $sErroMsg  = "N�o foi poss�vel incluir o vinculo com o elemento do lan�amento.\n\n";
      $sErroMsg .= "Erro T�cnico: {$oDaoConLanCamEle->erro_msg}";
      throw new BusinessException($sErroMsg);
      
    }
    unset($oDaoConLanCamEle);
    
    /**
     *  Incluindo vinculo do Lan�amento com o Complemento [conlancamcompl]
     */
    $oDaoConLanCamCompl = db_utils::getDao('conlancamcompl');
    $oDaoConLanCamCompl->c72_codlan  = $iCodigoLancamento;
    $oDaoConLanCamCompl->c72_complem = $this->getComplemento();
    $oDaoConLanCamCompl->incluir($iCodigoLancamento);
  
    if ($oDaoConLanCamCompl->erro_status == 0) {
    
      $sErroMsg  = "N�o foi poss�vel incluir o complemento do lan�amento.\n\n";
      $sErroMsg .= "Erro T�cnico: {$oDaoConLanCamCompl->erro_msg}";
      throw new BusinessException($sErroMsg);
      
    }
    unset($oDaoConLanCamCompl);
    
    /**
     *  Incluindo vinculo do Lan�amento com o CGM Favorecido [conlancamcgm]
     */
    $oDaoConLanCamCGM = db_utils::getDao('conlancamcgm');
    $oDaoConLanCamCGM->c76_codlan = $iCodigoLancamento;
    $oDaoConLanCamCGM->c76_numcgm = $this->iCodigoFavorecido;
    $oDaoConLanCamCGM->c76_data   = $dtLancamento;
    $oDaoConLanCamCGM->incluir($iCodigoLancamento);
    
    if ($oDaoConLanCamCGM->erro_status == 0) {
      
      $sErroMsg      = "N�o foi poss�vel incluir o CGM do lan�amento.\n\n";
      $sErroTecnico .= "Erro T�cnico : {$oDaoConLanCamCGM->erro_msg}";
      throw new BusinessException($sErroMsg);

    }
    unset($oDaoConLanCamCGM);
    
    /**
     *  Incluindo vinculo do Lan�amento com Empenho [conlancamemp]
     */
    $oDaoConLanCamEmp = db_utils::getDao('conlancamemp');
    $oDaoConLanCamEmp->c75_codlan = $iCodigoLancamento ;
    $oDaoConLanCamEmp->c75_numemp = $this->iNumeroEmpenho;
    $oDaoConLanCamEmp->c75_data   = $dtLancamento;
    $oDaoConLanCamEmp->incluir($iCodigoLancamento);
    
    if ($oDaoConLanCamEmp->erro_status == 0) {
      
      $sErroMsg      = "N�o foi poss�vel vincular Lan�amento e Empenho.\n\n";
      $sErroTecnico .= "Erro T�cnico : {$oDaoConLanCamEmp->erro_msg}";
      throw new BusinessException($sErroMsg);
      
    }
    unset($oDaoConLanCamEmp);
    
    
    /**
     *  Incluindo vinculo do Lan�amento com Dotacao [conlancamdot]
     */
    $oDaoConLanCamDot = db_utils::getDao('conlancamdot');
    $oDaoConLanCamDot->c73_codlan = $iCodigoLancamento ;
    $oDaoConLanCamDot->c73_data   = $dtLancamento;
    $oDaoConLanCamDot->c73_anousu = db_getsession('DB_anousu');
    $oDaoConLanCamDot->c73_coddot = $this->iCodigoDotacao;
    $oDaoConLanCamDot->incluir($iCodigoLancamento);
    
    if ($oDaoConLanCamDot->erro_status == 0) {
    
      $sErroMsg      = "N�o foi poss�vel vincular Lan�amento e Dotacao.\n\n";
      $sErroTecnico .= "Erro T�cnico : {$oDaoConLanCamDot->erro_msg}";
      throw new BusinessException($sErroMsg);
    
    }
    unset($oDaoConLanCamDot);
    
    /**
     *  Incluindo vinculo do Lan�amento com Caracteristica Peculiar [conlancamconcarpeculiar]
     */
    $oDaoConLanCamConCarPeculiar = db_utils::getDao('conlancamconcarpeculiar');
    $oDaoConLanCamConCarPeculiar->c08_sequencial     = null ;
    $oDaoConLanCamConCarPeculiar->c08_codlan         = $iCodigoLancamento ;
    $oDaoConLanCamConCarPeculiar->c08_concarpeculiar = $this->sCaracteristicaPeculiar;
    $oDaoConLanCamConCarPeculiar->incluir(null);
    
    if ($oDaoConLanCamConCarPeculiar->erro_status == 0) {
    
      $sErroMsg  = "N�o foi poss�vel vincular o lan�amento com a caracteristica peculiar.\n\n";
      $sErroMsg .= "Erro T�cnico : {$oDaoConLanCamConCarPeculiar->erro_msg}";
      throw new BusinessException($sErroMsg);
    
    }
    unset($oDaoConLanCamConCarPeculiar);
    return true;
  }
    
  /**
   *  Retorna o c�digo do Favorecido
   *  @return integer
   **/
  public function getFavorecido() {
    return $this->iCodigoFavorecido;
  }
  
  /**
   *  Seta o c�digo do Favorecido
   *  @param integer
   **/
  public function setFavorecido($iCodigoFavorecido) {
    $this->iCodigoFavorecido = $iCodigoFavorecido;
  }
  
  /**
   *  Retorna o c�digo do Elemento
   *  @return integer
   **/
  public function getCodigoElemento() {
    return $this->iCodigoFavorecido;  
  }
  
  /**
   *  Seta o c�digo do Elemento
   *  @param integer
   **/
  public function setCodigoElemento($iCodigoElemento) {
    $this->iCodigoElemento = $iCodigoElemento;
  }
  
  
  /**
   *  Retorna o N�mero do Empenho
   *  @return integer
   **/
  public function getNumeroEmpenho() {
    return $this->iNumeroEmpenho;  
  }
  
  /**
   *  Seta o N�mero do Empenho
   *  @param integer
   **/
  public function setNumeroEmpenho($iNumeroEmpenho) {
    $this->iNumeroEmpenho = $iNumeroEmpenho;
  }
  
  /**
   *  Retorna o c�digo do Dota��o
   *  @return integer
   **/
  public function getCodigoDotacao() {
    return $this->iCodigoDotacao;
  }
  
  /**
   *  Seta o c�digo do Dota��o
   *  @param integer
   **/
  public function setCodigoDotacao($iCodigoDotacao) {
    $this->iCodigoDotacao = $iCodigoDotacao;
  }
  
  /**
   *  Retorna o n�mero de inscri��o
   *  @return  integer
   **/
  public function getInscricao() {
    return $this->iInscricao;
  }

  /**
   *  Seta o n�mero de  Inscricao
   *  @return  integer
   **/
  public function setInscricao($iInscricao) {
    $this->iInscricao = $iInscricao;
  }
  
  
  /**
   *  Retorna o Complemento
   *  @return  string
   **/
  public function getComplemento() {
    return $this->sComplemento;
  }
  
  /**
   *  Seta o Complemento
   *  @param  string
   **/
  public function setComplemento($sComplemento) {
    $this->sComplemento = $sComplemento;
  }
  
  /**
   *  Retorna a Caracter�stica Peculiar
   *  @return  string
   **/
  public function getCaracteristicaPeculiar() {
    return $this->sCaracteristicaPeculiar;
  }
  
  /**
   *  Seta a Caracter�stica Peculiar
   *  @param string
   **/
  public function setCaracteristicaPeculiar($sCaracteristicaPeculiar) {
    $this->sCaracteristicaPeculiar = $sCaracteristicaPeculiar ;
  }
  
  //getters e setters referentes a interface
  /**
   * Implementa m�todo da Interface
   * Retorna o valor total
   * @return float
   */
  public function getValorTotal() {
    return $this->nValorTotal;
  }
  
  /**
   * Implementa m�todo da Interface
   * Seta o valor total do evento
   * @param float 
   */
  public function setValorTotal($nValorTotal){
    $this->nValorTotal = $nValorTotal;    
  }
  
  /**
   * Implementa m�todo da Interface
   * Retorna o hist�rico da opera��o
   * @return integer
   */
  public function getHistorico() {
    return $this->iHistorico; 
  }
  
  /**
   * Implementa m�todo da Interface
   * Seta o hist�rico da opera��o
   * @param integer
   */
  public function setHistorico($iHistorico) {
    $this->iHistorico = $iHistorico;
  }
  
  /**
   * Implementa m�todo da Interface
   * Retorna a observa��o do hist�rico da opera��o
   * @return string
   */
  public function getObservacaoHistorico() {
    return $this->sObservacaoHistorico;
  }
  
  /**
   * Implementa m�todo da Interface
   * Seta a observa��o do hist�rico da opera��o
   * @param string
   */
  public function setObservacaoHistorico($sObservacaoHistorico) {
    $this->sObservacaoHistorico = $sObservacaoHistorico;
  }
}
?>