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
include(modification("classes/db_ossoariojazigo_classe.php"));

db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clossoariojazigo = new cl_ossoariojazigo;
$clossoariojazigo->rotulo->label("cm25_i_codigo");
$clossoariojazigo->rotulo->label("cm25_c_numero");
$clossoariojazigo->rotulo->label("cm25_i_lotecemit");

?>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
      <tr>
        <td height="63" align="center" valign="top">
          <table width="35%" border="0" align="center" cellspacing="0">
            <form name="form2" method="post" action="" >
              <input type="hidden" name="tp" value="<?php echo $tp; ?>" >
              <tr>
                <td width="4%" align="right" nowrap title="<?php echo $Tcm25_i_codigo; ?>">
                  <strong>
                    <?php

                      if ($tipo == 'O') {
                        $Lcm25_i_codigo = "Código Ossário";
                      } else if($tipo == 'J') {
                        $Lcm25_i_codigo = "Código Jazigo";
                      }

                      echo $Lcm25_i_codigo;
                    ?>:
                  </strong>
                </td>
                <td width="96%" align="left" nowrap>
                  <?php
                    db_input("cm25_i_codigo", 10, $Icm25_i_codigo, true, "text", 4, "", "chave_cm25_i_codigo");
                  ?>
                </td>
              </tr>
  		        <tr>
                <td width="4%" align="right" nowrap title="<?php echo $Tcm25_c_numero; ?>">
                  <?php echo $Lcm25_c_numero; ?>
                </td>
                <td width="96%" align="left" nowrap>
                  <?php
                    db_input("cm25_c_numero", 10, $Icm25_c_numero, true, "text", 4, "", "chave_cm25_c_numero");
                  ?>
                </td>
              </tr>
              <tr>
                <td width="4%" align="right" nowrap>
                  <strong>Quadra:</strong>
                </td>
                <td width="96%" align="left" nowrap>
                  <?php
                    db_input("cm25_c_quadra", 10, $cm25_c_quadra, true, "text", 4, "", "chave_cm25_c_quadra");
                  ?>
                </td>
              </tr>
              <tr>
                <td width="4%" align="right" nowrap title="<?php echo $Tcm25_c_numero; ?>">
                  <strong>Lote</strong>
                </td>
                <td width="96%" align="left" nowrap>
                  <?php
                    db_input("cm25_i_lote", 10, $Icm25_i_lotecemit, true, "text", 4, "", "chave_cm25_i_lote");
                  ?>
                </td>
              </tr>
              <tr>
                <td width="4%" align="right" nowrap title="<?php echo $Tcm25_c_tipo; ?>">
                  <?php echo $Lcm25_c_tipo; ?>
                </td>
                <td>
                  <?php

                    if (empty($tipo)) {

                      $x = array('' => 'Selecione', 'O' => 'Ossário', 'J' => 'Jazigo');
                      db_select('cm25_c_tipo', $x, true, 1, "");
                    }
                  ?>
                </td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                  <input name="limpar" type="reset" id="limpar" value="Limpar" >
                  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_ossoariojazigo.hide();">
                </td>
              </tr>
            </form>
          </table>
        </td>
      </tr>
      <tr>
        <td align="center" valign="top">
          <?php
            if(!isset($pesquisa_chave)) {

              if(isset($campos) == false) {

                if(file_exists("funcoes/db_func_ossoariojazigo.php") == true) {
                  include(modification("funcoes/db_func_ossoariojazigo.php"));
                } else {
                  $campos = "ossoariojazigo.*";
                }
              }

              if ($tp == 1) {
                $where = " cm28_i_ossoariojazigo is null ";
              }

              if ($tipo == 'O' and $tp != 2) {
               $where = " cm28_i_ossoariojazigo is not null and cm25_c_tipo = 'O' ";
              } else if ($tipo == 'J' and $tp != 2) {
               $where = " cm28_i_ossoariojazigo is not null and cm25_c_tipo = 'J' ";
              } else if ($tipo == 'O' and $tp == 2) {
               $where = " cm28_i_ossoariojazigo is null and cm25_c_tipo = 'O' ";
              } else if ($tipo == 'J' and $tp == 2) {
               $where = " cm28_i_ossoariojazigo is null and cm25_c_tipo = 'J' ";
              }

              if($cemiterio != "") {

                if($where != "") {
                  $where .= " and cm22_i_cemiterio = $cemiterio";
                } else {
                  $where = " cm22_i_cemiterio = $cemiterio";
                }
              }

          		if(isset($cm25_c_tipo) && $cm25_c_tipo != "") {

                $sAnd = '';
                if(!empty($where)) {
                  $sAnd = ' and ';
                }

          		  $where .= " $sAnd cm25_c_tipo = '$cm25_c_tipo' ";
          		}

          		if(isset($chave_cm25_i_codigo) && (trim($chave_cm25_i_codigo) != "")) {
                $sql = $clossoariojazigo->sql_query($chave_cm25_i_codigo, $campos, "cm25_i_codigo", $where." and cm25_i_codigo = $chave_cm25_i_codigo");
          		} else if(isset($chave_cm25_c_numero) && (trim($chave_cm25_c_numero) != "")) {
                $sql = $clossoariojazigo->sql_query("", $campos, "cm25_c_numero", $where." and cm25_c_numero like '$chave_cm25_c_numero%'");
              } else if(isset($chave_cm25_c_quadra) && (trim($chave_cm25_c_quadra) != "")) {
                $sql = $clossoariojazigo->sql_query("", $campos, "cm22_c_quadra", $where." and cm22_c_quadra like '$chave_cm25_c_quadra%'");
              } else if(isset($chave_cm25_i_lote) && (trim($chave_cm25_i_lote) != "")) {
                $sql = $clossoariojazigo->sql_query("", $campos, "cm23_i_lotecemit", $where." and cm23_i_lotecemit like '$chave_cm25_i_lote%'");
              } else {
                $sql = $clossoariojazigo->sql_query("", $campos, "cm25_i_codigo", $where);
              }

      		    $repassa = array();

              if(isset($chave_cm25_i_codigo)) {
                $repassa = array("chave_cm25_i_codigo" => $chave_cm25_i_codigo, "chave_cm25_i_codigo" => $chave_cm25_i_codigo);
              }

              db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
            } else {

              if($pesquisa_chave != null && $pesquisa_chave != "") {

                $result = $clossoariojazigo->sql_record($clossoariojazigo->sql_query($pesquisa_chave));

                if($clossoariojazigo->numrows != 0) {

                  db_fieldsmemory($result, 0);
                  echo "<script>".$funcao_js."('$cm25_i_codigo', false);</script>";
                } else {
                  echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado', true);</script>";
                }
              } else {
                echo "<script>".$funcao_js."('', false);</script>";
              }
            }
          ?>
         </td>
       </tr>
    </table>
  </body>
</html>
<script>
js_tabulacaoforms("form2", "chave_cm25_i_codigo", true, 1, "chave_cm25_i_codigo", true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
