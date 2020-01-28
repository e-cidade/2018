<?php
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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("classes/db_liclicitem_classe.php"));
require_once (modification("classes/db_pcprocitem_classe.php"));
require_once (modification("model/licitacao.model.php"));
require_once (modification("classes/db_liclicitasituacao_classe.php"));
parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);
$clliclicitem        = new cl_liclicitem;
$clliclicitasituacao = new cl_liclicitasituacao;
$clrotulo            = new rotulocampo;
$clrotulo->label("");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
</script>
<style>
<?//$cor="#999999"?>
.bordas{
    border: 2px solid #cccccc;
    border-top-color: #999999;
    border-right-color: #999999;
    border-left-color: #999999;
    border-bottom-color: #999999;
    background-color: #999999;
}
.bordas_corp{
    border: 1px solid #cccccc;
    border-top-color: #999999;
    border-right-color: #999999;
    border-left-color: #999999;
    border-bottom-color: #999999;
    background-color: #cccccc;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
<tr>
<td  align="center" valign="top" >

<table border='0'>
  </tr>

<?

if (isset($l20_codigo) && !empty($l20_codigo)) {

  $sSqlLog = "select l14_liclicita  from liclicitaitemlog where l14_liclicita = {$l20_codigo}";
  $rslog   = db_query($sSqlLog);
  $lLog    = false;
  $sIn     = "";
  if (pg_num_rows($rslog) > 0)  {

    $lLog       = true;
    $oLicitacao = new licitacao($l20_codigo);
    $oInfoLog   = $oLicitacao->getInfoLog();
    $numrows    = count($oInfoLog->item);
    $sVirgula   = "";
    foreach ($oInfoLog->item as $oItem) {

      $sIn      .= "{$sVirgula} {$oItem->l21_codpcprocitem} ";
      $sVirgula  = ",";
    }
  }

  if ($sIn == '') {
  	$sIn = 0;
  }

	if ($tipo == "p") {

	  $sCampos = "distinct pc80_codproc, pc80_data, pc80_usuario, nome, pc80_depto, descrdepto, pc80_resumo";
	  if ($lLog) {

	    $clpcprocitem = new cl_pcprocitem();
      $sql          = $clpcprocitem->sql_query(null, $sCampos, null, "pc81_codprocitem in({$sIn})");
    } else {
	    $sql = $clliclicitem->sql_query_proc(null, $sCampos, null, "l21_codliclicita=$l20_codigo");
    }
	} else if ($tipo=="s") {

	  $sCampos = "distinct pc10_numero, pc90_numeroprocesso, pc10_data, pc10_login, nome, pc10_depto, descrdepto, pc10_resumo";
	  if ($lLog) {

	    $clpcprocitem = new cl_pcprocitem();
      $sql          = $clpcprocitem->sql_query_dotac(null, $sCampos, null, "pc81_codprocitem in({$sIn})");
    } else {

		  $sql = $clliclicitem->sql_query_sol(null, $sCampos, null, "l21_codliclicita=$l20_codigo");
    }
	} else if ($tipo ='m') {

	  $sCampos = "nome, l08_descr, l11_data, l11_hora, l11_obs";
		$sql     = $clliclicitasituacao->sql_query(null, $sCampos, "l11_data, l11_hora", "l11_liclicita=$l20_codigo");
	}
}
db_lovrot(@$sql,15,"","","");
?>
</table>

</td>
</tr>
</table>
</body>
</html>