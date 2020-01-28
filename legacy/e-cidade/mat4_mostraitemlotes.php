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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/requisicaoMaterial.model.php");
require_once("classes/materialestoque.model.php");

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

$oGet         = db_utils::postMemory($_GET);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name='frmLotes' id='frmLotes' method='post'>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
  <table>
    <tr>
      <td>
        <b>Quantidade Solicitada do Item:</b>
        <span id='solicitado'><?=$oGet->nValorSolicitado;?></span>
      </td>
    </tr>
    <tr>
      <td>
        <fieldset>
          <legend><b>Lotes:</b></legend>

            <table  cellspacing="0" cellpadding="0" width='100%' style='border:2px inset white'>
              <tr>
                <th class='table_header'>Cod. Lanc</th>
                <th class='table_header'>Código do Material</th>
                <th class='table_header'>Descrição do Material</th>
                <th class='table_header'>Unidade de Saida</th>
                <th class='table_header'>Lote</th>
                <th class='table_header'>Validade</th>
                <th class='table_header'>Quantidade lote</th>
                <th class='table_header'>quantidade Solicitada</th>
                <th class='table_header' width='18px'>&nbsp;</td>
              </tr>
              <tbody id='lotesitens' style='height:80;width:95%;overflow:scroll;overflow-x:hidden;background-color:white'>

              </tbody>
            </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="text-align: center;">
         <input type="button" value="Lançar" onclick='js_saveLote()'>
         <input type="button" value="Rateio Automático" onclick='js_rateioAutomatico()'>
      </td>
    </tr>
</table>
</center>
</form>
</body>
</html>
<script>
iCodMater = <?=$oGet->iCodMater;?>;
function js_marca(idObjeto, sClasse, sLinha){

   obj = document.getElementById(idObjeto);
   if (obj.checked){
     obj.checked = false;
   }else{
     obj.checked = true;
   }
   itens = js_getElementbyClass(frmLotes, sClasse);
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

//Faz a requisicao de saida de material.
function js_consultaItens(iCodMater,iCodEstoque, nValor){

   js_divCarregando("Aguarde, efetuando pesquisa","msgBox");
   strJson = '{"exec":"getLotes","params":[{"iCodMater":'+iCodMater+',"iCodEstoque":'+iCodEstoque+',"nValor":'+nValor+'}]}';
   $('lotesitens').innerHTML    = '';
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
  saida           = "";

  if (obj.itens) {

    for (iInd = 0; iInd < obj.itens.length; iInd++){

      var lHabilitado = "";
      with (obj.itens[iInd]) {

        if (rateio > 0) {
          lHabilitado = " checked";
        }
        saida += "<tr id='linhachk"+m71_codlanc+"'>";
        saida += "  <td class='linhagrid' id='codlanc"+m71_codlanc+"'>";
        saida += "  <input type='checkbox' checked class='chkmarca' style='display:none' value='"+m71_codlanc+"'";
        saida += "  <span >"+m71_codlanc+"</span>";
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:right' id='codmater"+m71_codlanc+"'>";
        saida +=     m70_codmatmater;
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:left' id='descrmater"+m71_codlanc+"'>";
        saida +=     m60_descr.urlDecode();
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:left' id='descrunid"+m71_codlanc+"'>";
        saida +=     m61_descr.urlDecode();
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:left' id='lote"+m71_codlanc+"'>";
        saida +=     m77_lote.urlDecode();
        saida += "  &nbsp;</td>";
        saida += "  <td class='linhagrid' style='text-align:left' id='validade"+m71_codlanc+"'>";
        saida +=     js_formatar(m77_dtvalidade,"d");
        saida += "  &nbsp;</td>";
        saida += "  <td class='linhagrid' style='text-align:right' id='quantsol"+m71_codlanc+"'>";
        saida +=     saldo;
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:center;width:10%'>";
        saida += "    <input type='text'  style='text-align:right' name='atendido"+m71_codlanc+"'";
        saida += "           id='atendido"+m71_codlanc+"'";
        saida += "           onblur='js_verificaQuantidade(this.value, "+(saldo)+",\"Quantidade maior que o saldo.\")'";
        saida += "           value='"+(rateio)+"' size='5' class='valores' ";
        saida += "     onkeypress='return js_teclas(event)'>";
        saida += "   </td>";
        saida += "</tr>";

      }
    }
    saida += "<tr style='height:auto'><td>&nbsp;</td></tr>";
    $('lotesitens').innerHTML = saida;
  }
}
/**
 * Salva o lote na sessao
 */
function js_saveLote() {


  aItens = js_getElementbyClass(frmLotes, "chkmarca");
  sJsonItem        = "";
  sVirgula         = "";
  nTotalItens      = new Number(0);
  for (var i = 0; i < aItens.length; i++) {

    var iCodLanc           = aItens[i].value;
    var nTotalAtendido = new Number($('quantsol'+iCodLanc).innerHTML);
    var nTotalDigitado = new Number($('atendido'+iCodLanc).value);
    sItemDescr         = $('descrmater'+iCodLanc).innerHTML;
    sMsg               = "item ("+sItemDescr+") sem saldo para efetuar o atendimento.";

    if (js_verificaQuantidade(nTotalDigitado, nTotalAtendido, sMsg)) {

      sJsonItem   += sVirgula+"{'iCodItem':"+iCodLanc+",'qtde':"+nTotalDigitado+"}";
      sVirgula     = ",";
      nTotalItens += nTotalDigitado;

    } else {
      return false;
    }
  }
  if (nTotalItens > new Number($('solicitado').innerHTML)) {

   alert('Total Digitado é maior que o solicitado');
   return false;

  }
  js_divCarregando("Aguarde, Salvando Lote","msgBox");
  strJson = '{"exec":"saveLote","params":[{"iCodMater":'+iCodMater+',"aItens":['+sJsonItem+']}]}';
  var url     = 'mat4_requisicaoRPC.php';
  var oAjax   = new Ajax.Request(
                           url,
                             {
                              method: 'post',
                              parameters: 'json='+strJson,
                              onComplete: js_saidaSave
                             }
                            );

}
/**
 * Controle de saida da requisao para salvar o rateio do lote
 *
 */
function js_saidaSave(oAjax) {

  js_removeObj("msgBox");
  var obj               = eval("(" + oAjax.responseText + ")");
  if (obj.status == 2) {

    alert(obj.message.urlDecode());
    return false;

  }  else {

    var aItens = js_getElementbyClass(frmLotes, "valores");
    var nQtdeTotal = new Number(0);
    for (i = 0; i < aItens.length; i++) {

      nQtdeTotal += new Number(aItens[i].value);
    }
    <? if (isset($oGet->updateField) && $oGet->updateField != '') {
        echo "parent.$('{$oGet->updateField}').value = nQtdeTotal;\n";
        echo "parent.db_iframe_lotes.hide();\n";
       }
    ?>
  }
}

function js_rateioAutomatico() {

  if (confirm('Essa operação ira cancelar o rateio manul.Confirma?')) {
    js_divCarregando("Aguarde, cancelando Lote","msgBox");
     strJson = '{"exec":"cancelarLote","params":[{"iCodMater":'+iCodMater+'}]}';
     var url     = 'mat4_requisicaoRPC.php';
     var oAjax   = new Ajax.Request(
                           url,
                             {
                              method: 'post',
                              parameters: 'json='+strJson,
                              onComplete: js_saidaCancelar
                             }
                            );
  }
}
/**
 * Controle de saida da requisao para salvar o rateio do lote
 *
 */
function js_saidaCancelar(oAjax) {

  js_removeObj("msgBox");
  var obj               = eval("(" + oAjax.responseText + ")");
  if (obj.status == 2) {

    alert(obj.message.urlDecode());
    return false;

  } else {
   location.reload();
  }
}

function js_getQuantidadeTotal() {


}

function js_verificaQuantidade(nValor, nMaximo,sMsg) {

  if (nValor > nMaximo) {

    alert(sMsg);
    return false;

  }
  return true;
}

<?
echo "js_consultaItens({$oGet->iCodMater},{$oGet->iCodDepto},{$oGet->nValor});\n";
?>
</script>