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
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);

$oRotulo = new rotulocampo();
$oRotulo->label("ve13_sequencial");
$oRotulo->label("ve01_placa");
$oRotulo->label("ve05_numcgm");

$oDaoAutorizacao = new cl_autorizacaocirculacaoveiculo();
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body class="body-default">
<div class="container">
  <table>
    <tr>
      <td>
        <table width="25%" border="0" align="center" cellspacing="0">
          <form name="form1" method="post" action="" >
            <tr>
              <td><label for="chave_ve13_sequencial"><?=$Lve13_sequencial?></label></td>
              <td>
                <?php
                db_input('ve13_sequencial', 10, $Ive13_sequencial, true, "text", 4, "", "chave_ve13_sequencial");
                ?>
              </td>
            </tr>
            <tr>
              <td><label for="chave_ve01_placa"><?=$Lve01_placa?></label></td>
              <td>
                <?php
                db_input('ve01_placa', 10, $Ive01_placa, true, "text", 4, "", "chave_ve01_placa");
                ?>
              </td>
            </tr>
            <tr>
              <td><label for="chave_motorista"><?=$Lve05_numcgm?></label></td>
              <td>
                <?php
                db_input('motorista', 20, false, true, "text", 4, "", "chave_motorista");
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar">
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_autorizacaocirculacaoveiculo.hide();">
              </td>
            </tr>
          </form>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" valign="top">
        <?php

        $iCodigoInstituicao  = db_getsession('DB_instit');
        $iCodigoDepartamento = db_getsession('DB_coddepto');

        $sOrdem   = "ve13_sequencial";
        $aWhere   = array();
        $aWhere[] = " ve13_instituicao  = {$iCodigoInstituicao}  ";
        $aWhere[] = " ve13_departamento = {$iCodigoDepartamento} ";

        if (!isset($pesquisa_chave)) {

          $sCampos  = " ve13_sequencial, ve01_placa, b.z01_nome as dl_Motorista, ve13_datainicial, ve13_datafinal, ve13_dataemissao, ve13_observacao ";

          if (isset($chave_ve13_sequencial) && !empty($chave_ve13_sequencial)) {
            $aWhere[] = " ve13_sequencial = {$chave_ve13_sequencial} ";
          }

          if (isset($chave_ve01_placa) && !empty($chave_ve01_placa)) {
            $aWhere[] = " ve01_placa ilike '{$chave_ve01_placa}%' ";
          }

          if (isset($chave_motorista) && !empty($chave_motorista)) {
            $aWhere[] = " b.z01_nome ilike '{$chave_motorista}%' ";
          }

          $sWhere = implode(" AND ", $aWhere);
          $sql    = $oDaoAutorizacao->sql_query(null, $sCampos, $sOrdem, $sWhere);

          db_lovrot($sql, 15, "()", "", $funcao_js);
        } else {

          if ($pesquisa_chave != null && $pesquisa_chave != "") {

            $sCampos  = "'ve13_sequencial'";
            $aWhere[] = " ve13_sequencial = {$pesquisa_chave} ";
            $sWhere   = implode(" and ", $aWhere);

            $rsAutorizacao = $oDaoAutorizacao->sql_record($oDaoAutorizacao->sql_query(null, $sCampos, $sOrdem, $sWhere));
            if ($oDaoAutorizacao->numrows != 0) {


              $oStdAutorizacao = db_utils::fieldsMemory($rsAutorizacao, 0);
              echo "<script>".$funcao_js."('$oStdAutorizacao->ve13_sequencial', false);</script>";
            } else {
              echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado', true);</script>";
            }
          } else {
            echo "<script>".$funcao_js."('', '', '', false);</script>";
          }
        }
        ?>
      </td>
    </tr>
  </table>
</div>
</body>
</html>