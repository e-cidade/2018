<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("classes/db_itenserv_classe.php"));
require_once(modification("classes/db_taxaserv_classe.php"));
require_once(modification("classes/db_taxaservval_classe.php"));
require_once(modification("classes/db_txsepultamentos_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clitenserv        = new cl_itenserv;
$cltaxaserv        = new cl_taxaserv;
$cltaxaservval     = new cl_taxaservval;
$cltxsepultamentos = new cl_txsepultamentos;
$clrotulo          = new rotulocampo;

$db_opcao = 1;
$db_botao = true;
$lSqlErro = false;

if ( isset($cm01_i_declarante) && trim($cm01_i_declarante) == "" ) {

 $sMsgDeclarate = 'Declarante não informado!\nAtualize o cadastro do Sepultamento e informe o Declarante.';
 unset($incluir);
}

if ( isset($incluir) ) {

  $result_numpre = db_query("select nextval('numpref_k03_numpre_seq')");
  $oNumpre = db_utils::fieldsMemory($result_numpre,0);

  db_inicio_transacao();

  if ( !$lSqlErro ) {

    $clitenserv->cm10_i_numpre    = $oNumpre->nextval;
    $clitenserv->cm10_i_taxaserv  = $oPost->cm10_i_taxaserv;
    $oPost->cm10_f_valor          = str_replace(".","",$oPost->cm10_f_valor);
    $clitenserv->cm10_f_valor     = str_replace(",",".",$oPost->cm10_f_valor);
    $clitenserv->cm10_t_obs       = $oPost->cm10_t_obs;
    $clitenserv->cm10_i_usuario   = db_getsession("DB_id_usuario");

    $oPost->cm10_f_valortaxa      = str_replace(".","",$oPost->cm10_f_valortaxa);
    $clitenserv->cm10_f_valortaxa = str_replace(",",".",$oPost->cm10_f_valortaxa);

    $clitenserv->incluir($oPost->cm10_i_codigo);

    $sErroMsg = $clitenserv->erro_msg;
    if ( $clitenserv->erro_status == 0 ) {
      $lSqlErro = true;
    }
  }

  if ( !$lSqlErro ) {

    $cltxsepultamentos->cm31_i_sepultamento = $oPost->cm31_i_sepultamento;
    $cltxsepultamentos->cm31_i_itenserv     = $clitenserv->cm10_i_codigo;
    $cltxsepultamentos->incluir(null);

    $sErroMsg = $cltxsepultamentos->erro_msg;
    if ( $cltxsepultamentos->erro_status == 0 ) {
      $lSqlErro = true;
    }
  }

  if ( !$lSqlErro ) {

	  if ( $clitenserv->numrows_incluir != 0 ) {

	    $sSql = "select fc_cemitarrecad(2,{$oNumpre->nextval},true) as retorno";
	    $result_arrecad = db_query($sSql) or die("Erro ao incluir em arrecad.");
      $oArrecad       = db_utils::fieldsMemory($result_arrecad, 0);

	    if ( substr( $oArrecad->retorno, 0, 1 ) != '9' ) {
	      db_msgbox($oArrecad->retorno);
	    }
	  }
  }

  db_fim_transacao($lSqlErro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js");
  db_app::load("widgets/messageboard.widget.js, widgets/windowAux.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_habilitabotaocalcular();" >
<table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table align="center" width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
     <?
       include(modification("forms/db_frmitenserv.php"));
     ?>
    </center>
     </td>
  </tr>
</table>
<?
if(!isset($tp)){
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
}
?>
</body>
</html>
<?
if ( isset($incluir) ) {

  if ( $clitenserv->erro_status == "0" ) {

    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ( $clitenserv->erro_campo != "" ) {

      echo "<script> document.form1.".$clitenserv->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clitenserv->erro_campo.".focus();</script>";
    }
  }

	if ( isset($sErroMsg) ) {

	  if ( !empty($sErroMsg) ) {
	    db_msgbox($sErroMsg);
	  }
	}
}

if ( isset($sMsgDeclarate) ) {
	db_msgbox($sMsgDeclarate);
}
?>