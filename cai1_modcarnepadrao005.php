<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_db_config_classe.php");
require_once("classes/db_modcarnepadrao_classe.php");
require_once("classes/db_modcarnepadraotipo_classe.php");
require_once("classes/db_modcarnepadraolayouttxt_classe.php");
require_once("classes/db_modcarnepadraocadmodcarne_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);	

$cldb_config				         = new cl_db_config;
$clmodcarnepadrao 			     = new cl_modcarnepadrao;
$clmodcarnepadraolayouttxt   = new cl_modcarnepadraolayouttxt;
$clmodcarnepadraocadmodcarne = new cl_modcarnepadraocadmodcarne;

$db_opcao = 22;
$db_botao = false;

if (isset($oPost->alterar)) {
	
  $lSqlErro = false;
  
  db_inicio_transacao();
  
  $rsModCarnePadrao = $clmodcarnepadrao->sql_record($clmodcarnepadrao->sql_query($oPost->k48_sequencial));
  $iNroLinhasRegra  = $clmodcarnepadrao->numrows;
  
  $aListaTipo = array();  
  $aListaIp   = array();
    
  for ($i=0; $i < $iNroLinhasRegra; $i++) {
  	
    $oModCarnePadrao  = db_utils::fieldsMemory($rsModCarnePadrao,$i);
    
    if (trim($oModCarnePadrao->k49_tipo)) {  
      $aListaTipo[] = $oModCarnePadrao->k49_tipo;  
    }
    if (trim($oModCarnePadrao->k36_ip)) {    
      $aListaIp[]   = $oModCarnePadrao->k36_ip;  
    }
    
  }
  
  $sDataInicio        = implode("-",array_reverse(explode("/",$oPost->k48_dataini)));
  $sDataFim           = implode("-",array_reverse(explode("/",$oPost->k48_datafim)));
  
  $sWhereValidaRegra  = " 	  k48_instit	  = ".db_getsession('DB_instit');
  $sWhereValidaRegra .= " and k48_cadtipomod  = {$oPost->k48_cadtipomod}                   ";
  $sWhereValidaRegra .= " and k48_sequencial != {$oPost->k48_sequencial}                   ";
  $sWhereValidaRegra .= " and k48_sequencial != {$oPost->k48_sequencial}                   ";
  $sWhereValidaRegra .= " and (                                                            ";
  $sWhereValidaRegra .= "      ({$oPost->k48_parcini} between k48_parcini and k48_parcfim) ";
  $sWhereValidaRegra .= "       and                                                         ";
  $sWhereValidaRegra .= "      ({$oPost->k48_parcfim} between k48_parcini and k48_parcfim) ";
  $sWhereValidaRegra .= "     )                                                            ";
  $sWhereValidaRegra .= " and ('{$sDataInicio}'       between k48_dataini and k48_datafim) ";
  $sWhereValidaRegra .= " and ('{$sDataFim}'          between k48_dataini and k48_datafim) ";
  $sWhereValidaRegra .= " and k48_sequencial != {$oPost->k48_sequencial}                   ";
  

  
  if (count($aListaTipo) != 0 ) {
    
    $sListaTipo         = implode(",",array_unique($aListaTipo));
   	$sWhereValidaRegra .= " and ( k49_modcarnepadrao is null			     ";
    $sWhereValidaRegra .= "       or ( k49_modcarnepadrao is not null and    ";
    $sWhereValidaRegra .= "		  	   k49_tipo in ({$sListaTipo})           ";
    $sWhereValidaRegra .= "		     ) )                                     ";

  } else {
    $sWhereValidaRegra .= " and k49_modcarnepadrao is null			 	     ";
  }
  if (count($aListaIp) != 0 ) {
    
    $sListaIp           = "'".implode(",",array_unique($aListaIp))."'";
   	$sWhereValidaRegra .= " and ( k36_modcarnepadrao is null			      ";
    $sWhereValidaRegra .= "      or ( k36_modcarnepadrao is not null and      ";
    $sWhereValidaRegra .= "		      k36_ip in ({$sListaIp})                 ";  
    $sWhereValidaRegra .= "		    )                                         ";   
    $sWhereValidaRegra .= "		)                                             ";  
    
  } else {
    $sWhereValidaRegra .= " and k36_modcarnepadrao is null 		              ";  	
  }
  
//  $rsValidaRegra = $clmodcarnepadrao->sql_record($clmodcarnepadrao->sql_query(null,"k48_sequencial",null,$sWhereValidaRegra));
//   if ( $clmodcarnepadrao->numrows == 0 ) {
  $rsModCarnePadrao = $clmodcarnepadrao->sql_record($clmodcarnepadrao->sql_query_func($oPost->k48_sequencial,"*"));
  $oModCarnePadrao  = db_utils::fieldsMemory($rsModCarnePadrao,0);
  $clmodcarnepadraocadmodcarne->m01_sequencial     = $oModCarnePadrao->m01_sequencial;
  $clmodcarnepadraocadmodcarne->m01_cadmodcarne    = $oPost->m01_cadmodcarne;
  $clmodcarnepadraocadmodcarne->m01_modcarnepadrao = $oPost->k48_sequencial;
  
  $clmodcarnepadraolayouttxt->m02_sequencial       = $oModCarnePadrao->m02_sequencial;
  $clmodcarnepadraolayouttxt->m02_db_layouttxt     = $oModCarnePadrao->m02_db_layouttxt;
  $clmodcarnepadraolayouttxt->m02_modcarnepadrao   = $oPost->k48_sequencial;
  
  
  if ($oPost->selPdfTxt == "pdf") {
	  if (!empty($oModCarnePadrao->m02_sequencial)) {
	    $clmodcarnepadraolayouttxt->excluir($oModCarnePadrao->m02_sequencial);
	    $clmodcarnepadraocadmodcarne->incluir(null);	
    } else {
    $clmodcarnepadraocadmodcarne->alterar($oModCarnePadrao->m01_sequencial);
 	  }

	  if ($clmodcarnepadraocadmodcarne->erro_status == 0 ) {
	    $lSqlErro = true;
    }
  
    $erro_msg = $clmodcarnepadraocadmodcarne->erro_msg;

  } else if ($oPost->selPdfTxt == "txt") {
  	
	  if (!empty($oModCarnePadrao->m01_sequencial)) {
	    $clmodcarnepadraocadmodcarne->excluir($oModCarnePadrao->m01_sequencial);	
	    $clmodcarnepadraolayouttxt->incluir(null);
	  } else {
	    $clmodcarnepadraolayouttxt->alterar($oModCarnePadrao->m02_sequencial);	
	  }

    if ($clmodcarnepadraolayouttxt->erro_status == 0 ) {
	    $lSqlErro = true;  	
    }
    
    $erro_msg = $clmodcarnepadraolayouttxt->erro_msg;
      	
  }

  
  if (!$lSqlErro) {
  
    $clmodcarnepadrao->k48_sequencial  = $oPost->k48_sequencial;
    $clmodcarnepadrao->k48_cadconvenio = $oPost->k48_cadconvenio;
    $clmodcarnepadrao->k48_cadtipomod  = $oPost->k48_cadtipomod;
    $clmodcarnepadrao->k48_instit      = $oPost->k48_instit;
    $clmodcarnepadrao->k48_dataini     = $oPost->k48_dataini_ano."-".$oPost->k48_dataini_mes."-".$oPost->k48_dataini_dia;
    $clmodcarnepadrao->k48_datafim     = $oPost->k48_datafim_ano."-".$oPost->k48_datafim_mes."-".$oPost->k48_datafim_dia;
    $clmodcarnepadrao->k48_parcini     = $oPost->k48_parcini;
    $clmodcarnepadrao->k48_parcfim     = $oPost->k48_parcfim;
    
    $clmodcarnepadrao->alterar($oPost->k48_sequencial);
  
    if ($clmodcarnepadrao->erro_status == 0) {
      $lSqlErro = true;
    }
    $erro_msg = $clmodcarnepadrao->erro_msg;
  }
    
//   } else {
//   	$oValidaRegra = db_utils::fieldsMemory($rsValidaRegra,0);
//   	$lSqlErro = true;
//   	$erro_msg = "Já existe regra nº {$oValidaRegra->k48_sequencial} com os mesmo parâmetros configurados! Verifique.";
//   }
  
  db_fim_transacao($lSqlErro);
  
  $db_opcao = 2;
  $db_botao = true;
  
}else if (isset($oGet->chavepesquisa)) {
	
   $db_opcao = 2;
   $db_botao = true;
   $result   = $clmodcarnepadrao->sql_record($clmodcarnepadrao->sql_query_func($oGet->chavepesquisa));
   db_fieldsmemory($result,0);
   
   if (isset($m02_sequencial) && trim($m02_sequencial) != "") {
   	  $selPdfTxt = "txt";
   } 
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" align="center" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmmodcarnepadrao.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if (isset($oPost->alterar) ) {
  if ($lSqlErro) {
    db_msgbox($erro_msg);
    if ($clmodcarnepadrao->erro_campo!="") {
      echo "<script> document.form1.".$clmodcarnepadrao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmodcarnepadrao->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
  }
}
if (isset($oGet->chavepesquisa) ) {
 echo "
  <script>
      function js_db_libera() {
         parent.document.formaba.modcarnepadraotipo.disabled = false;
         parent.document.formaba.modcarneexcessao.disabled   = false;
         top.corpo.iframe_modcarnepadraotipo.location.href='cai1_modcarnepadraotipo001.php?k49_modcarnepadrao=".@$k48_sequencial."';
         top.corpo.iframe_modcarneexcessao.location.href='cai1_modcarneexcessao001.php?k36_modcarnepadrao=".@$k48_sequencial."';
     ";
         if (isset($oGet->liberaaba)) {
           echo "  parent.mo_camada('modcarnepadraotipo');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if ($db_opcao==22||$db_opcao==33) {
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>