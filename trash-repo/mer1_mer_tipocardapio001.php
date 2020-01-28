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
include("libs/db_stdlibwebseller.php");
include("classes/db_mer_tipocardapio_classe.php");
include("dbforms/db_funcoes.php");
$escola=db_getsession("DB_coddepto");
db_postmemory($HTTP_POST_VARS);
$clmer_tipocardapio = new cl_mer_tipocardapio;
$db_opcao           = 1;
$db_botao           = true;
$naopode  = false;
if (isset($incluir)) {
	
  db_inicio_transacao();
  $clmer_tipocardapio->me27_f_versao = 0.1;
  $clmer_tipocardapio->me27_i_id     = 9999;
  $clmer_tipocardapio->me27_c_ativo  = "S";
  $clmer_tipocardapio->incluir($me27_i_codigo);
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
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
 <tr>
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Inclusão de Cardápio</b></legend>
    <?include("forms/db_frmmer_tipocardapio.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","me27_c_nome",true,1,"me27_c_nome",true);
</script>
<?
if (isset($incluir)) {
	
 if ($clmer_tipocardapio->erro_status=="0") {
 	
   $clmer_tipocardapio->erro(true,false);  
   $db_botao=true;
   echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
   
   if ($clmer_tipocardapio->erro_campo!="") {
   	
     echo "<script> document.form1.".$clmer_tipocardapio->erro_campo.".style.backgroundColor='#99A9AE';</script>";
     echo "<script> document.form1.".$clmer_tipocardapio->erro_campo.".focus();</script>";
     
  }
 } else {
 	
   $clmer_tipocardapio->erro(true,false); 
   $codigo = $clmer_tipocardapio->me27_i_codigo;
   $clmer_tipocardapio->me27_i_id = $codigo;
   $clmer_tipocardapio->me27_i_codigo = $codigo;
   $clmer_tipocardapio->alterar($codigo);
   db_redireciona("mer1_mer_tipocardapio002.php?chavepesquisa=$codigo");
 
 } 
}
?>