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

/*
 * Implementa interface iViradaIPTU.interface.php
 * @method public vira()
 */
require_once('interfaces/iViradaIPTU.interface.php');

class CaraliqIPTU implements iViradaIPTU {
  
  /**
   * Anual atual do exercicío
   * @var integer
   */
  private $iAnoAtual      = 0;
  
  /**
   * Ano novo do exercicío
   * @var integer
   */
  private $iAnoNovo       = 0;
  
  /**
   * Código da tabela da virada anual
   * @var integer
   */
  private $iCodigoTabela  = 0;
  
  /**
   * Percentual aplicado para os valores
   * @var numeric
   */
  private $nPercentual    = 0;
  
  /**
   * Campos chave configurados na tabela iptutabelasconfigcampochave
   * @var array
   */
  private $aCampoChave    = array();
  
  /**
   * Campos correcao configurados na tabela iptutabelasconfigcampocorrecao
   * @var array
   */
  private $aCampoCorrecao = array();
  
  /**
   * @return $this->iAnoAtual
   */
  private function getAnoAtual() {

    return $this->iAnoAtual;
  }
  
  /**
   * @param integer type $iAnoAtual
   */
  private function setAnoAtual($iAnoAtual) {

    $this->iAnoAtual = $iAnoAtual;
  }
  
  /**
   * @return $this->iAnoNovo
   */
  private function getAnoNovo() {

    return $this->iAnoNovo;
  }
  
  /**
   * @param integer type $iAnoNovo
   */
  private function setAnoNovo($iAnoNovo) {

    $this->iAnoNovo = $iAnoNovo;
  }
  
  /**
   * @return integer
   */
  private function getCodigoTabela() {

    return $this->iCodigoTabela;
  }
  
  /**
   * @param integer $iCodigoTabela
   */
  private function setCodigoTabela($iCodigoTabela) {

    $this->iCodigoTabela = $iCodigoTabela;
  }

  /**
   * @return $this->nPercentual
   */
  private function getPercentual() {

    return $this->nPercentual;
  }
  
  /**
   * @param numeric type $nPercentual
   */
  private function setPercentual($nPercentual) {

    $this->nPercentual = $nPercentual;
  }

  /**
   * @return $this->aCampoChave
   */
  private function getCampoChave() {

    return $this->aCampoChave;
  }
  
  /**
   * @param string  type $sCampoChave
   */
  private function setCampoChave($sCampoChave) {

    $this->aCampoChave[] = $sCampoChave;
    return $this;
  }
  
  /**
   * @return $this->aCampoCorrecao
   */
  private function getCampoCorrecao() {

    return $this->aCampoCorrecao;
  }
  
  /**
   * @param string type $sCampoCorrecao
   */
  private function setCampoCorrecao($sCampoCorrecao) {

    $this->aCampoCorrecao[] = $sCampoCorrecao;
    return $this;
  }
  
  function __construct() {
     
    $this->setAnoAtual(db_getsession('DB_anousu'));
    $this->setAnoNovo(db_getsession('DB_anousu') + 1);
    
    /**
     * Pesquisa se já foi feito a virada anual 
     */
    $oDaoIptuTabelasConfig  = db_utils::getDao('iptutabelasconfig');
    $sSqlIptuTabelasConfig  = $oDaoIptuTabelasConfig->sql_query(null, "iptutabelasconfig.j122_sequencial", 
                                                                null, "db_sysarquivo.nomearq = 'caraliq'");
    $rsSqlIptuTabelasConfig = $oDaoIptuTabelasConfig->sql_record($sSqlIptuTabelasConfig);
    if ($oDaoIptuTabelasConfig->numrows > 0) {
      
      $oDadosIptuTabelasConfig      = db_utils::fieldsMemory($rsSqlIptuTabelasConfig, 0);
      $oDaoIptuTabelasConfigVirada  = db_utils::getDao('iptutabelasconfigvirada');
      
      $sWhere                       = "j129_iptutabelasconfig = {$oDadosIptuTabelasConfig->j122_sequencial}";
      $sWhere                      .= " and j129_anousu = {$this->getAnoNovo()}";
      $sSqlIptuTabelasConfigVirada  = $oDaoIptuTabelasConfigVirada->sql_query_file(null, "*", null, $sWhere);
      $rsSqlIptuTabelasConfigVirada = $oDaoIptuTabelasConfigVirada->sql_record($sSqlIptuTabelasConfigVirada);
      if ($oDaoIptuTabelasConfigVirada->numrows > 0) {
      	
        $sMensagem = "ERRO: Tabela caraliq já foi feito virada anual para exercicío {$this->getAnoNovo()}!";
        throw new Exception($sMensagem);
      }
      
      $this->setCodigoTabela($oDadosIptuTabelasConfig->j122_sequencial);
    }
    
    /**
     * Pesquisa percentual padrao
     */
    $oDaoCfIptuCorrecao  = db_utils::getDao('cfiptu');
    $sCampos             = "cfiptu.j18_perccorrepadrao";
    $sSqlCfIptuCorrecao  = $oDaoCfIptuCorrecao->sql_query_file($this->getAnoNovo(), $sCampos, null, '');
    $rsSqlCfIptuCorrecao = $oDaoCfIptuCorrecao->sql_record($sSqlCfIptuCorrecao);
    if ($oDaoCfIptuCorrecao->numrows == 0) {
      
      $sMensagem = "ERRO: Nenhum registro encontrado na cfiptu exercicío {$this->getAnoNovo()}!";
      throw new Exception($sMensagem);
    }
    
    $oCfIptuCorrecao = db_utils::fieldsMemory($rsSqlCfIptuCorrecao, 0);
    $this->setPercentual($oCfIptuCorrecao->j18_perccorrepadrao);
    
    /**
     * Pesquisa campos chave
     */
    $oDaoIptuTabelasConfigCampoChave  = db_utils::getDao('iptutabelasconfigcampochave');   
    $sWhere                           = "db_sysarquivo.nomearq = 'caraliq'";
    $sSqlIptuTabelasConfigCampoChave  = $oDaoIptuTabelasConfigCampoChave->sql_query(null, "db_syscampo.nomecam", null, $sWhere);
    $rsSqlIptuTabelasConfigCampoChave = $oDaoIptuTabelasConfigCampoChave->sql_record($sSqlIptuTabelasConfigCampoChave);
    for ($iInd = 0; $iInd < $oDaoIptuTabelasConfigCampoChave->numrows; $iInd++) {
        
      $oIptuTabelasConfigCampoChave = db_utils::fieldsMemory($rsSqlIptuTabelasConfigCampoChave, $iInd);
      $this->setCampoChave($oIptuTabelasConfigCampoChave->nomecam);
    }
    
    /**
     * Pesquisa campo para correcao de percentual
     */
    $oDaoIptuTabelasConfigCorrecao  = db_utils::getDao('iptutabelasconfigcampocorrecao');
    $sWhere                         = "db_sysarquivo.nomearq = 'caraliq'";
    $sSqlIptuTabelasConfigCorrecao  = $oDaoIptuTabelasConfigCorrecao->sql_query(null, "*", null, $sWhere);
    $rsSqlIptuTabelasConfigCorrecao = $oDaoIptuTabelasConfigCorrecao->sql_record($sSqlIptuTabelasConfigCorrecao);
    if ($oDaoIptuTabelasConfigCorrecao->numrows > 0) {
      
      for ($iInd = 0; $iInd < $oDaoIptuTabelasConfigCorrecao->numrows; $iInd++) {
        
        $oIptuTabelasConfigCorrecao = db_utils::fieldsMemory($rsSqlIptuTabelasConfigCorrecao, $iInd);
        $this->setCampoCorrecao($oIptuTabelasConfigCorrecao->nomecam); 
      }
    }
  }
  
  /**
   * Processa virada anual
   *
   * @return $this
   */
  public function vira() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Nenhuma transação com o banco de dados aberta.  \\n\\nProcessamento cancelado.");
    }

    $iAnoAtual = $this->getAnoAtual();
    if ($iAnoAtual == 0) {
    
      $sMensagem = "ERRO: Ano exercício atual nao definido!";
      throw new Exception($sMensagem);
    }
    
    $iAnoNovo = $this->getAnoNovo();
    if ($iAnoNovo == 0) {
    
      $sMensagem = "ERRO: Ano próximo exercício nao definido!";
      throw new Exception($sMensagem);
    }
  	
    $oDaoCaraLiq  = db_utils::getDao('caraliq');
    
    $oDaoCaraLiq->excluir(null, null, "j73_anousu = {$this->getAnoNovo()}");
    if ($oDaoCaraLiq->erro_status == 0) {
      throw new Exception($oDaoCaraLiq->erro_msg);
    }
    
    $sWhere       = " j73_anousu = {$this->getAnoAtual()} ";
    $sSqlCaraLiq  = $oDaoCaraLiq->sql_query_file(null, null, "*", null, $sWhere);
    $rsSqlCaraLiq = $oDaoCaraLiq->sql_record($sSqlCaraLiq);
    $iNumRows = $oDaoCaraLiq->numrows;
    if ($iNumRows > 0) {

    	for ( $iInd=0; $iInd < $iNumRows; $iInd++ ) {
    		
    		$oDadosCaraLiq  = db_utils::fieldsMemory($rsSqlCaraLiq, $iInd);
        $aCamposCaraLiq = get_object_vars($oDadosCaraLiq);
        
        foreach ($aCamposCaraLiq as $sNomeCampoCaraLiq => $sValorCampoCaraLiq ) {
        	
          if (in_array($sNomeCampoCaraLiq, $this->getCampoCorrecao())) {
            
            $nPercentual     = $this->getPercentual();
            $nSomaPercentual = ($oDadosCaraLiq->$sNomeCampoCaraLiq + ($oDadosCaraLiq->$sNomeCampoCaraLiq * ($nPercentual / 100)));
            $oDaoCaraLiq->$sNomeCampoCaraLiq = "{$nSomaPercentual}";
          } else {
          	
          	if (trim($sNomeCampoCaraLiq) == 'j73_anousu') {
          		
          		$iAnoUso = $this->getAnoNovo();
          		$oDaoCaraLiq->j73_anousu = $iAnoUso;
          		
          	} else if (trim($sNomeCampoCaraLiq) == 'j73_caract') {
          		
              $iCaract = $oDadosCaraLiq->$sNomeCampoCaraLiq;
              
            } else {
            	
              $oDaoCaraLiq->$sNomeCampoCaraLiq = "{$sValorCampoCaraLiq}";
              
            }
            
          }
        }
        
        $oDaoCaraLiq->incluir($iAnoUso, $iCaract);
        if ($oDaoCaraLiq->erro_status == 0) {
          throw new Exception($oDaoCaraLiq->erro_msg);
        }
    	}
    }

    /**
     * Adiciona registro para verificação se tabela já fez virada anual
     */
	  $oDaoIptuTabelasConfigVirada = db_utils::getDao('iptutabelasconfigvirada');	  
	  $oDaoIptuTabelasConfigVirada->j129_iptutabelasconfig = $this->getCodigoTabela();
	  $oDaoIptuTabelasConfigVirada->j129_anousu            = $this->getAnoNovo();
	  $oDaoIptuTabelasConfigVirada->incluir(null);
	  if ($oDaoIptuTabelasConfigVirada->erro_status == 0) {
	    throw new Exception($oDaoIptuTabelasConfigVirada->erro_msg);
	  }
        
    return $this;
  }
}
?>