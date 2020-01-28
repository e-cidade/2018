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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_mer_desperdicio_classe.php");
include("classes/db_mer_cardapioitem_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmer_desperdicio  = new cl_mer_desperdicio;
$clmer_cardapioitem = new cl_mer_cardapioitem;
$clmer_cardapioitem->rotulo->label();
$clrotulo           = new rotulocampo;
$clrotulo->label("m60_descr");
$clrotulo->label("me01_c_nome");
if (!isset($op)) {
  $op = 1;
}
$escola = db_getsession("DB_coddepto");
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">   
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Informações Nutricionais por alimento</b></legend>
    <table border="0">
     <tr>
      <td><b>Alimento:</b></td>
      <td>
       <select name="cod_item" value="0">
        <option value="">Todos</option>
        <?
        $sql    = " select me35_i_codigo,me35_c_nomealimento from mer_alimento order by me35_c_nomealimento";
        $result = pg_query($sql);
        $linhas = pg_num_rows($result);
        for ($x=0; $x<$linhas; $x++) {
        	
      	  db_fieldsmemory($result,$x);
          echo "<option value=\"$me35_i_codigo\">".substr($me35_c_nomealimento,0,40)."</option>";
         
        }
	?>
       </select>
      </td>
      <td><b>Grupo Alimentar:</b></td>
      <td>
       <select name="cod_grupo" value="0">
        <option value="">Todos</option>
        <?
        $sql    = " select me30_i_codigo,me30_c_descricao from mer_grupoalimento order by me30_c_descricao";
        $result = pg_query($sql);
        $linhas = pg_num_rows($result);
        for ($x=0; $x<$linhas; $x++) {
            
          db_fieldsmemory($result,$x);
          echo "<option value=\"$me30_i_codigo\">".substr($me30_c_descricao,0,40)."</option>";
         
        }
    ?>
       </select>
      </td>
      <td>
       <input name="pesquiasar" type="button" value="Pesquisar" onclick="changeSrc(<?=$linhas?>)" >
      </td>
     </tr>
    </table>
   </fieldset>
   <center>
  </td>
 </tr>
</table>
<iframe name="iframe_tabela" id="iframe_tabela" src="" frameborder="0" width="100%" height="400"></iframe>
</form>
<?db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit")
         );
?>
</body>
</html>
<script>
function js_trocaop() {
	
  selecionado = document.form1.op[0].checked;
  if (selecionado == true) {
    location.href = ('mer3_mer_nutriitem001.php?op=1');
  } else {
   location.href  = ('mer3_mer_nutriitem001.php?op=2');
  } 
}

function changeSrc(tam) {
	
  item = document.form1.cod_item.value;
  grupo = document.form1.cod_grupo.value;	
  if (item!="" || grupo!="") {
	  
	  page = 'mer3_mer_nutriitem002.php?item='+item+'&grupo='+grupo;
	  document.getElementById('iframe_tabela').src = page;

  }
  
}
</script>