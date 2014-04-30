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
include("classes/db_mer_item_classe.php");
include("classes/db_mer_itemunisaida_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_libdicionario.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmer_item         = new cl_mer_item;
$clmer_itemunisaida = new cl_mer_itemunisaida;
$db_opcao           = 22;
$db_botao           = false;
if (isset($alterar)) {
	
  db_inicio_transacao();
  $db_opcao = 2;
  $result1  = $clmer_item->sql_record($clmer_item->sql_query("","me10_i_codigo","","me10_i_codigo=$me10_i_codigo")); 
  if ($clmer_item->numrows>0) {
    db_fieldsmemory($result1,0);
  }   
  $result2 = $clmer_itemunisaida->sql_record($clmer_itemunisaida->sql_query("","*","","me20_i_item=$me10_i_codigo")); 
  if ($clmer_itemunisaida->numrows>0) {
    db_fieldsmemory($result2,0);
  }     
  $clmer_item->me10_c_ativo           = $me10_c_ativo;
  $clmer_item->me10_c_descr           = $me10_c_descr;
  $clmer_item->me10_i_unidade         = $me10_i_unidade;
  $clmer_item->alterar($me10_i_codigo);
  $clmer_itemunisaida->me20_i_item    = $me10_i_codigo;
  $clmer_itemunisaida->me20_i_unidade = $me20_i_unidade;
  $clmer_itemunisaida->me20_i_codigo  = $me20_i_codigo;
  $clmer_itemunisaida->alterar($me20_i_codigo);
  db_fim_transacao();
  
} elseif (isset($chavepesquisa)) {
	
  $db_opcao = 2;
  $result   = $clmer_item->sql_record($clmer_item->sql_query($chavepesquisa)); 
  db_fieldsmemory($result,0);   
  $db_botao = true;
  ?>
  <script>
   parent.document.formaba.a2.disabled    = false;
   parent.document.formaba.a2.style.color = "black";
   top.corpo.iframe_a2.location.href      = 'mer1_mer_infnutricional001.php?meritem=<?=$me10_i_codigo?>';
  </script>
<?

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
<center>
<table border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
    <fieldset style="width:95%"><legend><b>Alteração Item</b></legend>
	<? include("forms/db_frmmer_item.php");?>
	</fieldset>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<?
if(isset($alterar)){
	
  if($clmer_item->erro_status=="0"){
  	
    $clmer_item->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clmer_item->erro_campo!="") {
    	
      echo "<script> document.form1.".$clmer_item->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmer_item->erro_campo.".focus();</script>";
      
    }
  } else {
  	
    $clmer_item->erro(true,false);
	?>
    <script>
     parent.iframe_a1.location.href = "mer1_mer_item002.php?chavepesquisa=<?=$me10_i_codigo?>";
    </script>
    <?
    
  }
}
if ($db_opcao==22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","me10_c_controlavalidade",true,1,"me10_c_controlavalidade",true);
</script>