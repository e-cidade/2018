<?
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

$sqlerro = false;
db_inicio_transacao();

if(!isset($r11_anousu) || (isset($r11_anousu) && trim($r11_anousu) == "")){
  $r11_anousu = db_anofolha();
}
if(!isset($r11_mesusu) || (isset($r11_mesusu) && trim($r11_mesusu) == "")){
  $r11_mesusu = db_mesfolha();
}

if(!isset($r11_instit) || (isset($r11_instit) && trim($r11_instit) == "")){
  $r11_instit = db_getsession("DB_instit");
}
$clcfpess->r11_anousu       = $r11_anousu;      
$clcfpess->r11_mesusu       = $r11_mesusu;      
$clcfpess->r11_instit       = $r11_instit;      

 /**
 * Verificamos se o histуrico para a geraзгo dos Slips existe na tabela conhist 
 */
 if (isset($r11_histslip)) {
   $rsConhist = db_query("select * from conhist where c50_codhist = {$r11_histslip}");
   if (pg_numrows($rsConhist) == 0){
 	   $erro_msg = "Histуrico de Slip informado nгo cadastrado (conhist).";
 	   $sqlerro = true;
   }
 }
 
$result = $clcfpess->sql_record($clcfpess->sql_query($r11_anousu,$r11_mesusu,$r11_instit));
if($result==false || $clcfpess->numrows==0){
  $clcfpess->incluir($r11_anousu,$r11_mesusu,$r11_instit);
}else{
  $clcfpess->alterar($r11_anousu,$r11_mesusu,$r11_instit);
}
if ($clcfpess->erro_status == "0") {
	$sqlerro = true;
}

if ($sqlerro ==  true) {
	 $clcfpess->erro_msg = "Alteraзгo nгo Efetuada!\\n\\n-".$erro_msg;
}

db_fim_transacao($sqlerro);
//exit;
?>