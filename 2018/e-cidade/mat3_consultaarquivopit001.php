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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, widgets/windowAux.widget.js");
db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor="#CCCCCC" style='margin:1em;' onload="js_main()">
  <table width="790" border="0" cellpadding="0" cellspacing="0" >
    <tr>
      <td width="360" height="25">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <center>
  <form>
    <table>
       <tr>
         <td>
           <fieldset>
             <legend>
               <b>Data de Emissão das Notas</b>
             </legend>
             <table>
               <tr>
                 <td>
                   <b>Data Inicial:</b>
                 </td> 
                 <td>
                   <?
                    $dtDataInicio = date("Y-m-d", db_getsession("DB_datausu"));
                    $aPartesDataInicio = explode("-", $dtDataInicio); 
                    db_inputdata('datainicial', '01', $aPartesDataInicio[1], $aPartesDataInicio[0], true, "text", 1);
                   ?>
                 </td>
                 <td>
                   <b>Data Final:</b>
                 </td> 
                 <td>
                   <?
                    $dtDataFinal      = date("Y-m-d", db_getsession("DB_datausu"));
                    $aPartesDataFinal = explode("-", $dtDataInicio);
                    $iUltimoDia       = cal_days_in_month(CAL_GREGORIAN, $aPartesDataFinal[1], $aPartesDataFinal[0]); 
                    db_inputdata('datafinal', $iUltimoDia, $aPartesDataFinal[1], $aPartesDataFinal[0], true, "text", 1);
                   ?>
                 </td>
               </tr>
             </table>
           </fieldset>
         </td>
       </tr>
       <tr>
         <td style="text-align: center">
           <input type='button' id='pesquisar'       name='pesquisar'       value='Pesquisar'
                  onclick='js_getArquivosNoPeriodo()'>
         </td>
       </tr>
    </table>
    <table style="width: 70%">
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Notas Recolhidas no Mês
            </legend>
            <div id='containergridNotas' style="width: 100%">
            </div>
          </fieldset>
        </td>
     </tr>
    </table>
  </form>
  </center>
  <div style='padding:3px ;border: 1px inset white;display:none' id='digitarObs'>         
    <textarea id='historicoAnulacao' rows="10" cols="30">
    </textarea>
    <center>
    <input value='Confirma' type='button' id='atualizarHistorico' onclick='js_atualizaHistorico'>
    </center>          
 </div> 
 <div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            width:300px;
            padding:3px;
            background-color: #FFFFCC;
            display:none;' id='ajudaItem'>

  </div>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
urlRPC            = 'mat4_arquivosPIT.RPC.php';
var dtDataInicial = '';
var dtDataFinal   = '';
var iErros        = 0; 
function js_main() { 

  gridNotas = new DBGrid("gridNotas");
  gridNotas.nameInstance = "gridNotas";
  gridNotas.setCellAlign(new Array("right","center", "center", "left", "right", "center", "right", "right", "right"));
  gridNotas.setHeader(new Array("&nbsp;", "Cód arquivo", "Arquivo", "Usuario", "Data Geração", "Hora","Motivo"));
  gridNotas.show($('containergridNotas'));
  
}


function js_getArquivosNoPeriodo() {

  dtDataInicial = $F('datainicial');
  dtDataFinal   = $F('datafinal');
  
  if (dtDataFinal == "") {
  
    alert('informe a data final');
    $('dtafinal').focus();
    
  }
  
  if (dtDataInicial == "") {
  
    alert('informe a data inicial');
    $('dtainicial').focus();
    
  }
  
  var oParamPit = new Object();
  oParamPit.exec          = "getArquivos";
  oParamPit.datainicial   = dtDataInicial;  
  oParamPit.datafinal     = dtDataFinal;  
  oParamPit.tipodocumento = 50;
  js_divCarregando("Aguarde, pesquisando arquvivos para o período indicado.","msgBox");  
  var oAjax    = new Ajax.Request(
                          urlRPC, 
                          {
                           method    : 'post', 
                           parameters: 'json='+Object.toJSON(oParamPit), 
                           onComplete: js_retornoGetNotas
                          }
                        ); 
}

function js_retornoGetNotas(oResponse) {

  js_removeObj('msgBox');
  gridNotas.clearAll(true);
  var oRetorno = eval("("+oResponse.responseText+")");
  lDisabled    = false;
  if (oRetorno.status == 1) {
    
    if (oRetorno.itens.length  > 0) {
      
      for (var i = 0; i < oRetorno.itens.length; i++) {
        
        var iErroLinha = false;  
        with(oRetorno.itens[i]) {
          
          aLinha    = new Array();
          aLinha[0] = (i+1); 
          aLinha[1] = e14_sequencial;  
          aLinha[2] = e14_nomearquivo.urlDecode();  
          aLinha[3] = nome.urlDecode();  
          aLinha[4] = js_formatar(e14_dtarquivo.urlDecode(), "d");  
          aLinha[5] = e14_hora.urlDecode();  
          aLinha[6] = situacao.urlDecode();
          gridNotas.addRow(aLinha);
          gridNotas.aRows[i].sStyle += ";padding:1px";
          gridNotas.aRows[i].sEvents = "onDblClick='js_pesquisaNotasEmArquivo("+e14_sequencial+")'";
        } 
      }
      gridNotas.renderRows();
    } 
  }  
}

function js_cancelarArquivos() {
  
  var aArquivosSelecionados = gridNotas.getSelection("object");
  if (aArquivosSelecionados.length == 0) {
    
    alert('Selecione algum arquivo para ser anulado.');
    return false;
     
  } 
  
  var oParametros           = new Object();
  oParametros.exec          = "anularArquivos";
  oParametros.tipodocumento = 50;
  oParametros.aArquivos     = new Array();
  for (var i= 0; i < aArquivosSelecionados.length; i++) {
  
    with (aArquivosSelecionados[i]) {
    
      var iIdArquivo = aCells[0].getValue();
      if ($('historico'+iIdArquivo).innerHTML == "") {
      
        alert('Informe do motivo da anulação do arquivo '+iIdArquivo+'!\nProcessamento Cancelado.');
        return false;
        
      } 
      var oArquivo        = new Object();
      oArquivo.idArquivo  = iIdArquivo;    
      oArquivo.sMotivo    = encodeURIComponent($('historico'+iIdArquivo).innerHTML.trim());
      oParametros.aArquivos.push(oArquivo);
          
    }
  }
  
  var sMSgConfirma = 'Confirma a anulação do (s) Arquivo(s) Selecionados?'; 
  if (!confirm(sMSgConfirma)) {
    return false;
  }
  
  js_divCarregando("Aguarde, anulando arquivos selecionados.","msgBox");  
  var oAjax    = new Ajax.Request(
                          urlRPC, 
                          {
                           method    : 'post', 
                           parameters: 'json='+Object.toJSON(oParametros), 
                           onComplete: js_retornoAnularArquivos
                          }
                        ); 
  
} 

function js_retornoAnularArquivos(oResponse) {
  
  js_removeObj('msgBox');
  var oRetorno = eval("("+oResponse.responseText+")");
  if (oRetorno.status == 1) {
    
    gridNotas.clearAll(true);
    alert('Arquivo(s) anulado(s) com sucesso!');
    
  } else {
   alert(oRetorno.message.urlDecode());
  }
 
}

function js_setAjuda(sTexto,lShow) {

  if (lShow) {
  
    el =  $('containergridNotas'); 
    var x = 0;
    var y = el.offsetHeight;
    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {

     x += el.offsetLeft;
     y += el.offsetTop;
     el = el.offsetParent;

   }
   x += el.offsetLeft;
   y += el.offsetTop;
   $('ajudaItem').innerHTML     = sTexto;
   $('ajudaItem').style.display = '';
   $('ajudaItem').style.top     = y+10;
   $('ajudaItem').style.left    = x;
   
  } else {
   $('ajudaItem').style.display = 'none';
  }
} 

oWndHistorico = null;
function js_showHistorico(iCodMov) {

  var el =  $('containergridNotas'); 
  var x  = el.scrollWidth/2;
  var y  = 100;

  $('historicoAnulacao').value = $('historico'+iCodMov).innerHTML;
  $('digitarObs').style.zIndex = '1001';
  $('atualizarHistorico').onclick = function() {
    
    $('historico'+iCodMov).innerHTML       = $('historicoAnulacao').value;
    $('historicoAnulacao').value           = '';
    $('digitarObs').style.zIndex='-1';
    oWndHistorico.hide();
    
  }
  
  $('historicoAnulacao').focus();
  if (oWndHistorico == null) {
    
    oWndHistorico = new windowAux('wndHistorico', 'Motivo', 270, 240);
    oWndHistorico.setObjectForContent($('digitarObs'));
    oWndHistorico.show(y, x);
    
  } else {
    oWndHistorico.show(y, x);
  }
  $("windowwndHistorico_btnclose").onclick =$('atualizarHistorico').onclick;
  
}
oWndJanelaTeste = null;

function js_pesquisaNotasEmArquivo(idArquivo) {

  if (oWndJanelaTeste != null) {
    
    oWndJanelaTeste.setTitle('Notas para Arquvivo '+idArquivo); 
      
  } else {
    

    oWndJanelaTeste  = new windowAux('wndNotasArquivo', 'Notas para Arquvivo '+idArquivo,0,500);
    var sStrContent  = "<div id='containerdadosarquivo' style='height:100%'></div>";
    sStrContent += "";
    oWndJanelaTeste.setContent(sStrContent);
    
    gridDadosArquivo = new DBGrid('gridDadosArquivo');
    gridDadosArquivo.nameInstance = "gridDadosArquivo";
    gridDadosArquivo.setHeight(400);
    gridDadosArquivo.allowSelectColumns(true);
    
    gridDadosArquivo.setCellAlign(new Array("right","center", "left", "left", "right", "center", "left",
                                            "right", "right", "right", "right", "right", "right"));
    gridDadosArquivo.setHeader(new Array("&nbsp;", 
                                         "Cnpj", 
                                         "Nome",
                                         "IE", "Nota", 
                                         "Série","Emissão",
                                         "CFOP",
                                         "Base Calc ICMS",
                                         "ICMS",
                                         "Base Calc Subst",
                                         "ICMS Subst",
                                         "Valor Nota"
                                         ));
    gridDadosArquivo.show($('containerdadosarquivo'));
  }
  
  gridDadosArquivo.clearAll(true);
  var oParametros           = new Object();
  oParametros.exec          = "pesquisaNotasArquivo";
  oParametros.iArquivo      = idArquivo;
  oParametros.tipodocumento = 50;
  oParametros.aArquivos     = new Array();
  js_divCarregando("Aguarde, pesquisando notas para o arquivo.","msgBox");  
  var oAjax    = new Ajax.Request(
                          urlRPC, 
                          {
                           method    : 'post', 
                           parameters: 'json='+Object.toJSON(oParametros), 
                           onComplete: js_retornoGetArquivosNotas
                          }
                        ); 
  
}

function js_retornoGetArquivosNotas(oResponse) {

  js_removeObj('msgBox');
  gridDadosArquivo.clearAll(true);
  var oRetorno = eval("("+oResponse.responseText+")");
   
  if (oRetorno.status == 1) {
  
    for (var i = 0; i < oRetorno.itens.length; i++) {
    
      with (oRetorno.itens[i]) {
        
        aLinha     = new Array();
        aLinha[0]  = (i+1); 
        aLinha[1]  = z01_cgccpf.urlDecode();
        aLinha[2]  = z01_numcgm+" - "+z01_nome.urlDecode();   
        aLinha[3]  = z01_incest.urlDecode();  
        aLinha[4]  = e69_numero;
        aLinha[5]  = e11_seriefiscal;
        aLinha[6]  = js_formatar(e69_dtnota.urlDecode(), "d");  
        aLinha[7]  = e10_cfop; 
        aLinha[8]  = js_formatar(e11_basecalculoicms, "f"); 
        aLinha[9]  = js_formatar(e11_valoricms, "f"); 
        aLinha[10] = js_formatar(e11_basecalculosubstitutotrib, "f"); 
        aLinha[11] = js_formatar(e11_valoricmssubstitutotrib, "f"); 
        aLinha[12] = js_formatar(valornota, "f");
        gridDadosArquivo.addRow(aLinha);
         
      }
    }
    gridDadosArquivo.renderRows();
  }
  oWndJanelaTeste.show(28, 10);
} 
</script>