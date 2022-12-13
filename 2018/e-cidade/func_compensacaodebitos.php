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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));

$oGET          = db_utils::postMemory($_GET);
$oPOST         = db_utils::postMemory($_POST);
$iChave        = isset($oGET->pesquisa_chave) ? $oGET->pesquisa_chave : null;
$iCgm          = isset($oGET->cgm) ? $oGET->cgm : null;
$sSelecionados = isset($oGET->selecionados) ? $oGET->selecionados : null;
$iSequencial   = isset($oPOST->chave_codigo) ? $oPOST->chave_codigo : null;
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
            <label for="chave_codigo">Numpre:</label>
          </td>
          <td>
            <?php
            $Scodigo = 'Numpre';
            db_input("codigo", 10, 1, true, "text", 4, "", "chave_codigo")
            ?>
          </td>
        </tr>
      </table>

    </fieldset>

    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_compensacaodebitos.hide();">
  </form>

  <?php
  if ($iCgm) :

    $iInstituicao = db_getsession('DB_instit');
    $sCamposDebitos = implode(", ", array(
      "distinct arrecad.k00_numpre",
      "arrecad.k00_numpar",
      "arrecad.k00_tipo",
      "arrecad.k00_dtvenc",
      "arretipo.k00_descr",
      "arrecad.k00_numcgm",
      "sum((
            select (substr(fc_calcula,15,13)::float8 +
                     substr(fc_calcula,28,13)::float8 +
                     substr(fc_calcula,41,13)::float8 -
                     substr(fc_calcula,54,13)::float8) as total_calculado
            from (select fc_calcula(arrecad.k00_numpre, arrecad.k00_numpar, arrecad.k00_receit, current_date, current_date, 2016)) as calculo
          )) as k00_valor"
    ));

    $aWhereDebitos = array(
      "arrecad.k00_valor > 0"
    );

    if ($iSequencial) {
      $aWhereDebitos[] = "arrecad.k00_numpre = {$iSequencial}";
    }

    if ($iCgm) {
      $aWhereDebitos[] = "arrenumcgm.k00_numcgm = {$iCgm}";
    }

    /**
     * Não exibe na lookup débitos que já foram selecionados
     */
    if ($sSelecionados) {

      $aWhereSelecionados = array();
      $aDebitos = explode('|', $sSelecionados);
      foreach ($aDebitos as $sDebito) {

        list($iNumpre, $iNumpar) = explode('/', $sDebito);
        $aWhereSelecionados[] = "(arrecad.k00_numpre, arrecad.k00_numpar) <> ({$iNumpre}, {$iNumpar})";
      }
      $sWhereSelecionados = ' (' . implode(' and ', $aWhereSelecionados) . ') ';
      $aWhereDebitos[] = $sWhereSelecionados;
    }

    $sWhereDebitos = implode(' and ', $aWhereDebitos);

    $sGroupBy = implode(", ", array(
      "arrecad.k00_numpre",
      "arrecad.k00_numpar",
      "arrecad.k00_tipo",
      "arrecad.k00_dtvenc",
      "arrecad.k00_numcgm",
      "arretipo.k00_descr"
    ));

    $sSql = "select {$sCamposDebitos} ";
    $sSql .= " from arrenumcgm ";
    $sSql .= "    inner join arrecad    on arrecad.k00_numpre    = arrenumcgm.k00_numpre";
    $sSql .= "    inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre and arreinstit.k00_instit = {$iInstituicao}";
    $sSql .= "    inner join arretipo   on arretipo.k00_tipo     = arrecad.k00_tipo";
    $sSql .= "    inner join tabrec     on k02_codigo            = arrecad.k00_receit";
    $sSql .= " where {$sWhereDebitos} ";
    $sSql .= " group by {$sGroupBy} ";
    $sSql .= " order by arrecad.k00_numpre, arrecad.k00_numpar, arrecad.k00_dtvenc";

    $aRepassa = array();
    if(isset($iSequencial)) :

      $aRepassa = array(
        "chave_codigo" => $iSequencial,
      );
    endif;
    ?>

    <div class="container">
      <fieldset>
        <legend>Resultado da Pesquisa</legend>
        <?php db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $aRepassa) ?>
      </fieldset>
    </div>

  <?php else : ?>
    <div class="container">
      <fieldset>
        <legend>Resultado da Pesquisa</legend>
        <p style="text-align: center;">É obrigatório informar um CGM.</p>
      </fieldset>
    </div>
  <?php endif ?>
</body>
</html>
