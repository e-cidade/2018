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
include("classes/db_mer_tipocardapio_classe.php");
include("classes/db_mer_cardapioitem_classe.php");
include("classes/db_diasemana_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cldiasemana        = new cl_diasemana;
$clmer_desperdicio  = new cl_mer_desperdicio;
$clmer_cardapioitem = new cl_mer_cardapioitem;
$clmer_tipocardapio = new cl_mer_tipocardapio;
$escola             =db_getsession("DB_coddepto");
$hoje               = date("Y-m-d",db_getsession("DB_datausu"));
$clmer_cardapioitem->rotulo->label();
$clrotulo           = new rotulocampo;
$clrotulo->label("me01_c_nome");
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
   <fieldset style="width:95%"><legend><b>Informações Nutricionais por Refeição</b></legend>
   <table border="0">
    <tr>
     <td><b>Cardápio:</b></td>
     <td>
      <select name="cardapio" onchange="js_load();">
       <option></option>
       <?       
         $hoje = date("Y-m-d",db_getsession("DB_datausu"));
         $result = $clmer_tipocardapio->sql_record($clmer_tipocardapio->sql_query("",
                                                                                          "me27_i_codigo,me27_c_nome,me27_f_versao,me27_i_id",
                                                                                          "me27_i_id,me27_f_versao desc",
                                                                                          "((me27_d_inicio is not null 
                                                                                             and me27_d_fim is null
                                                                                             and me27_d_inicio <= '$hoje') 
                                                                                            or (me27_d_fim is not null and '$hoje'
                                                                                                between me27_d_inicio and me27_d_fim))"
                                                                                         ));
       $linhas = pg_num_rows($result);
       for ($x=0;$x<$linhas;$x++) {
       	
         db_fieldsmemory($result,$x);
         if (@$cardapio == $me27_i_codigo) {
           echo "<option value=\"$me27_i_codigo\" selected>$me27_c_nome - Versão: $me27_f_versao</option>";
         } else {
           echo "<option value=\"$me27_i_codigo\">$me27_c_nome - Versão: $me27_f_versao</option>";
         }
         
        }
        ?>
       </select>
     </td>
    </tr>
    <tr>
     <td><b>Refeição:</b></td>
     <td>
      <select name="item">
       <option value="0">Todos</option>
       <?if (isset($cardapio)) {
       	
           $sql    = " select distinct me01_i_codigo,me01_c_nome,me01_f_versao from mer_cardapiodia ";
           $sql   .= "             inner join mer_cardapio on me01_i_codigo=mer_cardapiodia.me12_i_cardapio "; 
           $sql   .= "             where me01_i_tipocardapio=$cardapio";
           $result = pg_query($sql);
           $linhas = pg_num_rows($result);
           for ($x=0;$x<$linhas;$x++) {
        	
    	     db_fieldsmemory($result,$x);
    	     echo "<option value=\"$me01_i_codigo\">$me01_c_nome - V.$me01_f_versao </option>";
    	   
           }        
         }
       ?>
      </select>
     </td>
    </tr>
    <tr>
     <td><b>Data:</b></td>
     <td>
      <?db_inputdata("data","","","","","",1); ?>
     </td>
    </tr>
    <tr>
     <td colspan="4">
      <center>
      <input name="pesquiasar" type="button" value="Pesquisar" onclick="changeSrc()" >
      </center>
     </td>
    </tr>
   </table>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
<iframe name="iframe_tabela" id="iframe_tabela" src="" frameborder="0" width="100%" height="340"></iframe>
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
function js_load() {
	
  cardapio = document.form1.cardapio.value
  location.href='mer3_mer_nutricardapio001.php?cardapio='+cardapio;
  
}

function changeSrc() {
	
  data     = document.form1.data.value;
  refeicao = document.form1.item.value;
  cardapio = document.form1.cardapio.value;
  str      = '';
  sep      = '?';
  if (data!='') {
	  
    str = str+sep+'data='+data;
    sep = '&';
    
  }
  if (refeicao!='') {
	  
    str = str+sep+'refeicao='+refeicao;
    sep = '&';
    
  }
  if (cardapio!=0) {
    str = str+sep+'cardapio='+cardapio;
  }
  page = 'mer3_mer_nutricardapio002.php'+str;
  document.getElementById('iframe_tabela').src = page;
}
</script>