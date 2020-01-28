<?php
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

require ("libs/db_stdlib.php");
require ("libs/db_utils.php");
require ("libs/db_app.utils.php");
require ("std/db_stdClass.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_liborcamento.php");
include ("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$db_opcao = 3;
$clrotulo->label("descrdepto");
$clrotulo->label("pc50_descr");
$clrotulo->label("pc54_solicita");
$clrotulo->label("pc12_vlrap");
$clrotulo->label("pc12_tipo");
$clrotulo->label("o74_sequencial");
$clrotulo->label("o74_sequencial");
$clrotulo->label("o74_descricao");
$clrotulo->label("pc10_numero");
$iBloqueia = 3;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<?
db_app::load("scripts.js, strings.js, prototype.js,datagrid.widget.js, widgets/dbautocomplete.widget.js");
db_app::load("widgets/windowAux.widget.js");
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<style>

 .fora {background-color: #d1f07c;}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <table width="790" border="0" cellpadding="0" cellspacing="0">
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
          <fieldset>
            <legend>
              <b>Cancelar Processamento Compila��o</b>
            </legend>
            <table>
            <tr>
              <td nowrap title="C�digo da Abertura">
                 <b>C�digo:</b>
              </td>
              <td  colspan="2"> 
                <?
                db_input('pc10_numero',10,$Ipc10_numero,true,'text',3)
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tpc10_data?>">
                <b>Data Vig�ncia:</b>
              </td>
              <td colspan="2">
                <?
                $recebedata = db_getsession("DB_datausu");
                $recebedata = date("Y-m-d",$recebedata);
                if(isset($pc10_data) && trim($pc10_data) != ""){
                  $recebedata = $pc10_data;
                }
                $arr_data = split("-",$recebedata);
                @$pc10_datadia = $arr_data[2];
                @$pc10_datames = $arr_data[1];
                @$pc10_dataano = $arr_data[0];
                db_inputdata('pc54_datainicio',null,null,null,true,'text',$db_opcao);
                echo "&nbsp;<b>a</b>&nbsp;";
                db_inputdata('pc54_datatermino',null,null,null,true,'text', $db_opcao);
                ?>
              </td> 
            <tr>
              <td nowrap title="<?=@$Tpc10_resumo?>">
                <b>Resumo:</b>
              </td>
              <td colspan='3'> 
              <?
               @$pc10_resumo = stripslashes($pc10_resumo);
               db_textarea("pc10_resumo",10,120,"",true,"text",$db_opcao,"","","",735); 
              ?>
              </td>
            </tr> 
            <tr>
              <td>
                 <?
                 db_ancora("<b>Abertura de Pre�o:</b>", "js_pesquisaaberturaprecos(true);", $iBloqueia);
                 ?>
              </td>
              <td>
                <?
                 db_input('pc54_solicita', 8, $Ipc54_solicita, true, 'text', 3, "");
                ?>
              </td>
              <td>
                <input type="button" value='visualizar Itens' id='btnVisualizarItens'>
              </td>
            </tr> 
          </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td style="text-align: center;">
          <input type='button' value='Processar' disabled id='btnProcessar'>
          <input type='button' value='Pesquisar' id='btnConsultar'>
        </td>
      </tr>
    </table>
  </center>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
var sUrlRC = 'com4_solicitacaoComprasRegistroPreco.RPC.php';
function js_pesquisar() {

  js_OpenJanelaIframe('',
                      'db_iframe_solicita',
                      'func_solicitacompilacao.php?funcao_js=parent.js_completaPesquisa|pc10_numero&departamento=true'+
                      '&anuladas=1&comcompilacaoprocessada=2',
                      'Compila��o de Registro de Pre�o',
                      true
                      );
}

function js_completaPesquisa(iSolicitacao) {

   var oParam          = new Object();
   oParam.exec         = "pesquisarAbertura";
   oParam.iSolicitacao = iSolicitacao;
   oParam.tipo         = 6;
   db_iframe_solicita.hide();
   var oAjax           = new Ajax.Request(sUrlRC,
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoCompletaPesquisa
                                         });
}

function js_retornoCompletaPesquisa(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
  
    $('pc54_datainicio').value  = oRetorno.datainicio;
    $('pc54_datatermino').value = oRetorno.datatermino;
    $('pc10_resumo').value      = oRetorno.resumo.urlDecode();
    $('pc10_numero').value      = oRetorno.solicitacao;
    $('pc54_solicita').value = oRetorno.codigoabertura;
    js_preencheGrid(oRetorno.itens);
    $('btnProcessar').disabled = false;
    
  } else {
    alert(oRetorno.message.urlDecode());
  }
  
}

function js_init() {

  windowAuxiliar = new windowAux('wndAuxiliar', 'itens', 600, 400);
  windowAuxiliar.setContent("<fieldset><legend>Itens da Compila��o</legend><div  id='griditens'></div></fieldset>");
  windowAuxiliar.hide();
  $('btnProcessar').disabled = true;
  oGridItens     = new DBGrid('gridItens');
  oGridItens.nameInstance = "oGridItens";
  oGridItens.setHeight(200);
  oGridItens.setCellAlign(new Array("right","right","Left","left","center","right","right", "right"));
  oGridItens.setCellWidth(new Array("4%","10%","45%",'10%', "5%","10%",'16%','16%','16%'));
  oGridItens.setHeader(new Array("Seq","Codigo","Descri��o","Unidade(Qtd)","Out.Inf.","Qtde","Qtd. Min.","Qtd. Min.",
                                 "Ativo"));
  oGridItens.show($('griditens')); 
  
}

function js_preencheGrid(aItens) {
  
  oGridItens.clearAll(true);
  for(var i = 0; i < aItens.length; i++) {
    
    with (aItens[i]) {
    
      var aLinha = new Array();
      aLinha[0]  = i+1;
      aLinha[1]  = codigoitem;
      aLinha[2]  = descricaoitem.urlDecode();
      aLinha[3]  = "<span id='unidade"+indice+"'>"+unidade+"</span>";  
      aLinha[3] += "<span id='quantunid"+indice+"' style='display:none'>"+quantidadeunid+"</span>("+quantidadeunid+")";  
      aLinha[4]  = "<span id='justificativa"+indice+"' style='display:none'>"+justificativa.urlDecode()+"</span>";  
      aLinha[4] += "<span id='resumo"+indice+"'        style='display:none'>"+resumo.urlDecode()+"</span>";  
      aLinha[4] += "<span id='pgto"+indice+"'          style='display:none'>"+pagamento.urlDecode()+"</span>";  
      aLinha[4] += "<span id='prazo"+indice+"'         style='display:none'>"+prazo.urlDecode()+"</span>";  
      aLinha[4] += "<span><a href='#' ></a></span>";
      aLinha[5] = js_formatar(aItens[i].quantidade,'f');
      aLinha[6] = js_formatar(aItens[i].qtdemin,'f');
      aLinha[7] = js_formatar(aItens[i].qtdemax,'f');
      aLinha[8] = ativo?"Sim":"N�o";
      oGridItens.addRow(aLinha);
      if (!automatico) {
         oGridItens.aRows[i].setClassName("fora");
      }
      //oGridItens.aRows[i].aCells[0].sStyle +="background-color:#DED5CB;font-weight:bold;padding:1px";
      
    }
  }
  oGridItens.renderRows();
}
js_init();
function js_showItens() {
  windowAuxiliar.show(100,10); 
}

function js_processarCompilacao() {

  if ($F('pc10_numero') != "") {
  
    var sMsg  = "Confirmar o cancelamento do processamento da compila��o do registro de pre�o?\n";
        sMsg += "Esso processo ira cancelar o processo de compras gerado para essa compila��o."; 
    if (!confirm(sMsg)) {
      return false;
    }
    js_divCarregando("Aguarde, processando Compila��o.<br>Esse pocesso pode demorar um pouco.","msgBox");
    var oParam          = new Object();
    oParam.exec         = "cancelaProcessoCompras";
    oParam.iSolicitacao = $F('pc10_numero');
    oParam.tipo         = 6;
    db_iframe_solicita.hide();
    var oAjax           = new Ajax.Request(sUrlRC,
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoProcessarCompilacao
                                         });
    
  }
  
}

function js_retornoProcessarCompilacao(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
  
   alert('Cancelamento do processamento da compilacao realizada com sucesso!');    
  } else {
    alert(oRetorno.message.urlDecode());
  }
}
function js_limpar() {
  
  $('btnProcessar').disabled = true;
  $('pc10_numero').value = '';
  $('pc10_resumo').value = '';
  $('pc54_datatermino').value = '';
  $('pc54_datainicio').value = '';
  oGridItens.clearAll(true);
}
$('btnConsultar').observe('click', js_pesquisar);
$('btnProcessar').observe('click', js_processarCompilacao);
$('btnVisualizarItens').observe('click', js_showItens);
</script>