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
           <input type='button' id='btnGerarArquivo' name='btnGerarArquivo' value='Gerar Arquivo'
                  onclick='js_gerarArquivos()' disabled>
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
  gridNotas.setHeader(new Array("&nbsp;", "CNPJ", "Inscrição Estadual", 
                                "Credor", "Nº Nota", "Data Emissão", "Serie", "CFOP", "Valor"));
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
  oParamPit.exec          = "getNotas";
  oParamPit.datainicial   = dtDataInicial;  
  oParamPit.datafinal     = dtDataFinal;  
  oParamPit.tipodocumento = 50;
  js_divCarregando("Aguarde, pesquisando notas para o período indicado.","msgBox");  
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
  iErros = 0;
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
          aLinha[1] = z01_cgccpf.urlDecode();  
          aLinha[2] = z01_incest.urlDecode();  
          aLinha[3] = z01_numcgm+" - "+z01_nome.urlDecode();  
          aLinha[4] = e69_numero;  
          aLinha[5] = js_formatar(e69_dtnota.urlDecode(), "d");  
          aLinha[6] = e11_seriefiscal;  
          aLinha[7] = e10_cfop;  
          aLinha[8] = js_formatar(valornota, "f");
          gridNotas.addRow(aLinha);
          gridNotas.aRows[i].sStyle += ";padding:1px";
          if (z01_incest == "" ) {
            
            gridNotas.aRows[i].aCells[2].sStyle='background-Color:#C08163';
            var lDisabled = true;
            iErros++;
            iErroLinha = true;   
              
          }
          
          if (e69_numero == "" ) {
            
            gridNotas.aRows[i].aCells[4].sStyle='background-Color:#C08163';
            var lDisabled = true;
            iErros++;
            iErroLinha = true;   
              
          }
          
          if (e10_cfop == "") {
            
            gridNotas.aRows[i].aCells[7].sStyle='background-Color:#C08163';
            var lDisabled = true;
            if (iErroLinha == false) {
              iErros++;
            }
            iErroLinha = true;   
              
          }
        } 
      }
      
      gridNotas.renderRows();
      if (lDisabled) {
      
        $('btnGerarArquivo').disabled   = true;
        $('gridNotasstatus').innerHTML  = '&nbsp;<b>Há '+iErros+' linhas notas com erro no cadastro.';
        $('gridNotasstatus').innerHTML += ' Não será possível gerar Arquivo</b>';
        
      } else {
        $('btnGerarArquivo').disabled   = false;
      }
    } 
  }  
}

function js_gerarArquivos() {

  if (iErros != 0) {
  
     alert('há erros nos cadastros das notas.\n Corriga os problema apontados antes de gerar o arquivo');
     return false;
     
  }
  
  if (dtDataFinal == "" || dtDataInicial == "") {
      
     alert('Verifique o período da consulta!\nData final ou data inicial não informados');
     return false; 
  } 
  
  if (dtDataInicial != $F('datainicial') || dtDataFinal != $F('datafinal')) {
    
     var sMsg  = 'Verifique o período da consulta!\n';
     sMsg     += 'Data informada para geração dos arquivos diferente da data informada para a pesquisa';
     alert(sMsg);
     return false;
  
  }
  
  var sUrl  = 'mat4_processararquivopit002.php?';
  sUrl     += '&datainicial='+dtDataInicial;  
  sUrl     += '&datafinal='+dtDataFinal;  
  sUrl     += '&tipodocumento='+50;  
  js_OpenJanelaIframe('', 'db_iframe_pit',sUrl,"Gerar arquivos Pit", true);
  
} 
 
function js_validaSomenteNumeros(sValor) {
  var sReg    = /[^0-9]/;
  var lRetorno = sReg.test(sValor);
  if (lRetorno) {
     lRetorno = false;
  } else {
    lRetorno = true;
  }
  return lRetorno;
}

</script>