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
db_postmemory($HTTP_POST_VARS);
$clmer_item         = new cl_mer_item;
$clmer_itemunisaida = new cl_mer_itemunisaida;
$db_opcao           = 1;
$db_botao           = true;
if (isset($incluir)) {
	
  db_inicio_transacao();
  $clmer_item->me10_c_ativo           = $me10_c_ativo;
  $clmer_item->me10_c_descr           = $me10_c_descr;
  $clmer_item->me10_i_unidade         = $me10_i_unidade;
  $clmer_item->incluir($me10_i_codigo);
  $clmer_itemunisaida->me20_i_item    = $clmer_item->me10_i_codigo;
  $clmer_itemunisaida->me20_i_unidade = $clmer_item->me10_i_unidade;
  $clmer_itemunisaida->incluir(null);
  db_fim_transacao();
  
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
    <fieldset style="width:95%"><legend><b>Inclusão Item</b></legend>
	<? include("forms/db_frmmer_item.php");?>
	</fieldset>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<script>
js_tabulacaoforms("form1","me10_c_controlavalidade",true,1,"me10_c_controlavalidade",true);
</script>
<?
if (isset($incluir)) {
	
  if ($clmer_item->erro_status=="0") {
  	
    $clmer_item->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clmer_item->erro_campo!="") {
    	
      echo "<script> document.form1.".$clmer_item->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmer_item->erro_campo.".focus();</script>";
      
    }
  } else {
  	
    $clmer_item->erro(true,false);
    $result = @pg_query("select last_value from mer_item_me10_codigo_seq");
    $ultimo = pg_result($result,0,0);
    ?>
    <script>
     parent.iframe_a1.location.href = "mer1_mer_item002.php?chavepesquisa=<?=$ultimo?>";
    </script>
<?
  }
}
?>