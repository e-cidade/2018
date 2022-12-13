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

//MODULO: empenho
$clrotulo = new rotulocampo;
$clrotulo->label("e60_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("e60_vlrliq");
$clrotulo->label("e60_vlranu");
$clrotulo->label("e60_vlremp");
$clrotulo->label("e60_vlrpag");
$clrotulo->label("o56_elemento");
$clrotulo->label("o15_codigo");
$clrotulo->label("o15_descr");
$clrotulo->label("e60_coddot");
$clorcdotacao->rotulo->label();
if (empty ($e60_numemp)) {
  $db_opcao_inf = 3;
} else {
  $db_opcao_inf = $db_opcao;
}
?>

<form name="form1" method="post" action="" id='form1'>
  <table border='0' cellspacing='0' cellpadding='0'>
    <tr>
      <td colspan='2' align='center'>
        <table border="0" >
          <tr>
            <td valign='top'>
              <fieldset><legend><b> Dados Empenho:</b></legend>
                <table>
                  <tr>
                    <td nowrap title="<?=@$Te60_numemp?>">
                      <?=db_ancora($Le60_numemp,"js_JanelaAutomatica('empempenho',\$F('e60_numemp'))",1)?>
                    </td>
                    <td style='text-align:left' colspan='4'>
                      <?
                      db_input('e60_numemp', 10, $Ie60_numemp, true, 'text', 3);
                      echo $Le60_codemp;
                      db_input('e60_codemp', 10, $Ie60_codemp, true, 'text', 3);
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td nowrap title="<?=@$Tz01_nome?>">
                      <?=db_ancora($Lz01_nome,"js_JanelaAutomatica('cgm',\$F('e60_numcgm'))",1)?>
                    </td>
                    <td colspan=3>
                      <?
                      db_input('e60_numcgm', 10, $Ie60_numcgm, true, 'text', 3);
                      db_input('z01_nome', 43, $Iz01_nome, true, 'text', 3, '');
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td><?=db_ancora($Le60_coddot,"js_JanelaAutomatica('orcdotacao',\$F('e60_coddot'),'".@$e60_anousu."')",1)?></td>
                    <td><? db_input('e60_coddot', 10, $Ie60_coddot, true, 'text', 3);
                      db_ancora($Lo15_codigo,"",3);
                      db_input('o15_codigo', 5, $Io15_codigo, true, 'text', 3); db_input('o15_descr', 24, $Io15_descr, true, 'text', 3)?></td>
                  </tr>
                  <tr>
                    <td><b>Motivo:</b></td>
                    <td nowrap colspan='3'><textarea name='motivo' rows='1' cols='55'  id='motivo' ></textarea></td>
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
                    </td>
                  </tr>
                  <!--[Extensao OrdenadorDespesa] inclusao_ordenador-->

                  <tr>
                    <td><b>&nbsp;</b></td>
                    <td nowrap colspan='4'><input type='checkbox' name='reserva' id='reserva' value='1'><label for='reserva'>Recriar a reserva de saldo</label>
                      <input type='checkbox' name='imprimir' id='imprimir' value='1'><label for='imprimir'>Imprimir Documento</label></td>
                  </tr>
                </table>
              </fieldset>
            </td>
            <td valign='top' colspan='2'>
              <table cellspacing='0' border='1'>
                <tr>
                  <td>
                    <fieldset><legend><b>Saldos</b></legend>
                      <table style="width:200px" >
                        <tr><td nowrap><?=@$Le60_vlremp?></td><td align=right><? db_input('e60_vlremp', 12, $Ie60_vlremp, true, 'text', 3, '','','','text-align:right')?></td></tr>
                        <tr><td nowrap><?=@$Le60_vlranu?></td><td align=right><? db_input('e60_vlranu', 12, $Ie60_vlranu, true, 'text', 3, '','','','text-align:right')?></td></tr>
                        <tr><td nowrap><?=@$Le60_vlrliq?></td><td align=right><? db_input('e60_vlrliq', 12, $Ie60_vlrliq, true, 'text', 3, '','','','text-align:right')?></td></tr>
                        <tr><td nowrap><?=@$Le60_vlrpag?></td><td align=right><? db_input('e60_vlrpag', 12, $Ie60_vlrpag, true, 'text', 3, '','','','text-align:right')?></td></tr>
                        <tr><td colspan='2' class='table_header'>Saldo a Anular </td></tr>
                        <tr><td nowrap><b>Saldo:</></td><td align=right><? db_input('saldo_dis', 12,'', true, 'text', 3, '','','','text-align:right')?></td></tr>
                        <tr>
                      </table>
                    </fieldset>
                  </td>
                </tr>
              </table>
          </tr>
          <tr>
            <td colspan='4' style='text-align:center'>
              <input name="confirmar" type="button" id="confirmar" value="Confirmar" onclick="return js_anularEmpenho()" disabled>
              <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa('');" >
            </td>
          </tr>
          <tr>

            <td colspan='10'>
              <table width='100%'>
                <tr>
                  <td>
                    <fieldset><legend><b>Itens do Empenho</b></legend>
                      <table style='border:2px inset white;backgorund-color:white;' width='100%' cellspacing='0'>

                        <thead>
                        <tr>
                          <th class='table_header'><input type='checkbox'  style='display:none'
                                                          id='mtodositens' onclick='js_marca()'>
                            <a onclick='js_marca("mtodositens","chkmarca","tr")' style='cursor:pointer'>M</a></b></th>
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
                        <tbody id='dados' style='height:100px;width:95%;overflow:scroll;overflow-x:hidden;background-color:white'>
                        <td colspan='10'>&nbsp;</td>
                        </tbody>
                        <tfoot>
                        <tr>
                          <td class='table_footer' style='text-align:right'  colspan='7'><b>Total</b></td>
                          <td class='table_footer' colspan='1' id='valortotal' style='text-align:right;font-weigth:bold'>0.00</td>
                          <td class='table_footer' colspan='1'>&nbsp;</td>
                        </tr>
                        </tfoot>
                      </table>
                    </fieldset>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td colspan='10'>
              <table width='100%'>
                <tr>
                  <td>
                    <fieldset><legend><b>Solicitacao de Anulação</b></legend>
                      <table style='border:2px inset white;backgorund-color:white;' width='100%' cellspacing='0'>
                        <thead>
                        <tr>
                          <th class='table_header'>&nbsp;</th>
                          <th class='table_header'>Sequência</th>
                          <th class='table_header'>Item</th>
                          <th class='table_header'>Quantidade solicitada</th>
                          <th class='table_header'>Valor Solicitado</th>
                          <th class='table_header' width='18px'>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody id='itenssolicitados' style='height:80;width:95%;overflow:scroll;overflow-x:hidden;background-color:white'>
                        <td colspan='10'>&nbsp;</td>
                        </tbody>
                      </table>
                    </fieldset>
                  </td>
                </tr>
              </table>
        </table>
</form>
<script>
  function js_pesquisa(){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_preenchepesquisa|e60_numemp','Pesquisa',true);
    //js_consultaEmpenho(38118);
  }
  function js_preenchepesquisa(chave){
    db_iframe_empempenho.hide();
    js_consultaEmpenho(chave);
  }
  function js_marcaSolic(obj){

    var itens = js_getElementbyClass(form1,obj.className);
    for (var i = 0;i < itens.length;i++){
      if (itens[i].disabled == false){
        if (obj.checked == true){

          itens[i].checked = true;
          setValoresItem(itens[i]);
          js_marcaLinha(itens[i],"tr");
        }else{

          itens[i].checked = false;
          setValoresItem(itens[i]);
          $('tr'+itens[i].id).className='normal';
          js_marcaLinha(itens[i],"tr");
        }
      }
    }
  }

  function js_consultaEmpenho(iEmpenho,operacao){

    js_divCarregando("Aguarde, efetuando pesquisa","msgBox");
    strJson = '{"method":"getEmpenhos","pars":"'+iEmpenho+'","operacao":"1","itens":"1","iEmpenho":"'+iEmpenho+'"}';
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

  function js_saida(oAjax){

    obj                             = eval("("+oAjax.responseText+")");
    $('e60_codemp').value           = obj.e60_codemp;
    $('e60_numemp').value           = obj.e60_numemp;
    $('e60_coddot').value           = obj.e60_coddot;
    $('e60_numcgm').value           = obj.e60_numcgm;
    $('z01_nome').value             = obj.z01_nome.urlDecode();
    $('o15_codigo').value           = obj.o58_codigo;
    $('o15_descr').value            = obj.o15_descr.urlDecode();
    $('e60_vlremp').value           = obj.e60_vlremp;
    $('e60_vlranu').value           = obj.e60_vlranu;
    $('e60_vlrpag').value           = obj.e60_vlrpag;
    $('e60_vlrliq').value           = obj.e60_vlrliq;
    $('saldo_dis').value            = obj.saldo_dis;
    saida                           = '';
    $('dados').innerHTML            = '';
    $('itenssolicitados').innerHTML = '';
    nSaldoEmpenho                   = obj.saldo_dis;
    nSaldoEmpenho                   = nSaldoEmpenho.replace(/\./g,'');
    nSaldoEmpenho                   = nSaldoEmpenho.replace(",",".");
    var sErroSaldo                  = new Number(0);

    //caso nao exista valores para anular, bloqueamos as componentes na tela.
    if (nSaldoEmpenho > 0 ) {
      lDisabled = "";
    } else {
      lDisabled = "disabled";
    }
    //alert(obj.numnotas);
    if (obj.numnotas > 0){
      for (var i = 0; i < obj.data.length;i++){


        sClassName = "normal"
        if (lDisabled == "disabled") {

          if (obj.data[i].saldodiferenca == 0) {

            lDisabled         = '';
            obj.data[i].libera = 'disabled';
            sClassName         = "disabled";
          }
        }
        if (obj.data[i].saldo == 0 && obj.data[i].e62_vlrtot == 0) {

          if (obj.data[i].saldodiferenca == 0) {

            obj.data[i].libera = 'disabled';
            sClassName         = "disabled";
            sErroSaldo++;
          } else {
            obj.data[i].libera = '';
            sClassName         = "saldocentavos";
          }
        }

        var lDisabledQuantidade = lDisabled;
        var sDisableValor       = ' disabled ';
        var sValorTotal         = 0;
        var sQuantidadeTotal    = 0;
        if (obj.data[i].saldodiferenca > 0 && obj.data[i].e62_vlrtot == 0) {

          var sValorTotal        = obj.data[i].saldodiferenca;
          lDisabledQuantidade    = ' disabled ';
          var sDisableValor      = ' ';
          var sQuantidadeTotal   = 0;
          obj.data[i].e62_vlrtot = sValorTotal;
        }

        if (obj.data[i].libera == "") {
          sDisableValor = '';
        }

        descrmater = obj.data[i].pc01_descrmater.replace(/\+/g," ");
        descrmater = unescape(descrmater);

        saida += "<tr class='"+sClassName+"' style='height:1em' id='trchkmarca"+obj.data[i].e62_sequen+"'>";
        saida += "<td class='linhagrid' style='text-align:center'>";
        saida += "<input type='hidden' id='e62_sequencial"+obj.data[i].e62_sequen+"' value='"+obj.data[i].e62_sequencial+"'>";
        saida += "<input type='checkbox' "+obj.data[i].libera+" onclick='js_marcaLinha(this,\"tr\",\""+sClassName+"\")'";
        saida += "    class='chkmarca' name='chk"+obj.data[i].e62_sequen+"' id='chkmarca"+obj.data[i].e62_sequen+"'";
        saida += "    value='"+obj.data[i].e62_sequen+"' style='height:12px'></td>";
        saida += "<td class='linhagrid' style='text-align:right'>"+obj.data[i].e62_sequen+"</td>";
        saida += "<td class='linhagrid' style='text-align:left'>"+descrmater+"</td>";
        saida += "<td class='linhagrid' id='vlruni"+obj.data[i].e62_sequen+"' style='text-align:right'>"+js_formatar(obj.data[i].e62_vlrun, 'f')+"</td>";
        saida += "<td class='linhagrid' id='saldo"+obj.data[i].e62_sequen+"'  style='text-align:right'>"+obj.data[i].saldo+"</td>";
        saida += "<td class='linhagrid' id='saldovlr"+obj.data[i].e62_sequen+"' style='text-align:right'>"+js_formatar(obj.data[i].e62_vlrtot, 'f')+"</td>";
        saida += "<td class='linhagrid' style='text-align:center;width:10%'>";

        saida += "<input type='text' name='qtdesol"+obj.data[i].e62_sequen+"' "+lDisabledQuantidade+" id='qtdesol"+obj.data[i].e62_sequen+"'";
        saida += " value='"+sQuantidadeTotal+"' style='text-align:right' size='5' oninput=\"js_ValidaCampos(this, 4, 'Quantidade', '', '', event);\" onblur='js_calculaValor("+obj.data[i].e62_sequen+",1)'></td>";

        saida += "<td class='linhagrid' style='text-align:center;width:10%'>";
        saida += "<input type='text' "+lDisabled+" style='text-align:right' name='vlrtot"+obj.data[i].e62_sequen+"' "+sDisableValor+" id='vlrtot"+obj.data[i].e62_sequen+"'";
        saida += " value='" + sValorTotal + "' size='5' class='valores' oninput=\"js_ValidaCampos(this, 4, 'Valor Total', '', '', event);\" onblur='js_calculaValor("+obj.data[i].e62_sequen+",2)'></td></tr>";
      }
      $('confirmar').disabled = true;
    }
    saida += "<tr style='height:auto'><td colspan='10'>&nbsp;</td></tr>";
    $('dados').innerHTML  = saida;
    if (nSaldoEmpenho == 0) {

      $('confirmar').disabled = true;
      alert('Não há saldo disponível para anular.');
    }else if (sErroSaldo == obj.data.length){

      $('confirmar').disabled = true;
      alert('todos os itens estao em ordem de compra ou anulados. Não podera ser feita a anulação do empenho.');
    }else if (nSaldoEmpenho > 0){
      $('confirmar').disabled = false;
    }
    //grid dos itens solicitados para anular
    saida                 = '';
    if (obj.itensAnulados.length > 0){
      for (var i = 0; i < obj.itensAnulados.length;i++){

        descrmater = obj.itensAnulados[i].pc01_descrmater.replace(/\+/g," ");
        descrmater = unescape(descrmater);

        saida += "<tr class='normal' style='height:1em' id='trchkmarcaanulado"+obj.itensAnulados[i].e62_sequen+"'>";
        saida += "  <td class='linhagrid' style='text-align:center'>";
        saida += "  <input type='hidden' value='"+obj.itensAnulados[i].e36_empsolicitaanul+"' id='e36_empsolic"+obj.itensAnulados[i].e62_sequen+"'>";
        saida += "  <input type='checkbox' onclick='js_marcaSolic(this);' class='chkmarcaanulado'";
        saida += "    name ='chkanulado"+obj.itensAnulados[i].e62_sequen+"' id='chkmarcaanulado"+obj.itensAnulados[i].e62_sequen+"'";
        saida += "    value='"+obj.itensAnulados[i].e62_sequen+"' style='height:12px'></td>";
        saida += "  <td class='linhagrid' style='text-align:right'>"+obj.itensAnulados[i].e62_sequen+"</td>";
        saida += "  <td class='linhagrid' style='text-align:left'>"+descrmater+"</td>";
        saida += "  <td class='linhagrid' id='qtdsolic"+obj.itensAnulados[i].e62_sequen+"' style='text-align:right'>"+obj.itensAnulados[i].e36_qtdanu+"</td>";
        saida += "  <td class='linhagrid' id='vlrsolic"+obj.itensAnulados[i].e62_sequen+"' style='text-align:right'>"+obj.itensAnulados[i].e36_vrlanu+"</td>";
        saida += "</tr>";
      }
    }
    saida += "<tr style='height:auto'><td colspan='10'>&nbsp;</td></tr>";
    $('itenssolicitados').innerHTML = saida;
    js_removeObj("msgBox");
    //$('pesquisar').disabled = false;
  }
  //seta os valores das solicitações de anulacao
  function setValoresItem(item){

    $('confirmar').disabled = false;
    nValor      = js_strToFloat($('vlrsolic'+item.value).innerHTML);
    nQtde       = js_strToFloat($('qtdsolic'+item.value).innerHTML);
    nItensDigi  = $F('qtdesol'+item.value); //valores digitados pelo usuario
    nValorDigi  = $F('vlrtot'+item.value);  //valores digitados pelo usuario
    nItensEmp   = js_strToFloat($('saldo'+item.value).innerHTML);//saldo de itens do empenho
    nValorEmp   = js_strToFloat($('saldovlr'+item.value).innerHTML); //saldo do valor do item;
    nValorTotal = nValorDigi + nValor;
    nQtdeTotal  = nItensDigi + nQtde;

    if (item.checked){

      /**
       * Marcamos o item do empenho e atualizamos os valores caso o valor nao seja maior que
       * o saldo do empenho.
       */
      if ((nValorTotal <= nValorEmp) && (nQtdeTotal <= nItensEmp)){
        if (!$('chkmarca'+item.value).checked){
          $('chkmarca'+item.value).click();
        }
        $('vlrtot'+item.value).value  = js_round(nValorTotal, 2);
        $('qtdesol'+item.value).value = nQtdeTotal;
      }else{
        alert("Quantidade de itens/valor maior que  saldos do empenho!");
        item.checked = false;
        $('trchkmarcaanulado'+item.value).className='normal';
      }

    }else{
      if ($('chkmarca'+item.value).checked){
        $('chkmarca'+item.value).click();
      }

      nValorTotal = js_round(nValorDigi-nValor , 2);
      nQtdeTotal  = (nItensDigi - nQtde);

      if (nValorTotal < 0){
        nValorTotal = 0;
      }
      if (nQtdeTotal < 0){
        nQtdeTotal = 0;
      }
      $('vlrtot'+item.value).value  = nValorTotal;
      $('qtdesol'+item.value).value = nQtdeTotal;
    }
    setTotal();
  }
  //controle dos valores digitados no empenho.
  function js_calculaValor(id,tipo){

    $('confirmar').disabled = false;
    var nVlrUni        = js_strToFloat($('vlruni'+id).innerHTML);
    var nQtde          = $F('qtdesol'+id);
    var nVlrTotal      = $F('vlrtot'+id);

    //consideramos como saldo valido os saldos do empenho menos o saldo solicitado.
    var iSaldoSolic    = 0;
    var nSaldoVlrSolic = 0;
    if ($('qtdsolic'+id)){
      iSaldoSolic = new Number($('qtdsolic'+id).innerHTML);
    }
    if ($('vlrsolic'+id)){
      nSaldoVlrSolic = new Number($('vlrsolic'+id).innerHTML);
    }
    iSaldo         = ((js_strToFloat($('saldo'+id).innerHTML))    - iSaldoSolic);
    iSaldovlr      = ((js_strToFloat($('saldovlr'+id).innerHTML)) - nSaldoVlrSolic);
    if (nQtde > 0){
      $('vlrtot'+id).disabled = true;
    }else if (nQtde == 0) {

      $('vlrtot'+id).disabled = false;
      if (tipo == 1) {
        $('vlrtot'+id).value    = "";
      }

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
    }else if(tipo == 2){

      if (nQtde == '' || nQtde == 0) {
        nTotal = (nVlrTotal/nVlrUni);
        if ((nVlrTotal <= iSaldovlr)){
          if (nTotal > 0){
            // $('qtdesol'+id).value = nTotal.toFixed(2);
            $('confirmar').disabled = false;
            if ($('chkmarca'+id).checked == false ){
              $('chkmarca'+id).click();
            }
          }
        }else{

          alert("Valor total maior que o saldo restante.");
          $('confirmar').disabled = true;

        }
      }else{
        if ((nVlrTotal > iSaldovlr)){
          alert("Valor total maior que o saldo restante.");
          $('confirmar').disabled = true;
        }
      }
    }
    setTotal();
  }


  function setTotal(){

    aListaValores = js_getElementbyClass(form1,'valores');
    var nTotal = 0;
    for (var i = 0; i < aListaValores.length; i++){

      if (aListaValores[i].value.indexOf(',') > 0) {

        aListaValores[i].value = aListaValores[i].value.replace('.', '');
        aListaValores[i].value = aListaValores[i].value.replace(',', '.');
      }

      nTotal += Number(aListaValores[i].value);
    }
    $('valortotal').innerHTML = js_formatar(nTotal, 'f');
  }

  function js_anularEmpenho(){


    //validamos se existe solicitacao de anulacao. caso exista, e nao foi atendida confirmar se o usuário
    //se realmente vai deixar sem atendar a solicitracao.
    var itensAnulados = js_getElementbyClass(form1,'chkmarcaanulado');
    var iErro         = 0;
    var sSolicAtend   = '';
    var sV            = '';
    if (confirm('Confirma a anulação do empenho?')){

      if ($F('motivo').trim() == '') {

        alert('Motivo da anulação não informado');
        return false;

      }
      if (itensAnulados.length > 0){

        var sSolicAnt = 0;
        for (i = 0;i < itensAnulados.length;i++){
          if (itensAnulados[i].checked == true){

            //so uma entrada para cada solicitacao.
            if (sSolicAnt != $F('e36_empsolic'+itensAnulados[i].value)){
              sSolicAtend += sV+"{'e35_sequencial':'"+$F('e36_empsolic'+itensAnulados[i].value)+"'}";
              sV           = ','
            }
            sSolicAnt    = $F('e36_empsolic'+itensAnulados[i].value);
          }else{
            iErro++;
          }
        }
      }

      if (iErro != 0){

        if (!confirm('Existem solicitações de anulação para esse empenho. Há itens não antendidos.\nContinuar assim mesmo?')){
          return false;
        }
      }
      //pegamos os itens selecionados pelo usuário.
      itens                   = js_getElementbyClass(form1,'chkmarca');
      itensEmp                = '';
      sV                      = '';
      //$('pesquisar').disabled = true;
      //$('confirmar').disabled = true;
      valorTotal = 0;
      for (i = 0;i < itens.length;i++){
        if (itens[i].checked == true){

          if ($F('vlrtot'+itens[i].value) != 0 && $F('vlrtot'+itens[i].value) !=''){
            /*
             * verificamos o total digitado pelo usuario;
             * caso o total seja maior que o existente avisamos o usuário e nao deixamos
             * continuar com a operaçao;
             */
            nVlrItem    = new Number($('qtdesol'+itens[i].value).value);
            nVlrSaldo   = new Number($('vlrtot'+itens[i].value).value);
            nItensEmp   = new Number($('saldo'+itens[i].value).innerHTML);//saldo de itens do empenho
            nValorEmp   = new Number($('saldovlr'+itens[i].value).innerHTML); //saldo do valor do item;
            if ( (nVlrItem > nItensEmp) || ( nVlrSaldo > nValorEmp) ){

              $('pesquisar').disabled = false;
              $('confirmar').disabled = false;
              alert('Item '+itens[i].value+' com valor/quantidade maior que o disponível.\nVerifique.');
              return false;
            }
            itensEmp   += sV+'{"e62_sequencial":"'+$F('e62_sequencial'+itens[i].value)+'","sequen":"'+itens[i].value+'","quantidade":"';
            itensEmp   += $F('qtdesol'+itens[i].value)+'","vlrtot":"'+$F('vlrtot'+itens[i].value)+'",';
            itensEmp   += '"vlruni":"'+js_strToFloat($('vlruni'+itens[i].value).innerHTML)+'"}';
            sV          = ",";

            valorTotal += nVlrSaldo;
          }

        }
      }

      if ($('reserva').checked){
        lRecriarReserva = true;
      } else {
        lRecriarReserva = false;
      }

      var sJson  = '{"method":"anularEmpenho","iEmpenho":"'+$F('e60_numemp')+'","itensAnulados":['+itensEmp+'],"nValor":"'+valorTotal+'",';
      sJson     += '"aSolicitacoes":['+sSolicAtend+'],"lRecriarReserva":'+lRecriarReserva+',"sMotivo":"'+encodeURIComponent($F('motivo'))+'",';
      sJson     += '"iTipoAnulacao":'+$F('e94_empanuladotipo')+'}';
      if (itensEmp != ''){

        js_divCarregando("Aguarde, efetuando Anulação do Empenho.","msgBox");
        var url     = 'emp4_liquidacao004.php';
        var oAjax   = new Ajax.Request(
          url,
          {
            method: 'post',
            parameters: 'json='+sJson,
            onComplete: js_saidaAnulacao
          }
        );
      }else{

        alert('Selecione ao menos 1 (um) item para anular');'vlrtot'+itens[i].value
        $('pesquisar').disabled = false;
        $('confirmar').disabled = false;

      }
    }
  }
  function js_saidaAnulacao(oAjax){

    $('pesquisar').disabled = false;
    $('confirmar').disabled = false;
    js_removeObj("msgBox");
    obj                     = eval("("+oAjax.responseText+")");
    if (obj.status == 1){

      alert('A Anulação foi realizada com sucesso!');
      if ($('imprimir').checked){

        jan = window.open('emp2_anulemp002.php?e60_numemp='+$F('e60_numemp'),'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        jan.moveTo(0,0);
      }
      js_reset();
      js_pesquisa();
    }else{

      mensagem = obj.mensagem.replace(/\+/g," ");
      mensagem = unescape(mensagem);
      alert(mensagem)
    }


  }
  function js_reset(){

    $('form1').reset();
    $('dados').innerHTML            = '';
    $('itenssolicitados').innerHTML = '';
  }
  function js_marca(idObjeto, sClasse, sLinha){

    obj = document.getElementById(idObjeto);
    if (obj.checked){
      obj.checked = false;
    }else{
      obj.checked = true;
    }
    itens = js_getElementbyClass(form1, sClasse);
    for (i = 0;i < itens.length;i++){

      if (itens[i].disabled == false){
        if (obj.checked == true){

          itens[i].click();
        }else{
          itens[i].click();
        }
      }
    }
  }

  function js_marcaLinha(obj, linha, sClassName) {

    if (sClassName == null) {
      sClassName = 'normal';
    }
    if (obj.checked) {
      $(linha+obj.id).className='marcado';
    } else {
      $(linha+obj.id).className=sClassName;
    }
  }
  //js_pesquisa();
</script>
