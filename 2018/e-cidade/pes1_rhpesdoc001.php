<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("classes/db_rhpesdoc_classe.php");
require_once("classes/db_rhpessoal_classe.php");
require_once("classes/db_db_uf_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clrhpesdoc   = new cl_rhpesdoc;
$clrhpessoal  = new cl_rhpessoal;
$cldb_uf      = new cl_db_uf;
$db_opcao     = 22;
$db_botao     = false;
$rhregistorig = $rh16_regist;

if (isset($alterar) || isset($excluir) || isset($incluir)) {
  $sqlerro = false;
}

if (isset($incluir)) {

  if ($sqlerro == false) {

    db_inicio_transacao();
    $db12_uf = "  ";

    if ($rh16_ctps_uf != 0) {

      $result_uf = $cldb_uf->sql_record($cldb_uf->sql_query_file($rh16_ctps_uf,"db12_uf"));

      if ($cldb_uf->numrows > 0) {
        db_fieldsmemory($result_uf, 0);
      }
    }

    $clrhpesdoc->rh16_ctps_uf = $db12_uf;
    $clrhpesdoc->incluir($rh16_regist);
    $erro_msg = $clrhpesdoc->erro_msg;
    if ($clrhpesdoc->erro_status == 0) {
      $sqlerro = true;
    }

    db_fim_transacao($sqlerro);
  }
} else if (isset($alterar)) {

  if ($sqlerro == false) {

    db_inicio_transacao();
    $db12_uf = "  ";

    if ($rh16_ctps_uf != 0) {

      $result_uf = $cldb_uf->sql_record($cldb_uf->sql_query_file($rh16_ctps_uf,"db12_uf"));
      if ($cldb_uf->numrows > 0) {
        db_fieldsmemory($result_uf, 0);
      }
    }

    $clrhpesdoc->rh16_ctps_uf = $db12_uf;
    $clrhpesdoc->rh16_regist  = $rh16_regist;

    /**
     * Verificamos se existem variáveis com o nome de rh16_pis
     * Caso existir, damos unset nela pois não é permitido alterar por esse menu.
     * Tarefa: 72708
     * @author: Acácio Schneider <acacio.schneider@dbseller.com.br>
     */
    if (isset($GLOBALS["HTTP_POST_VARS"]["rh16_pis"])) {
      unset($GLOBALS["HTTP_POST_VARS"]["rh16_pis"]);
    }

    if (isset($_POST["rh16_pis"])) {
      unset($_POST["rh16_pis"]);
    }

    if (isset($rh16_pis)) {
      unset($rh16_pis);
    }

    $clrhpesdoc->alterar($rh16_regist);
    $erro_msg = $clrhpesdoc->erro_msg;
    if ($clrhpesdoc->erro_status == 0) {
      $sqlerro=true;
    }
    $opcao = "alterar";
    db_fim_transacao($sqlerro);
  }
} else if (isset($excluir)) {

  if ($sqlerro == false) {

    db_inicio_transacao();
    $clrhpesdoc->excluir($rh16_regist);
    $erro_msg = $clrhpesdoc->erro_msg;
    if ($clrhpesdoc->erro_status == 0) {

      $sqlerro = true;
      $opcao   = "excluir";
    }
    db_fim_transacao($sqlerro);
  }
}

if (isset($opcao) || isset($rh16_regist)) {

  $result = $clrhpesdoc->sql_record($clrhpesdoc->sql_query_file($rh16_regist));

  if ($result != false && $clrhpesdoc->numrows > 0) {

    $opcao = "alterar";
    db_fieldsmemory($result, 0);
    if (trim($rh16_ctps_uf) != "") {

      $result_uf = $cldb_uf->sql_record($cldb_uf->sql_query_file(null,"*","","db12_uf = '".$rh16_ctps_uf."'"));
      if ($cldb_uf->numrows > 0) {

        db_fieldsmemory($result_uf, 0);
        $rh16_ctps_uf = $db12_codigo;
      }
    }
  } else if ($clrhpesdoc->numrows <= 0) {

    /**
     * Caso não encontrar o pis na tabela rhpesdoc, procuramos no cgm do servidor
     */
    $sSqlPessoal = $clrhpessoal->sql_query_file($rh16_regist, "rh01_numcgm");
    $rsPessoal   = $clrhpessoal->sql_record($sSqlPessoal);
    if ($clrhpessoal->numrows > 0) {

      require_once("libs/db_utils.php");
      $iNumCgm = db_utils::fieldsMemory($rsPessoal, 0)->rh01_numcgm;
      $oDaoCgm = db_utils::getDao("cgm");
      $sSqlCgm = $oDaoCgm->sql_query_file($iNumCgm, "z01_pis");
      $rsCgm   = $oDaoCgm->sql_record($sSqlCgm);
      if ($oDaoCgm->numrows > 0) {
        $rh16_pis = db_utils::fieldsMemory($rsCgm, 0)->z01_pis;
      }
    }
    $opcao = "alterar";
  }
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
<table width="790" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td height="430" valign="top" bgcolor="#CCCCCC">
    <center>
	  <?php
	  include("forms/db_frmrhpesdoc.php");
	  ?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?php
if (isset($alterar) || isset($excluir) || isset($incluir)) {

  db_msgbox($erro_msg);
  if($clrhpesdoc->erro_campo != "") {

    echo "<script> document.form1.".$clrhpesdoc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clrhpesdoc->erro_campo.".focus();</script>";
  } else {

    if (isset($alterar) || isset($incluir)) {

      echo "<script> location.href=\"?rh16_regist={$rh16_regist}&opcao=alterar\";</script>";
      echo "<script> parent.mo_camada('rhpessoalmov'); </script>";
    }
  }
}
?>