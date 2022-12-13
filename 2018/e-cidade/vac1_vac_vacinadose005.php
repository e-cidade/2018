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
require_once("classes/db_vac_vacinadose_classe.php");
require_once("classes/db_vac_doseperiodica_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoVacVacinadose    = new cl_vac_vacinadose;
$oDaoVacDoseperiodica = new cl_vac_doseperiodica;
$db_opcao             = 22;
$db_botao             = false;

if (isset($alterar)) { 

  db_inicio_transacao();
  $db_opcao = 2;
  if ($vc07_i_tipocalculo == 3) {

    if ($vc14_i_codigo == "") {

      $oDaoVacDoseperiodica->vc14_i_vacinadose=$vc07_i_codigo;
      $oDaoVacDoseperiodica->incluir(null);

    } else {

      $oDaoVacDoseperiodica->vc14_i_codigo=$vc14_i_codigo;
      $oDaoVacDoseperiodica->vc14_i_vacinadose=$vc07_i_codigo;
      $oDaoVacDoseperiodica->alterar($vc14_i_codigo);

    }

  }
  $oDaoVacVacinadose->alterar($vc07_i_codigo);
  db_fim_transacao();

} else if (isset($chavepesquisa)) {

   $db_opcao = 2;
   $rsResult = $oDaoVacVacinadose->sql_record($oDaoVacVacinadose->sql_query($chavepesquisa));
   db_fieldsmemory($rsResult,0);
   
   $sSql     =$oDaoVacDoseperiodica->sql_query("","*",""," vc14_i_vacinadose=$chavepesquisa ");
   $rsResult = $oDaoVacDoseperiodica->sql_record($sSql);
   if ($oDaoVacDoseperiodica->numrows>0) {
     db_fieldsmemory($rsResult,0);
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
<br><br>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
      <center>
	      <?
	      require_once("forms/db_frmvac_vacinadose2.php");
	      ?>
      </center>
	  </td>
  </tr>
</table>
</center>
</body>
</html>
<?
if (isset($alterar)) {

  if ($oDaoVacVacinadose->erro_status == '0') {

    $oDaoVacVacinadose->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($oDaoVacVacinadose->erro_campo != '') {

      echo "<script> document.form1.".$oDaoVacVacinadose->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoVacVacinadose->erro_campo.".focus();</script>";

    }

  } else {

    $oDaoVacVacinadose->erro(true,false);
    db_redireciona("vac1_vac_vacinadose005.php?chavepesquisa=$vc07_i_codigo");

  }

}
if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1", "vc07_i_diasvalidade", true, 1, "vc07_i_diasvalidade", true);
</script>