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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oDaoCadEnderBairroCadEnderRua = new cl_cadenderbairrocadenderrua();
$oDaoCadEnderEstado            = new cl_cadenderestado();
$oPost                         = db_utils::postMemory($_POST);
$oGet                          = db_utils::postMemory($_GET);

$oRotulo                       = new rotulocampo();
$oRotulo->label("db73_descricao");
$oRotulo->label("db74_descricao");
$oRotulo->label("db71_descricao");
$oRotulo->label("db72_descricao");
$oRotulo->label("tre09_linhatransporte");

$oDadosInstituicao = db_stdClass::getDadosInstit();
$iCodigoBrasil     = 1;

/**
 * Busca os municípios
 */
$sSqlEstados       = $oDaoCadEnderEstado->sql_query_file(null, "*", "db71_descricao",
                                                         "db71_cadenderpais = {$iCodigoBrasil}"
                                                        );
$rsEstados       = $oDaoCadEnderEstado->sql_record($sSqlEstados);
$aEstados        = array();
$aDadosEstados   = db_utils::getCollectionByRecord($rsEstados);
$iCodigoEstado   = '';

foreach ($aDadosEstados as $oEstado) {

  $aEstados[$oEstado->db71_sequencial] = $oEstado->db71_descricao;
  if ($oEstado->db71_sigla  == $oDadosInstituicao->uf) {
    $iCodigoEstado = $oEstado->db71_sequencial;
  }
}

/**
 * Busca as linhas que possuem itinerário
 */
$oDaoItinerario = new cl_itinerariologradouro;
$sSqlItinerario = $oDaoItinerario->sql_query(null, "distinct tre06_sequencial, tre06_nome", 'tre06_nome');
$rsItinerario   = db_query($sSqlItinerario);

if ( !$rsItinerario ) {
  db_redireciona('db_erros.php?db_erro=Erro ao buscar Itinerário!');
}
$aLinhas = array('' => 'Selecione');
if ( pg_num_rows($rsItinerario) > 0 ) {

  $iLinhas = pg_num_rows($rsItinerario);
  for( $i = 0; $i < $iLinhas; $i++ ) {

    $oDados = db_utils::fieldsMemory($rsItinerario, $i );
    $aLinhas[$oDados->tre06_sequencial] = $oDados->tre06_nome;
  }
}

/**
 * Define os valores nos filtros
 */
if (!isset($oPost->db71_sequencial)) {
  $db71_sequencial = $iCodigoEstado;
}

if (!empty($oPost->db71_sequencial)) {
  $db71_sequencial = $oPost->db71_sequencial;
}

if (!isset($oPost->chave_db72_descricao)) {
  $chave_db72_descricao = $oDadosInstituicao->munic;
}

if ( !empty($oPost->tre06_sequencial) ) {
  $tre09_linhatransporte = $oPost->tre06_sequencial;
}

?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <?php
    db_app::load("scripts.js, prototype.js, strings.js, AjaxRequest.js");
  ?>
</head>
<body >

  <form name="form2" method="post" action="" class="container">

    <fieldset>
      <legend>Pesquisa Logradouros</legend>
      <fieldset class="separator">
        <legend>Pesquisa Logradouros da Linha Informada</legend>
        <table class="form-container">
          <tr>
            <td class="field-size2"><label for="tre09_linhatransporte">Linha:</label></td>
            <td>
              <?php
                db_select('tre09_linhatransporte', $aLinhas, true, 1, 'onchange="validaLinhaSeleceionada()"');
              ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <fieldset class="separator">
        <legend>Pesquisa no Cadastro de Logradouros</legend>
        <table class="form-container">
          <tr>
            <td class="field-size2" title="<?=$Tdb71_descricao?>">
              <label for="db71_sequencial">Estado:</label>
            </td>
            <td>
              <?php
                db_select('db71_sequencial', $aEstados, true, 1);
              ?>
            </td>
          </tr>
          <tr>
            <td title="<?=$Tdb72_descricao?>">
              <label for="chave_db72_descricao">Município:</label>
            </td>
            <td align="left">
              <?php
                db_input("db72_descricao", 50, $Idb72_descricao, true, "text", 4, "", "chave_db72_descricao");
              ?>
            </td>
          </tr>
          <tr>
            <td title="<?=$Tdb73_descricao?>">
              <label for="chave_db73_descricao">Bairro:</label>
            </td>
            <td>
              <?php
                db_input("db73_descricao", 50, $Idb73_descricao, true, "text", 4, "", "chave_db73_descricao");
              ?>
            </td>
          </tr>
          <tr>
            <td title="<?=$Tdb74_descricao?>">
              <label for="chave_db74_descricao">Logradouro:</label>
            </td>
            <td >
              <?php
                db_input("db74_descricao", 50, $Idb74_descricao, true, "text", 4, "", "chave_db74_descricao");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </fieldset>
    <br />
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_bairrologradouro.hide();">
  </form>
  <?php

    /**
     * Campos de retorno
     */
    $sCamposBairroLogradouro  = "db87_sequencial, db73_descricao, db74_descricao, db87_cadenderrua, db71_descricao";
    $sCamposBairroLogradouro .= ", db72_descricao";
    $aWhereBairroLogradouro   = array();

    /**
     * Caso tenha sido setado o codigo do municipio, buscamos apenas os logradouros e bairros vinculados ao
     * municipio informado
     */
    if (isset($oGet->iMunicipio) && !empty($oGet->iMunicipio)) {

      $aWhereBairroLogradouro[] = "db73_cadendermunicipio = {$oGet->iMunicipio}";
      $aWhereBairroLogradouro[] = "db74_cadendermunicipio = {$oGet->iMunicipio}";
    }

    if (!isset($pesquisa_chave)) {

      if (isset($chave_db73_descricao) && !empty($chave_db73_descricao)) {
        $aWhereBairroLogradouro[] = "db73_descricao ilike '{$chave_db73_descricao}%'";
      }

      if (isset($chave_db74_descricao) && !empty($chave_db74_descricao)) {
        $aWhereBairroLogradouro[] = "db74_descricao ilike '{$chave_db74_descricao}%'";
      }

      if (isset($chave_db72_descricao) && !empty($chave_db72_descricao)) {
        $aWhereBairroLogradouro[] = "db72_descricao ilike '{$chave_db72_descricao}%'";
      }

      if (!empty($db71_sequencial)) {
        $aWhereBairroLogradouro[] = "db71_sequencial = {$db71_sequencial}";
      }

      $sWhereBairroLogradouro  = implode(" and ", $aWhereBairroLogradouro);

      /**
       * Sempre que selecionado uma Linha como filtro, os outros filtros informados são descartados e é buscado todos
       * logradouros presentes na linha informada
       */
      if ( !empty($tre09_linhatransporte)) {

        $sWhereBairroLogradouro  = " db87_sequencial in ( ";
        $sWhereBairroLogradouro .= "  select tre10_cadenderbairrocadenderrua ";
        $sWhereBairroLogradouro .= "    from itinerariologradouro ";
        $sWhereBairroLogradouro .= "    join linhatransporteitinerario on  tre09_sequencial = tre10_linhatransporteitinerario ";
        $sWhereBairroLogradouro .= "   where tre09_linhatransporte = {$oPost->tre09_linhatransporte} ";
        $sWhereBairroLogradouro .= ") ";
      }

      $sSqlBairroLogradouro    = $oDaoCadEnderBairroCadEnderRua->sql_query_completa(
                                                                                     null,
                                                                                     $sCamposBairroLogradouro,
                                                                                     null,
                                                                                     $sWhereBairroLogradouro
                                                                                   );

      $repassa = array();
      if (isset($chave_db73_descricao)) {

        $repassa = array(
          'tre09_linhatransporte' => $tre09_linhatransporte,
          'db71_sequencial'       => $db71_sequencial,
          'chave_db72_descricao'  => $chave_db72_descricao,
          'chave_db73_descricao'  => $chave_db73_descricao,
          'chave_db74_descricao'  => $chave_db74_descricao
        );
      }

      echo '<div class="container">';
      echo '  <fieldset>';
      echo '    <legend>Resultado da Pesquisa</legend>';
        db_lovrot($sSqlBairroLogradouro,15,"()","",$funcao_js,"","NoMe",$repassa);
      echo '  </fieldset>';
      echo '</div>';
    } else {

      if ($pesquisa_chave != null && $pesquisa_chave != "") {

        $aWhereBairroLogradouro[] = "db87_sequencial = {$pesquisa_chave}";
        $sWhereBairroLogradouro   = implode(" and ", $aWhereBairroLogradouro);

        $sSqlBairroLogradouro = $oDaoCadEnderBairroCadEnderRua->sql_query_completa(
                                                                                    null,
                                                                                    $sCamposBairroLogradouro,
                                                                                    "db87_sequencial",
                                                                                    $sWhereBairroLogradouro
                                                                                  );
        $rsBairroLogradouro = $oDaoCadEnderBairroCadEnderRua->sql_record($sSqlBairroLogradouro);

        if ($oDaoCadEnderBairroCadEnderRua->numrows > 0) {

          db_fieldsmemory($rsBairroLogradouro, 0);
          echo "<script>".$funcao_js."(false,
                                       '$db87_sequencial',
                                       '$db73_descricao',
                                       '$db74_descricao',
                                       '$db87_cadenderrua',
                                       '$db71_descricao',
                                       '$db72_descricao'
                                      );</script>";
        } else {
          echo "<script>".$funcao_js."(true, 'Chave(".$pesquisa_chave.") não Encontrado');</script>";
        }
      } else {
        echo "<script>".$funcao_js."(true, '');</script>";
      }
    }
  ?>

</body>
</html>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();

function validaLinhaSeleceionada() {

  $('db71_sequencial').removeAttribute('disabled');
  $('chave_db72_descricao').removeAttribute('disabled');
  $('chave_db73_descricao').removeAttribute('disabled');
  $('chave_db74_descricao').removeAttribute('disabled');
  if ($F('tre09_linhatransporte') != '') {

    $('db71_sequencial').setAttribute('disabled', 'disabled');
    $('chave_db72_descricao').setAttribute('disabled', 'disabled');
    $('chave_db73_descricao').setAttribute('disabled', 'disabled');
    $('chave_db74_descricao').setAttribute('disabled', 'disabled');
  }
}

validaLinhaSeleceionada();
</script>