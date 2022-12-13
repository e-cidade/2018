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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$oRegraCalculoCargaHorariaDao = new cl_regracalculocargahoraria();

?>

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <script type="text/javascript" src="scripts/strings.js"></script>
</head>
<body>
  <div class="container">
    <form action="" method="post" name="form1" id="form1">
      <fieldset>
        <legend>Filtros</legend>
        <table>
          <tr>
            <td>
              <label class="bold">Ano:</label>
            </td>
            <td>
              <input type="text" name="ed127_ano" id="ed127_ano" maxlength="4" class="field-size2"/>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar">
      <input name="limpar"    type="button" id="limpar"    value="Limpar" onClick="limparCampos();">
      <input name="Fechar"    type="button" id="fechar"    value="Fechar" onClick="parent.db_iframe_calculocargahoraria.hide();">
    </form>
  </div>

  <div class="container">
    <table>
    <tr>
        <td>
        <?php
            $iEscola = db_getsession('DB_coddepto');
            $sSql    = '';
            $sWhere  = "ed127_escola = {$iEscola}";
            $sCampos = "ed127_codigo, ed127_ano, ";
            $sCampos .= " case
                            when ed127_calculaduracaoperiodo is true
                              then '( Aulas Dadas x Duração do Período ) / 60'::varchar
                            else 'Soma aulas dadas / Dias letivos'::varchar
                          end as \"dl_Forma_Calculo\", ";
            $sCampos .= " ed127_escola";

            if( isset( $ed127_ano ) && trim( $ed127_ano ) != "" ) {
              $sWhere  .= " and ed127_ano = {$ed127_ano} ";
            }

            $sSql = $oRegraCalculoCargaHorariaDao->sql_query_file( null, $sCampos, "ed127_ano desc", $sWhere );
            db_lovrot( $sSql, 15, "()", "", $funcao_js, "", "NoMe" );
          ?>
    </table>
  </div>
</body>
</html>
<script>

$('pesquisar').onclick = function() {

  if ( !js_ValidaCampos( $('ed127_ano'), 1, 'Ano', true, false) ) {
    return;
  }

  $('form1').submit();
}


function limparCampos() {

  $('ed127_ano').value = '';
  document.form1.submit();
}

document.body.onload = function() {
  $('ed127_ano').focus();
};
</script>
