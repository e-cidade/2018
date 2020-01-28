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

$la22_d_data_ini_dia = '';
$la22_d_data_ini_mes = '';
$la22_d_data_ini_ano = '';
$la22_d_data_fim_dia = '';
$la22_d_data_fim_mes = '';
$la22_d_data_fim_ano = '';

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($_POST);
db_postmemory($_GET);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$cllab_requisicao = new cl_lab_requisicao;
$cllab_requisicao->rotulo->label("la22_i_codigo");

$clrotulo = new rotulocampo;
$clrotulo->label("z01_v_nome");
$clrotulo->label("la22_d_data");
$clrotulo->label("z01_i_cgsund");
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor=#CCCCCC>
  <div class="container">
    <form name="form2" method="post" action="" class="form-container">
      <fieldset>
        <legend>Filtros</legend>
        <table>
          <tr>
            <td nowrap title="<?=$Tla22_i_codigo?>">
              <label class="bold">Requisição:</label>
            </td>
            <td nowrap>
                <?php
                db_input("la22_i_codigo", 10, $Ila22_i_codigo, true, "text", 4, "", "la22_i_codigo");
                ?>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold">CGS:</label>
            </td>
            <td>
                <?php
                db_input("z01_i_cgsund", 40, $Iz01_i_cgsund, true, "text", 4, "", "chave_z01_i_cgsund");
                ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=$Tz01_v_nome?>">
                <?=$Lz01_v_nome?>
            </td>
            <td nowrap>
                <?php
                db_input("z01_v_nome", 40, $Iz01_v_nome, true, "text", 4, "", "chave_z01_v_nome");
                ?>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <b>Data inicial:</b>
            </td>
            <td nowrap>
                <?php
                db_inputdata(
                    'la22_d_data_ini',
                    $la22_d_data_ini_dia,
                    $la22_d_data_ini_mes,
                    $la22_d_data_ini_ano,
                    true,
                    'text',
                    1
                );
                ?>
            </td>
          </tr>
          <tr>
            <td nowrap>
              <b>Data fim:</b>
            </td>
            <td nowrap>
                <?php
                db_inputdata(
                    'la22_d_data_fim',
                    $la22_d_data_fim_dia,
                    $la22_d_data_fim_mes,
                    $la22_d_data_fim_ano,
                    true,
                    'text',
                    1
                );
                ?>
            </td>
          </tr>
        </table>

      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar">
      <input name="limpar"    type="reset"  id="limpar"    value="Limpar" >
      <input name="Fechar"    type="button" id="fechar"    value="Fechar" onClick="parent.db_iframe_lab_requisicao.hide();">
    </form>
  </div>
  <div class="container">
    <table>
      <tr>
        <td>
            <?php
            if (!isset($pesquisa_chave)) {
                if (isset($campos) == false) {
                    $campos = "la22_i_codigo, z01_i_cgsund, z01_v_nome, la22_d_data, la22_c_hora";
                }

                $aWhere = array();
                $where    = "";
                $sep      = "";
                $lCarrega = true;

                if (isset($la22_d_data_ini) && ($la22_d_data_ini != "")) {
                    $aDat = explode("/", $la22_d_data_ini);
                    $aWhere[] = " la22_d_data >= '".$aDat[2]."-".$aDat[1]."-".$aDat[0]."' ";
                }

                if (isset($autoriza)) {
                    $aWhere[] = " la22_i_autoriza = {$autoriza} ";
                }

                if (isset($la22_d_data_fim) && ($la22_d_data_fim != "")) {
                    $aDat = explode("/", $la22_d_data_fim);
                    $aWhere[] = " la22_d_data <= '".$aDat[2]."-".$aDat[1]."-".$aDat[0]."' ";
                }


                if (isset($iLaboratorioLogado)) {
                    $sWhereRequiItem = "";
                    if (isset($lSomenteConferidos)) {
                        $sWhereRequiItem = " and la21_c_situacao = '" . RequisicaoExame::CONFERIDO . "' ";
                    }

                    $sWhereAutorizados = "";
                    if (isset($lSomenteNaoDigitados)) {
                        $sWhereAutorizados = " and la21_c_situacao = '" . RequisicaoExame::NAO_DIGITADO . "' ";
                    }

                    $where .= " EXISTS( select 1 ";
                    $where .= "           from lab_requiitem ";
                    $where .= "                inner join lab_setorexame on lab_setorexame.la09_i_codigo = lab_requiitem.la21_i_setorexame";
                    $where .= "                inner join lab_labsetor   on lab_labsetor.la24_i_codigo   = lab_setorexame.la09_i_labsetor";
                    $where .= "          where lab_requiitem.la21_i_requisicao = lab_requisicao.la22_i_codigo";
                    $where .= "            and la24_i_laboratorio = {$iLaboratorioLogado} {$sWhereRequiItem} {$sWhereAutorizados} ) ";

                    $aWhere[] = $where;
                }

                if (isset($iDepResitante)) {
                    $lCarrega = false;
                    $aWhere[] = " la22_i_departamento = {$iDepResitante} ";
                }

                if (isset($iCgs) && !empty($iCgs)) {
                    $aWhere[] = " la22_i_cgs = {$iCgs} ";
                }

                if (isset($la22_i_codigo) && (trim($la22_i_codigo) != "")) {
                    $aWhere[] = " la22_i_codigo = {$la22_i_codigo} ";
                } else if (isset($chave_z01_v_nome) && (trim($chave_z01_v_nome) != "")) {
                    $aWhere[] = " z01_v_nome like '{$chave_z01_v_nome}%' ";
                } else if (isset($chave_z01_i_cgsund) && (trim($chave_z01_i_cgsund) != "")) {
                    $aWhere[] = " z01_i_cgsund = {$chave_z01_i_cgsund} ";
                }

                $where = implode(" and ", $aWhere);
                $sql = $cllab_requisicao->sql_query("", $campos, "z01_v_nome", $where);

                // Na rotina de requisição de exames, só carrega as requisições após informar um filtro
                if (!$lCarrega && (empty($la22_i_codigo) && empty($chave_z01_v_nome) && empty($chave_z01_i_cgsund) && empty($la22_d_data_ini) && empty($la22_d_data_fim))) {
                    $sql = "";
                }

                $repassa = array();
                if (isset($la22_i_codigo)) {
                    $repassa = array( "la22_i_codigo" => $la22_i_codigo, "chave_z01_v_nome" => $chave_z01_v_nome );
                }

                if (!empty($sql)) {
                    db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
                }
            } else {
                if ($pesquisa_chave != null && $pesquisa_chave != "") {
                    $sWhere = "la22_i_codigo = {$pesquisa_chave}";

                    if (isset($iCgs) && !empty($iCgs)) {
                        $sWhere .= " AND la22_i_cgs = {$iCgs} ";
                    }

                    if (isset($iLaboratorioLogado)) {
                        $sWhereRequiItem = "";
                        if (isset($lSomenteConferidos)) {
                            $sWhereRequiItem = " and la21_c_situacao = '" . RequisicaoExame::CONFERIDO . "' ";
                        }

                        $sWhereAutorizados = "";
                        if (isset($lSomenteNaoDigitados)) {
                            $sWhereAutorizados = " and la21_c_situacao = '" . RequisicaoExame::NAO_DIGITADO . "' ";
                        }

                        $sWhere .= " AND EXISTS( select 1 ";
                        $sWhere .= "               from lab_requiitem ";
                        $sWhere .= "                    inner join lab_setorexame on lab_setorexame.la09_i_codigo = lab_requiitem.la21_i_setorexame";
                        $sWhere .= "                    inner join lab_labsetor   on lab_labsetor.la24_i_codigo   = lab_setorexame.la09_i_labsetor";
                        $sWhere .= "              where lab_requiitem.la21_i_requisicao = lab_requisicao.la22_i_codigo";
                        $sWhere .= "                and la24_i_laboratorio = {$iLaboratorioLogado} {$sWhereRequiItem} {$sWhereAutorizados} ) ";
                    }

                    $sCampos           = "z01_v_nome, z01_i_cgsund";
                    $sSqlLabRequisicao = $cllab_requisicao->sql_query(null, $sCampos, null, $sWhere);
                    $result            = $cllab_requisicao->sql_record($sSqlLabRequisicao);
                    if ($cllab_requisicao->numrows != 0) {
                        db_fieldsmemory($result, 0);
                        echo "<script>".$funcao_js."('{$z01_v_nome}',false, '{$z01_i_cgsund}');</script>";
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
$('la22_i_codigo').className      = "field-size2";
$('la22_d_data_ini').className    = "field-size2";
$('la22_d_data_fim').className    = "field-size2";
$('chave_z01_v_nome').className   = "field-size9";
$('chave_z01_i_cgsund').className = "field-size2";

js_tabulacaoforms( "form2", "la22_i_codigo", true, 1, "la22_i_codigo", true );
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
