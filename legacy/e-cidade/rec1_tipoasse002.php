<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));

parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);

$cltipoasse                    = new cl_tipoasse;
$clportariatipo                = new cl_portariatipo;
$clportariaenvolv              = new cl_portariaenvolv;
$clportariatipoato             = new cl_portariatipoato;
$clportariaproced              = new cl_portariaproced;
$clrotulo                      = new rotulocampo;
$clportariatipodocindividual   = new cl_portariatipodocindividual;
$clportariatipodoccoletiva	   = new cl_portariatipodoccoletiva;
$oDaoAssentamentoJustificativa = new cl_assentamentojustificativaperiodo();

$db_opcao = 22;
$db_botao = false;
$sqlerro  = false;

function vinculaAssentamentoJustificativa($iTipoAssentamento) {

  $oDaoAssenta   = new cl_assenta();
  $sWhereAssenta = "h16_assent = {$iTipoAssentamento}";
  $sSqlAssenta   = $oDaoAssenta->sql_query(null, 'h16_codigo', null, $sWhereAssenta);
  $rsAssenta     = db_query($sSqlAssenta);

  $aCodigosAssenta = array();

  if($rsAssenta && pg_num_rows($rsAssenta) > 0) {
    $aCodigosAssenta = db_utils::getCollectionByRecord($rsAssenta);
  }

  $oDaoAssentamentoJustificativa = new cl_assentamentojustificativaperiodo();

  foreach($aCodigosAssenta as $oAssenta) {

    for($iContador = 1; $iContador <= 3; $iContador++) {

      $oDaoAssentamentoJustificativa->rh206_codigo  = $oAssenta->h16_codigo;
      $oDaoAssentamentoJustificativa->rh206_periodo = $iContador;
      $oDaoAssentamentoJustificativa->incluir(null);

      if($oDaoAssentamentoJustificativa->erro_status == '0') {
        throw new DBException('Erro ao vincular o assentamento a justificativa. Contate o suporte');
      }
    }
  }
}

function desvinculaAssentamentoJustificativa($iTipoAssentamento) {

  $aCampos = array('distinct rh206_codigo');
  $aWhere  = array("h12_codigo = {$iTipoAssentamento}");

  $oDaoAssentamentoJustificativa = new cl_assentamentojustificativaperiodo();
  $sSqlAssentamentoJustificativa = $oDaoAssentamentoJustificativa->sqlTipoAsse($aCampos, $aWhere);
  $rsAssentamentoJustificativa   = db_query($sSqlAssentamentoJustificativa);

  if($rsAssentamentoJustificativa && pg_num_rows($rsAssentamentoJustificativa) > 0) {

    $aCodigosAssenta = db_utils::makeCollectionFromRecord($rsAssentamentoJustificativa, function($oRetorno) {
      return $oRetorno->rh206_codigo;
    });

    $sCodigosAssenta = implode(', ', $aCodigosAssenta);

    $oDaoAssentamentoJustificativa->excluir(null, null, "rh206_codigo in(" . implode(', ', $aCodigosAssenta) . ")");

    if($oDaoAssentamentoJustificativa->erro_status == '0') {
      throw new DBException('Erro ao remover os vínculos do assentamento com justificativa. Contate o suporte.');
    }
  }

  $oDaoJustificativaTipoAssentamento = new cl_pontoeletronicojustificativatipoasse();
  $oDaoJustificativaTipoAssentamento->excluir(null, "rh205_tipoasse = {$iTipoAssentamento}");

  if($oDaoJustificativaTipoAssentamento->erro_status == '0') {
    throw new DBException('Erro ao remover o vínculo do assentamento com a justificativa. Contate o suporte.');
  }
}

if(isset($alterar)) {

  db_inicio_transacao();

  $db_opcao = 2;
  $cltipoasse->h12_assent = $h12_assent = trim($h12_assent);
  $cltipoasse->alterar($h12_codigo);

  if (!empty($h79_db_cadattdinamico)) {

    $oDaoTipoassedb_cadattdinamico                        = new cl_tipoassedb_cadattdinamico();
    $oDaoTipoassedb_cadattdinamico->h79_db_cadattdinamico = $h79_db_cadattdinamico;
    $oDaoTipoassedb_cadattdinamico->h79_tipoasse          = $cltipoasse->h12_codigo;

    $sSqlTipoAsseCadDinamico = $oDaoTipoassedb_cadattdinamico->sql_query(
      null,
      null,
      'h79_db_cadattdinamico',
      null,
      "h79_tipoasse = {$h12_codigo}"
    );
    $rsTipoAsseCadDinamico = db_query($sSqlTipoAsseCadDinamico);

    if (pg_num_rows($rsTipoAsseCadDinamico) > 0) {
      $oDaoTipoassedb_cadattdinamico->alterar($h79_db_cadattdinamico, $cltipoasse->h12_codigo);
    } else {
      $oDaoTipoassedb_cadattdinamico->incluir($h79_db_cadattdinamico, $cltipoasse->h12_codigo);
    }

    if ($oDaoTipoassedb_cadattdinamico->erro_sql == '0') {
      $sqlerro = true;
    }
  }

  if ($cltipoasse->erro_status == 0) {
    $sqlerro  = true;
  }

  if ($sqlerro == false) {

    $flag_alt = false;
    if (isset($h30_sequencial) && trim($h30_sequencial) != "") {
      $flag_alt = true;
    }

    if($flag_alt == false) {

      $flag_inc = false;

      if(isset($h30_portariaenvolv) && trim($h30_portariaenvolv) != "") {
        $flag_inc = true;
      }
    }

    if ($flag_alt == true) {
      $clportariatipo->h30_sequencial = $h30_sequencial;
    }

    $clportariatipo->h30_tipoasse        = $h12_codigo;
    $clportariatipo->h30_portariaenvolv  = $h30_portariaenvolv;
    $clportariatipo->h30_portariatipoato = $h30_portariatipoato;
    $clportariatipo->h30_portariaproced  = $h30_portariaproced;
    $clportariatipo->h30_amparolegal     = $h30_amparolegal;

    $clportariatipodocindividual->h37_modportariaindividual = $h37_modportariaindividual;
    $clportariatipodocindividual->h37_portariatipo 		      = $clportariatipo->h30_sequencial;

    $clportariatipodoccoletiva->h38_modportariacoletiva = $h38_modportariacoletiva;
    $clportariatipodoccoletiva->h38_portariatipo       	= $clportariatipo->h30_sequencial;

    if ($flag_alt == true) {

      $sSqlConsultaModIndividual = $clportariatipodocindividual->sql_query(
        null,
        "h37_sequencial",
        null,
        " h37_portariatipo = {$h30_sequencial}"
      );
      $rsConsultaModIndividual = $clportariatipodocindividual->sql_record($sSqlConsultaModIndividual);

      $sSqlConsultaModColetiva = $clportariatipodoccoletiva->sql_query(
        null,
        "h38_sequencial",
        null,
        "h38_portariatipo = {$h30_sequencial} "
      );
      $rsConsultaModColetiva = $clportariatipodoccoletiva->sql_record($sSqlConsultaModColetiva);

      $lTemModInd = false;
      if($clportariatipodocindividual->numrows > 0) {

        $lTemModInd   = true;
        $oPortariaInd = db_utils::fieldsMemory($rsConsultaModIndividual, 0);

        $clportariatipodocindividual->h37_sequencial = $oPortariaInd->h37_sequencial;
      }

      $lTemModCol = false;
      if ($clportariatipodoccoletiva->numrows > 0) {

        $lTemModCol   = true;
        $oPortariaCol = db_utils::fieldsMemory($rsConsultaModColetiva, 0);

        $clportariatipodoccoletiva->h38_sequencial = $oPortariaCol->h38_sequencial;
      }

      if (isset($h30_portariaenvolv) && trim($h30_portariaenvolv) != "") {

        $clportariatipo->alterar($h30_sequencial);

        if($lTemModInd) {

          if(isset($h37_modportariaindividual) && trim($h37_modportariaindividual) != "") {
            $clportariatipodocindividual->alterar($oPortariaInd->h37_sequencial);
          } else {
            $clportariatipodocindividual->excluir($oPortariaInd->h37_sequencial);
          }
        } else {

          if(isset($h37_modportariaindividual) && trim($h37_modportariaindividual) != "") {
            $clportariatipodocindividual->incluir(null);
          }
        }

        if($lTemModCol) {

          if(isset($h38_modportariacoletiva) && trim($h38_modportariacoletiva) != "") {
            $clportariatipodoccoletiva->alterar($oPortariaCol->h38_sequencial);
          } else {
            $clportariatipodoccoletiva->excluir($oPortariaCol->h38_sequencial);
          }
        } else {

          if(isset($h38_modportariacoletiva) && trim($h38_modportariacoletiva) != "") {
            $clportariatipodoccoletiva->incluir(null);
          }
        }
      } else {

        if($lTemModInd) {
          $clportariatipodocindividual->excluir($oPortariaInd->h37_sequencial);
        }

        if($lTemModCol) {
          $clportariatipodoccoletiva->excluir($oPortariaCol->h38_sequencial);
        }

        $clportariatipo->excluir($h30_sequencial);
      }
    }

    if ($flag_alt == false && $flag_inc == true) {

      $clportariatipo->h30_tipoasse        = $h12_codigo;
      $clportariatipo->h30_portariaenvolv  = $h30_portariaenvolv;
      $clportariatipo->h30_portariatipoato = $h30_portariatipoato;
      $clportariatipo->h30_portariaproced  = $h30_portariaproced;
      $clportariatipo->h30_amparolegal     = $h30_amparolegal;
      $clportariatipo->incluir(null);

      if ($clportariatipo->erro_status == 0) {
        $sqlerro = true;
      }

      if(isset($h37_modportariaindividual) && trim($h37_modportariaindividual) != "") {

        $clportariatipodocindividual->h37_modportariaindividual = $h37_modportariaindividual;
        $clportariatipodocindividual->h37_portariatipo 		      = $clportariatipo->h30_sequencial;
        $clportariatipodocindividual->incluir(null);

        if($clportariatipodocindividual->erro_status == 0) {
          $sqlerro = true;
        }
      }

      if(isset($h38_modportariacoletiva) && trim($h38_modportariacoletiva) != "") {

        $clportariatipodoccoletiva->h38_modportariacoletiva = $h38_modportariacoletiva;
        $clportariatipodoccoletiva->h38_portariatipo       	= $clportariatipo->h30_sequencial;
        $clportariatipodoccoletiva->incluir(null);

        if($clportariatipodoccoletiva->erro_status == 0) {
          $sqlerro = true;
        }
      }

      if ($clportariatipo->erro_status != 0) {
        $h30_sequencial = $clportariatipo->h30_sequencial;
      }
    }

    if($h12_natureza != $natureza_validacao && $h12_natureza == 5) {
      vinculaAssentamentoJustificativa($h12_codigo);
    }

    if($h12_natureza != $natureza_validacao && $h12_natureza != 5) {
      desvinculaAssentamentoJustificativa($h12_codigo);
    }
  }

  db_fim_transacao($sqlerro);
} else if(isset($chavepesquisa)) {

  $db_opcao = 2;
  $result   = $cltipoasse->sql_record($cltipoasse->sql_query($chavepesquisa));

  db_fieldsmemory($result, 0);

  $sCamposPortariaTipo = "h30_sequencial, h42_descr";
  $sWherePortariaTipo  = "h30_tipoasse = {$h12_codigo}";

  $sSqlPortariaTipo = $clportariatipo->sql_query_func(null, $sCamposPortariaTipo, "h42_sequencial", $sWherePortariaTipo);
  $res_portariatipo = $clportariatipo->sql_record($sSqlPortariaTipo);

  if ($clportariatipo->numrows > 0) {

    db_fieldsmemory($res_portariatipo,0);

    // Consulta Modelo de Portaria Individual
    $sSqlConsultaModIndividual = $clportariatipodocindividual->sql_query(
      null,
      "h37_modportariaindividual, db63_nomerelatorio as descrModIndividual",
      null,
      " h37_portariatipo = {$h30_sequencial}"
    );
    $rsConsultaModIndividual = $clportariatipodocindividual->sql_record($sSqlConsultaModIndividual);

    if ($clportariatipodocindividual->numrows > 0) {

      db_fieldsmemory($rsConsultaModIndividual, 0);
      $descrModIndividual = $descrmodindividual;
    }

    // Consulta Modelo de Portaria Coletiva
    $sSqlConsultaModColetiva = $clportariatipodoccoletiva->sql_query(
      null,
      "h38_modportariacoletiva, db63_nomerelatorio as descrModColetiva",
      null,
      "h38_portariatipo = {$h30_sequencial} "
    );
    $rsConsultaModColetiva = $clportariatipodoccoletiva->sql_record($sSqlConsultaModColetiva);

    if ($clportariatipodoccoletiva->numrows > 0) {

      db_fieldsmemory($rsConsultaModColetiva, 0);
      $descrModColetiva = $descrmodcoletiva;
    }
  }

  $oDaoTipoassedb_cadattdinamico = new cl_tipoassedb_cadattdinamico();
  $sSqlTipoAsseCadDinamico       = $oDaoTipoassedb_cadattdinamico->sql_query(
    null,
    null,
    "h79_db_cadattdinamico",
    null,
    "h79_tipoasse = {$h12_codigo}"
  );

  $rsTipoAsseCadDinamico = db_query($sSqlTipoAsseCadDinamico);

  if (pg_num_rows($rsTipoAsseCadDinamico) > 0) {
    db_fieldsmemory($rsTipoAsseCadDinamico, 0);
  }

  /**
   * Verica se o tipo de assentamento possui algum assentamento vinculado,
   * se possuir criamos a variavel $lAssentamentoVinculado, para avisar o usuario no momento
   * que ele for efetuar a manutenção de Campos dinamicos
   */
  $oDaoAssenta = new cl_assenta();
  $sSqlAssenta = $oDaoAssenta->sql_query_file(null, "h16_codigo", null, "h16_assent = {$h12_codigo}");
  $rsAssenta   = db_query($sSqlAssenta);

  if (pg_num_rows($rsAssenta) > 0) {
    $lAssentamentoVinculado = true;
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
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <?php db_app::load("AjaxRequest.js"); ?>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
  <table width="790" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
        <center>
          <?php
          include(modification("forms/db_frmtipoasse.php"));
          ?>
        </center>
      </td>
    </tr>
  </table>
</center>
<?php
db_menu();
?>
</body>
</html>
<?php
if(isset($alterar)) {

  if($cltipoasse->erro_status == "0") {

    $cltipoasse->erro(true, false);
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if($cltipoasse->erro_campo != "") {

      echo "<script> document.form1.".$cltipoasse->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltipoasse->erro_campo.".focus();</script>";
    }
  } else {
    $cltipoasse->erro(true, true);
  }
}

if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
  js_tabulacaoforms("form1","h12_assent",true,1,"h12_assent",true);
</script>