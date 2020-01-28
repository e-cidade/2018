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

class modeloAutentTermicaSlip extends modeloAutentTermicaBasica {
  
	private $sBuffer     = '';
	
  // private $oImpressora = null;
  
  /**
   * 
   */
  function __construct($sIp,$sPorta,$iId,$sData,$iAutent) {
    parent::__construct($sIp,$sPorta,$iId,$sData,$iAutent);
  }
  
  function imprimir() {
  	
  	$iCodSlip = $this->getSlip();
  	
  	$sSqlSlip  = "select slip.*,cgm.*,contacred.c60_descr as conta_credito,contadeb.c60_descr as conta_debito ";
  	$sSqlSlip .= "  from slip ";
  	$sSqlSlip .= "       inner join slipnum                    on slipnum.k17_codigo   = slip.k17_codigo ";
  	$sSqlSlip .= "       inner join cgm                        on cgm.z01_numcgm       = slipnum.k17_numcgm ";
    $sSqlSlip .= "       inner join conplanoreduz as reduzdeb  on reduzdeb.c61_reduz   = slip.k17_debito";
    $sSqlSlip .= "                                            and reduzdeb.c61_anousu  = ".db_getsession('DB_anousu');
    $sSqlSlip .= "       inner join conplano as contadeb       on contadeb.c60_codcon  = reduzdeb.c61_codcon";
    $sSqlSlip .= "                                            and contadeb.c60_anousu  = reduzdeb.c61_anousu";
  	$sSqlSlip .= "       inner join conplanoreduz as reduzcred on reduzcred.c61_reduz  = slip.k17_credito";
    $sSqlSlip .= "                                            and reduzcred.c61_anousu = ".db_getsession('DB_anousu');
    $sSqlSlip .= "       inner join conplano as contacred      on contacred.c60_codcon = reduzcred.c61_codcon";
    $sSqlSlip .= "                                            and contacred.c60_anousu = reduzcred.c61_anousu";
    $sSqlSlip .= " where slip.k17_codigo = {$iCodSlip} ";
  	$rsSlip    = db_query($sSqlSlip);
  	$oSlip     = db_utils::fieldsMemory($rsSlip,0);
  	
  	$this->sBuffer .= "\n";
  	$this->sBuffer .= "\n<b>".str_pad("Tipo de Documento",48," ",STR_PAD_BOTH)."</b>" ;
  	$this->sBuffer .= "\n<b>".str_pad("COMPROVANTE DE",48," ",STR_PAD_BOTH)."</b>" ;
  	$this->sBuffer .= "\n<b>".str_pad("TRANSACAO DE SLIP",48," ",STR_PAD_BOTH)."</b>" ;
  	$this->sBuffer .= "\n";
    $this->sBuffer .= "\n<b>".str_pad("Slip : ",20," ",STR_PAD_RIGHT)."</b>".str_pad("{$iCodSlip}",28," ",STR_PAD_LEFT);
    $this->sBuffer .= "\n<b>".str_pad("CGM : ",10," ",STR_PAD_RIGHT)."</b>".str_pad("{$oSlip->z01_numcgm} - {$oSlip->z01_nome}",38," ",STR_PAD_LEFT);
    $this->sBuffer .= "\n<b>".str_pad("Debito:",10," ",STR_PAD_RIGHT)."</b>".str_pad("{$oSlip->k17_debito}-{$oSlip->conta_debito}",38," ",STR_PAD_LEFT);
    $this->sBuffer .= "\n<b>".str_pad("Credito:",10," ",STR_PAD_RIGHT)."</b>".str_pad("{$oSlip->k17_credito}-{$oSlip->conta_credito}",38," ",STR_PAD_LEFT);
    if ($this->isEstorno()){
      $sTipoAutent = 'Estorno';
    }else{
      $sTipoAutent = 'Pagamento';
    }
    $this->sBuffer .= "\n<b>".str_pad("Tipo Autent. : ",15," ",STR_PAD_RIGHT)."</b>".str_pad("{$sTipoAutent}",28," ",STR_PAD_BOTH);
    $this->sBuffer .= "\n<b>".str_pad("Vlr. Total : ",20," ",STR_PAD_RIGHT)."</b>".str_pad(trim(db_formatar($this->getValorTotal(),'f')),28," ",STR_PAD_LEFT);
  	
  	$this->oImpressora->inicializa();
    $this->oImpressora->setLarguraPadrao();
  	parent::imprimir($this->sBuffer);  	
 	 	$this->oImpressora->cortarPapel();
    $this->oImpressora->finaliza();
    $this->oImpressora->rodarComandos();
    
  }
  
  function getSlip(){
    
    $sSqlSlip  = "select k12_codigo ";
    $sSqlSlip .= "  from corlanc ";
    $sSqlSlip .= " where k12_id = {$this->iId} ";
    $sSqlSlip .= "   and k12_data = '{$this->sData}' ";
    $sSqlSlip .= "   and k12_autent = {$this->iAutent}";
    $rsSlip    = db_query($sSqlSlip);
    $oSlip     = db_utils::fieldsMemory($rsSlip,0);
    if ($rsSlip === false || pg_num_rows($rsSlip) == 0) {
      throw new Exception("Codigo do slip nao encontrada para autenticacao. Term:{$this->iId} Data:{$this->sData} Autent:{$this->sAutent} {$sSqlSlip}");
    }
    return $oSlip->k12_codigo;
  }
  
}

?>