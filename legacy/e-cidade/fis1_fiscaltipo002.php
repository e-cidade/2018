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
include("classes/db_fiscaltipo_classe.php");
include("classes/db_fiscalandam_classe.php");
include("classes/db_fandam_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clfiscaltipo = new cl_fiscaltipo;
$db_opcao = 22;
$db_botao = false;
global $y39_codandam;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  db_inicio_transacao();
  $db_opcao = 2;
  $clfiscaltipo->excluir($y31_codnoti,$y31_codtipo_old);
  $clfiscaltipo->incluir($y31_codnoti,$y31_codtipo);
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clfiscaltipo->sql_record($clfiscaltipo->sql_query($chavepesquisa,$chavepesquisa1)); 
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmfiscaltipo.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
js_setatabulacao();
</script>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  if($clfiscaltipo->erro_status=="0"){
    $clfiscaltipo->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    echo "<script> document.form1.db_opcao.value='Incluir';</script>  ";
    if($clfiscaltipo->erro_campo!=""){
      echo "<script> document.form1.".$clfiscaltipo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clfiscaltipo->erro_campo.".focus();</script>";
    }else{
      echo "<script>parent.iframe_fiscaltipo.location.href='fis1_fiscaltipo001.php?y31_codnoti=".$y31_codnoti."&abas=1';</script>\n";
      echo "<script>parent.iframe_fiscais.location.href='fis1_fiscalusuario001.php?y38_codnoti=".$y31_codnoti."&y39_codandam=$y39_codandam&abas=1';</script>\n";
      echo "<script>parent.iframe_artigos.location.href='fis1_fiscarquivos001.php?y26_codnoti=".$y31_codnoti."&y39_codandam=$y39_codandam';</script>\n";
      echo "<script>parent.iframe_venc.location.href='fis1_vencimento001.php?y30_codnoti=".$y31_codnoti."&y39_codandam=$y39_codandam';</script>\n";
    }
  }else{
    $clfiscaltipo->erro(true,false);
    echo "<script>parent.iframe_fiscaltipo.location.href='fis1_fiscaltipo001.php?y31_codnoti=".$y31_codnoti."&abas=1';</script>\n";
    echo "<script>parent.iframe_receitas.location.href='fis1_fiscalrec001.php?y42_codnoti=".$y31_codnoti."&abas=1';</script>\n";
    echo "<script>parent.iframe_fiscais.location.href='fis1_fiscalusuario001.php?y38_codnoti=".$y31_codnoti."&y39_codandam=$y39_codandam&abas=1';</script>\n";
    echo "<script>parent.iframe_artigos.location.href='fis1_fiscarquivos001.php?y26_codnoti=".$y31_codnoti."&y39_codandam=$y39_codandam';</script>\n";
    echo "<script>parent.iframe_venc.location.href='fis1_vencimento001.php?y30_codnoti=".$y31_codnoti."&y39_codandam=$y39_codandam';</script>\n";
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>