<?php
/**
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
include(modification("classes/db_especmedico_classe.php"));

db_postmemory( $_POST );
parse_str( $_SERVER["QUERY_STRING"] );

$clespecmedico = new cl_especmedico;
$clespecmedico->rotulo->label("sd27_i_codigo");

$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("sd04_i_medico");
$clrotulo->label("rh70_estrutural");
$clrotulo->label("rh70_descr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body class="body-default">
  <div class="container">
    <form name="form2" method="post" action="">
      <fieldset>
        <legend>Filtros</legend>
        <table class="form-container">
          <tr>
            <td title="<?=$Trh70_estrutural?>">
              <label for="rh70_estrutural">
                <?=$Lrh70_estrutural?>
              </label>
            </td>
            <td>
              <?php
              db_input("rh70_estrutural", 10, $Irh70_estrutural, true, "text", 4, "", "chave_rh70_estrutural");
              ?>
            </td>
          </tr>
          <tr>
            <td title="<?=$Tz01_nome?>">
              <label for="z01_nome">
                <?=$Lz01_nome?>
              </label>
            </td>
            <td>
              <?php
              db_input("z01_nome", 60, $Iz01_nome, true, "text", 4, "", "chave_z01_nome");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar"    type="reset"  id="limpar"     value="Limpar">
      <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_cboups.hide();">
    </form>
  </div>
  <div class="container">
    <table>
      <tr>
        <td>
          <?php
          $aWhere   = array();
          $aWhere[] = "sd04_c_situacao = 'A' and sd27_c_situacao = 'A'";
          $aWhere[] = "exists ( select * from undmedhorario where sd30_i_undmed = sd27_i_codigo )";
          
          if(isset($chave_sd04_i_unidade)) {
            $aWhere[] = "sd04_i_unidade = {$chave_sd04_i_unidade}";
          }

          if(isset($chave_sd04_i_medico) && (int)$chave_sd04_i_medico != 0) {
            $aWhere[] = "sd04_i_medico = {$chave_sd04_i_medico}";
          }

          if(isset($chave_rh70_estrutural) && (trim($chave_rh70_estrutural) != "")) {
            $aWhere[] = "rh70_estrutural = '{$chave_rh70_estrutural}'";
          }

          if(!isset($pesquisa_chave)) {

            $campos = "sd03_i_codigo, a.z01_nome, sd27_i_codigo, rh70_descr";

            if(isset($chave_z01_nome) && trim($chave_z01_nome) != "") {
              $aWhere[] = "a.z01_nome ilike '{$chave_z01_nome}%'";
            }

            $repassa = array();
            if(isset($chave_sd27_i_codigo)) {
              $repassa = array("chave_sd27_i_codigo" => $chave_sd27_i_codigo);
            }

            $sWhere = implode(' AND ', $aWhere);
            $sSql   = $clespecmedico->sql_query("", $campos, "", $sWhere);

            db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
          } else {

            if($pesquisa_chave != null && $pesquisa_chave != "") {

              $aWhere[] = "sd03_i_codigo = {$pesquisa_chave}";
              $sWhere   = implode(' AND ', $aWhere);
              $sSql     = $clespecmedico->sql_query(null, "*", null, $sWhere);
              $result   = $clespecmedico->sql_record($sSql);

              if($clespecmedico->numrows != 0) {

                db_fieldsmemory($result,0);
                echo "<script>".$funcao_js."(false, '$sd03_i_codigo', '$z01_nome', '$sd27_i_codigo');</script>";
              } else {
                echo "<script>".$funcao_js."(true, 'Chave(".$pesquisa_chave.") não Encontrado');</script>";
              }
            }else{
              echo "<script>".$funcao_js."('',false);</script>";
            }
          }
          ?>
        </td>
      </tr>
    </table>
  </div>
</body>
</html>
<script>
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
