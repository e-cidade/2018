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

//MODULO: material
$clmatpedido->rotulo->label();
$clrotulo = new rotulocampo();
$clrotulo->label("nome");
$clrotulo->label("descrdepto");
?>
<!--<form name="form1" method="post" action="" onsubmit='js_buscavalores();' >-->
<form name="form1" method="post" action="">
<center>
<table border='0' width='81%'>
  <tr>
    <td>
      <fieldset style="width: 98%"><legend><b>Dados da Solicitação</b></legend>
        <table border="0">
          <tr>
            <td nowrap title="<?=@$Tm97_sequencial?>">
              <b>Código da Solicitação: </b></td>
            <td>
              <?db_input( 'm97_sequencial', 10, $Im97_sequencial, true, 'text', 3, "" )?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tm97_data?>">
              <?=@$Lm97_data?>
            </td>
            <td>
              <?db_inputdata( 'm97_data', @$m97_data_dia, @$m97_data_mes, @$m97_data_ano, true, 'text', 3, "" )?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tm97_hora?>">
              <?=@$Lm97_hora?>
            </td>
            <td>
              <?db_input( 'm97_hora', 10, $Im97_hora, true, 'text', 3, "" )?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tm97_login?>">
              <?=@$Lm97_login?>
            </td>
            <td>
              <?db_input( 'm97_login', 10, $Im97_login, true, 'text', 3, " " )?>
              <?db_input( 'nome', 40, $Inome, true, 'text', 3, '' )?>
            </td>
          </tr>
          <tr>
            <td nowrap title="departamento destino">
              <b>Departamento Destino</b>
            </td>
            <td>
              <?
              db_input( 'm97_coddepto', 10, $Im97_coddepto, true, 'text', 3, "" );
              db_input( 'descrdepto', 40, $Idescrdepto, true, 'text', 3, '' );
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="departamento origem">
              <b>Departamento Origem</b>
            </td>
            <td>
              <?
              db_input( 'm91_depto', 10, $Im97_coddepto, true, 'text', 3, "" );
              db_input( 'descr_depto', 40, @$Idescr_depto, true, 'text', 3, '' );
              db_input( 'linha', 40, @$linha, true, 'hidden', 3, '' );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>
      <table width='100%'>
        <tr>
          <td rowspan='1' valign='top' height='100%'>
            <fieldset><legend><b>Itens da Solicitação : </legend>
              <div id='grid_solicitacao' style='width: 100%; -moz-user-select: none'></div>
            </fieldset>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center">
      <input name="incluir" type="button" value="Efetuar Atendimento" onclick="return js_atendeSolicitacao();">
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
      <?
      db_input( 'solicitacao', 10, '', true, 'hidden', 3 );
      db_input( 'm80_codigo', 10, '', true, 'hidden', 3 );
      db_input( "valores", 100, 0, true, "hidden", 3 );
      ?>
    </td>
  </tr>
</table>
</center>
</form>
<script>
iTipoControle = <?=$iTipoControleCustos;?>;
function js_pesquisa() {

  sFiltro = "sFiltro=almox";
  js_OpenJanelaIframe('top.corpo','db_iframe_atendsolitransf','func_atendsolitransf.php?'+sFiltro+'&funcao_js=parent.js_preenchepesquisa|m97_sequencial','Pesquisa',true);

}

function js_preenchepesquisa(chave) {

  db_iframe_atendsolitransf.hide();
  js_consultaSolicitacao(chave);

}


function js_marca(){

  for (var a=0; a < document.form1.linha.value ; a++) {

    if(document.getElementById('chk'+a).disabled==false){

      if(document.getElementById('chk'+a).checked==false){
        document.getElementById('chk'+a).checked = true;
      }else{
        document.getElementById('chk'+a).checked = false;
      }

    }

  }

}




function js_janelaSolicitacao(oAjax) {

  var obj = eval("(" + oAjax.responseText + ")");
  if (obj.status && obj.status == 2) {

    js_removeObj("msgBox");
    alert(obj.sMensagem.urlDecode());
    return false ;

  }
  $('m97_sequencial').value = obj.m97_sequencial;
  $('m97_data').value       = js_formatar(obj.m97_data,"d");
  $('m97_hora').value       = obj.m97_hora.urlDecode();
  $('m97_login').value      = obj.m97_login;
  $('m97_coddepto').value   = obj.m97_coddepto;
  $('m91_depto').value      = obj.m91_depto;
  $('descrdepto').value     = obj.descrdepto.urlDecode();
  $('descr_depto').value    = obj.descr_depto.urlDecode();
  $('nome').value           = obj.nome.urlDecode();
  var iErros    = new Number(0);
  var lDisabled = true;
  var saida     = "";
  oDBGridSolicitacao                = new DBGrid('grid_solicitacao');
  oDBGridSolicitacao.nameInstance   = 'oDBGridSolicitacao';
  oDBGridSolicitacao.hasTotalizador = true;
  aHeader     = new Array();
  aHeader[0]  = "<a style=\"color:black;text-decoration:none;\" href=\"javascript:js_marca()\">M</a>";
  aHeader[1]  = 'Material';
  aHeader[2]  = 'Descrição do Material';
  aHeader[3]  = 'Unidade de Saída';
  aHeader[4]  = 'Qtd.Solic';
  aHeader[5]  = 'Qtd.Atendida';
  aHeader[6]  = 'Qtd.em Estoque';
  aHeader[7]  = 'Qtd.Anulada';
  aHeader[8]  = 'Qtd.Pendente';
  aHeader[9]  = 'Qtd. Retirar';
  aHeader[10] = 'Codigo';
  oDBGridSolicitacao.setHeader(aHeader);
  oDBGridSolicitacao.setCellWidth(['2%', '50%', '10%', '5%', '5%', '5%', '5%', '5%', '5%', '5%']);
  oDBGridSolicitacao.setHeight(200);
  oDBGridSolicitacao.allowSelectColumns(true);
  var aAligns = new Array();
  aAligns[0]  = 'center';
  aAligns[1]  = 'left';
  aAligns[2]  = 'left';
  aAligns[3]  = 'center';
  aAligns[4]  = 'center';
  aAligns[5]  = 'center';
  aAligns[6]  = 'center';
  aAligns[7]  = 'center';
  aAligns[8]  = 'center';
  aAligns[9]  = 'center';
  aAligns[10] = 'center';
  oDBGridSolicitacao.setCellAlign(aAligns);
  oDBGridSolicitacao.aHeaders[1].lDisplayed = false;
  oDBGridSolicitacao.aHeaders[10].lDisplayed = false;
  oDBGridSolicitacao.show($('grid_solicitacao'));
  oDBGridSolicitacao.clearAll(true);
  if (obj) {

    var aLinha = new Array();
    for (var iInd = 0; iInd < obj.itens.length; iInd++) {

      with (obj.itens[iInd]) {

        lhabilitado = "";
        if (qtdpendente==0 || qtdeestoque==0) {
          lhabilitado = " disabled ";
        }
        aLinha[0]  = "<input "+lhabilitado+" name='chk"+iInd+"' id='chk"+iInd+"' value='"+iInd+"' class='chkmarca' size=1 type='checkbox'>";
        aLinha[1]  = m98_matmater;
        aLinha[2]  = m60_descr.urlDecode();
        aLinha[3]  = m61_descr.urlDecode();
        aLinha[4]  = m98_quant;
        aLinha[5]  = totalatendido;
        aLinha[6]  = qtdeestoque;
        aLinha[7]  = qtdanulada;
        aLinha[8]  = qtdpendente;
        aLinha[9]  =" <input "+lhabilitado+" name='atendido"+iInd+"' id='atendido"+iInd+"' value='"+(qtdpendente)+"'size=10 type='text' onblur='js_verificaQuantidade(this.value, \""+(qtdpendente)+"\",\"Quantidade maior que o saldo.\",\"Quantidade deve ser maior que zero.\")'>";
        aLinha[10]  = m98_sequencial;
        oDBGridSolicitacao.addRow(aLinha);
        oDBGridSolicitacao.aRows[iInd].isSelected = true;

      }

    }
    document.form1.linha.value = obj.itens.length;
    oDBGridSolicitacao.renderRows();

  }
  js_removeObj("msgBox");
  oWindowSolicitacao.show(60, 90);

}

function js_verificaQuantidade(nValor, nMaximo, sMsg, sMsg1){

  if (nValor=="" || nValor==0) {

    alert(sMsg1);
    return false;

  }
  if (parseFloat(nValor) > parseFloat(nMaximo)) {

    alert(sMsg);
    return false;

  }
  return true;

}

function js_consultaSolicitacao(iSolicitacao) {

  js_divCarregando("Aguarde, efetuando pesquisa","msgBox");
  strJson = '{"exec":"getDados_solicitacao","params":[{"iCodSol":'+iSolicitacao+'}]}';
  var url     = 'mat4_atendsolicitacaoRPC.php';
  var oAjax   = new Ajax.Request(
                                 url,
                                {
                                 method: 'post',
                                 parameters: 'json='+strJson,
                                 onComplete: js_janelaSolicitacao
                                }
                               );

}

function js_atendeSolicitacao() {

  if (confirm('Confirma Atendimento da Solicitação?')) {

    aItens= new Array();
    vVerif = false;
    for (var a=0; a < document.form1.linha.value ; a++)  {

      aItens[a] =document.getElementById('chk'+a).checked;
      if (aItens[a]==true) {
        vVerif = true;
      }

    }
    sJsonItem        = "";
    if (vVerif == false) {

      alert('Selecione um item para efetuar o atendimento');
      return false;

    }
    js_divCarregando("Aguarde, efetuando atendimento","msgBox");
    sVirgula = "";
    for (var i = 0; i < aItens.length; i++) {

      if (aItens[i]==true) {

        var nTotalAtendido   = oDBGridSolicitacao.aRows[i].aCells[5].getValue();
        var nTotalSolicitado = oDBGridSolicitacao.aRows[i].aCells[4].getValue() ;
        var nTotalDigitado   = new Number($('atendido'+i).value);
        var sItemDescr       = oDBGridSolicitacao.aRows[i].aCells[2].getValue();
        var iCodMater        = oDBGridSolicitacao.aRows[i].aCells[1].getValue();
        var nSaldo           = new Number(nTotalSolicitado-nTotalAtendido);
        var iMatPedidoItem   = oDBGridSolicitacao.aRows[i].aCells[10].getValue();
        var sMsg             = "Item ("+sItemDescr+") sem saldo para efetuar o atendimento.";
        var sMsg1            = "Item ("+sItemDescr+") deve ter quantidade maior que zero.";
        if (js_verificaQuantidade(nTotalDigitado,nSaldo,sMsg,sMsg1)) {

          sJsonItem += sVirgula+"{'iMatPedidoItem':"+iMatPedidoItem+",'nQtde':"+nTotalDigitado+",";
          sJsonItem += "'iCodMater':"+iCodMater+",'iCodDepto':"+$F('m91_depto')+",'iCodEstoque':"+$F('m97_coddepto')+"}";
          sVirgula  = ",";

        } else {

          js_removeObj("msgBox");
          return false;

        }
      }
    }
    sParams    = "'$iCodSol':"+$F('m97_sequencial')+",'iTipo':5,'aItens':["+sJsonItem+"],'iCodEstoque':"+$F('m91_depto')+",'iCodDepto':"+$F('m97_coddepto');
    var sJson  = "{'exec':'atendeSolicitacao','params':[{"+sParams+"}]}";
    var url    = 'mat4_atendsolicitacaoRPC.php';
    var oAjax   = new Ajax.Request(
                                   url,
                                  {
                                    method: 'post',
                                    parameters: 'json='+sJson,
                                    onComplete: js_saidaAtendimento
                                  }
                                 );

  }

}

function js_saidaAtendimento(oAjax) {

  js_removeObj("msgBox");
  var obj = eval("(" + oAjax.responseText + ")");
  if (obj.status == 2) {

    alert(obj.message.urlDecode());
    return false;

  } else {

    if (confirm('Atendimento Efetuado com sucesso.\nDeseja imprimir a Solicitação de Material?')) {

      obj    = document.form1;
      query  ='';
      query += "&ini="+obj.m97_sequencial.value;
      query += "&fim="+obj.m97_sequencial.value;
      query += "&codalmox="+obj.m91_depto.value;
      query += "&departamento=<?=db_getsession ( "DB_coddepto" )?>";
      jan    = window.open('mat2_matpedido001.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);

    }
    js_reset();
    js_pesquisa();

  }

}

function js_mostraLotes(iItem) {

  iCodItem      = new Number($('codmater'+iItem).innerHTML);//código do material
  nValor        = new Number($('atendido'+iItem).value);//Quantidade digitada pelo usuário
  iCodEstoque   = new Number($F('m91_depto')); //Codigo do departamento estoque
  nValorSolItem = new Number($('quantsol'+iItem).innerHTML);
  sField        = $('atendido'+iItem).id;
  sUrl          = 'mat4_mostraitemlotes.php?iCodMater='+iCodItem+'&iCodDepto='+iCodEstoque;
  sUrl         += '&nValor='+nValor+'&nValorSolicitado='+nValorSolItem+'&updateField='+sField;
  js_OpenJanelaIframe('top.corpo','db_iframe_lotes',sUrl,'Lotes ',true);

}

function js_reset(){

  document.form1.reset();
  $('grid_solicitacao').innerHTML = '';

}

js_pesquisa();
</script>