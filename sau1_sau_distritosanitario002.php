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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_sau_distritosanitario_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoSauDistritoSanitario = new cl_sau_distritosanitario;
$db_opcao                 = 22;
$db_botao                 = false;

if (isset($alterar)) {

  db_inicio_transacao();
  $db_opcao = 2;
  $oDaoSauDistritoSanitario->alterar($s153_i_codigo);
  db_fim_transacao($oDaoSauDistritoSanitario->erro_status == '0' ? true : false);

} elseif (isset($chavepesquisa)) {

   $db_opcao = 2;
   $sSql     = $oDaoSauDistritoSanitario->sql_query($chavepesquisa);
   $result   = $oDaoSauDistritoSanitario->sql_record($sSql); 
   $db_botao = true;
   db_fieldsmemory($result, 0);

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
<br><br>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
        <fieldset style='width: 75%;'> <legend><b>Destino</b></legend>
          <?
          require_once("forms/db_frmsau_distritosanitario.php");
          ?>
        </fieldset>
      </center>
    </td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"),
        db_getsession("DB_anousu"), db_getsession("DB_instit")
       );
?>
</body>
</html>
<?
if (isset($alterar)) {

  if ($oDaoSauDistritoSanitario->erro_status == '0') {

    $oDaoSauDistritoSanitario->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($oDaoSauDistritoSanitario->erro_campo != '') {

      echo "<script> document.form1.".$oDaoSauDistritoSanitario->erro_campo.
           ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoSauDistritoSanitario->erro_campo.".focus();</script>";

    }

  } else {
    $oDaoSauDistritoSanitario->erro(true, true);
  }
}
if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","s153_c_codigo",true,1,"s153_c_codigo",true);
</script>