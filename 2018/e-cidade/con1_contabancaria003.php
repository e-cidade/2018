<?
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_contabancaria_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clcontabancaria = new cl_contabancaria;
$db_botao        = false;
$db_opcao        = 33;

if (isset($excluir)) {

  db_inicio_transacao();

  $oDaoContaPlanoContaBancaria = db_utils::getDao("conplanocontabancaria");
  $oDaoContaPlanoContaBancaria->excluir(null, "c56_contabancaria = {$db83_sequencial}");

  if ($oDaoContaPlanoContaBancaria->erro_status == "0") {

    db_msgbox($oDaoContaPlanoContaBancaria->erro_msg);
  } else {

    $clcontabancaria->excluir($db83_sequencial);
  }

  $db_opcao = 3;
  db_fim_transacao();
} elseif (isset($chavepesquisa)) {

   $db_opcao = 3;
   $result   = $clcontabancaria->sql_record($clcontabancaria->sql_query($chavepesquisa)); 
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
<table align="center" style="margin-top:20px;">
  <tr> 
    <td> 
    <center>
			<?
			  include("forms/db_frmcontabancaria.php");
			?>
    </center>
	</td>
  </tr>
</table>
<?php   
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?
if (isset($excluir)) {

  if ($clcontabancaria->erro_status == "0") {
    $clcontabancaria->erro(true, false);
  } else {
    $clcontabancaria->erro(true, true);
  }
}
if ($db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
  js_tabulacaoforms("form1", "excluir", true, 1,"excluir", true);
</script>