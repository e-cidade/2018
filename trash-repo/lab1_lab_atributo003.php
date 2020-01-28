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
include("classes/db_lab_atributo_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_lab_parametros_classe.php");
include("classes/db_lab_exameatributoligacao_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cllab_atributo = new cl_lab_atributo;
$cllab_parametros = new cl_lab_parametros;
$cllab_exameatributoligacao = new cl_lab_exameatributoligacao;
$db_botao = false;
$db_opcao = 33;

$la49_c_estrutural="";
$tamanho=0;
if($cllab_parametros->numrows>0){
	db_fieldsmemory($rResult,0);
	$aVet=explode(".",$la49_c_estrutural);
	$tamanho=count($aVet);
}

if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;

  $cllab_exameatributoligacao->excluir(null, 'la26_i_exameatributofilho = '.$la25_i_codigo);
  if ($cllab_exameatributoligacao->erro_status == '0') {

    $cllab_atributo->erro_status = '0';
    $cllab_atributo->erro_msg    = $cllab_exameatributoligacao->erro_msg;

  }

  if ($cllab_atributo->erro_status != '0') {
    $cllab_atributo->excluir($la25_i_codigo);
  }
  db_fim_transacao($cllab_atributo->erro_status == '0');
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $cllab_atributo->sql_record($cllab_atributo->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $sCampos = " la26_i_exameatributopai,
                la25_c_estrutural as la25_c_estrutural_pai,
                la25_c_descr as la25_c_descr_pai,
                la25_i_nivel as la25_i_nivel_pai ";
   $sSql=$cllab_exameatributoligacao->sql_query_pai("",$sCampos,""," la26_i_exameatributofilho=$chavepesquisa");
   $result = $cllab_exameatributoligacao->sql_record($sSql); 
   if($cllab_exameatributoligacao->numrows>0){
      db_fieldsmemory($result,0);
   }
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
<br><br>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmlab_atributo.php");
	?>
    </center>
	</td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir)){
  if($cllab_atributo->erro_status=="0"){
    $cllab_atributo->erro(true,false);
  }else{
    $cllab_atributo->erro(true,true);
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>