<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

class InflaIPTU implements iViradaIPTU {
  
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
                                                                null, "db_sysarquivo.nomearq = 'infla'");
    $rsSqlIptuTabelasConfig = $oDaoIptuTabelasConfig->sql_record($sSqlIptuTabelasConfig);
    if ($oDaoIptuTabelasConfig->numrows > 0) {
      
      $oDadosIptuTabelasConfig      = db_utils::fieldsMemory($rsSqlIptuTabelasConfig, 0);
      $oDaoIptuTabelasConfigVirada  = db_utils::getDao('iptutabelasconfigvirada');
      
      $sWhere                       = "j129_iptutabelasconfig = {$oDadosIptuTabelasConfig->j122_sequencial}";
      $sWhere                      .= " and j129_anousu = {$this->getAnoNovo()}";
      $sSqlIptuTabelasConfigVirada  = $oDaoIptuTabelasConfigVirada->sql_query_file(null, "*", null, $sWhere);
      $rsSqlIptuTabelasConfigVirada = $oDaoIptuTabelasConfigVirada->sql_record($sSqlIptuTabelasConfigVirada);
      if ($oDaoIptuTabelasConfigVirada->numrows > 0) {
      	
        $sMensagem = "ERRO: Tabela infla já foi feito virada anual para exercicío {$this->getAnoNovo()}!";
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
     * Pesquisa campo para correcao de percentual
     */
    $oDaoIptuTabelasConfigCorrecao  = db_utils::getDao('iptutabelasconfigcampocorrecao');
    $sWhere                         = "db_sysarquivo.nomearq = 'infla'";
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
  	    
		$oDaoInfla  = db_utils::getDao('infla');
		    
		$sSqlDeleteInfla  = "  delete                                                                           ";
		$sSqlDeleteInfla .= "    from infla                                                                     ";
		$sSqlDeleteInfla .= "         using cfiptu                                                              ";
		$sSqlDeleteInfla .= "   where infla.i02_codigo = cfiptu.j18_infla                                       ";
		$sSqlDeleteInfla .= "     and cfiptu.j18_anousu                  = {$this->getAnoNovo()}                ";
		$sSqlDeleteInfla .= "     and extract (year from infla.i02_data) = {$this->getAnoNovo()}                ";
		$rsSqlDeleteInfla = db_query($sSqlDeleteInfla);
		if (!$rsSqlDeleteInfla) {
			
			$sMensagem = "ERRO: Registro nao excluído da tabela infla para o exercicío de {$this->getAnoNovo()}! Exclusão Abortada.";
			throw new Exception($sMensagem);
		}
		
		$sSqlInfla     = " select infla.i02_codigo,                                                                   ";
		$sSqlInfla    .= "        infla.i02_data + '1 year'::interval as i02_data,                                    "; 
		$sSqlInfla    .= "        infla.i02_valor as i02_valor                                                        "; 
		$sSqlInfla    .= "   from infla                                                                               ";
		$sSqlInfla    .= "        inner join cfiptu       on infla.i02_codigo  = j18_infla                            ";
		$sSqlInfla    .= "        left  join infla infla2 on infla2.i02_codigo = infla.i02_codigo                     ";
		$sSqlInfla    .= "                              and infla2.i02_data    = infla.i02_data + '1 year'::interval  "; 
		$sSqlInfla    .= "  where j18_anousu = {$this->getAnoNovo()}                                                  ";
		$sSqlInfla    .= "    and extract (year from infla.i02_data) = {$this->getAnoAtual()}                         "; 
		$sSqlInfla    .= "    and infla2.i02_codigo is null                                                           ";
		$rsSqlInfla    = db_query($sSqlInfla);
		$iNumRowsInfla = pg_num_rows($rsSqlInfla);
		if ($iNumRowsInfla > 0) {
			
		  for ( $iInd=0; $iInd < $iNumRowsInfla; $iInd++ ) {
		        
		    $oDadosInfla  = db_utils::fieldsMemory($rsSqlInfla, $iInd);
		    $aCamposInfla = get_object_vars($oDadosInfla);
		    
		    foreach ($aCamposInfla as $sNomeCampoInfla => $sValorCampoInfla ) {
		          
		      if (in_array($sNomeCampoInfla, $this->getCampoCorrecao())) {
		            
            $nPercentual     = $this->getPercentual();
            $nSomaPercentual = ($oDadosInfla->$sNomeCampoInfla + ($oDadosInfla->$sNomeCampoInfla * ($nPercentual / 100)));
		        $oDaoInfla->$sNomeCampoInfla = "{$nSomaPercentual}";
		      } else {
		            
		        if (trim($sNomeCampoInfla) == 'i02_codigo') {
		          $iCodigo = $oDadosInfla->$sNomeCampoInfla;
		        }
		      
		        if (trim($sNomeCampoInfla) == 'i02_data') {
		          $sData = $oDadosInfla->$sNomeCampoInfla;
		        }
		        
		        $oDaoInfla->$sNomeCampoInfla = $oDadosInfla->$sNomeCampoInfla;
		      }
		    }
		        
		    $oDaoInfla->incluir($iCodigo, $sData);
		    if ($oDaoInfla->erro_status == 0) {
		      throw new Exception($oDaoInfla->erro_msg);
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