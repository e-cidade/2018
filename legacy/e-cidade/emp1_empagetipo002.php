<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_empagetipo_classe.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_conplanoconta_classe.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);

$clempagetipo    = new cl_empagetipo;
$clconplanoconta = new cl_conplanoconta;
$db_opcao        = 22;
$db_botao        = false;

if (isset($alterar)) {

  $db_opcao = 2;
  $lErro    = false;

  $sCampos = " e83_codtipo, e83_conta ";
  $sWhere  = " e83_conta = {$e83_conta} ";
  $sSql    = $clempagetipo->sql_query(null, $sCampos, null, $sWhere);

  $rsContas = $clempagetipo->sql_record($sSql);
  if ($clempagetipo->numrows > 0) {

    $lErro     = true;
    for ($iIndice = 0; $iIndice < $clempagetipo->numrows; $iIndice++) {

      $oConta = db_utils::fieldsMemory($rsContas, $iIndice);
      if ($oConta->e83_codtipo == $e83_codtipo && $oConta->e83_conta == $e83_conta) {

        $lErro = false;
        break;
      }
    }

    $sMsgErro  = "A Conta informada já está vinculada a uma Conta Pagadora. \n";
    $sMsgErro .= "Não é possível vincular uma Conta a mais de uma Conta Pagadora.";
  }

  if (!$lErro) {

    db_inicio_transacao();

    $clempagetipo->e83_codigocompromisso = str_pad($e83_codigocompromisso, 4, "0", STR_PAD_LEFT);
    $clempagetipo->e83_codtipo           = $e83_codtipo;
    $clempagetipo->e83_codmod            = 3;
    $clempagetipo->e83_sequencia         = $e83_sequencia;
    $clempagetipo->alterar($e83_codtipo);
    $sMsgErro = $clempagetipo->erro_msg;
    if ($clempagetipo->erro_status == "0") {
      $lErro = true;
    }
    $chavepesquisa = $e83_codtipo;
    db_fim_transacao($lErro);
  }
}

if (isset($chavepesquisa)) {

  $db_opcao = 2;
  $result   = $clempagetipo->sql_record($clempagetipo->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
  if (isset($e83_conta) && $e83_conta != '') {

    $sequencia = $clempagetipo->getMaxCheque($e83_conta);
    if (trim($sequencia) != "") {
      $e83_sequencia = $sequencia;
    }
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
<table width="680" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top">
      <?
        include(modification("forms/db_frmempagetipo.php"));
      ?>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if (isset($alterar)) {

  if (!$lErro) {
    db_msgbox($sMsgErro);
  } else {

    $db_botao = true;
    db_msgbox($sMsgErro);
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clempagetipo->erro_campo != "") {

      echo "<script> document.form1.".$clempagetipo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clempagetipo->erro_campo.".focus();</script>";
    }
  }
}

if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>