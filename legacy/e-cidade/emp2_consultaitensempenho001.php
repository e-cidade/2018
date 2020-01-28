<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
  require_once("libs/db_app.utils.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      
      db_app::load("scripts.js, strings.js, prototype.js, estilos.css");
      db_app::load("datagrid.widget.js, grid.style.css, DBHint.widget.js, DBAbas.widget.js");
      

    ?>
  </head>
  <body style="background-color: #ccc;">
    <div id="ctnGlobalItemEmpenho">

      <div id="divItensAtivos">
        <fieldset>
          <legend><b>Itens do Empenho</b></legend>
  
            <div id="ctnItensEmpenho">
            </div>
          </fieldset>
          <div id='ajudaItem' style='position:absolute;border:1px solid #FFDD00; display:none; text-indent: 15px;
                                   background-color: #FFFFCC;width: 70%; '>
        </div>    
      </div>
      
      <div id="divItensAnulados">
        <fieldset>
          <legend><b>Itens Anulados do Empenho</b></legend>
          <div id="ctnItensAnuladosEmpenho">
          </div>
        </fieldset>
      </div>
    </div>
  </body>
</html>

<script type="text/javascript">
  
  var oGet = js_urlToObject();

  var oGridItensEmpenho          = new DBGrid('oGridItensEmpenho');
  oGridItensEmpenho.nameInstance = "oGridItensEmpenho";
  oGridItensEmpenho.setCellWidth(new Array('5%', '10%', '25%', '10%', '10%', '10%', '10%', '20%'));
  oGridItensEmpenho.setCellAlign(new Array('center',
                                           'center',
                                           'left',
                                           'right',
                                           'right',
                                           'right',
                                           'right',
                                           'lef'));
  oGridItensEmpenho.setHeader(new Array('Item',
                                        'Código do Material',
                                        'Descrição Material',
                                        'Quantidade',
                                        'Valor Total',
                                        'Valor Unitário',
                                        'Saldo',
                                        'Observação'));
  oGridItensEmpenho.hasTotalizador = true;
  oGridItensEmpenho.show($('ctnItensEmpenho'));

  /**
   * Grid de empenhos anulados
   */
  var oGridItensAnuladoEmpenho          = new DBGrid('oGridItensAnuladoEmpenho');
  oGridItensAnuladoEmpenho.nameInstance = "oGridItensAnuladoEmpenho";
  oGridItensAnuladoEmpenho.setCellWidth(new Array('10%', '60%', '10%', '10%', '10%'));
  oGridItensAnuladoEmpenho.setCellAlign(new Array('center',
                                                  'left',
                                                  'right',
                                                  'right',
                                                  'center'));
  oGridItensAnuladoEmpenho.setHeader(new Array('Código do Material',
                                               'Descrição Material',
                                               'Quantidade',
                                               'Valor Anulado',
                                               'Data'));
  oGridItensAnuladoEmpenho.hasTotalizador = true;
  oGridItensAnuladoEmpenho.show($('ctnItensAnuladosEmpenho'));

  /**
   * Carrega os itens cadastrados no empenho
   */
  function carregarItensEmpenho() {

    js_divCarregando("Aguarde, carregando dados do empenho...", "msgBox");

    var oJson = {"exec":"getItensEmpenho", "iCodigoEmpenho":oGet.e60_numemp};
    new Ajax.Request("emp2_consultaempenho.RPC.php", 
                    {method: 'post',
                    async: false,
                    parameters: 'json='+Object.toJSON(oJson), 
                    onComplete: preencherGrid });
  }

  /**
   * Carrega os itens anulados do empenho
   */
  function carregarItensAnuladoEmpenho() {

    js_divCarregando("Aguarde, carregando dados do empenho...", "msgBox");
    var oJson = {"exec":"getItensAnulados", "iCodigoEmpenho":oGet.e60_numemp};
    new Ajax.Request("emp2_consultaempenho.RPC.php", 
                    {method: 'post', 
                    async: false,
                    parameters: 'json='+Object.toJSON(oJson), 
                    onComplete: preencherGridItensAnulado });
  }

  /**
   * Preenche a grid com os itens encontrados
   */
  function preencherGrid(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");

    oGridItensEmpenho.clearAll(true);

    var nTotal = new Number(0);
    oRetorno.aItensEmpenho.each(function (oItem, iIndice) {

      var aLinha = new Array();
      aLinha[0]  = oItem.item_empenho;
      aLinha[1]  = oItem.codigo_material;
      aLinha[2]  = oItem.descricao_material.urlDecode();
      aLinha[3]  = js_formatar(oItem.quantidade, "f");
      aLinha[4]  = js_formatar(oItem.valor_total, "f");
      aLinha[5]  = js_formatar(oItem.valor_unitario, "f");
      aLinha[6]  = js_formatar(oItem.saldo_valor, "f");
      aLinha[7]  = oItem.observacao.urlDecode();
      oGridItensEmpenho.addRow(aLinha);

      oGridItensEmpenho.aRows[iIndice].aCells[2].sEvents += "onmouseover='mostrarAjuda(\""+aLinha[2]+"\", true)'";
      oGridItensEmpenho.aRows[iIndice].aCells[2].sEvents += "onmouseout='mostrarAjuda(\"\", false)'";

      nTotal = nTotal + new Number(oItem.valor_total);
    });

    oGridItensEmpenho.renderRows();
    oGridItensEmpenho.oFooter.rows[0].cells[4].innerHTML = js_formatar(nTotal, "f");
  }
  

  function preencherGridItensAnulado(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");

    oGridItensAnuladoEmpenho.clearAll(true);

    var nTotalAnulado = new Number(0);
    oRetorno.aItensAnuladoEmpenho.each(function (oDado, iIndice) {

      var aLinha = new Array();
      aLinha[0] = oDado.pc01_codmater;
      aLinha[1] = oDado.pc01_descrmater;
      aLinha[2] = js_formatar(oDado.e37_qtd, "f");
      aLinha[3] = js_formatar(oDado.e37_vlranu, "f");
      aLinha[4] = js_formatar(oDado.e94_data, "d");
      
      nTotalAnulado = (nTotalAnulado + new Number(oDado.e37_vlranu));
      oGridItensAnuladoEmpenho.addRow(aLinha);
    });

    oGridItensAnuladoEmpenho.renderRows();
    oGridItensAnuladoEmpenho.oFooter.rows[0].cells[3].innerHTML = js_formatar(nTotalAnulado, "f");
    oGridItensAnuladoEmpenho.oFooter.style.width = "100%";
  }


  function mostrarAjuda(sTexto, lShow) {

    if (lShow) {
      
      el     =  $('ctnItensEmpenho'); 
      var x  = 0;
      var y  = el.offsetHeight;
          x += el.offsetLeft;
          y += el.offsetTop;
      $('ajudaItem').innerHTML     = sTexto;
      $('ajudaItem').style.display = '';
      $('ajudaItem').style.top     = $('ctnItensEmpenho').scrollTop + 20;
      $('ajudaItem').style.left    = x;
     
    } else {
     $('ajudaItem').style.display = 'none';
    }
  }


  function iniciar() {
    carregarItensEmpenho();
    carregarItensAnuladoEmpenho();
  }
  iniciar();


  var oAba = new DBAbas($("ctnGlobalItemEmpenho"));
  oAba.adicionarAba("Itens Incluídos", $("divItensAtivos"), true);
  oAba.adicionarAba("Itens Anulados", $("divItensAnulados"), false);
</script>