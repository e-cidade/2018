<?
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

//MODULO: material
$clatendrequi->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("descrdepto");
?>
<form name="form1" method="post" action=""  >
<center>
<table width="100%">
  <tr>
    <td>
      <br></br>
      <fieldset><legend><b>Anula��o da Requisi��o</b></legend>
        <table border="0">
          <tr>
            <td nowrap title="<?=@$Tm40_codigo?>">
              <b>C�digo da Requisi��o: </b>
            </td>
            <td>
              <?db_input('m40_codigo',10,@$Im40_codigo,true,'text',3,"")?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tm40_data?>">
              <?=@$Lm40_data?>
            </td>
            <td>
              <?db_inputdata('m40_data',@$m40_data_dia,@$m40_data_mes,@$m40_data_ano,true,'text',3,"")?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tm40_hora?>">
              <?=@$Lm40_hora?>
            </td>
            <td>
              <?db_input('m40_hora',5,@$Im40_hora,true,'text',3,"")?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tm40_login?>">
              <?=@$Lm40_login?>
            </td>
            <td>
              <?db_input('m40_login',10,@$Im40_login,true,'text',3," ")?>
              <?db_input('nome',40,@$Inome,true,'text',3,'')?>
            </td>
          </tr>
          <tr>
            <td nowrap title="departamento destino">
              <b>Departamento Destino</b>
            </td>
            <td>
              <?
              db_input ( 'm40_depto', 10, @$Im40_depto, true, 'text', 3, "" );
              db_input ( 'descrdepto', 40, @$Idescrdepto, true, 'text', 3, '' );
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="departamento origem">
              <b>Departamento Origem</b>
            </td>
            <td>
              <?
              db_input ( 'm91_depto', 10, @$Im40_depto, true, 'text', 3, "" );
              db_input ( 'descr_depto', 40, @$Idescr_depto, true, 'text', 3, '' );
              db_input ( 'linha', 40, @$linha, true, 'hidden', 3, '' );
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
            <fieldset><legend><b>Itens da Solicita��o: </legend>
              <div id='grid_anularequisicao' style='width: 100%; -moz-user-select: none'></div>
            </fieldset>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center">

      <?php
        if (!USE_PCASP) {
          echo '<input id="btnAnularRequisicao" name="anular" type="button"  value="Anular Requisi��o" onclick="return js_atendeRequisicao();" >';
        }
      ?>

      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
      <?
      db_input('atendimento',10,'',true,'hidden',3);
      db_input('m80_codigo',10,'',true,'hidden',3);
      db_input("valores",100,0,true,"hidden",3);
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
  js_OpenJanelaIframe('top.corpo','db_iframe_atendrequi','func_matrequianula.php?'+sFiltro+'&lSaldoZerado=1&funcao_js=parent.js_preenchepesquisa|m40_codigo','Pesquisa',true);
}


function js_preenchepesquisa(chave) {

  db_iframe_atendrequi.hide();
  js_consultaRequisicao(chave);

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
  $('m40_codigo').value  = obj.m40_codigo;
  $('m40_data').value    = js_formatar(obj.m40_data,"d");
  $('m40_hora').value    = obj.m40_hora.urlDecode();
  $('m40_login').value   = obj.m40_login;
  $('m40_depto').value   = obj.m40_depto;
  $('m91_depto').value   = obj.m91_depto;
  $('descrdepto').value  = obj.descrdepto.urlDecode();
  $('descr_depto').value = obj.descr_depto.urlDecode();
  $('nome').value        = obj.nome.urlDecode();
  var iErros             = new Number(0);
  var lDisabled          = true;
  var lHabilitado        = "";
  var saida              = "";
  oDBGridanulaRequisicao                = new DBGrid('grid_anulaRequisicao');
  oDBGridanulaRequisicao.nameInstance   = 'oDBGridanulaRequisicao';
  oDBGridanulaRequisicao.hasTotalizador = true;
  oDBGridanulaRequisicao.setCheckbox(0);

  aHeader     = new Array();
  aHeader[0]  = 'C�digo do Material';
  aHeader[1]  = 'Descri��o do Material';
  aHeader[2]  = 'Unidade de Sa�da';
  aHeader[3]  = 'Qtd.Solic';
  aHeader[4]  = 'Qtd.Atendida';
  aHeader[5]  = 'Qtd.em Estoque';
  aHeader[6]  = 'Qtd.Anulada';
  aHeader[7]  = 'Qtd.Pendente';
  aHeader[8]  = 'Qtd.Anular';
  aHeader[9]  = 'Motivo';
  aHeader[10] = 'C�d. Atendimento';
  oDBGridanulaRequisicao.setHeader(aHeader);
  oDBGridanulaRequisicao.setHeight(200);
  oDBGridanulaRequisicao.allowSelectColumns(true);

  var aAligns = new Array();
  aAligns[0]  = 'left';
  aAligns[1]  = 'left';
  aAligns[2]  = 'center';
  aAligns[3]  = 'center';
  aAligns[4]  = 'center';
  aAligns[5]  = 'center';
  aAligns[6]  = 'center';
  aAligns[7]  = 'center';
  aAligns[8]  = 'center';
  aAligns[9]  = 'center';
  aAligns[10] = 'center';
  oDBGridanulaRequisicao.setCellAlign(aAligns);
  oDBGridanulaRequisicao.show($('grid_anularequisicao'));
  oDBGridanulaRequisicao.clearAll(true);
  if (obj) {

    var aLinha = new Array();
    for (var iInd = 0; iInd < obj.itens.length; iInd++) {

      with (obj.itens[iInd]) {

        eval("oMotivo"+iInd+" = new DBTextField('motivo"+iInd+"','oMotivo"+iInd+"',''),10;");
        eval("oMotivo"+iInd+".setExpansible(true);");
        lhabilitado = "false";
        if (qtdpendente==0 ){
          lhabilitado = "true";
        }
        aLinha[0]  = m41_codmatmater;
        aLinha[1]  = m60_descr.urlDecode();
        aLinha[2]  = m61_descr.urlDecode();
        aLinha[3]  = m41_quant;
        aLinha[4]  = totalatendido;
        aLinha[5]  = qtdeestoque;
        aLinha[6]  = qtdanulada;
        aLinha[7]  = qtdpendente;
        aLinha[8]  =" <input readonly "+lhabilitado+" name='atendido"+iInd+"' id='atendido"+iInd+"' value='"+(qtdpendente)+"'size=10 type='text' onblur='js_verificaQuantidade(this.value, \""+(qtdpendente)+"\",\"Quantidade maior que o saldo.\",\"Quantidade deve ser maior que zero.\")'>";
        aLinha[9]  = eval("oMotivo"+iInd+".toInnerHtml();");
        aLinha[10] = m41_codigo;
        oDBGridanulaRequisicao.addRow(aLinha);
        oDBGridanulaRequisicao.aRows[iInd].isSelected = true;

      }

    }
    document.form1.linha.value = obj.itens.length;
    oDBGridanulaRequisicao.renderRows();

  }
  js_removeObj("msgBox");
  oWindowanulaRequisicao.show(60, 90);

}

function js_verificaQuantidade(nValor, nMaximo, sMsg, sMsg1) {

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

function js_consultaRequisicao(iRequisicao) {

  js_divCarregando("Aguarde, efetuando pesquisa", "msgBox");
  strJson = '{"exec":"getDadosPedidoRequisicao","params":[{"iCodReq":'+iRequisicao+'}]}';
  var url     = 'mat4_requisicaoRPC.php';
  var oAjax   = new Ajax.Request(url,
                                 {
                                   method: 'post',
                                   parameters: 'json='+strJson,
                                   onComplete: js_janelaSolicitacao
                                 }
                                );

}

function js_atendeRequisicao() {

  if (confirm('Confirma Anula��o da Requisi��o?')) {

    aItens = oDBGridanulaRequisicao.getSelection();

    sJsonItem = "";
    if ( aItens.length == 0) {

      alert('Selecione um item para efetuar a Anula��o');
      return false;

    }

    js_divCarregando("Aguarde, efetuando atendimento","msgBox");
    sVirgula         = "";
    for (var i = 0; i < aItens.length; i++) {

      with(aItens[i]) {

        var nTotalAtendido   = oDBGridanulaRequisicao.aRows[i].aCells[5].getValue();
        var nTotalSolicitado = oDBGridanulaRequisicao.aRows[i].aCells[4].getValue() ;
        var nTotalDigitado   = new Number($('atendido'+i).value);
        var sItemDescr       = oDBGridanulaRequisicao.aRows[i].aCells[2].getValue();
        var iCodMater        = oDBGridanulaRequisicao.aRows[i].aCells[1].getValue();
        var nSaldo           = new Number(nTotalSolicitado-nTotalAtendido);
        var sItemMotivo      = oDBGridanulaRequisicao.aRows[i].aCells[10].getValue();
        var iCodItemReq      = oDBGridanulaRequisicao.aRows[i].aCells[11].getValue();
        if ($('cc08_sequencial'+aItens[i].value)) {
          var iCodigoCriterio  = $('cc08_sequencial'+aItens[i].value).innerHTML;
        }
        var sMsg  = "Item ("+sItemDescr+") sem saldo para efetuar o atendimento.";
        var sMsg1 = "Item ("+sItemDescr+") deve ter quantidade maior que zero.";
        if (js_verificaQuantidade(nTotalDigitado,nSaldo,sMsg,sMsg1)) {

          sJsonItem += sVirgula+"{'iCodItemReq':"+iCodItemReq+",'nQtde':"+nTotalDigitado+",";
          sJsonItem += "'iCodMater':"+iCodMater+",'iCentroDeCusto':"+iCodigoCriterio+",'sItemMotivo':'"+sItemMotivo+"'}";
          sVirgula  = ",";

        } else {

          js_removeObj("msgBox");
          return false;

        }
        var sMsgMotiv = "Item ("+sItemDescr+") deve estar com o motivo preenchido.";
        if (sItemMotivo=="") {

          alert(sMsgMotiv);
          js_removeObj("msgBox");
          return false;

        }

      }

    }
    sParams    = "'iCodReq':"+$F('m40_codigo')+",'iTipo':17,'aItens':["+sJsonItem+"],'iCodEstoque':"+$F('m91_depto');
    var sJson  = "{'exec':'anularRequisicao','params':[{"+sParams+"}]}";
    var url     = 'mat4_requisicaoRPC.php';
    var oAjax   = new Ajax.Request(url,
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

    if (confirm('Anula��o Efetuada com sucesso.\nDeseja imprimir a Anula��o de Material?')) {

      obj    = document.form1;
      query  ='';
      query += "&ini="+obj.m40_codigo.value;
      query += "&fim="+obj.m40_codigo.value;
      jan    = window.open('mat2_anulareq001.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);

    }
    js_reset();
    js_pesquisa();

  }

}

function js_mostraLotes(iItem) {

  iCodItem      = oDBGridanulaRequisicao.aRows[iItem].aCells[1].getValue();//c�digo do material
  nValor        = new Number($('atendido'+iItem).value);//Quantidade digitada pelo usu�rio
  iCodEstoque   = new Number($F('m91_depto')); //Codigo do departamento estoque
  nValorReqItem = oDBGridanulaRequisicao.aRows[iItem].aCells[4].getValue();
  sField        = new Number($('atendido'+iItem).value);
  sUrl          = 'mat4_mostraitemlotes.php?iCodMater='+iCodItem+'&iCodDepto='+iCodEstoque;
  sUrl         += '&nValor='+nValor+'&nValorSolicitado='+nValorReqItem+'&updateField='+sField;
  js_OpenJanelaIframe('top.corpo','db_iframe_lotes',sUrl,'Lotes ',true);

}

function js_reset() {

  document.form1.reset();
  $('dadosrequisicao').innerHTML = '';

}

function js_adicionaCentroCusto(iLinha, iCodItem) {

  var iOrigem  = 2;
  var sUrl     = 'iOrigem='+iOrigem+'&iCodItem='+iCodItem+'&iCodigoDaLinha='+iLinha;
  sUrl    += '&iCodigoDepto='+$F('m40_depto');
  js_OpenJanelaIframe('',
                      'db_iframe_centroCusto',
                      'cus4_escolhercentroCusto.php?'+sUrl,
                      'Centro de Custos',
                      true,
                      '25',
                      '1',
                      (document.body.scrollWidth-10),
                      (document.body.scrollHeight-100)
                     );

}

function js_completaCustos(iCodigo, iCriterio, iDescr) {

  $('cc08_sequencial'+iCodigo).innerHTML = iCriterio;
  $('cc08_descricao'+iCodigo).innerHTML  = iDescr;
  db_iframe_centroCusto.hide();

}

function js_reset() {

  document.form1.reset();
  $('grid_anularequisicao').innerHTML = '';

}
js_pesquisa();
</script>