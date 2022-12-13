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
include("classes/db_mer_nutricionista_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clmer_nutricionista = new cl_mer_nutricionista;
$db_opcao            = 1;
$db_opcao1           = 1;
$db_botao            = true;
if (isset($incluir)) {
	
  $db_opcao1 = 3;
  db_inicio_transacao();
  $clmer_nutricionista->incluir($me02_i_codigo);
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
<br><br>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">   
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Inclusão de Nutricionista</b></legend>
    <?include("forms/db_frmmer_nutricionista.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","me02_i_cgm",true,1,"me02_i_cgm",true);
</script>
<?
if (isset($incluir)) {
  if ($clmer_nutricionista->erro_status=="0") {
  	
    $clmer_nutricionista->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($clmer_nutricionista->erro_campo!="") {
    	
      echo "<script> document.form1.".$clmer_nutricionista->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmer_nutricionista->erro_campo.".focus();</script>";
      
    }
  } else {
  	
    $clmer_nutricionista->erro(true,false);
    $result = @pg_query("select last_value from mernutricionista_me02_codigo_seq");
    $ultimo = pg_result($result,0,0);
    db_redireciona("mer1_mer_nutricionista002.php?chavepesquisa=$ultimo&z01_nome=$z01_nome");
    
 }
}
?>