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
  
  /** propriedades usadas nos mйtodos getters e setters da interface **/
  
  /**
   * Valor Total do Lancamento
   * @var float
   */
  private $nValorTotal;
  
  /**
   * Cуdigo do Histуrico
   * @var integer
   */
  private $iHistorico;
  
  /**
   * Observaзгo do Histуrico
   * @var string
   */
  private $sObservacaoHistorico;
  
  /** propriedades  especнficas do LanзamentoAuxiliarEmpenho **/
  
  /**
   * Cуdigo Favorecido
   * @var integer
   */
  private $iCodigoFavorecido;

  
  /**
   * Cуdigo da Inscriзгo
   * @var integer
   */
  private $iInscricao;
  
  /**
   * Complemento do Lanзamento
   * @var string
   */
  private $sComplemento;
  
  /**
   * Caracterнstica Peculiar
   * @var string
   */
  private $sCaracteristicaPeculiar;
   
 
  /**
   * Implementa mйtodo da Interface
   * Executa Lanзamento, efetuando vнnculo nas tabelas auxiliares da inscriзгo
   **/
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento) {

    /**
     *  Grava o vinculo da Inscriзгo  com o Lanзamento
     */
    $oDaoConLanCamInscricaoPassivo                       = db_utils::getDao('conlancaminscricaopassivo');
    $oDaoConLanCamInscricaoPassivo->c37_sequencial       = null;
    $oDaoConLanCamInscricaoPassivo->c37_inscricaopassivo = $this->iInscricao;
    $oDaoConLanCamInscricaoPassivo->c37_data             = $dtLancamento;
    $oDaoConLanCamInscricaoPassivo->c37_instit           = db_getsession('DB_instit');
    $oDaoConLanCamInscricaoPassivo->c37_conlancam        = $iCodigoLancamento;
    $oDaoConLanCamInscricaoPassivo->incluir(null);
    if ($oDaoConLanCamInscricaoPassivo->erro_status == 0) {
      
      $sErroMsg  = "Nгo foi possнvel incluir o vнnculo com o inscriзгo passivo do lanзamento.\n\n";
      $sErroMsg .= "Erro Tйcnico:{$oDaoConLanCamInscricaoPassivo->erro_msg}";
      throw new BusinessException($sErroMsg);
      
    }
    unset($oDaoConLanCamInscricaoPassivo);
    
    /**
     *  Inlcuindo vнnculo do lanзamento com o Elemento [comlancamele]
     */
    $oDaoConLanCamEle = db_utils::getDao('conlancamele');
    $oDaoConLanCamEle->c67_codlan = $iCodigoLancamento;
    $oDaoConLanCamEle->c67_codele = $this->iCodigoElemento;
    $oDaoConLanCamEle->incluir($iCodigoLancamento);
    
    if ($oDaoConLanCamEle->erro_status == 0) {
      
      $sErroMsg  = "Nгo foi possнvel incluir o vinculo com o elemento do lanзamento.\n\n";
      $sErroMsg .= "Erro Tйcnico: {$oDaoConLanCamEle->erro_msg}";
      throw new BusinessException($sErroMsg);
      
    }
    unset($oDaoConLanCamEle);
    
    /**
     *  Incluindo vinculo do Lanзamento com o Complemento [conlancamcompl]
     */
    $oDaoConLanCamCompl = db_utils::getDao('conlancamcompl');
    $oDaoConLanCamCompl->c72_codlan  = $iCodigoLancamento;
    $oDaoConLanCamCompl->c72_complem = $this->getComplemento();
    $oDaoConLanCamCompl->incluir($iCodigoLancamento);
  
    if ($oDaoConLanCamCompl->erro_status == 0) {
    
      $sErroMsg  = "Nгo foi possнvel incluir o complemento do lanзamento.\n\n";
      $sErroMsg .= "Erro Tйcnico: {$oDaoConLanCamCompl->erro_msg}";
      throw new BusinessException($sErroMsg);
      
    }
    unset($oDaoConLanCamCompl);
    
    /**
     *  Incluindo vinculo do Lanзamento com o CGM Favorecido [conlancamcgm]
     */
    $oDaoConLanCamCGM = db_utils::getDao('conlancamcgm');
    $oDaoConLanCamCGM->c76_codlan = $iCodigoLancamento;
    $oDaoConLanCamCGM->c76_numcgm = $this->iCodigoFavorecido;
    $oDaoConLanCamCGM->c76_data   = $dtLancamento;
    $oDaoConLanCamCGM->incluir($iCodigoLancamento);
    
    if ($oDaoConLanCamCGM->erro_status == 0) {
      
      $sErroMsg      = "Nгo foi possнvel incluir o CGM do lanзamento.\n\n";
      $sErroTecnico .= "Erro Tйcnico : {$oDaoConLanCamCGM->erro_msg}";
      throw new BusinessException($sErroMsg);

    }
    unset($oDaoConLanCamCGM);
    
    /**
     *  Incluindo vinculo do Lanзamento com Empenho [conlancamemp]
     */
    $oDaoConLanCamEmp = db_utils::getDao('conlancamemp');
    $oDaoConLanCamEmp->c75_codlan = $iCodigoLancamento ;
    $oDaoConLanCamEmp->c75_numemp = $this->iNumeroEmpenho;
    $oDaoConLanCamEmp->c75_data   = $dtLancamento;
    $oDaoConLanCamEmp->incluir($iCodigoLancamento);
    
    if ($oDaoConLanCamEmp->erro_status == 0) {
      
      $sErroMsg      = "Nгo foi possнvel vincular Lanзamento e Empenho.\n\n";
      $sErroTecnico .= "Erro Tйcnico : {$oDaoConLanCamEmp->erro_msg}";
      throw new BusinessException($sErroMsg);
      
    }
    unset($oDaoConLanCamEmp);
    
    
    /**
     *  Incluindo vinculo do Lanзamento com Dotacao [conlancamdot]
     */
    $oDaoConLanCamDot = db_utils::getDao('conlancamdot');
    $oDaoConLanCamDot->c73_codlan = $iCodigoLancamento ;
    $oDaoConLanCamDot->c73_data   = $dtLancamento;
    $oDaoConLanCamDot->c73_anousu = db_getsession('DB_anousu');
    $oDaoConLanCamDot->c73_coddot = $this->iCodigoDotacao;
    $oDaoConLanCamDot->incluir($iCodigoLancamento);
    
    if ($oDaoConLanCamDot->erro_status == 0) {
    
      $sErroMsg      = "Nгo foi possнvel vincular Lanзamento e Dotacao.\n\n";
      $sErroTecnico .= "Erro Tйcnico : {$oDaoConLanCamDot->erro_msg}";
      throw new BusinessException($sErroMsg);
    
    }
    unset($oDaoConLanCamDot);
    
    /**
     *  Incluindo vinculo do Lanзamento com Caracteristica Peculiar [conlancamconcarpeculiar]
     */
    $oDaoConLanCamConCarPeculiar = db_utils::getDao('conlancamconcarpeculiar');
    $oDaoConLanCamConCarPeculiar->c08_sequencial     = null ;
    $oDaoConLanCamConCarPeculiar->c08_codlan         = $iCodigoLancamento ;
    $oDaoConLanCamConCarPeculiar->c08_concarpeculiar = $this->sCaracteristicaPeculiar;
    $oDaoConLanCamConCarPeculiar->incluir(null);
    
    if ($oDaoConLanCamConCarPeculiar->erro_status == 0) {
    
      $sErroMsg  = "Nгo foi possнvel vincular o lanзamento com a caracteristica peculiar.\n\n";
      $sErroMsg .= "Erro Tйcnico : {$oDaoConLanCamConCarPeculiar->erro_msg}";
      throw new BusinessException($sErroMsg);
    
    }
    unset($oDaoConLanCamConCarPeculiar);
    return true;
  }
    
  /**
   *  Retorna o cуdigo do Favorecido
   *  @return integer
   **/
  public function getFavorecido() {
    return $this->iCodigoFavorecido;
  }
  
  /**
   *  Seta o cуdigo do Favorecido
   *  @param integer
   **/
  public function setFavorecido($iCodigoFavorecido) {
    $this->iCodigoFavorecido = $iCodigoFavorecido;
  }
  
  /**
   *  Retorna o cуdigo do Elemento
   *  @return integer
   **/
  public function getCodigoElemento() {
    return $this->iCodigoFavorecido;  
  }
  
  /**
   *  Seta o cуdigo do Elemento
   *  @param integer
   **/
  public function setCodigoElemento($iCodigoElemento) {
    $this->iCodigoElemento = $iCodigoElemento;
  }
  
  
  /**
   *  Retorna o Nъmero do Empenho
   *  @return integer
   **/
  public function getNumeroEmpenho() {
    return $this->iNumeroEmpenho;  
  }
  
  /**
   *  Seta o Nъmero do Empenho
   *  @param integer
   **/
  public function setNumeroEmpenho($iNumeroEmpenho) {
    $this->iNumeroEmpenho = $iNumeroEmpenho;
  }
  
  /**
   *  Retorna o cуdigo do Dotaзгo
   *  @return integer
   **/
  public function getCodigoDotacao() {
    return $this->iCodigoDotacao;
  }
  
  /**
   *  Seta o cуdigo do Dotaзгo
   *  @param integer
   **/
  public function setCodigoDotacao($iCodigoDotacao) {
    $this->iCodigoDotacao = $iCodigoDotacao;
  }
  
  /**
   *  Retorna o nъmero de inscriзгo
   *  @return  integer
   **/
  public function getInscricao() {
    return $this->iInscricao;
  }

  /**
   *  Seta o nъmero de  Inscricao
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
   *  Retorna a Caracterнstica Peculiar
   *  @return  string
   **/
  public function getCaracteristicaPeculiar() {
    return $this->sCaracteristicaPeculiar;
  }
  
  /**
   *  Seta a Caracterнstica Peculiar
   *  @param string
   **/
  public function setCaracteristicaPeculiar($sCaracteristicaPeculiar) {
    $this->sCaracteristicaPeculiar = $sCaracteristicaPeculiar ;
  }
  
  //getters e setters referentes a interface
  /**
   * Implementa mйtodo da Interface
   * Retorna o valor total
   * @return float
   */
  public function getValorTotal() {
    return $this->nValorTotal;
  }
  
  /**
   * Implementa mйtodo da Interface
   * Seta o valor total do evento
   * @param float 
   */
  public function setValorTotal($nValorTotal){
    $this->nValorTotal = $nValorTotal;    
  }
  
  /**
   * Implementa mйtodo da Interface
   * Retorna o histуrico da operaзгo
   * @return integer
   */
  public function getHistorico() {
    return $this->iHistorico; 
  }
  
  /**
   * Implementa mйtodo da Interface
   * Seta o histуrico da operaзгo
   * @param integer
   */
  public function setHistorico($iHistorico) {
    $this->iHistorico = $iHistorico;
  }
  
  /**
   * Implementa mйtodo da Interface
   * Retorna a observaзгo do histуrico da operaзгo
   * @return string
   */
  public function getObservacaoHistorico() {
    return $this->sObservacaoHistorico;
  }
  
  /**
   * Implementa mйtodo da Interface
   * Seta a observaзгo do histуrico da operaзгo
   * @param string
   */
  public function setObservacaoHistorico($sObservacaoHistorico) {
    $this->sObservacaoHistorico = $sObservacaoHistorico;
  }
}
?>