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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_orcunidade_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clorcunidade = new cl_orcunidade;
$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){
	$lerro = false;
  db_inicio_transacao();
  $db_opcao = 3;
  
  $sCampos = " max(o41_anousu) as maxo41anousu ";
  $sWhere = " o41_unidade = $o41_unidade and o41_orgao = $o41_orgao";
  $rsUnidadeMaxAno = $clorcunidade->sql_record($clorcunidade->sql_query_file(null,null,null,$sCampos,null,$sWhere));
  if ($clorcunidade->numrows > 0){
  	db_fieldsmemory($rsUnidadeMaxAno,0);
  	$aAnousu = "(";
  	$anousu_atual = db_getsession('DB_anousu');
  	$virgula = "";
  	for($iInd = $anousu_atual; $iInd <= $maxo41anousu; $iInd++){
  		$aAnousu .= $virgula.$iInd;
  		$virgula = ",";
  	}
  	$aAnousu .= ")";
  	
  	$sSqlUnidadePpa = "select * from  ppadotacao
  													where o08_unidade = $o41_unidade and o08_orgao = $o41_orgao and o08_ano in $aAnousu";
  	$rsSqlUnidadePpa = pg_query($sSqlUnidadePpa);
  	if(pg_num_rows($rsSqlUnidadePpa) > 0){
  		$lerro = true;
  		$mensagem = "Usuário:\\n\\nUnidade encontra-se em estimativas do ppa\\n\\n";
  	}
  	$sSqlUnidadeDotacao = "select * from  orcdotacao
  													where o58_unidade = $o41_unidade and o58_orgao = $o41_orgao and o58_anousu in $aAnousu";
  	$rsSqlUnidadeDotacao = pg_query($sSqlUnidadeDotacao);
  	if(pg_num_rows($rsSqlUnidadeDotacao) > 0){
  		$lerro = true;
  		$mensagem = "Usuário:\\n\\nUnidade encontra-se vinculada a Dotação\\n\\nNão excluído!" ;
  	}
  	
  }
  
  if (!$lerro) {
  	$sWhereExcluir = "o41_unidade = $o41_unidade and o41_orgao = $o41_orgao and o41_anousu in $aAnousu";
  	$clorcunidade->excluir(null,null,null,$sWhereExcluir);
  
  	if($clorcunidade->numrows == "0"){
  		$mensagem = $clorcunidade->erro_msg;
  		$lerro = true;
  	}
  }
  
  //$clorcunidade->excluir($o41_anousu,$o41_orgao,$o41_unidade);
  db_fim_transacao($lerro);
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clorcunidade->sql_record($clorcunidade->sql_query($chavepesquisa,$chavepesquisa1,$chavepesquisa2)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmorcunidade.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir) && $lerro == true){
	db_msgbox($mensagem);
}else if(isset($excluir)){
  if($clorcunidade->erro_status=="0"){
    $clorcunidade->erro(true,false);
  }else{
    $clorcunidade->erro(true,true);
  };
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>