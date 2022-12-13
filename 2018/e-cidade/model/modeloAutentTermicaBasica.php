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


require_once 'model/impressao.bematechMP2100TH.php';

abstract class modeloAutentTermicaBasica {
	
	private   $sBuffer     = '';
	protected $oImpressora = null;
	private   $oInstit     = null;
	public    $oCorrente   = null;
	public    $sPorta      = null;
	public    $sIp         = null;
	public    $lEstorno    = false;
	public    $nValorTotal = 0;
  public    $iId         = "";
  public    $sData       = "";
  public    $iAutent     = "";
	
  /**
   * Classe que implementa a estrutura basica do modelo de autenticacao
   */
  function __construct($sIp,$sPorta,$iId,$sData,$iAutent) {
  	
    $this->sIp     = $sIp;
    $this->sPorta  = $sPorta;
    $this->iId     = $iId;
    $this->sData   = $sData;
    $this->iAutent = $iAutent;
    
    $this->oImpressora = new impressaoMP2100TH($sIp,$sPorta);
  	
  	$sSqlCorrente  = "select * ";
    $sSqlCorrente .= "  from corrente ";
    $sSqlCorrente .= " where k12_data   = '{$sData}'";
    $sSqlCorrente .= "   and k12_id     = {$iId}";
    $sSqlCorrente .= "   and k12_autent = {$iAutent}";    
    $rsCorrente    = db_query($sSqlCorrente);
    if (pg_num_rows($rsCorrente) == 0) {
      throw new Exception("Autenticacao nao encontrada ID:{$iId} DATA:{$sData} AUTENT:{$iAutent}");     
    }
    $this->oCorrente = db_utils::fieldsMemory($rsCorrente,0);
    
    if ($this->oCorrente->k12_estorn == 't'){
      $this->lEstorno = true;
    }

    $this->nValorTotal = $this->oCorrente->k12_valor;
  	
  }
  
  function imprimir($sStringCorpo) {
  	
  	$sSqlInstit = "select * from db_config where codigo = ".db_getsession('DB_instit');
  	$rsInstit   = db_query($sSqlInstit);
  	if (pg_num_rows($rsInstit) == 0) {
      throw new Exception("Dados da instituicao nao encontrados.");      
    }
    $this->oInstit = db_utils::fieldsMemory($rsInstit,0);
    
  	/**
  	 * Cabecalho padrao
  	 */
  	$sStr  = "\n".db_formatar($this->oCorrente->k12_data,'d').str_pad($this->oInstit->nomeinst,33,' ',STR_PAD_BOTH);
    $sStr .= "\n<b>Conta:</b> {$this->oCorrente->k12_conta} CNPJ: ".db_formatar($this->oInstit->cgc,'cnpj')." <b>Term:</b>".$this->oCorrente->k12_id;
    $sStr .= "\n".str_pad("",48,'=',STR_PAD_BOTH);
    /**
     * Corpo da autenticacao
     */
    $sStr .= $sStringCorpo;
    
    /**
     * Rodape da atutenticacao
     */    
    $sSqlDepartamento = "select * from db_depart where coddepto = ".db_getsession('DB_coddepto');
    $rsDepartamento   = db_query($sSqlDepartamento,0);
    $oDepartamento    = db_utils::fieldsMemory($rsDepartamento,0);
        
    $sSqlUsuario = "select * from db_usuarios where id_usuario = ".db_getsession('DB_id_usuario');
    $rsUsuario   = db_query($sSqlUsuario);
    $oUsuario    = db_utils::fieldsMemory($rsUsuario,0);
    
    
    $sSqlNumpref = " select k03_msgautent 
                       from numpref 
                      where k03_anousu = ".date("Y",db_getsession('DB_datausu'))." 
                        and k03_instit = ".db_getsession('DB_instit');
    
    
    $rsNumpref   = db_query($sSqlNumpref);
    $sMsgAutent  = db_utils::fieldsmemory($rsNumpref,0)->k03_msgautent;
    
    $sStr .= "\n".str_pad("",48,'=',STR_PAD_BOTH);
		$sStr .= "\n<b>".str_pad("Login:</b>",14,' ',STR_PAD_RIGHT)."</b>{$oUsuario->id_usuario} - ".substr($oUsuario->nome,0,32);
		$sStr .= "\n<b>".str_pad("Departamento:",14,' ',STR_PAD_RIGHT)."</b>{$oDepartamento->coddepto} - $oDepartamento->descrdepto";
		$sStr .= "\n<b>".str_pad("IP Terminal:",14,' ',STR_PAD_RIGHT)."</b>{$this->sIp}";
		$sStr .= "\n<b>".str_pad("Data:",14,' ',STR_PAD_RIGHT)."</b>".trim(db_formatar($this->sData,'d'))."<b>".str_pad("Hora:"."</b> ".trim(db_hora()),28,' ',STR_PAD_LEFT);
		
		if (trim($sMsgAutent) != '') {
			$sStr .= "\n\n<b>".$sMsgAutent;
		}
    
    $sStr .= "\n";
		
		
		$this->oImpressora->escreverTexto($sStr);
  	
  }
  
  function isEstorno() {
    return $this->lEstorno;
  }

  function getValorTotal() {
    return $this->nValorTotal;
  }
  
      
}