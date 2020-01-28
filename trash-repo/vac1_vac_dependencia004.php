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
require_once("classes/db_vac_dependencia_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);

$oIframeAE          = new cl_iframe_alterar_excluir;
$oDaoVacDependencia = new cl_vac_dependencia;
$db_opcao           = 1;
$db_botao           = true;

//altera exclui inicio
$db_botao1 = false;
if (isset($opcao)) {

  /////comeca classe alterar excluir
  $campos = "";
  $sSql   = $oDaoVacDependencia->sql_query_alt(null,
                                               "vac_dependencia.*,dependencia.*",
                                               "", " vc09_i_codigo = $vc09_i_codigo "
                                              );

  $result1 = $oDaoVacDependencia->sql_record($sSql);
  if ($oDaoVacDependencia->numrows>0) {
    db_fieldsmemory($result1,0);
  }
  if ( $opcao == "alterar") {

    $db_opcao = 2;
    $db_botao1 = true;

  } else {

    if ( $opcao == "excluir" || isset($db_opcao) && $db_opcao==3) {

      $db_opcao  = 3;
      $db_botao1 = true;

    } else {

      if (isset($alterar)) {

        $db_opcao  = 2;
        $db_botao1 = true;

      }

    }
  } 
}

if (isset($incluir)) {

  db_inicio_transacao();
  $oDaoVacDependencia->incluir($vc09_i_codigo);
  db_fim_transacao();

} elseif (isset($excluir)){

  db_inicio_transacao();
  $db_opcao = 3;
  $oDaoVacDependencia->excluir($vc09_i_codigo);
  db_fim_transacao();

} elseif (isset($alterar)) {

  db_inicio_transacao();
  $db_opcao = 2;
  $oDaoVacDependencia->alterar($vc09_i_codigo);
  db_fim_transacao();

} elseif (isset($chavepesquisa)) {

  $db_opcao = 2;
  $result   = $oDaoVacDependencia->sql_record($oDaoVacDependencia->sql_query($chavepesquisa)); 
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
<br><br>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
	      <?
	      require_once("forms/db_frmvac_dependencia.php");
	      ?>
      </center>
	  </td>
  </tr>
</table>
</center>
</body>
</html>
<script>
js_tabulacaoforms("form1", "vc09_i_dependente", true, 1, "vc09_i_dependente", true);
</script>
<?
if ((isset($incluir)) || (isset($alterar)) || (isset($excluir))) {

  if ($oDaoVacDependencia->erro_status == '0') {

    $oDaoVacDependencia->erro(true, false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($oDaoVacDependencia->erro_campo != "") {

      echo "<script> document.form1.".$oDaoVacDependencia->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoVacDependencia->erro_campo.".focus();</script>";

    }
  } else {

    $oDaoVacDependencia->erro(true,false);
    db_redireciona("vac1_vac_dependencia004.php?vc09_i_dependente=$vc09_i_dependente&dependente=$dependente");

  }

}
?>