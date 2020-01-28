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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_taxaserv_classe.php"));
require_once(modification("classes/db_taxaservval_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$cltaxaserv               = new cl_taxaserv;
$cltaxaservval            = new cl_taxaservval;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$db_opcao   = 1;
$db_botao   = true;
$lSqlErro   = false;

$sMsgInt    = "Usuário: \\n\\n";
$sMsgInt   .= "Valor informado já cadastrado para esse período! \\n\\n";
$sMsgInt   .= "Administrador: \\n\\n";

if ( isset($oGet->codigo) ) {

	$sSqlTaxaServ  = $cltaxaserv->sql_query($oGet->codigo,"cm11_i_codigo,cm11_c_descr",null,"");
	$rsSqlTaxaServ = $cltaxaserv->sql_record($sSqlTaxaServ);

	if ( $cltaxaserv->numrows > 0 ) {

		$oTaxaServ = db_utils::fieldsMemory($rsSqlTaxaServ,0);
		$cm11_i_codigo = $oTaxaServ->cm11_i_codigo;
		$cm11_c_descr  = $oTaxaServ->cm11_c_descr;
	}
}

if ( isset($oPost->incluir) ) {

  db_inicio_transacao();

  $dtDataIni        = implode("-",array_reverse(explode("/",$oPost->cm35_dataini)));
  $dtDataFim        = implode("-",array_reverse(explode("/",$oPost->cm35_datafin)));

  $sWhere           = " cm35_taxaserv = {$oPost->cm11_i_codigo}                         ";
  $sWhere          .= " and (cm35_dataini::date,cm35_datafin::date)                                                       ";
  $sWhere          .= "      overlaps ('{$dtDataIni}'::date - '1 day'::interval,'{$dtDataFim}'::date + '1 day'::interval) ";

  $sSqlTaxaServVal  = $cltaxaservval->sql_query(null,"taxaservval.*",null,$sWhere);
  $rsSqlTaxaServVal = $cltaxaservval->sql_record($sSqlTaxaServVal);

  if ( $cltaxaservval->numrows > 0 ) {

    	$sErroMsg = $sMsgInt;
    	$lSqlErro = true;
  }

  if ( !$lSqlErro ) {

  	$cltaxaservval->cm35_taxaserv = $oPost->cm11_i_codigo;
  	$cltaxaservval->cm35_dataini  = $dtDataIni;
  	$cltaxaservval->cm35_datafin  = $dtDataFim;
  	$cltaxaservval->cm35_valor    = $oPost->cm35_valor;
    $cltaxaservval->incluir(null);

    $sErroMsg = $cltaxaservval->erro_msg;
    if ( $cltaxaservval->erro_status == 0 ) {
      $lSqlErro = true;
    }
  }

  db_fim_transacao($lSqlErro);

} else if ( isset($oPost->alterar) ) {

  db_inicio_transacao();

  $dtDataIni        = implode("-",array_reverse(explode("/",$oPost->cm35_dataini)));
  $dtDataFim        = implode("-",array_reverse(explode("/",$oPost->cm35_datafin)));

  $sWhere           = "     cm35_taxaserv = {$oPost->cm11_i_codigo}                                                       ";
  $sWhere          .= " and cm35_sequencial <> $oPost->cm35_sequencial                                                    ";
  $sWhere          .= " and (cm35_dataini::date,cm35_datafin::date)                                                       ";
  $sWhere          .= "      overlaps ('{$dtDataIni}'::date - '1 day'::interval,'{$dtDataFim}'::date + '1 day'::interval) ";

  $sSqlTaxaServVal  = $cltaxaservval->sql_query(null,"taxaservval.*",null, $sWhere);
  $rsSqlTaxaServVal = $cltaxaservval->sql_record($sSqlTaxaServVal);

  if ( $cltaxaservval->numrows > 0 ) {

    $sErroMsg = $sMsgInt;
    $lSqlErro = true;
  }

  if ( !$lSqlErro ) {

    $cltaxaservval->cm35_taxaserv = $oPost->cm11_i_codigo;
    $cltaxaservval->cm35_dataini  = $dtDataIni;
    $cltaxaservval->cm35_datafin  = $dtDataFim;
    $cltaxaservval->cm35_valor    = $oPost->cm35_valor;
    $cltaxaservval->alterar($oPost->cm35_sequencial);

    $sErroMsg = $cltaxaservval->erro_msg;
    if ( $cltaxaservval->erro_status == 0 ) {
      $lSqlErro = true;
    }
  }

  db_fim_transacao($lSqlErro);

} else if ( isset($oPost->excluir) ) {

  db_inicio_transacao();

  if ( !$lSqlErro ) {

    $cltaxaservval->excluir($oPost->cm35_sequencial);

    $sErroMsg = $cltaxaservval->erro_msg;
    if ( $cltaxaservval->erro_status == 0 ) {
      $lSqlErro = true;
    }
  }

  db_fim_transacao($lSqlErro);

}

if ( isset($oPost->opcao) ) {

	if ( $oPost->opcao == 'alterar' ) {

		$db_opcao   = 2;
		if ( isset($oPost->cm35_sequencial) ) {

		  $sSqlTaxaServVal  = $cltaxaservval->sql_query($oPost->cm35_sequencial,"taxaservval.*",null,"");
		  $rsSqlTaxaServVal = $cltaxaservval->sql_record($sSqlTaxaServVal);

		  if ( $cltaxaservval->numrows > 0 ) {
		    db_fieldsMemory($rsSqlTaxaServVal,0);
		  }
		}

	} else if ( $oPost->opcao == 'excluir' ) {

	  $db_opcao   = 3;
    if ( isset($oPost->cm35_sequencial) ) {

      $sSqlTaxaServVal  = $cltaxaservval->sql_query($oPost->cm35_sequencial,"taxaservval.*",null,"");
      $rsSqlTaxaServVal = $cltaxaservval->sql_record($sSqlTaxaServVal);

      if ( $cltaxaservval->numrows > 0 ) {
        db_fieldsMemory($rsSqlTaxaServVal,0);
      }
    }
	}
} else {

	$cm35_dataini_dia = "";
	$cm35_dataini_mes = "";
	$cm35_dataini_ano = "";
	$cm35_datafin_dia = "";
	$cm35_datafin_mes = "";
	$cm35_datafin_ano = "";
	$cm35_valor       = "";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top">
      <?
        include(modification("forms/db_frmtaxaservval.php"));
      ?>
  </td>
  </tr>
</table>
</body>
</html>
<?
if ( isset($sErroMsg) ) {

	if ( !empty($sErroMsg) ) {
		db_msgbox($sErroMsg);
	}
}
?>