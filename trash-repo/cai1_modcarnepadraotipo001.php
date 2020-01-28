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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("classes/db_modcarnepadraotipo_classe.php");
include("classes/db_modcarnepadrao_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmodcarnepadraotipo = new cl_modcarnepadraotipo;
$clmodcarnepadrao = new cl_modcarnepadrao;
$db_opcao = 22;
$db_botao = false;

if(isset($alterar) || isset($incluir) || isset($excluir)){
  
  
  $rsModCarnePadrao = $clmodcarnepadrao->sql_record($clmodcarnepadrao->sql_query($k49_modcarnepadrao));
  $iNroLinhasRegra  = $clmodcarnepadrao->numrows;
  $oModCarnePadrao  = db_utils::fieldsMemory($rsModCarnePadrao,0);
  
  $sWhereValidaRegra  = " 	  k48_instit	 	  = ".db_getsession('DB_instit')		  	   ;
  $sWhereValidaRegra .= " and k48_cadtipomod 	  = {$oModCarnePadrao->k48_cadtipomod} 		  ";
  $sWhereValidaRegra .= " and k48_sequencial     != {$k49_modcarnepadrao}	 		  		  ";
  
  if (isset($excluir)){
    $rsModCarnePadraoExc = $clmodcarnepadrao->sql_record($clmodcarnepadrao->sql_query($k49_modcarnepadrao,"*",null,"k49_tipo is not null and k49_tipo != '{$k49_tipo}'"));
    $iNroLinhasRegraExc  = $clmodcarnepadrao->numrows;
	if ($iNroLinhasRegraExc > 0) {
      $aListaTipo = array();
      for ($i=0; $i < $iNroLinhasRegraExc; $i++) {
        $oModCarnePadraoExc  = db_utils::fieldsMemory($rsModCarnePadraoExc,$i);
        if ($oModCarnePadraoExc->k49_tipo != $k49_tipo) {
          $aListaTipo[] = $oModCarnePadraoExc->k49_tipo;
        }
      }
  	  $sWhereValidaRegra .= " and k49_tipo in ('".implode("','",$aListaTipo)."')	 		  		  ";
	}
	$sWhereValidaRegra .= " and k49_tipo is null									 		  		  ";
  } else {
    $sWhereValidaRegra .= " and k49_tipo 			  = '{$k49_tipo}'			    		  		  ";
  }

  if (trim($oModCarnePadrao->k36_ip) != ""){
  	$aListaIp = array();
  	for ($i=0; $i < $iNroLinhasRegra; $i++) {
  	  $oListaIp = db_utils::fieldsMemory($rsModCarnePadrao,$i);
  	  $aListaIp[] = $oListaIp->k36_ip;  
  	}
    $sWhereValidaRegra .= " and k36_modcarnepadrao is not null 					  	  	  ";
    $sWhereValidaRegra .= "	and k36_ip in ('".implode("','",$aListaIp)."')				  ";
  } else {
    $sWhereValidaRegra .= " and k36_modcarnepadrao is null			 		 		  	  ";
  }
  
  $rsValidaRegra = $clmodcarnepadrao->sql_record($clmodcarnepadrao->sql_query(null,"k48_sequencial",null,$sWhereValidaRegra));
  if ($clmodcarnepadrao->numrows > 0 ) {
  	$oValidaRegra = db_utils::fieldsMemory($rsValidaRegra,0);
    $sqlerro  = true;
    $erro_msg = "Já existe regra nº{$oValidaRegra->k48_sequencial} com os mesmo parâmetros configurados! Verifique.";
  } else {
    $sqlerro  = false;  	
  }
  
}

if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    //echo "aqui"; die();
    $clmodcarnepadraotipo->incluir(null);
    $erro_msg = $clmodcarnepadraotipo->erro_msg;
    if($clmodcarnepadraotipo->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clmodcarnepadraotipo->alterar($k49_sequencial);
    $erro_msg = $clmodcarnepadraotipo->erro_msg;
    if($clmodcarnepadraotipo->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clmodcarnepadraotipo->excluir($k49_sequencial);
    $erro_msg = $clmodcarnepadraotipo->erro_msg;
    if($clmodcarnepadraotipo->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $clmodcarnepadraotipo->sql_record($clmodcarnepadraotipo->sql_query($k49_sequencial));
   if($result!=false && $clmodcarnepadraotipo->numrows>0){
     db_fieldsmemory($result,0);
   }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" align="center" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmmodcarnepadraotipo.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clmodcarnepadraotipo->erro_campo!=""){
        echo "<script> document.form1.".$clmodcarnepadraotipo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clmodcarnepadraotipo->erro_campo.".focus();</script>";
    }
}
?>