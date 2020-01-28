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
include("classes/db_orcorgao_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clorcorgao = new cl_orcorgao;
$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){
	$lerro = false;
  db_inicio_transacao();
  $db_opcao = 3;
  $sCampos = " max(o40_anousu) as maxo40anousu ";
  $sWhere = " o40_orgao = $o40_orgao ";
  //echo $clorcorgao->sql_query_file(null,null,$sCampos,null,$sWhere)."<br>";
  $rsOrgaoMaxAno = $clorcorgao->sql_record($clorcorgao->sql_query_file(null,null,$sCampos,null,$sWhere));
  if($clorcorgao->numrows > 0){
  	db_fieldsmemory($rsOrgaoMaxAno,0);
  	$aAnousu = "(";
  	$anousu_atual = db_getsession('DB_anousu');
  	$virgula = "";
  	for($iInd = $anousu_atual; $iInd <= $maxo40anousu; $iInd++){
  		$aAnousu .= $virgula.$iInd;
  		$virgula = ",";
  	}
  	$aAnousu .= ")";
  	$sSqlOrgaoUnidade = "select * from orcunidade 
  													where o41_anousu in $aAnousu and o41_orgao = $o40_orgao ";
 		//echo ($sSqlOrgaoUnidade)."<br>";
 		$mensagem  = "Usuário:\\n\\nFalha ao excluir orgão !\\n\\n.";
 		$rsSqlOrgaoUnidade = pg_query($sSqlOrgaoUnidade);
 		if (pg_num_rows($rsSqlOrgaoUnidade) > 0) {
 			$lerro = true;
 			$rsSqlOrgao = $clorcorgao->sql_record($clorcorgao->sql_query_file(null,null,"o40_descr",null,"o40_orgao = $o40_orgao"));
 			if($clorcorgao->numrows > 0){
 				db_fieldsmemory($rsSqlOrgao,0);
 			}
 			if(!isset($o40_descr)) {
 				$o40_descr = ""; 				
 			}
 			$mensagem  = "Usuário:\\n\\nOrgão $o40_descr possui unidades vinculadas.";
 			$mensagem .= "\\nAntes de excluir esse orgão, exclua as unidades correspondentes ao orgão";
 			$mensagem .= "\\n\\n";
 		}
 		
 		if (!$lerro)	{
 			$sSqlPpaDotacao  = "select * from ppadotacao where o08_orgao = $o40_orgao and  o08_ano in $aAnousu ";
 			//die($sSqlPpaDotacao);
 			$rsSqlPpaDotacao = pg_query($sSqlPpaDotacao);
 			if (pg_num_rows($rsSqlPpaDotacao) > 0) {
 				$lerro = true;
 				$mensagem  = "Usuário:\\n\\n";
 				$mensagem .= "Orgão encontra-se em estimativas do ppa";
 				$mensagem .= "\\n\\n";
 			}
 		}
 		
 		if (!$lerro) {
 			$sWhereExcluir = " o40_orgao = $o40_orgao and o40_anousu in $aAnousu ";
 			$clorcorgao->excluir(null,null,$sWhereExcluir);
 			if($clorcorgao->erro_status=="0"){
 				$lerro = true;
 			}
 		}
 		
  }
  
  
  //$clorcorgao->excluir($o40_anousu,$o40_orgao);
  db_fim_transacao($lerro);
  //die();
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clorcorgao->sql_record($clorcorgao->sql_query($chavepesquisa,$chavepesquisa1)); 
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmorcorgao.php");
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
  if($clorcorgao->erro_status=="0"){
    $clorcorgao->erro(true,false);
  }else{
    $clorcorgao->erro(true,true);
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>