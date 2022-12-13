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
$clatendrequi->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("descrdepto");
?>
<!--<form name="form1" method="post" action="" onsubmit='js_buscavalores();' >-->
<div class="container">
  <form name="form1" method="post" action="">
  <fieldset>
    <legend><b>Atendimento de Requisição</b></legend>
    <fieldset style="'text-align:left" class="separator">
      <legend><b>Dados da Requisição</b></legend>
        <table border="0">
        <tr>
          <td nowrap title="<?=@$Tm40_codigo?>">
             <?//=@$Lm40_codigo?>
          <b>Código da Requisição: </b>
          </td>
          <td>
            <?
            db_input('m40_codigo',10,$Im40_codigo,true,'text',3,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tm40_data?>">
             <?=@$Lm40_data?>
          </td>
          <td>
            <?php
            db_inputdata('m40_data',@$m40_data_dia,@$m40_data_mes,@$m40_data_ano,true,'text',3,"")
            ?>
          </td>
          <td style="text-align: right" nowrap title="<?=@$Tm40_hora?>">
             <?=@$Lm40_hora?>
          </td>
          <td>
            <?
            db_input('m40_hora', 10,$Im40_hora,true,'text',3,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tm40_login?>">
             <?=@$Lm40_login?>
          </td>
          <td colspan="3">
           <?php
            db_input('m40_login',10,$Im40_login,true,'text',3," ");
            db_input('nome',40,$Inome,true,'text',3,'')
             ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tm40_depto?>">
             <?=@$Lm42_depto?>
          </td>
          <td  colspan="3">
           <?php
            db_input('m40_depto',5,$Im40_depto,true,'hidden',3,"");
            db_input('m91_depto',10,$Im40_depto,true,'text',3,"");
            db_input('descrdepto',40,$Idescrdepto,true,'text',3,'');
             ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <fieldset class="separator">
      <legend>
        <b>Materiais da Requisição</b>
      </legend>
      <table  cellspacing=0 cellpadding=0 width='100%' style='border:2px inset white'>
            <tr>
              <th class='table_header'><input type='checkbox'  style='display:none'
                  id='mtodosnotas' onclick='js_marca()'>
              <a onclick='js_marca("mtodosnotas","chkmarca","linha")' style='cursor:pointer'>M</a></b></th>
              <th class='table_header'>Código do Material</th>
              <th class='table_header'>Descrição do Material</th>
              <th class='table_header'>Unidade de Saida</th>
              <th class='table_header'>Qtde Solic.</th>
              <th class='table_header'>Qtde Atend.</th>
              <th class='table_header'>Qtde Anul.</th>
              <th class='table_header'>Qtde Em Estoque</th>
              <th class='table_header'>Lote</th>
              <th class='table_header'>Qtde a Entregar</th>
              <?
              if ($iTipoControleCustos > 0) {
               echo "<th class='table_header'>Centro De Custo</th>";
              }

              ?>
              <th class='table_header' width='18px'>&nbsp;</td>
            </tr>
            <tbody id='dadosrequisicao' style='height:80;width:95%;overflow:scroll;overflow-x:hidden;background-color:white'>
            </tbody>
          </table>
          </fieldset>
   </fieldset>
    <!--
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Confirma":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    -->
         <input name="incluir" type="button"  value="Efetuar Atendimento" onclick="return js_atendeRequisicao();" >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  <?
  db_input('atendimento',10,'',true,'hidden',3);
  db_input('m80_codigo',10,'',true,'hidden',3);
  db_input("valores",100,0,true,"hidden",3);
  ?>
  </form>
  <div id='mensagempontopedido' style="width: 98%; text-align: left; display: inherit; background-color: #fcf8e3;border: 1px solid #fcc888;padding: 10px">
    asdfasdfasdf
  </div>
</div>
<script>
iTipoControle = <?=$iTipoControleCustos;?>;
function js_pesquisa(){

  sFiltro = "sFiltro=almox";
  js_OpenJanelaIframe('top.corpo','db_iframe_atendrequi','func_matrequiatend.php?'+sFiltro+'&funcao_js=parent.js_preenchepesquisa|m40_codigo','Pesquisa',true);

  $('mensagempontopedido').innerHTML     = '';
  $('mensagempontopedido').style.display = 'none';
}
function js_preenchepesquisa(chave){
  db_iframe_atendrequi.hide();
  <?
//  if($db_opcao!=1){
   // echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
//  }
  ?>
  js_consultaRequisicao(chave);
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
    $('atendido'+obj.value).disabled  = false;
    
  } else {
  
    $(linha+obj.id).className='normal';
    $('atendido'+obj.value).disabled  = true;
    
  }
}

//Faz a requisicao de saida de material.
function js_consultaRequisicao(iRequisicao){
   
   js_divCarregando("Aguarde, efetuando pesquisa","msgBox");
   strJson = '{"exec":"getDados","params":[{"iCodReq":'+iRequisicao+'}]}';
   $('dadosrequisicao').innerHTML    = '';
   //$('pesquisar').disabled = true;
   var url     = 'mat4_requisicaoRPC.php';
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

  js_removeObj("msgBox");
  var obj               = eval("(" + oAjax.responseText + ")");
  if (obj.status == 2) {
  
    alert(obj.message.urlDecode());
    return false;
     
  }
  $('m40_codigo').value = obj.m40_codigo;
  $('m40_data').value   = js_formatar(obj.m40_data,"d");
  $('m40_hora').value   = obj.m40_hora.urlDecode();
  $('m40_login').value  = obj.m40_login;
  $('m40_depto').value  = obj.m40_depto;
  $('m91_depto').value  = obj.m91_depto;
  $('descrdepto').value = obj.descrdepto.urlDecode();
  var iErros            = new Number(0);
  var lDisabled         = true;
  var lHabilitado       = "";
  var saida             = "";
  if (obj.itens) {

    var sPontoPedido        = '';
    var lMostrarPontoPedido = false;
    for (iInd = 0; iInd < obj.itens.length; iInd++){
    
      with (obj.itens[iInd]) {
        
        var lLote = false;
        if (m60_controlavalidade == 1 || m60_controlavalidade == 2) {
          lLote = true;
        }
        var lClassName = "normal";
        lHabilitado = "";
        nPendente = parseFloat(m41_quant) - (parseFloat(totalatendido) + parseFloat(quantanulada)); 
        if (nPendente == 0) {

          lHabilitado = " disabled ";
          lClassName  = "disabled";
        }

        if (avisarpontopedido) {

          sPontoPedido += "O item <b>"+m60_descr.urlDecode()+ "</b> atingiu o seu Ponto de Pedido: "+pontopedido+".<br>";
          lMostrarPontoPedido = true;
        }
        saida += "<tr class='"+lClassName+"'id='linhachk"+m41_codigo+"'>";
        saida += "  <td class='linhagrid'>";
        saida += "     <input type='checkbox' id='chk"+m41_codigo+"' onclick=\"js_marcaLinha(this,'linha');\""; 
        saida += "      value='"+m41_codigo+"' class='chkmarca' "+lHabilitado+"  style='height:12px'>";      
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:right' id='codmater"+m41_codigo+"'>";
        saida +=     m41_codmatmater;
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:left' id='descrmater"+m41_codigo+"'>";
        saida +=     m60_descr.urlDecode();
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:left' id='descrunid"+m41_codigo+"'>";
        saida +=     m61_descr.urlDecode();
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:right' id='quantsol"+m41_codigo+"'>";
        saida +=     m41_quant;
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:right' id='quantatend"+m41_codigo+"'>";
        saida +=     totalatendido;
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:right' id='quantanul"+m41_codigo+"'>";
        saida +=     quantanulada;
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:right' id='quantestoque"+m41_codigo+"'>";
        saida +=     qtdeestoque;
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:center' id='lote"+m41_codigo+"'>";
        if (lLote && nPendente>0){
            saida += "<a href='' onclick='js_mostraLotes("+m41_codigo+");return false'>lote</>";
        } else {
          saida += "&nbsp;";
        } 
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:center;width:10%'>";
        saida += "    <input type='text'  style='text-align:right' name='atendido"+m41_codigo+"'";
        saida += "           id='atendido"+m41_codigo+"'";
        saida += "           onblur='js_verificaQuantidade(this.value, "+nPendente+",\"Quantidade maior que o saldo.\")'";
        saida += "           value='"+nPendente+"' size='5' class='valores' disabled ";
        saida += "     onkeypress='return js_teclas(event)'>";
        saida += "   </td>";
        if (iTipoControle > 0){
        
          if (cc08_descricao == "") {
            cc08_descricao   = "Escolher";
          } else {
            cc08_descricao = cc08_descricao.urlDecode();
          } 
          saida += " <td class='linhagrid' style='text-align:right'>";
          if (lHabilitado == "") {
            
            saida += "   <span id='cc08_sequencial"+m41_codigo+"'>"+cc08_sequencial+"</span>";
            saida += "   <a id='cc08_descricao"+m41_codigo+"' href='#' ";
            saida += "     onclick='js_adicionaCentroCusto("+m41_codigo+","+m41_codmatmater+")';>"+cc08_descricao+"</a>";
            
          } else {
            saida +=" <span id='cc08_sequencial"+m41_codigo+"'>"+cc08_sequencial+" - "+cc08_descricao+"</span>";
          }
          saida += "  </td>";
        }
        saida += "</tr>";
        
        
      }
    }
    saida += "<tr style='height:auto'><td colspan=10>&nbsp;</td></tr>";
    $('dadosrequisicao').innerHTML = saida;
    if (lMostrarPontoPedido) {

      $('mensagempontopedido').innerHTML     = sPontoPedido;
      $('mensagempontopedido').style.display = '';
    }
  }

}
  
function js_verificaQuantidade(nValor, nMaximo,sMsg) {
    
  if (nValor > nMaximo) {
      
    alert(sMsg);
    return false;
      
  } 
  return true;
}

function js_atendeRequisicao() {

  if (confirm('Confirma Atendimento da Requisição?')) {
    
    /*
     * percorremos todos os itens marcados pelo usuário.
     * validamos a quantidade selecionada pelo usuário, que não pode ser maior que o saldo da requisição.
     */
    
    aItens = js_getElementbyClass(form1, "chkmarca", "checked==true");
    sJsonItem        = "";
    if (aItens.length == 0) {
      
      alert('Selecione um item para efetuar o Atendimento');
      return false;
      
    }
    sVirgula         = "";
    for (var i = 0; i < aItens.length; i++) {
    
      var nTotalAtendido   = new Number($('quantatend'+aItens[i].value).innerHTML);
      var nTotalSolicitado = new Number($('quantsol'+aItens[i].value).innerHTML);
      var nTotalDigitado   = new Number($('atendido'+aItens[i].value).value);
      var sItemDescr       = $('descrmater'+aItens[i].value).innerHTML;
      var iCodMater        = new Number($('codmater'+aItens[i].value).innerHTML);
      var nSaldo           = new Number(nTotalSolicitado-nTotalAtendido);
      if ($('cc08_sequencial'+aItens[i].value)) {
        var iCodigoCriterio  = $('cc08_sequencial'+aItens[i].value).innerHTML;
      }
      var sMsg             = "item ("+sItemDescr+") sem saldo para efetuar o atendimento.";
      if (js_verificaQuantidade(nTotalDigitado,nSaldo,sMsg)) {

         sJsonItem += sVirgula+"{'iCodItemReq':"+aItens[i].value+",'nQtde':"+nTotalDigitado+",";
         sJsonItem += "'iCodMater':"+iCodMater+",'iCentroDeCusto':"+iCodigoCriterio+"}";
         sVirgula  = ",";
         
      } else {
        return false;
      }
    }

    js_divCarregando("Aguarde, efetuando atendimento", "msgBox");
    sParams    = "'iCodReq':"+$F('m40_codigo')+",'iTipo':17,'aItens':["+sJsonItem+"],'iCodEstoque':"+$F('m91_depto');
    var sJson  = "{'exec':'atenderRequisicao','params':[{"+sParams+"}]}";
    var url     = 'mat4_requisicaoRPC.php';
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
  
   if (confirm('Atendimento Efetuado com sucesso.\nDeseja imprimir a Requisição de Saída de Material?')) {
   
     obj = document.form1;
     query='';
     query += "&ini="+obj.m40_codigo.value;
     query += "&fim="+obj.m40_codigo.value;
     //query += "&tObserva="+obj.tobserva.value;
     query += "&departamento=<?=db_getsession("DB_coddepto")?>";
     jan = window.open('mat2_matrequi002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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
  nValorReqItem = new Number($('quantsol'+iItem).innerHTML);
  sField        = $('atendido'+iItem).id;
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
js_pesquisa();
</script>