<?
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

require_once "dbforms/db_classesgenericas.php";
require_once "libs/db_utils.php";

$cliframe_seleciona = new cl_iframe_seleciona;
$clpcorcamitem->rotulo->label();

/**
 * Verifica se é por processo de compras e se é por lote
 */
$lLote = false;
if (!empty($pc80_codproc)) {

  $sSqlProcessoCompra = $oDaoProcessoCompra->sql_query_file($pc80_codproc, "pc80_tipoprocesso");
  $rsProcessoCompra   = $oDaoProcessoCompra->sql_record( $sSqlProcessoCompra );

  if ($rsProcessoCompra && pg_num_rows($rsProcessoCompra) && db_utils::fieldsMemory($rsProcessoCompra, 0)->pc80_tipoprocesso == 2) {
    $lLote = true;
  }
}

$db_altexc = false;
$checked   = false;
$sql_itens = $clpcorcamitemproc->sql_query(null,null," distinct pc81_codprocitem ","pc81_codprocitem","pc80_codproc=".@$pc80_codproc." and pc22_codorc=".@$pc22_codorc);
$result_itens = $clpcorcamitemproc->sql_record($sql_itens);
if($clpcorcamitemproc->numrows>0){
  $db_altexc = true;
  $checked   = true;
}

$db_botao=true;
if((isset($db_opcaoal) && $db_opcaoal==33) || (!isset($pc80_codproc) && !isset($pc22_codorc))){
  $db_altexc = false;
  $db_botao=false;
}
$select = $pc22_codorc;
if(isset($pc22_codorc) && $pc22_codorc=="" || !isset($pc22_codorc)){
  $select = "-1";
}

$result_pcorcam = $clpcorcam->sql_record($clpcorcam->sql_query_file($select,"pc20_codorc"));

$where_numero = " pc80_codproc=".@$pc80_codproc." and (e54_autori is null or (e54_autori is not null and e54_anulad is not null)) ";
$where_codorc = " pc81_codproc=".@$pc80_codproc." and pc22_codorc=$pc22_codorc ";

$sql = $clpcprocitem->sql_query_pcmater(null,"distinct pc68_nome as db_Lote, pc01_codmater, m61_descr, pc11_quant as db_Quantidade, pc11_seq, pc81_codprocitem,pc01_descrmater as db_Material,pc11_resum","pc81_codprocitem",$where_numero);
$result_sql = $clpcprocitem->sql_record($sql);
$numrows_sql = $clpcprocitem->numrows;

if ($numrows_sql == 0) {

  $db_botao=false;
  $result_autoriz = $clempautitem->sql_record($clempautitem->sql_query_autoridot(null,null,"e55_autori","","e55_sequen in (select distinct pc81_codprocitem from pcprocitem where pc81_codproc=$pc80_codproc) and e54_autori is null"));
  if($clempautitem->numrows>0){
    $impok = true;
  }
}

$iNumeroAcordo = '';
if (isset($pc80_codproc)) {

  $oDaoAcordoPcprocitem = db_utils::getDao("acordopcprocitem");
  $sSqlDadosAcordo      = $oDaoAcordoPcprocitem->sql_query_acordo(null,
                                                          "ac26_acordo",
                                                           null,
                                                          "pc80_codproc = {$pc80_codproc}
                                                           and (ac16_acordosituacao  not in (2,3))"
                                                           );
  $rsDadosAcordo = $oDaoAcordoPcprocitem->sql_record($sSqlDadosAcordo);
  if ($oDaoAcordoPcprocitem->numrows > 0) {

    $iNumeroAcordo = db_utils::fieldsMemory($rsDadosAcordo, 0)->ac26_acordo;
    $db_botao      = false;
  }
}
?>
<style>
<?$cor="#999999"?>
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #cccccc;
}
</style>

<div class="container">
  <form name="form1" method="post" action="">
    <fieldset style="width: 800px">
      <legend>Itens do Orçamento</legend>
      <div id="griditens"></div>
    </fieldset>
    <?php

      if($db_altexc == false){
        echo '<input name="incluir" type="submit" id="incluir" value="Incluir" onclick="return js_verif();" '.($db_botao==false?"disabled":"").' >';
      }else{
        echo '<input name="alterar" type="submit" id="alterar" value="Alterar" onclick="return js_verif();" '.($db_botao==false?"disabled":"").' >';
      }

      db_input('valores',6,0,true,'hidden',3);
      db_input('pc80_codproc',6,0,true,'hidden',3);
    ?>
  </form>
</div>
<script type="text/javascript">
  var lDisable    = false,
      lAutoInsert = false,
      lLote       = false;

  <?php if (!$db_botao) { ?>
    lDisable = true;
  <?php } ?>

  <?php if (!empty($autoinsert) && $autoinsert) { ?>
    lAutoInsert = true;
  <?php } ?>

  <?php if ($lLote) { ?>
    lLote = true;
  <?php } ?>
</script>
<script>

  var oGridItens = new DBGrid('oGridItens'),
      oParams = js_urlToObject();

  var aAlign  = new Array('center', 'center', 'center', 'left', 'left', 'center', 'left'),
      aWidth  = new Array("0%", "6%", "15%", "24%", "10%", "10%"),
      aHeader = new Array('x', 'Item', 'Código do Material', 'Material', 'Unidade', 'Quantidade', 'Resumo do Item');

  /**
   * Caso seja por lote o processo de compra adiciona a coluna na grid
   */
  if (lLote) {

    aAlign.push('left');
    aWidth.push("25%");
    aWidth.push("10%");
    aHeader.push('Lote');
  }

  oGridItens.nameInstance = 'oGridItens';
  oGridItens.setCheckbox(0);
  oGridItens.setCellAlign(aAlign);
  oGridItens.setCellWidth(aWidth);
  oGridItens.setHeader(aHeader);
  oGridItens.aHeaders[1].lDisplayed = false;
  oGridItens.setHeight(200);
  oGridItens.show($('griditens'));

  var oParametros = {
    sExecutar : 'getItensProcessoCompra',
    iCodigoProcesso : oParams.pc80_codproc,
    iCodigoOrcamento : oParams.pc22_codorc
  }

  new AjaxRequest("com4_orcamentoprocessocompra.RPC.php", oParametros, function(oRetorno, lErro) {

    if (lErro) {
      alert(oRetorno.sMessage.urlDecode());
      return false;
    }

    oGridItens.clearAll(true);

    oRetorno.aItens.each(function(oItem, iLinha) {

      var aItem = new Array();

      aItem[0] = oItem.codigo_item;
      aItem[1] = oItem.sequencial;
      aItem[2] = oItem.codigo_material;
      aItem[3] = oItem.descricao_material.urlDecode();
      aItem[4] = oItem.unidade.urlDecode();
      aItem[5] = oItem.quantidade;
      aItem[6] = oItem.resumo.urlDecode();

      if (lLote) {
        aItem[7] = oItem.lote.urlDecode();
      }

      oGridItens.addRow( aItem,
                         null,
                         (lDisable || oItem.bloqueado),
                         (oItem.selecionado || oRetorno.lSelectAll) && !oItem.bloqueado);

      oGridItens.aRows[iLinha].aCells[0].adicionarEvento("onclick", 'js_verificaLoteSelecionado(' + iLinha + ')');
    });

    oGridItens.renderRows();

    oRetorno.aItens.each(function(oItem, iLinha) {

      var oHintMaterial = eval("oDBHint_"+iLinha+"_1 = new DBHint('oDBHint_"+iLinha+"_1')");
      var oHintResumo = eval("oDBHint_"+iLinha+"_2 = new DBHint('oDBHint_"+iLinha+"_2')");

      oHintMaterial.setWidth(400);
      oHintMaterial.setText(oItem.descricao_material.urlDecode());
      oHintMaterial.setShowEvents(["onmouseover"]);
      oHintMaterial.setHideEvents(["onmouseout"]);
      oHintMaterial.setScrollElement($('body-container-oGridItens'));
      oHintMaterial.setPosition('B', 'L');

      oHintResumo.setWidth(400);
      oHintResumo.setText(oItem.resumo.urlDecode());
      oHintResumo.setShowEvents(["onmouseover"]);
      oHintResumo.setHideEvents(["onmouseout"]);
      oHintResumo.setScrollElement($('body-container-oGridItens'));
      oHintResumo.setPosition('B', 'L');

      oHintMaterial.make($(oGridItens.aRows[iLinha].aCells[4].sId));
      oHintResumo.make($(oGridItens.aRows[iLinha].aCells[7].sId))
    })

    if (lAutoInsert && oGridItens.getSelection().length > 0) {
      document.form1.incluir.click();
    }

  }).execute();

  function js_verificaLoteSelecionado(iLinha) {

    if (!lLote) {
      return true;
    }

    var sLote = oGridItens.aRows[iLinha].aCells[8].getContent(),
        lSelected = oGridItens.aRows[iLinha].isSelected;

    oGridItens.aRows.each(function(oRow, iRow) {

      if (oRow.aCells[8].getContent() == sLote && iRow != iLinha) {

        var sEventCopy = $(oRow.aCells[0].getId()).onclick;
        $(oRow.aCells[0].getId()).onclick = '';
        oRow.select(lSelected);

        $(oRow.aCells[0].getId()).onclick = sEventCopy;
      }
    });
  }

  function js_verif() {

    aItens = new Array();

    oGridItens.getSelection().each(function(aItem) {
      aItens.push(aItem[0]);
    });

    document.form1.valores.value = aItens.join(',');

    if (!aItens.length) {
      alert('Nenhum item selecionado.');
      return false;
    }
  }

</script>
<?php
  if(isset($impok) && isset($db_chama)){
    echo "<script>
            alert('Usuário:\\n\\nAlguns itens incluídos neste orçamento estão em autorização de empenho!\\n\\nAdministrador:');";
    if($db_chama=="alterar"){
      echo "  top.corpo.iframe_orcam.location.href = 'com1_processo005.php';";
    }else if($db_chama=="excluir"){
      echo "  top.corpo.iframe_orcam.location.href = 'com1_processo006.php';";
    }
    echo "</script>";
  }
?>