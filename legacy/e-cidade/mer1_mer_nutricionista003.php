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
include("classes/db_mer_cardapionutri_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmer_nutricionista = new cl_mer_nutricionista;
$clmer_cardapionutri = new cl_mer_cardapionutri;
$db_botao            = false;
$db_opcao            = 33;
if (isset($excluir)) {
	
  $db_opcao     = 3;
  $sWhere       = "me04_i_nutricionista = $me02_i_codigo";
  $result_verif = $clmer_cardapionutri->sql_record(
                                                   $clmer_cardapionutri->sql_query("",
                                                                                   "me04_i_cardapio",
                                                                                   "",
                                                                                    $sWhere
                                                                                  )
                                                  );
  
  if ($clmer_cardapionutri->numrows>0) {
  	
    $clmer_nutricionista->erro_msg    = "Nutricionista $z01_nome não pode ser excluído, "; 
    $clmer_nutricionista->erro_msg   .= " pois já está vinculado a alguma refeição!";
    $clmer_nutricionista->erro_status = "0";
    
  } else {
  	
    db_inicio_transacao();
    $clmer_nutricionista->excluir($me02_i_codigo);
    db_fim_transacao();
    
 }
} elseif (isset($chavepesquisa)) {
	
  $db_opcao = 3;
  $result   = $clmer_nutricionista->sql_record($clmer_nutricionista->sql_query($chavepesquisa));
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">   
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Exclusão de Nutricionista</b></legend>
    <?include("forms/db_frmmer_nutricionista.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit")
         );
?>
</body>
</html>
<?
if (isset($excluir)) {
	
  if ($clmer_nutricionista->erro_status=="0") {
    $clmer_nutricionista->erro(true,false);
  } else {
    $clmer_nutricionista->erro(true,true);
  } 
}
if ($db_opcao==33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>