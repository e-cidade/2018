<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_ruas_classe.php");
require_once("classes/db_ruascep_classe.php");
require_once("classes/db_cfiptu_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_ruastipo_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clruas            = new cl_ruas;
$clruascep         = new cl_ruascep;
$clcfiptu          = new cl_cfiptu;
$clruastipo        = new cl_ruastipo;
$oDaoRuasanterior  = new cl_ruas;
$oDaoRuashistorico = new cl_ruashistorico();
$db_opcao          = 22;
$db_botao          = false;
$db_codopcao       = 3;

//========================================================================================================================================================
/*
$j14_codigo2 = "";
$rsResult = $clcfiptu->sql_record($clcfiptu->sql_query_file (DB_getsession("DB_anousu"),"*",null,""));
if ($clcfiptu->numrows > 0){
	db_fieldsmemory($rsResult,0);
	if ($j18_logradauto == 'f'){
		$db_codopcao = 2;
		$mostrabotao = 't';
    }else{
    	$db_codopcao = 3;
    	$mostrabotao = 'f';
    }
}
*/
//================================================================================================================================================================

if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  
	db_inicio_transacao();
  $db_opcao = 2;
  
  /**
   * Salva os dados anteriores antes da alteração
   */
  $sSqlRuasAnterior = $oDaoRuasanterior->sql_query_file($j14_codigo);
  $rsRuasAnterior   = $oDaoRuasanterior->sql_record($sSqlRuasAnterior);
  
  if ($oDaoRuasanterior->numrows > 0) {
  	
  	$oRuaAnterior = db_utils::fieldsMemory($rsRuasAnterior, 0);
  	
  	if (levenshtein($oRuaAnterior->j14_nome, $j14_nome) > 3) {
	  	$oDaoRuashistorico->j136_ruas          = $oRuaAnterior->j14_codigo; 
	  	$oDaoRuashistorico->j136_ruastipo      = $oRuaAnterior->j14_tipo;
	  	$oDaoRuashistorico->j136_lei           = $oRuaAnterior->j14_lei;
	  	$oDaoRuashistorico->j136_datalei       = $oRuaAnterior->j14_dtlei;
	  	$oDaoRuashistorico->j136_nomeanterior  = $oRuaAnterior->j14_nome;
	  	$oDaoRuashistorico->j136_dataalteracao = date('Y-m-d', db_getsession('DB_datausu'));
	  	$oDaoRuashistorico->incluir(null);
	  	
	  	if ($oDaoRuashistorico->erro_status == '0') {
	  		$sqlerro  = true;
	  		$erro_msg = $oDaoRuashistorico->erro_msg;
	  	}
  	}
  	
  	 
  	
  }
  
  $clruas->alterar($j14_codigo);
  
  $result = $clruascep->sql_record($clruascep->sql_query($chavepesquisa));
   
  if($clruascep->numrows > 0){
    $clruascep->j29_cep = $j29_cep;
    $clruascep->j29_inicio="0";
    $clruascep->j29_codigo = $j14_codigo;
    $clruascep->alterar($j14_codigo,"0");
    if ($clruascep->erro_status==0){
      $erro_msg=$clruascep->erro_msg;
    }
  }else{
    if(isset($j29_cep) and $j29_cep <> ''){
      $clruascep->j29_cep=$j29_cep;
      $clruascep->j29_final='0';
      $clruascep->incluir($j14_codigo,'0');
      if ($clruascep->erro_status==0){
        $sqlerro=true;
        $erro_msg=$clruascep->erro_msg;
      }
    }
   }
     $db_botao = true;
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clruas->sql_record($clruas->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $result2 = $clruascep->sql_record($clruascep->sql_query($chavepesquisa)); 
   if($clruascep->numrows >0 ){
     db_fieldsmemory($result2,0);
   } 
   $db_botao = true;
}

    echo "<script>
	    parent.iframe_g2.location.href='cad1_aba2ruas002.php?chavepesquisa=".@$j14_codigo."';\n
        parent.iframe_g3.location.href='cad1_ruasbairroalt001.php?j16_lograd=".@$j14_codigo."';\n
	  </script>";
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmruas.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  if($clruas->erro_status=="0"){
    $clruas->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clruas->erro_campo!=""){
      echo "<script> document.form1.".$clruas->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clruas->erro_campo.".focus();</script>";
    }
  }else{
     $clruas->erro(true,false);
     echo "
     <script>
     parent.mo_camada('g3');
     </script>
     ";
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>