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
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oPOST = (object) $_POST;
$oGET = (object) $_GET;

$iContrato = isset($oGET->filtro_contrato) ? $oGET->filtro_contrato : null;
$iCgm      = isset($oGET->filtro_cgm) ? $oGET->filtro_cgm : null;

$oDaoEconomia = new cl_aguacontratoeconomia;
$oDaoEconomia->rotulo->label("x38_sequencial");
$oDaoEconomia->rotulo->label("x38_sequencial");
?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
  <form name="form2" method="post" action="" class="container">
    <fieldset>
      <legend>Dados para Pesquisa</legend>
      <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
        <tr>
          <td>
            <label class="bold" for="x38_sequencial">Código:</label>
          </td>
          <td>
            <?php db_input("x38_sequencial", 10, $Ix38_sequencial, true, "text", 4, "", "chave_x38_sequencial"); ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_aguacontratoeconomia.hide();">
  </form>
  <?php

  if (!isset($campos)) {
    require_once(modification("funcoes/db_func_aguacontratoeconomia.php"));
  }

  $aWhere = array();
  if (isset($pesquisa_chave) || isset($chave_x38_sequencial)) {

    $iCodigo = $pesquisa_chave ? $pesquisa_chave : $chave_x38_sequencial;
    $aWhere[] = "x38_sequencial = {$iCodigo}";
  }

  if ($iContrato) {
    $aWhere[] = "x38_aguacontrato = {$iContrato}";
  }

  if ($iCgm) {
    $aWhere[] = "x38_cgm = {$iCgm}";
  }

  $sWhere = implode(' and ', $aWhere);
  $sOrder = "x38_sequencial";
  $sSql   = $oDaoEconomia->sql_query(null, $campos, $sOrder, $sWhere);

  if (!isset($pesquisa_chave)) {

    $aRepassa = array();
    if (isset($chave_x38_sequencial)) {

      $aRepassa = array(
        'chave_x38_sequencial' => $chave_x38_sequencial,
      );
    }

    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
    db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $aRepassa, $lAutomatico = false);
    echo '  </fieldset>';
    echo '</div>';
  } else {

    $rsResultado = db_query($sSql);
    if (pg_num_rows($rsResultado) > 0) {

      $oResultado = pg_fetch_object($rsResultado);
      echo "<script>" . $funcao_js . "({$oResultado->x38_sequencial}, '{$oResultado->z01_nome}', false);</script>";
    } else {
      echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado', true);</script>";
    }
  }
  ?>
  <script>
    js_tabulacaoforms("form2","chave_x38_sequencial",true,1,"chave_x38_sequencial",true);
  </script>
</body>
</html>
