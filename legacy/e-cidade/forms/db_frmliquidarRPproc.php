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


$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("o56_elemento");
$clrotulo->label("e69_numero");

$clorctiporec->rotulo->label();
$clempempenho->rotulo->label();
$db_opcao_inf=1;
$sfileName = basename($_SERVER['PHP_SELF']);
if ($sfileName == "emp4_anularrpproc.php"){

  $sLabelSaldo  = "Processado";
  $iTipoResto   = 2;
  $sMostraItens = "none";
  $sMostraNotas = "";

}else if ($sfileName == "emp4_anularrpnaoproc.php"){

  $sLabelSaldo = " Não Processado";
  $iTipoResto  = 1;
  $sMostraItens = "";
  $sMostraNotas = "none";

}

?>
<form name='frmAnularEmpenho' id='frmAnularEmpenho' action="" method="POST">
  <table width='80%' cellspacing='0' style='padding:0px' border='0'>
    <tr><td  style='padding:0px' valign="top">
        <fieldset><legend><b>&nbsp;Empenho&nbsp;</b></legend>
          <table >
            <tr>
              <td><?=db_ancora($Le60_codemp,"js_JanelaAutomatica('empempenho',\$F('e60_numemp'))",$db_opcao_inf)?></td>
              <td><? db_input('e60_codemp', 13, $Ie60_codemp, true, 'text', 3)?> </td>
              <td width='20'><?=db_ancora($Le60_numemp,"js_JanelaAutomatica('empempenho',\$F('e60_numemp'))",$db_opcao_inf)?></td>
              <td><? db_input('e60_numemp', 13, $Ie60_numemp, true, 'text', 3,"")?> </td>
            </tr>
            <tr>
              <td><?=db_ancora($Le60_numcgm,"js_JanelaAutomatica('cgm',\$F('e60_numcgm'))",$db_opcao_inf)?></td>
              <td><? db_input('e60_numcgm', 13, $Ie60_numcgm, true, 'text', 3); ?> </td>
              <td colspan=3><? db_input('z01_nome', 52, $Iz01_nome, true, 'text', 3, "");?></td>
            </tr>
            <tr>
              <td><?=db_ancora($Le60_coddot,"js_JanelaAutomatica('orcdotacao',\$F('e60_coddot'),'".@$e60_anousu."')",$db_opcao_inf)?></td>
              <td nowrap ><? db_input('e60_coddot', 13, $Ie60_coddot, true, 'text', 3); ?></td>
              <td width="20"><?=db_ancora($Lo15_codigo,"",3)?></td>
              <td nowrap><? db_input('o15_codigo', 5, $Io15_codigo, true, 'text', 3); db_input('o15_descr', 33, $Io15_descr, true, 'text', 3)?></td>
            </tr>
            <tr>
              <td><b>Motivo:</b></td>
              <td colspan='4'>
                <textarea id="motivo" name="motivo" rows="2" cols="70"></textarea>
            </tr>
            <tr>
              <td>
                <b>Tipo:</b>
              </td>
              <td colspan='3' nowrap>
                <?
                $oEmpAnuladoTipo  = new cl_empanuladotipo;
                $rsEmpAnuladoTipo = $oEmpAnuladoTipo->sql_record(
                  $oEmpAnuladoTipo->sql_query(null,"*",
                                              "e38_sequencial")
                );
                $e94_empanuladotipo = 2;
                db_selectrecord("e94_empanuladotipo",$rsEmpAnuladoTipo,true,1);
                ?>
                <input type='checkbox' name='imprimir' id='imprimir' value='1'>
                <label for='imprimir'>Imprimir Documento</label>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
      <td valign='top' style='padding:0px'>
        <fieldset ><legend><b>&nbsp;Valores do Empenho&nbsp;</b></legend>
          <table style="width:200px;height:100%" >
            <tr><td nowrap><?=@$Le60_vlremp?></td><td align=right><? db_input('e60_vlremp', 12, $Ie60_vlremp, true, 'text', 3, '','','','text-align:right')?></td></tr>
            <tr><td nowrap><?=@$Le60_vlranu?></td><td align=right><? db_input('e60_vlranu', 12, $Ie60_vlranu, true, 'text', 3, '','','','text-align:right')?></td></tr>
            <tr><td nowrap><?=@$Le60_vlrliq?></td><td align=right><? db_input('e60_vlrliq', 12, $Ie60_vlrliq, true, 'text', 3, '','','','text-align:right')?></td></tr>
            <tr><td nowrap><?=@$Le60_vlrpag?></td><td align=right><? db_input('e60_vlrpag', 12, $Ie60_vlrpag, true, 'text', 3, '','','','text-align:right')?></td></tr>
            <tr><td colspan='2' class='table_header'>Saldo a Anular </td></tr>
            <tr>
              <td nowrap><b><?=@$sLabelSaldo?></></td>
              <td align=right>
                <? db_input('saldoRP', 12,'', true, 'text', 3, '','','','text-align:right')?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
    <tr style="display:<?=$sMostraNotas;?>">
      <td colspan='2' style='padding:0px;'>
        <fieldset><legend><b>&nbsp;Notas&nbsp;</b></legend>

          <table  cellspacing=0 cellpadding=0 width='100%' style='border:2px inset white'>
            <tr>
              <th class='table_header'><input type='checkbox'  style='display:none'
                                              id='mtodosnotas' onclick='js_marca()' />
                <a onclick='js_marca("mtodosnotas","chkmarca","linha")' style='cursor:pointer'>M</a></b></th>
              <th class='table_header'>Código da Nota</th>
              <th class='table_header'>Número da Nota</th>
              <th class='table_header'>Data da Nota</th>
              <th class='table_header'>Valor</th>
              <th class='table_header'>Liquidado</th>
              <th class='table_header'>Anulado</th>
              <th class='table_header'>Pago</th>
              <th class='table_header'>A Pagar</th>
              <th class='table_header'>Liquidado a Pagar</th>
              <th class='table_header' width='18px'>&nbsp;</th>
            </tr>
            <tbody id='dados' style='height:150;width:95%;overflow:scroll;overflow-x:hidden;background-color:white'>
            </tbody>
          </table>
        </fieldset>
      </td>
    </tr>
    <!---
    /* Mostramos apenas os itens quando temos RP não processado.
    -->
    <tr style="display:<?=$sMostraItens;?>">
      <td colspan='2'>
        <fieldset><legend><b>Itens do Empenho</b></legend>
          <table style='border:2px inset white;backgorund-color:white;' width='100%' cellspacing='0'>
            <thead>
            <tr>
              <th class='table_header'><input type='checkbox'  style='display:none'
                                              id='mtodositens' onclick='js_marca()'>
                <a onclick='js_marca("mtodositens", "chkmarcaItens","linhaItem")'
                   style='cursor:pointer'>M</a></b></th>
              <th class='table_header'>Sequência</th>
              <th class='table_header'>Item</th>
              <th class='table_header'>Vlr Unitário</th>
              <th class='table_header'>Quantidade</th>
              <th class='table_header'>Valor Total</th>
              <th class='table_header'>Quantidade</th>
              <th class='table_header'>Valor Total</th>
              <th class='table_header' width='18px'>&nbsp;</th>
            </tr>
            </thead>
            <tbody id='dadosItens' style='height:150;width:95%;overflow:scroll;overflow-x:hidden;background-color:white'>
            <td colspan='10'>&nbsp;</td>
            </tbody>
          </table>
        </fieldset>
      </td>
    </tr>
    </td>
    </tr>

  </table>
  <input name="confirmar" type="button" id="confirmar" value="Confirmar" onclick="return js_estornaRP(<?=$iTipoResto?>)" disabled>
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
  iTipoRP = <?=$iTipoResto;?>;
  function js_pesquisa(){

    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenhorp.php?funcao_js=parent.js_preenchepesquisa|e60_numemp','Pesquisa',true);
  }
  function js_preenchepesquisa(chave) {
    db_iframe_empempenho.hide();
    js_consultaEmpenho(chave);
  }
  function js_consultaEmpenho(iEmpenho,operacao){

    js_divCarregando("Aguarde, efetuando pesquisa","msgBox");
    strJson = '{"method":"getDadosRP","pars":"'+iEmpenho+'","iTipoRP":"<?=$iTipoResto?>","iEmpenho":"'+iEmpenho+'"}';
    $('dados').innerHTML    = '';
    //$('pesquisar').disabled = true;
    var url     = 'emp4_liquidacao004.php';
    var oAjax   = new Ajax.Request(
      url,
      {
        method: 'post',
        parameters: 'json='+strJson,
        onComplete: js_saida
      }
    );

  }

  function js_saida(oAjax) {

    var obj = eval("(" + oAjax.responseText + ")");
    if (obj.status && obj.status == 2){

      js_removeObj("msgBox");
      alert(obj.sMensagem.urlDecode());
      return false ;
    }
    $('e60_codemp').value = obj.e60_codemp+"/"+obj.e60_anousu;
    $('e60_numemp').value = obj.e60_numemp.urlDecode();
    $('e60_coddot').value = obj.e60_coddot.urlDecode();
    $('e60_numcgm').value = obj.e60_numcgm.urlDecode();
    $('z01_nome').value   = obj.z01_nome.urlDecode();
    $('o15_codigo').value = obj.o58_codigo.urlDecode();
    $('o15_descr').value  = obj.o15_descr.urlDecode();
    $('e60_vlremp').value = js_formatar(obj.e60_vlremp,'f',2);
    $('e60_vlranu').value = js_formatar(obj.e60_vlranu,'f',2);
    $('e60_vlrpag').value = js_formatar(obj.e60_vlrpag,'f',2);
    $('e60_vlrliq').value = js_formatar(obj.e60_vlrliq,'f',2);
    $('saldoRP').value    = js_formatar(obj.nValorProcessado,'f',2);
    saida                 = '';
    $('dados').innerHTML  = '';

    var iErros    = new Number(0);
    var lDisabled = true;

    if (obj.aNotasRP){

      for (iInd = 0; iInd < obj.aNotasRP.length; iInd++) {

        with (obj.aNotasRP[iInd]) {

          if (iTipoRP == 2 ) {

            var nSaldoNotaaPagar = new Number(e70_vlrliq - e53_vlrpag);

            if (js_round(nSaldoNotaaPagar, 2) > 0 ) {
              var lHabilitado = ""; //libera o checkbox - lembrando, que se usamos a propriedade disabled.

            } else {
              nSaldoNotaaPagar = 0;
              var lHabilitado = "disabled";
              iErros++;
            }
          }else if (iTipoRP == 1){

            var nSaldoPagar      = new Number((e70_valor - e70_vlranu - e53_vlrpag));
            var nSaldoNotaaPagar = new Number((e70_vlrliq - e53_vlrpag));

            if (Math.round(nSaldoNotaaPagar) > 0 ) {
              var lHabilitado = ""; //libera o checkbox - lembrando, que se usamos a propriedade disabled.

            } else{
              var lHabilitado = "disabled";
              iErros++;
            }
          }
          e69_numero = new String(e69_numero);
          saida += "<tr id='linhachk"+e69_codnota+"'>";
          saida += "  <td class='linhagrid'>";
          saida += "     <input type='checkbox' id='chk"+e69_codnota+"' onclick=\"js_marcaLinha(this,'linha');\"";
          saida += "      value='"+e69_codnota+"' class='chkmarca' "+lHabilitado+"  style='height:12px'>";
          saida += "  </td>";
          saida += "  <td class='linhagrid' id='e69_codnota"+e69_codnota+"' style='text-align:right'>";
          saida +=       e69_codnota;
          saida += "  </td>";
          saida += "  <td class='linhagrid' id='e69_numero"+e69_codnota+"' style='text-align:right'>";
          saida +=       e69_numero.urlDecode();
          saida += "  </td>";
          saida += "  <td class='linhagrid' id='e69_data"+e69_codnota+"'>";
          saida +=       js_formatar(e69_dtnota,"d");
          saida += "  </td>";
          saida += "  <td class='linhagrid' id='e70_valor"+e69_codnota+"' style='text-align:right'>";
          saida +=       js_formatar(e70_valor,'f',2);
          saida += "  </td>";
          saida += "  <td class='linhagrid' id='e70_vlrliq"+e69_codnota+"' style='text-align:right'>";
          saida +=       js_formatar(e70_vlrliq,'f',2);
          saida += "  </td>";
          saida += "  <td class='linhagrid' id='e70_vlranu"+e69_codnota+"' style='text-align:right'>";
          saida +=       js_formatar(e70_vlranu,'f',2);
          saida += "  </td>";
          saida += "  <td class='linhagrid' id='e53_vlrpag"+e69_codnota+"' style='text-align:right'>";
          saida +=       js_formatar(e53_vlrpag,'f',2);
          saida += "  </td>";
          saida += "  <td class='linhagrid' id='vlraliquidar"+e69_codnota+"' style='text-align:right'>";
          saida +=       js_formatar((e70_valor - e70_vlranu - e53_vlrpag),'f',2);
          saida += "  </td>";
          saida += "  <td class='linhagrid' id='vlrapagar"+e69_codnota+"' style='text-align:right'>";
          saida +=       js_formatar(nSaldoNotaaPagar,'f',2);
          saida += "  </td>";
          saida += "</tr>";
        }
        if (iErros != obj.aNotasRP.length) {
          lDisabled = false;
        }
      }
    }
    saida += "<tr style='height:auto'><td>&nbsp;</td></tr>";
    $('dados').innerHTML    = saida;
    if (obj.aItens) {

      var saida = '';
      for (var iInd = 0; iInd < obj.aItens.length; iInd++) {
        with (obj.aItens[iInd]) {


          if (Math.round(saldo) == 0 && js_round(saldovalor,2) == 0 || js_round(saldovalor,2) == 0) {

            sDisabled  = ' disabled ';
            sClassName = 'disabled';

          } else {

            sDisabled  = '';
            sClassName = 'normal';
          }

          lFraciona = pc01_fraciona == 'f'?false:true;
          saida += "<tr class='"+sClassName+"' id='linhaItemchkmarca"+e62_sequencial+"'>";
          saida += "  <td class='linhagrid' style='text-align:center'>";
          saida += "     <input type='hidden' id='e62_sequencial"+e62_sequencial+"'";
          saida += "            value='"+e62_sequencial+"'>";
          saida += "     <input type='checkbox' onclick=\"js_marcaLinha(this,'linhaItem')\"";
          saida += "            class='chkmarcaItens' name='chkItem"+e62_sequencial+"'";
          saida += "            id='chkmarca"+e62_sequencial+"'"+sDisabled;
          saida += "            value='"+e62_sequencial+"' style='height:12px'>";
          saida += "  </td>";
          saida += "  <td class='linhagrid' style='text-align:right'>";
          saida +=      e62_sequen;
          saida += "  </td>";
          saida += "  <td class='linhagrid' style='text-align:left'>";
          saida +=     pc01_descrmater.urlDecode();
          saida += "  </td>";
          saida += "  <td class='linhagrid' id='vlruni"+e62_sequencial+"'";
          saida += "      style='text-align:right'>";
          saida +=       js_formatar(e62_vlrun,'f');
          saida += "  </td>";
          saida += "  <td class='linhagrid' id='saldo"+e62_sequencial+"'";
          saida += "      style='text-align:right'>";
          saida +=       js_formatar(saldo,'f');
          saida += "  </td>";
          saida += "  <td class='linhagrid' id='saldovlr"+e62_sequencial+"'";
          saida += "      style='text-align:right'>";
          /**
           * Alterada a coluna para verificar o valor total vindo do saldo do ítem
           * Antes era possível anular uma quantidade de um ítem maior do que havia sido "reservado" para ele
           * caso fosse anulado parcialmente.
           */
          saida +=      js_formatar(saldovalor,'f');
          saida += "  </td>";
          saida += "  <td class='linhagrid' style='text-align:center;width:10%'>";
          saida += "    <input type='text' name='qtdesol"+e62_sequencial+"'  id='qtdesol"+e62_sequencial+"'";
          saida += "           value='"+saldo+"' style='text-align:right' size='5'"+sDisabled;
          saida += "           onblur='js_calculaValor("+e62_sequencial+",1);'";
          saida += "           onkeypress='return js_validaFracionamento(event,"+lFraciona+",this)'>";
          saida += "  </td>";
          saida += "  <td class='linhagrid' style='text-align:center;width:10%'>";
          saida += "    <input type='text'  style='text-align:right' name='vlrtot"+e62_sequencial+"'";
          saida += "           id='vlrtot"+e62_sequencial+"'";
          saida += "           value='"+js_round(saldovalor,2)+"' size='5' class='valores' disabled ";
          saida += "    onblur='js_calculaValor("+e62_sequencial+",2);' onkeypress='return js_teclas(event)'>";
          saida += "   </td>";
          saida += "</tr>";


        }
        lDisabled = false;
      }
      saida += "<tr style='height:auto'><td>&nbsp;</td></tr>";
      $('dadosItens').innerHTML = saida;
    }
    js_removeObj("msgBox");
    $('confirmar').disabled = lDisabled;
  }

  function js_estornaRP(iTipo) {

    var iEmpenho      = $F('e60_numemp');
    var nSaldoEmpenho = $('saldoRP').value.getNumber();
    var sMotivo       = new String($F('motivo'));
    var nTotalNotas   = new Number(0);
    var nTotalItens   = new Number(0);
    var sJsonNotas    = new String();
    var sJsonItens    = new String();
    var iTotNotas     = new Number(0);
    var iTotItens     = new Number(0);
    var aItens        = '';
    var aNotas        = '';

    /*
     * Validações:
     * - devemos verificar se o usuario selecionou alguma nota para anular.
     * caso nao selecionou nenhuma, devemos cancelar a operacao.
     * - o usuário deve preencher o campo motivo.
     * - Valor das notas nao pode ser maior que o saldo restante.
     */
    if (!confirm("Confirma Anulação do Empenho?")) {
      return false;
    }
    if (sMotivo.trim() == '') {

      alert('Preenchimento do motivo da anulação é Obrigatório.');
      $('motivo').focus();
      return false;

    }

    /*
     * Vamos percorrer as notas de RP existentes no empenho
     * e retornar as selecionadas pelo usuario.
     */
    var aNotas = js_getElementbyClass(frmAnularEmpenho, 'chkmarca');
    for (i = 0; i < aNotas.length; i++) {

      /*
       * trabalhamos com a nota apenas.
       * com a estrutura with, podemos acessar diretamente os metodos/propriedades do
       * objeto que passamos como parametro, logo não é necessário utilizar
       */
      with (aNotas[i]) {

        if (checked) {
          iTotNotas++;
          if (iTipoRP == 2) {
            nValorNota = $('vlrapagar' + value).innerHTML.trim().getNumber();
          }else{
            nValorNota = $('e70_valor' + value).innerHTML.trim().getNumber();
          }
          nTotalNotas += nValorNota;
          sJsonNotas  += (sJsonNotas != '' ? ',' : '') + "{'iCodNota':" + value + ",'sValorEstornado':" + nValorNota + "}";

        }
      }
    }

    nTotalNotas = nTotalNotas.toFixed(2);

    /*
     * Separamos algumas validacoes pelo tipo de RP;
     *  1 = RP nao processado
     *  2 = RP processado
     */
    if (iTipo == 2) {

      /* Validações
       *  - Deve ter ao menos uma nota pra estornar;
       */
      try {

        if (iTotNotas == 0) {
          throw 'E1';
        }
        if (nTotalNotas > nSaldoEmpenho) {
          throw 'E2';
        }
      }

      catch (exeption) {

        switch (exeption) {

          case 'E1':

            alert("Não há notas selecionadas. Procedimento cancelado");
            return false;
            break;

          case 'E2':

            alert("Não há saldo para efetuar essa operação.");
            return false;
            break;
        }
      }
    }else if (iTipo == 1) {

      var aItens = js_getElementbyClass(frmAnularEmpenho, 'chkmarcaItens');
      var sVirg  = '';
      for (i = 0; i < aItens.length; i++) {

        /*
         * trabalhamos com a nota apenas.
         * com a estrutura with, podemos acessar diretamente os metodos/propriedades do
         * objeto que passamos como parametro, logo não é necessário utilizar
         */
        with (aItens[i]) {

          if (checked) {

            iTotItens++;
            var nValorItem = new Number($F('vlrtot' + value));
            var nQtdItem   = new Number($F('qtdesol' + value));
            nTotalItens   += nValorItem;
            sJsonItens    += sVirg+"{'iCodItem':" + value + ",'nVlrTotal':" + nValorItem + ",'nQtde':"+nQtdItem+"}";
            sVirg = ',';
          }
        }

      }
      if (iTotItens == 0) {

        alert("Pelo menos um item deve ser informado.\nProcesso Anulado");
        return false;

      }
    }
    /*
     * Criamos a requisição para o RPC efetuar os procedimentos necessários;
     */

    var sMotivoConfigurado = encodeURIComponent(tagString($F('motivo')));
    var sSenderJson  = new String();
    sSenderJson      = "{";
    sSenderJson     += "'iEmpenho':"+iEmpenho+",'sValorEstornar':"+(nTotalNotas+nTotalItens)+",";
    sSenderJson     += "'aNotas':["+sJsonNotas+"],";
    sSenderJson     += "'iTipo':"+iTipo+",'method':'estornarRP','sMotivo':'"+sMotivoConfigurado+"',";
    sSenderJson     += "'tipoAnulacao':"+$('e94_empanuladotipo').value+",";
    sSenderJson     += "'aItens':["+sJsonItens+"]";
    sSenderJson     += "}";
    var oResponse    = new Ajax.Request
    (
      'emp4_liquidacao004.php',
      {
        method: 'post',
        parameters: 'json='+sSenderJson,
        onComplete: js_retornoProcedimento
      }
    );



  }

  function js_retornoProcedimento(oResponse) {

    var oRetorno = eval("(" + oResponse.responseText + ")");
    if (oRetorno.iStatus == '1') {

      alert(oRetorno.sMensagem.urlDecode());
      if ($('imprimir').checked) {

        jan = window.open('emp2_anulemp002.php?e60_numemp=' + $F('e60_numemp'), '', 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
        jan.moveTo(0, 0);
        js_pesquisa();

      } else {
        js_pesquisa();
      }
    }else {

      alert(oRetorno.sMensagem.urlDecode());
      js_pesquisa();
    }
  }

  /*
   * FUnções para controle de tela;
   */
  function js_limpa(){

    $('dados').innerHTML = '';
    $('frmAnularEmpenho').reset();

  }

  function js_marca(idObjeto, sClasse, sLinha){

    obj = document.getElementById(idObjeto);
    if (obj.checked){
      obj.checked = false;
    }else{
      obj.checked = true;
    }
    itens = js_getElementbyClass(frmAnularEmpenho, sClasse);
    for (i = 0;i < itens.length;i++){

      if (itens[i].disabled == false){
        if (obj.checked == true){

          itens[i].checked=true;
          js_marcaLinha(itens[i],sLinha);

        }else{

          itens[i].checked=false;
          js_marcaLinha(itens[i],sLinha);

        }
      }
    }
  }

  function js_marcaLinha(obj, linha) {

    if (obj.checked) {
      $(linha+obj.id).className='marcado';
    } else {
      $(linha+obj.id).className='normal';
    }
  }
  //controle dos valores digitados no empenho.
  function js_calculaValor(id,tipo){

    $('confirmar').disabled = false;
    nVlrUni        = js_strToFloat($('vlruni'+id).innerHTML);
    nQtde          = new Number($F('qtdesol'+id));
    nVlrTotal      = new Number($F('vlrtot'+id));
    //consideramos como saldo valido os saldos do empenho menos o saldo solicitado.
    iSaldo         = (js_strToFloat($('saldo'+id).innerHTML));
    iSaldovlr      = (js_strToFloat($('saldovlr'+id).innerHTML));

    if (nQtde > 0){
      $('vlrtot'+id).disabled = true;
    }else if (nQtde == 0){
      $('vlrtot'+id).disabled = false;
    }
    if (tipo == 1){
      nTotal = new Number (nVlrUni*nQtde);
      if ((nQtde <= iSaldo)){
        if (nTotal > 0){
          $('vlrtot'+id).value    = js_round(nTotal,2);
          $('confirmar').disabled = false;
          if ($('chkmarca'+id).checked == false ){
            $('chkmarca'+id).click();
          }
        }
      }else{

        alert("Valor total maior que o saldo restante.");
        $('confirmar').disabled = true;

      }
    }else if(tipo == 2) {
      if (nQtde == '' || nQtde == 0) {
        nTotal = (nVlrTotal/nVlrUni);
        if ((nVlrTotal <= iSaldovlr)) {
          if (nTotal > 0){
            $('qtdesol'+id).value = nTotal.toFixed(2);
            $('confirmar').disabled = false;
            if ($('chk'+id).checked == false ) {
              $('chk'+id).click();
            }
          }
        } else {

          alert("Valor total maior que o saldo restante.");
          $('confirmar').disabled = true;

        }
      } else {
        if ((nVlrTotal > iSaldovlr)) {

          alert("Valor total maior que o saldo restante.");
          $('confirmar').disabled = true;

        }
      }
    }
    // setTotal();
  }

  function setTotal(){

    aListaValores = js_getElementbyClass(frmAnularEmpenho,'valores');
    var nTotal = 0;
    for (var i = 0; i < aListaValores.length; i++){

      nTotal += new Number(aListaValores[i].value);

    }
    $('valortotal').innerHTML = js_formatar(nTotal,'f');
  }

  js_pesquisa();
</script>
