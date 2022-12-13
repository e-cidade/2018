<?
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
require_once("classes/db_veicmanut_classe.php");
require_once("classes/db_veiccadcentraldepart_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clveicmanut            = new cl_veicmanut;
$clveiccadcentraldepart = new cl_veiccadcentraldepart;

$clveicmanut->rotulo->label("ve62_codigo");
$clveiccadcentraldepart->rotulo->label("ve37_veiccadcentral");
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  </head>
<body class="body-default">
  <div class="container">
    <form name="form1" method="post" action="" >
      <fieldset>
        <legend>Filtros da Pesquisa</legend>
        <table  border="0" align="center" cellspacing="0">
          <tr>
            <td align="right" nowrap>
              <label class="bold" for="ve62_codigo">Código:</label>
            </td>
            <td align="left" nowrap>
            <?php
              $Sve62_codigo = "Código";
              db_input("ve62_codigo",10,$Ive62_codigo,true,"text",4,"","chave_ve62_codigo");
            ?>
            </td>
          </tr>
          <tr>
            <td align="right" nowrap>
              <label class="bold" for="numero">Número:</label>
            </td>
            <td align="left" nowrap>
            <?php db_input("numero",10,3,true,"text",4,"","chave_numero"); ?>
            </td>
          </tr>
          <tr>
            <td align="right" nowrap>
              <label class="bold" for="situacao">Situação:</label>
            </td>
            <td align="left" nowrap>
              <?php
                $aSituacoes = array(
                    0 => "Todas",
                    VeiculoManutencao::SITUACAO_PENDENTE => "Pendente",
                    VeiculoManutencao::SITUACAO_REALIZADO => "Realizado"
                  );

                db_select("chave_situacao", $aSituacoes, true, 1);
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar">
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_veicmanut.hide();">
    </form>
  </div>
  <div class="subcontainer">
    <fieldset>
      <legend>Resultado da Pesquisa</legend>
        <?php

        $iAnoUsu = db_getsession("DB_anousu");
        $aWhere  = array();

        //Busca pelos filtros da tela.
        if (!isset($pesquisa_chave) && !isset($pesquisa_chave_numero)) {

          if (isset($campos) == false) {

            if(file_exists("funcoes/db_func_veicmanut.php") == true) {
              include("funcoes/db_func_veicmanut.php");
            } else {
              $campos = "veicmanut.*";
            }
          }

          if (isset($chave_ve37_sequencial) && trim($chave_ve37_sequencial) != "" && $chave_ve37_sequencial != "0") {
            $dbwhere = " veiccadcentraldepart.ve37_sequencial = {$chave_ve37_sequencial} ";
          } else {

            $iDepartamento = db_getsession("DB_coddepto");
            $dbwhere = " (veiccadcentral.ve36_coddepto = {$iDepartamento} or veiccadcentraldepart.ve37_coddepto = {$iDepartamento}) ";
          }

          /**
           * Filtro por Número/Ano
           */
          if (!empty($chave_numero)) {

            $aNumero = explode('/', $chave_numero);

            if (count($aNumero) <= 2) {

              $iNumero = intval($aNumero[0]);
              $iAno = isset($aNumero[1]) ? intval($aNumero[1]) : null;

              if (!empty($iNumero)) {
                $dbwhere .= (!empty($dbwhere) ? " and " : '') . " ve62_numero = {$iNumero} ";
              }

              if (!empty($iAno)) {
                $dbwhere .= (!empty($dbwhere) ? " and " : '') . " ve62_anousu = {$iAno} ";
              }
            }
          }

          if (!empty($chave_situacao)) {
            $dbwhere .= (!empty($dbwhere) ? " and " : '') . " ve62_situacao = {$chave_situacao} ";
          }

          if (isset($chave_ve62_codigo) && (trim($chave_ve62_codigo) != "")) {
            $sql = $clveicmanut->sql_query(null, $campos, "ve62_codigo desc","ve62_codigo = {$chave_ve62_codigo} and {$dbwhere}");
          } else {
            $sql = $clveicmanut->sql_query("", $campos, "ve62_codigo desc", " {$dbwhere} ");
          }

          $repassa = array();
          if (isset($chave_ve62_codigo)) {
            $repassa = array("chave_ve62_codigo" => $chave_ve62_codigo, "chave_ve62_codigo" => $chave_ve62_codigo);
          }

          db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa, false);
        } else {

          //Busca pela chave sequencial
          if ($pesquisa_chave != null && $pesquisa_chave != "") {

            $result = $clveicmanut->sql_record($clveicmanut->sql_query($pesquisa_chave));
            if ($clveicmanut->numrows != 0) {

              db_fieldsmemory($result,0);
              echo "<script>".$funcao_js."('$ve62_codigo',false);</script>";
            } else {
              echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
            }
          //Busca pela "chave" numero/anousu.
          } else if ($pesquisa_chave_numero != null && $pesquisa_chave_numero != "") {

            $aChaveNumeroAno  = explode("/", $pesquisa_chave_numero);
            $sChaveNumero     = $aChaveNumeroAno[0];
            if (count($aChaveNumeroAno) > 1) {
              $iAnoUsu = $aChaveNumeroAno[1];
            }

            $sCampos   = "distinct ve62_codigo, ve62_numero||'/'||ve62_anousu as ve62_numero, ve62_descr";
            $aWhere[]  = " ve62_numero = {$sChaveNumero} and ve62_anousu = {$iAnoUsu} ";
            $sSql      = $clveicmanut->sql_query(null, $sCampos, "ve62_codigo desc", implode(' and ', $aWhere));

            $rsManutancao = $clveicmanut->sql_record($sSql);
            if ($clveicmanut->numrows != 0) {

              $oStdDados = db_utils::fieldsMemory($rsManutancao, 0);
              echo "<script>".$funcao_js."('$oStdDados->ve62_codigo', '$oStdDados->ve62_numero', '$oStdDados->ve62_descr', false);</script>";
            } else {
              echo "<script>".$funcao_js."('', '', 'Chave(".$pesquisa_chave_numero.") não Encontrado', true);</script>";
            }
          } else {
            echo "<script>".$funcao_js."('', '', '', false);</script>";
          }
        }
        ?>
    </fieldset>
  </div>
</body>
</html>
<?
if (!isset($pesquisa_chave)) {
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form1","chave_ve62_codigo",true,1,"chave_ve62_codigo",true);
</script>