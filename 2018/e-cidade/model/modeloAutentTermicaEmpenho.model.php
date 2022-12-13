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

require_once 'model/modeloAutentTermicaBasica.php';

class modeloAutentTermicaEmpenho extends modeloAutentTermicaBasica {
  
	private $sBuffer     = '';
	
  // private $oImpressora = null;
  
  /**
   * 
   */
  function __construct($sIp,$sPorta,$iId,$sData,$iAutent) {
    parent::__construct($sIp,$sPorta,$iId,$sData,$iAutent);
  }
  
  function imprimir() {
  	
  	$iCodOrdem = $this->getOp();
  	
  	$sSqlEmpenho = " select * ";
  	$sSqlEmpenho .= "  from pagordem ";
  	$sSqlEmpenho .= "       inner join empempenho on empempenho.e60_numemp = pagordem.e50_numemp ";
  	$sSqlEmpenho .= "       inner join cgm        on cgm.z01_numcgm = empempenho.e60_numcgm ";  	
  	$sSqlEmpenho .= " where e50_codord = {$iCodOrdem}";
  	$rsEmpenho   = db_query($sSqlEmpenho);
    if ($rsEmpenho == false || pg_num_rows($rsEmpenho) == 0) {
      throw new Exception("Empenho nao encontrado op : {$iCodOrdem}");
    }
  	$oEmpenho    = db_utils::fieldsMemory($rsEmpenho,0);
  	
  	$sSqlContas  = "select * ";
  	$sSqlContas .= "  from corrente ";
  	$sSqlContas .= "       inner join conplanoreduz on conplanoreduz.c61_reduz = corrente.k12_conta   ";
  	$sSqlContas .= "       inner join conplano      on conplano.c60_codcon = conplanoreduz.c61_codcon ";
    $sSqlContas .= "                               and conplano.c60_anousu = conplanoreduz.c61_anousu ";
    $sSqlContas .= "       left  join conplanoconta on conplanoconta.c63_codcon = conplanoreduz.c61_codcon ";
    $sSqlContas .= "                               and conplanoconta.c63_anousu = conplanoreduz.c61_anousu ";
    $sSqlContas .= "       left  join db_bancos     on db_bancos.db90_codban    = conplanoconta.c63_banco  ";
    $sSqlContas .= " where conplano.c60_anousu = ".db_getsession('DB_anousu');
  	$sSqlContas .= "   and corrente.k12_id     = {$this->iId}";
  	$sSqlContas .= "   and corrente.k12_data   = '{$this->sData}'";
  	$sSqlContas .= "   and corrente.k12_autent = {$this->iAutent}";
  	$rsContas    = db_query($sSqlContas);
  	$oConta      = db_utils::fieldsMemory($rsContas,0);
    if ($rsContas == false || pg_num_rows($rsContas) == 0) {
      throw new Exception("Dados da conta nao encontrados. Term:{$this->iId} Data:{$this->sData} Autent:{$this->iAutent}");
    }
    
  	$this->sBuffer .= "\n";
  	$this->sBuffer .= "\n<b>".str_pad("Tipo de Documento",48," ",STR_PAD_BOTH)."</b>" ;
  	$this->sBuffer .= "\n<b>".str_pad("COMPROVANTE DE",48," ",STR_PAD_BOTH)."</b>" ;
  	$this->sBuffer .= "\n<b>".str_pad("PAGAMENTO DE EMPENHO",48," ",STR_PAD_BOTH)."</b>" ;
  	$this->sBuffer .= "\n";
    $this->sBuffer .= "\n<b>".str_pad("Empenho :",20," ",STR_PAD_RIGHT)."</b>".str_pad("{$oEmpenho->e60_codemp}/{$oEmpenho->e60_anousu}",28," ",STR_PAD_LEFT);
    $this->sBuffer .= "\n<b>".str_pad("CGM :",10," ",STR_PAD_RIGHT)."</b>".str_pad("{$oEmpenho->z01_numcgm} - {$oEmpenho->z01_nome}",38," ",STR_PAD_LEFT);
    $this->sBuffer .= "\n<b>".str_pad("Ordem Pagamento :",20," ",STR_PAD_RIGHT)."</b>".str_pad("{$oEmpenho->e50_codord}",28," ",STR_PAD_LEFT);
    $this->sBuffer .= "\n<b>".str_pad("Conta :",20," ",STR_PAD_RIGHT)."</b>".str_pad("{$oConta->k12_conta}-{$oConta->c60_descr}",28," ",STR_PAD_LEFT);
    
    if ($oConta->c60_codsis != '5') {
      $this->sBuffer .= "\n<b>".str_pad("Banco :",7," ",STR_PAD_RIGHT)."</b>".str_pad("{$oConta->c63_banco} - {$oConta->db90_descr}",25," ",STR_PAD_BOTH).
                          "<b>".str_pad("CC :",4," ",STR_PAD_RIGHT)."</b>".str_pad("{$oConta->c63_conta}",12," ",STR_PAD_LEFT);
    }
    if ($this->isEstorno()){
      $sTipoAutent = 'Estorno';
    }else{
    	$sTipoAutent = 'Pagamento';
    }
    $this->sBuffer .= "\n<b>".str_pad("Tipo Autent. :",15," ",STR_PAD_RIGHT)."</b>".str_pad("{$sTipoAutent}",28," ",STR_PAD_BOTH);
    $this->sBuffer .= "\n<b>".str_pad("Vlr. Total :",20," ",STR_PAD_RIGHT)."</b>".str_pad(db_formatar($oConta->k12_valor,'f'),28," ",STR_PAD_LEFT);
  	
  	$this->oImpressora->inicializa();
    $this->oImpressora->setLarguraPadrao();
  	parent::imprimir($this->sBuffer);  	
 	 	$this->oImpressora->cortarPapel();
    $this->oImpressora->finaliza();
    $this->oImpressora->rodarComandos();
    
  }
  
  function getOp(){
  	
  	$sSqlOp  = "select k12_codord ";
  	$sSqlOp .= "  from coremp ";
  	$sSqlOp .= " where k12_id     = {$this->iId} ";
  	$sSqlOp .= "   and k12_data   = '{$this->sData}' ";
  	$sSqlOp .= "   and k12_autent = {$this->iAutent}";
  	$rsOp    = db_query($sSqlOp);
  	$oOp     = db_utils::fieldsMemory($rsOp,0);
  	if ($rsOp === false || pg_num_rows($rsOp) == 0) {
  		throw new Exception("Ordem de pagamento nao encontrada para autenticacao. Term:{$this->iId} Data:{$this->sData} Autent:{$this->iAutent}");
  	}
  	return $oOp->k12_codord;
  }
  
}

?>