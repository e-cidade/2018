<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
$clfar_retirada->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("descrdepto");
$func=$chavepesquisaretirada;
?>
<!--<form name="form1" method="post" action="" onsubmit='js_buscavalores();' >-->
<form name="form1" method="post" action=""  >
<center>
<table>
<tr>
<td>
<fieldset><legend><b>Dados da Requisição</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tfa04_i_codigo?>">
       <?//=@$Lfa04_i_codigo?>
    <b>Código da Retirada: </b>
    </td>
    <td> 
<?
db_input('fa04_i_codigo',10,$Ifa04_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
 
  <tr>
    <td nowrap title="<?=@$Tfa04_i_dbusuario?>">
       <?=@$Lfa04_i_dbusuario?>
    </td>
    <td> 
<?
db_input('fa04_i_dbusuario',10,$Ifa04_i_dbusuario,true,'text',3," ")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tfa04_i_unidades?>">
       <?=@$Lfa04_i_unidades?>
    </td>
    <td> 
       <?
db_input('descrdepto',40,$Idescrdepto,true,'text',3,'');
       ?>
    </td>
  </tr>
  </table>
  </fieldset>
  </td>
  <table>
   <tr>
     <td> 
     <fieldset><legend><b>&nbsp;Materiais da Requisição&nbsp;</b></legend>
         
        <table  cellspacing=0 cellpadding=0 width='100%' style='border:2px inset white'>
          <tr>
            <th class='table_header'><input type='checkbox'  style='display:none'
                id='mtodosnotas' onclick='js_marca()'>
            <a onclick='js_marca("mtodosnotas","chkmarca","linha")' style='cursor:pointer'>M</a></b></th>
            <th class='table_header'>Código do Material</th>
            <th class='table_header'>Descrição do Material</th>
            <th class='table_header'>Unidade de Saida</th>
            <th class='table_header'>Quantidade Solicitada</th>
            <th class='table_header'>Quantidade Atendida</th>
            <th class='table_header'>Qtde Em Estoque</th>
            <th class='table_header'>Lote</th>
            <th class='table_header'>Qtde a Entregar</th>
            <th class='table_header' width='18px'>&nbsp;</td>
          </tr>
          <tbody id='dadosrequisicao' style='height:80;width:95%;overflow:scroll;overflow-x:hidden;background-color:white'>
          </tbody>
        </table>
        </fieldset>
     </td>
    </tr>
    <tr>
    <td>   
      <!--  <iframe name="itens" id="itens" src="mat1_atendrequiitemalt001.php?m40_codigo=<?=$m40_codigo?>" width="720" height="220" marginwidth="0" marginheight="0" frameborder="0"></iframe>-->
     </td>
    </tr>
  </table>
  </center>
  <!--
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Confirma":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  -->
       <input name="incluir" type="button"  value="Efetuar Atendimento" onclick="return js_atendeRequisicao();" >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?
//db_input('atendimento',10,'',true,'hidden',3);
//db_input('m80_codigo',10,'',true,'hidden',3);
//db_input("valores",100,0,true,"hidden",3);
?>
</form>
<script>
function js_pesquisa(){

  sFiltro = "sFiltro=unidades";
  js_OpenJanelaIframe('top.corpo','db_iframe_far_retirada','func_far_retirada.php?func=<?=$func?>&'+sFiltro+'&funcao_js=parent.js_preenchepesquisa|fa04_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_far_retirada.hide();
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
function js_consultaRequisicao(fa04_i_codigo){
   
   js_divCarregando("Aguarde, efetuando pesquisa","msgBox");
   strJson = '{"exec":"getDados","params":[{"fa04_i_codigo":'+fa04_i_codigo+'}]}';
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
  $('fa04_i_codigo').value = obj.fa04_i_codigo;
  //$('m40_data').value   = js_formatar(obj.m40_data,"d");
  //$('m40_hora').value   = obj.m40_hora.urlDecode();
  $('fa04_i_unidades').value  = obj.fa04_i_unidades;
 // $('m40_depto').value  = obj.m40_depto;
  $('m91_depto').value  = obj.m91_depto;
  $('descrdepto').value = obj.descrdepto.urlDecode();
  var iErros            = new Number(0);
  var lDisabled         = true;
  var lHabilitado       = "";
  saida                 = "";
  if (obj.itens) {
   
    for (iInd = 0; iInd < obj.itens.length; iInd++){
    
      with (obj.itens[iInd]) {
        
        var lLote = false;
        if (fa04_i_codigo == 1 || fa04_i_codigo == 2) {
          lLote = true;
        }
        lClassName = "normal";
        if ((fa06_f_quant - totalatendido) == 0) {
          lHabilitado = " disabled ";
          lClassName  = "disabled";
        }
        saida += "<tr class='"+lClassName+"'id='linhachk"+fa06_i_codigo+"'>";
        saida += "  <td class='linhagrid'>";
        saida += "     <input type='checkbox' id='chk"+fa06_i_codigo+"' onclick=\"js_marcaLinha(this,'linha');\""; 
        saida += "      value='"+fa06_i_codigo+"' class='chkmarca' "+lHabilitado+"  style='height:12px'>";      
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:right' id='codmater"+fa06_i_codigo+"'>";
        saida +=     fa06_i_matersaude;
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:left' id='descrmater"+fa06_i_codigo+"'>";
        saida +=     fa06_t_posologia.urlDecode();
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:left' id='descrunid"+fa06_i_codigo+"'>";
        saida +=     m61_descr.urlDecode();
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:right' id='quantsol"+fa06_i_codigo+"'>";
        saida +=    fa06_f_quant;
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:right' id='quantatend"+fa06_i_codigo+"'>";
        saida +=     totalatendido;
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:right' id='quantestoque"+fa06_i_codigo+"'>";
        saida +=     qtdeestoque;
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:center' id='lote"+fa06_i_codigo+"'>";
        if (lLote){
            saida += "<a href='' onclick='js_mostraLotes("+fa06_i_codigo+");return false'>lote</>";
        } else {
          saida += "&nbsp;";
        } 
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:center;width:10%'>";
        saida += "    <input type='text'  style='text-align:right' name='atendido"+fa06_i_codigo+"'";
        saida += "           id='atendido"+fa06_i_codigo+"'";
        saida += "           onblur='js_verificaQuantidade(this.value, "+(fa06_f_quant - totalatendido)+",\"Quantidade maior que o saldo.\")'";
        saida += "           value='"+(fa06_f_quant - totalatendido)+"' size='5' class='valores' disabled ";
        saida += "     onkeypress='return js_teclas(event)'>";
        saida += "   </td>";
        saida += "</tr>";
        
        
      }
    }
    $('dadosrequisicao').innerHTML = saida;
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
    js_divCarregando("Aguarde, efetuando atendimento","msgBox");
    sVirgula         = "";
    for (var i = 0; i < aItens.length; i++) {
    
      nTotalAtendido   = new Number($('quantatend'+aItens[i].value).innerHTML);
      nTotalSolicitado = new Number($('quantsol'+aItens[i].value).innerHTML);
      nTotalDigitado   = new Number($('atendido'+aItens[i].value).value);
      sItemDescr       = $('descrmater'+aItens[i].value).innerHTML;
      iCodMater        = new Number($('codmater'+aItens[i].value).innerHTML);
      nSaldo           = new Number(nTotalSolicitado-nTotalAtendido);
      sMsg             = "item ("+sItemDescr+") sem saldo para efetuar o atendimento.";
      if (js_verificaQuantidade(nTotalDigitado,nSaldo,sMsg)) {
      
         sJsonItem += sVirgula+"{'iCodItemReq':"+aItens[i].value+",'nQtde':"+nTotalDigitado+",'iCodMater':"+iCodMater+"}";
         sVirgula  = ",";
         
      } else {
        return false;
      }
    }
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
  var obj               = eval("(" + oAjax.responseText + ")");
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
  
  iCodItem      = new Number($('fa06_i_matersaude'+iItem).innerHTML);//código do material
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
js_pesquisa();
</script>