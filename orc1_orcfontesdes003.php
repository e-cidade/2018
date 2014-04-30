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
include("classes/db_orcfontesdes_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_liborcamento.php");
include("classes/db_orcparametro_classe.php");
include("classes/db_orcfontes_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clorcfontesdes = new cl_orcfontesdes;
$clorcfontes = new cl_orcfontes;
$clorcparametro = new cl_orcparametro;
$clestrutura = new cl_estrutura;

$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $matriz03=split("#",$dados);
  $tamanho03=count($matriz03);
  for($i=0; $i<$tamanho03; $i++){
    $matriz04=split("-",$matriz03[$i]);
    $anousu=db_getsession('DB_anousu'); 
    
    $clorcfontesdes->o60_codfon = $matriz04[0];
    $clorcfontesdes->o60_anousu = $anousu;
    $clorcfontesdes->excluir($anousu,$matriz04[0]);
    //$clorcfontesdes->erro(true,false);
    if($clorcfontesdes->erro_status==0){
      $sqlerro=true;
    }
  } 
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clorcfontesdes->sql_record($clorcfontesdes->sql_query($chavepesquisa,$chavepesquisa1)); 
   db_fieldsmemory($result,0);
   
   $result = $clorcfontes->sql_record($clorcfontes->sql_query_file($o60_codfon,db_getsession("DB_anousu"),'o57_fonte as fontis')); 
   db_fieldsmemory($result,0);
   $o50_estrutreceita=db_formatar($fontis,'receita');
      
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
	include("forms/db_frmorcfontesdes.php");
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
if(isset($excluir)){
  if($clorcfontesdes->erro_status=="0"){
    $clorcfontesdes->erro(true,false);
  }else{
    $clorcfontesdes->erro(true,true);
  };
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>