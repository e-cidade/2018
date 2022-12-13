<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$clsau_tipoficha = new cl_sau_tipoficha;
$clsau_tipoficha->rotulo->label("sd101_i_codigo");
$clsau_tipoficha->rotulo->label("sd101_c_descr");
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
            <td nowrap title="<?=$Tsd101_i_codigo?>">
              <label for="chave_sd101_i_codigo">Código:</label>
            </td>
            <td nowrap>
              <?php
              db_input("sd101_i_codigo", 10, $Isd101_i_codigo, true, "text", 4, "", "chave_sd101_i_codigo");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=$Tsd101_c_descr?>">
              <label for="chave_sd101_c_descr">Descrição:</label>
            </td>
            <td nowrap>
              <?php
              db_input("sd101_c_descr", 30, $Isd101_c_descr, true, "text", 4, "", "chave_sd101_c_descr");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_sau_tipoficha.hide();">

    </form>
  </div>

  <div class="container">
    <table>
      <tr>
        <td align="center" valign="top">
          <?php

          $aWhere = array();

          if(!isset($pesquisa_chave)) {

            if(isset($campos) == false) {

              $campos = "sau_tipoficha.*";

              if(file_exists("funcoes/db_func_sau_tipoficha.php") == true) {
                include(modification("funcoes/db_func_sau_tipoficha.php"));
              }
            }

            if(isset($chave_sd101_i_codigo) && (trim($chave_sd101_i_codigo) != "")) {
              $aWhere[] = "sd101_i_codigo = {$chave_sd101_i_codigo}";
            }

            if(isset($chave_sd101_c_descr) && (trim($chave_sd101_c_descr) != "")) {
              $aWhere[] = "sd101_c_descr ilike '%{$chave_sd101_c_descr}%'";
            }

            $sql     = $clsau_tipoficha->sql_query( "", $campos, "sd101_i_codigo", implode(' AND ', $aWhere));
            $repassa = array();

            if(isset($chave_sd101_i_codigo)) {
              $repassa = array("chave_sd101_i_codigo" => $chave_sd101_i_codigo);
            }

            db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
          } else {

            if($pesquisa_chave != null && $pesquisa_chave != "") {

              $result = $clsau_tipoficha->sql_record($clsau_tipoficha->sql_query($pesquisa_chave));

              if($clsau_tipoficha->numrows != 0) {

                db_fieldsmemory($result, 0);
                echo "<script>".$funcao_js."('$sd101_i_codigo',false);</script>";
              } else {
                echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
              }
            } else {
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
js_tabulacaoforms("form2","chave_sd101_i_codigo",true,1,"chave_sd101_i_codigo",true);

(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
