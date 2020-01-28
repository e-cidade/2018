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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("libs/db_utils.php");
require_once ("classes/db_bens_classe.php");
require_once ("model/impressao.model.php");

$clBens   = new cl_bens();
$erro_msg = "";

//VERIFICA AUTENTICADORA
$sSqlAutenticadora  = "select k11_ipimpcheque as ip,k11_portaimpcheque as porta ";
$sSqlAutenticadora .= "  from cfautent "; 
$sSqlAutenticadora .= "       inner join db_impressora on db64_sequencial =  k11_tipoimp"; 
$sSqlAutenticadora .= "       inner join db_tipoimpressora on db65_sequencial = db64_db_tipoimpressora ";
$sSqlAutenticadora .= " where db65_sequencial = 3 and k11_ipterm = '" . db_getsession("DB_ip") . "'";
//echo $sSqlAutenticadora;
$rsSqlAutenticadora = db_query($sSqlAutenticadora);
echo pg_last_error();
if(pg_num_rows($rsSqlAutenticadora)>0){
	$oAutenticadora = db_utils::fieldsMemory($rsSqlAutenticadora,0);
}else{
	db_msgbox("Cadastre o ip " . db_getsession("DB_ip") . " como um caixa.");
  die();
}
 
$sIp    = $oAutenticadora->ip;
$sPorta = $oAutenticadora->porta;

/**
 *  Detalhes de configuracao de pagina
 *
 *    300dpi - 1mm  = 12 pontos
 *    203dpi - 1 mm = 8 pontos
 *
 */

$iPontosPadrao = 12;

$iLargura      = (45 * $iPontosPadrao);  // 45 mm
$iAltura       = (20 * $iPontosPadrao);  // 20 mm
$iEspEtiquetas = (3  * $iPontosPadrao);  // 3 mm
$oImpressao = new impressao();
$oImpressao->setPorta($sPorta);
$oImpressao->setIp($sIp);

?>
<html>
	<head>
		<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Expires" CONTENT="0">
		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
		<script type="text/javascript" src="scripts/strings.js"></script>
		<link href="estilos.css" rel="stylesheet" type="text/css">
	</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="">
<form name="form2" action="">
<table width="790" border="0" align="center" cellspacing="0"
	bgcolor="#CCCCCC" style="padding-top: 20px;">
	<tr>
		<td>
			<?
			db_criatermometro('termometro','Concluído','blue',1);
			echo "<br><br>";
			$oGet 				= db_utils::postMemory($_GET);
      
			
			$sqlInstit = "select nomeinstabrev as nomeabrev,uf from db_config where codigo = ".db_getsession("DB_instit");
			$rsqlInstit = pg_query($sqlInstit);
			if(pg_num_rows($rsqlInstit) > 0){
				$oInstit = db_utils::fieldsMemory($rsqlInstit,0);
			}
			
			$sWhere = "";
			
			if(isset($oGet->t52_bem) && trim($oGet->t52_bem) !=""){ 
				$sWhere = " where t52_bem = ".$oGet->t52_bem;
			}else{
				$erro_msg = "usuário:\\n\\n Falha ao imprimir etiqueta do bem !\\n\\n";
			}
      if($erro_msg == ""){
			
				$sSqlBens  = "select t52_bem,t64_descr "; 
				$sSqlBens .= "  from bens ";
				$sSqlBens .= "       inner join clabens ";
				$sSqlBens .= "    on clabens.t64_codcla = bens.t52_codcla ";
				$sSqlBens .= $sWhere;
				//die ($sSqlBens);
				$rsBens = $clBens->sql_record($sSqlBens);
	
				if($clBens->erro_status == "0"){
					
					$erro_msg = "usuário:\\n\\nNenhum bem encontrado para o intervalo informado !\\n\\n";
					
				}else{
					
					$iNumRows = pg_num_rows($rsBens);
					for ( $i = 0; $i < $iNumRows; $i++  ) {
							
					  db_atutermometro($i, $iNumRows, 'termometro', 1, "Imprimindo etiquetas");
	          $oBem = db_utils::fieldsMemory($rsBens,$i);
	
	          $sNomeAbrev   = db_removeAcentuacao($oInstit->nomeabrev." - ".$oInstit->uf);
	          $sT64Descr    = db_removeAcentuacao(substr($oBem->t64_descr,0,23));
	          $sPatrimonio  = str_pad('PATRIMONIO : '.$oBem->t52_bem,23," ",STR_PAD_BOTH);
	                  
	         
	          $oImpressao->addComando('N');
	          $oImpressao->addComando('A480,5,0,3,1,1,N,"'.str_pad($sNomeAbrev,23,' ',STR_PAD_BOTH).'"');
	          $oImpressao->addComando('A480,30,0,3,1,1,N,"'.str_pad($sT64Descr,23,' ',STR_PAD_BOTH).'"');
	          $oImpressao->addComando('A480,60,0,3,1,1,N,"'.$sPatrimonio.'"');
	          $oImpressao->addComando('B480,80,0,9,3,3,41,B,"'.str_pad($oBem->t52_bem,7,'0',STR_PAD_LEFT).'"');
	          $oImpressao->addComando('P1');
	          $oImpressao->rodarComandos("\n");
	          $oImpressao->resetComandos();
	                           
					}  
				  
				} 
      }
			?>
		</td>
	</tr>
	<tr align="center">
		<td>
			<input type="button" value="Fechar" onclick="parent.db_iframe_imprime.hide();">
		</td>
	</tr>
</table>
</form>
</body>
</html>
<? 
  if($erro_msg != ""){
  	
  	db_msgbox($erro_msg);
  	
  }
  
//  function db_removeAcentuacao($sRemover){
//
//  	$var = $sRemover;
//		
//		$var = ereg_replace("[ÁÀÂÃ]","A",$var);
//		
//		$var = ereg_replace("[áàâãª]","a",$var);
//		
//		$var = ereg_replace("[ÉÈÊ]","E",$var);
//		
//		$var = ereg_replace("[éèê]","e",$var);
//		
//		$var = ereg_replace("[ÓÒÔÕ]","O",$var);
//		
//		$var = ereg_replace("[óòôõº]","o",$var);
//		
//		$var = ereg_replace("[ÚÙÛ]","U",$var);
//		
//		$var = ereg_replace("[úùû]","u",$var);
//		
//		$var = str_replace("Ç","C",$var);
//		
//		$var = str_replace("ç","c",$var);
//		
//		return $var;	
//		  
//  }
  
?>