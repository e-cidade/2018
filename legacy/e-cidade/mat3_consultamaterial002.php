<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/materialestoque.model.php");
require_once("dbforms/verticalTab.widget.php");
require_once "libs/db_app.utils.php";
db_app::import("contabilidade.contacorrente.ContaCorrenteFactory");
db_app::import("Acordo");
db_app::import("AcordoComissao");
db_app::import("CgmFactory");
db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.contacorrente.*");
$oDaoMatMater   = db_utils::getDao('matmater');
$oDaoMatEstoque = db_utils::getDao('matestoque');
$oDaoMatParam   = db_utils::getDao('matparam');
$oDaoDepartOrg  = db_utils::getDao('db_departorg');
$oDaoAlmoxDepto = db_utils::getDao('db_almoxdepto');
$oDaoMatMater->rotulo->label();

$oGet              = db_utils::postMemory($_GET);
$sSqlBuscaMatMater = $oDaoMatMater->sql_query($oGet->iCodigoMaterial);
$rsBuscaMatMater   = $oDaoMatMater->sql_record($sSqlBuscaMatMater);
$oDadoMaterial     = db_utils::fieldsMemory($rsBuscaMatMater, 0);
$oMaterialEstoque  = new materialEstoque($oGet->iCodigoMaterial);
$nPrecoMedio       = $oMaterialEstoque->getPrecoMedioMaterial();

$sCamposMatEstoque        = "coalesce(sum(m70_quant), 0) as quantidade_total";
$sSqlMatEstoqueValores    = $oDaoMatEstoque->sql_query_almox(null, $sCamposMatEstoque, null, "m70_codmatmater = {$oGet->iCodigoMaterial} ", "", true);
$rsBuscaValorMatEstoque   = $oDaoMatEstoque->sql_record($sSqlMatEstoqueValores);
$oValorMatEstoque         = db_utils::fieldsMemory($rsBuscaValorMatEstoque, 0);
$iQuantidadeTransferencia = $oMaterialEstoque->getSaldoTransferencia(true);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/tab.style.css" rel="stylesheet" type="text/css">

  <style>
  .tdValores {
    background-color: #FFF;
  }
  </style>
</head>
<body style="background-color: #cccccc;">
<fieldset>
  <legend><b>Dados do material</b></legend>
  <table width="600" border='0'>
    <tr>
      <td width="100"><b>Material:<b/></td>
      <td class='tdValores' colspan="3">
        <?php
          echo "{$oDadoMaterial->m60_codmater} - {$oDadoMaterial->m60_descr}";
        ?>
      </td>
    </tr>
    <tr>
      <td width="150"><b>Quantidade total em estoque:</b></td>
      <td class='tdValores' align="right" width="120" >
        <?php
          $iQuantidadeTotal = $oValorMatEstoque->quantidade_total + $iQuantidadeTransferencia;
          echo "{$iQuantidadeTotal}";
        ?>
      </td>
      <td width="130"><b>Valor total em estoque:<b/></td>
      <td class='tdValores' align="right" width="120">
        <?php
          // O Valor do estoque tem que ser a quantidadeTotal * precoMedio
          echo db_formatar(($iQuantidadeTotal * $nPrecoMedio), "f");
        ?>
      </td>
    </tr>
    <tr>
      <td width="170"><b>Quantidade total reservada:</b></td>
      <td class='tdValores' align="right" width="120" >
        <?php
          echo "{$iQuantidadeTransferencia}";
        ?>
      </td>
      <td width="165"><!--<b>Valor total em transferência:</b>--></td>
      <!--<td class='tdValores' align="right" width="120" >
        <?php
          /*$iValorTransferencia = $nPrecoMedio * $oValorMatEstoque->quantidadetransferencia;
          echo "{$iValorTransferencia}";*/
        ?>
      </td>-->
    </tr>
    <tr>
      <td width="100"><b>Preço Médio:</b></td>
      <td class='tdValores' align="right" width="120" >
        <?php
          echo "{$nPrecoMedio}";
        ?>
      </td>
    </tr>
  </table>
</fieldset>
<fieldset>
  <legend><b>Movimentações</b></legend>
  <?php
    $oVerticalTab = new verticalTab('detalhesMaterial', 350);
    $sQueryString = "codmater={$oGet->iCodigoMaterial}&lNovaConsulta=true";

    $oVerticalTab->add('detalhesMaterial', 'Estoque', "mat1_matconsultaiframe001.php?{$sQueryString}");
    $oVerticalTab->add('detalhesMaterial', 'Lançamentos', "mat3_matconsultaiframe002.php?{$sQueryString}");
    $oVerticalTab->add('detalhesMaterial', 'Requisições', "mat3_matconsultaiframe004.php?{$sQueryString}");
    $oVerticalTab->add('detalhesMaterial', 'Atendimentos', "mat3_matconsultaiframe005.php?{$sQueryString}");
    $oVerticalTab->add('detalhesMaterial', 'Devoluções', "mat3_matconsultaiframe006.php?{$sQueryString}");
    $oVerticalTab->add('detalhesMaterial', 'Ponto de Pedido', "mat3_matconsultaiframe008.php?{$sQueryString}");
    $oVerticalTab->add('detalhesMaterial', 'Lotes', "mat3_matconsultalotes.php?{$sQueryString}");
    $oVerticalTab->add('detalhesMaterial', 'Nota Fiscal', "mat3_matconsultanota.php?{$sQueryString}");
    $oVerticalTab->add('detalhesMaterial', 'Imprimir', "mat3_consultamaterialimprimir001.php?{$sQueryString}");
    $oVerticalTab->show();

  ?>
</fieldset>
</body>
</html>