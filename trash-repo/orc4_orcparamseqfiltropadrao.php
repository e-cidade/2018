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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$lReadOnly = "false";
$lUsuario  = "false";
$oGet = db_utils::postMemory($_GET);
if (isset($oGet->readonly)) {
  $lReadOnly = $oGet->readonly;
}
if (isset($oGet->usuario)) {
  $lUsuario = $oGet->usuario;
}
?>
<html>
  <head>
  <?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js, strings.js");
  db_app::load("widgets/dbtextField.widget.js, datagrid.widget.js");
  db_app::load("estilos.css, grid.style.css");
  ?>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <form method='post' id='form1'>
    <center>
      <table>
        <tr>
          <td>
            <fieldset>
              <legend><b>Filtro Padr�o</b></legend>
              <table>
                <tr>
                  <td rowspan="4" valign="top" height="100%">
                    <fieldset style='height: 95%'>
                       <legend><b>Contas</b></legend>
                       <table>
                         <tr>
                           <td><b>Conta:</b></td>
                           <td id='inputconta'></td>
                         </tr>
                         <tr>
                           <td><b>N�vel:</b></td>
                           <td id='inputnivel'></td>
                         </tr>
                         <tr>
                           <td>&nbsp;</td>
                           <td><input type="checkbox" id='chkExclusao'>
                               <label for='chkExclusao'>Conta Exclus�o
                           </td>
                         </tr>
                         <tr>
                           <td colspan="2" style="text-align: center">
                             <input type="button" value='Incluir' id='btnIncluirConta'>
                           </td>
                         </tr>
                         <tr>
                           <td>&nbsp;</td>
                           <td colspan="2" style="width:400px">
                            <fieldset id='ctnGridContas'>
                            </fieldset>    
                           </td>
                         </tr>
                         <tr>
                           <td>&nbsp;</td>
                         </tr>
                         <tr>
                           <td>&nbsp;</td>
                         </tr>
                       </table>
                     </fieldset>
                  </td>
                  <td valign="top">
                    <fieldset>
                      <legend><b>Vinculo Com o Or�amento</b></legend>
                      <table>
                        <tr>
                          <td>
                            <b>Orgao:</b>
                          </td>
                          <td>
                            <select id='cboOperadorOrgao'>
                               <option value='in'>Contendo</option>
                               <option value='notin'>N�o Contendo</option>
                            </select>
                          </td> 
                          <td id='inputorgao'></td>
                         </tr>
                         <tr>
                           <td>
                            <b>Unidade:</b>
                           </td>
                           <td>
                             <select id='cboOperadorUnidade'>
                               <option value='in'>Contendo</option>
                               <option value='notin'>N�o Contendo</option>
                            </select>
                          </td> 
                          <td id='inputunidade'></td>
                        </tr>
                        <tr>
                           <td>
                            <b>Funcao:</b>
                           </td>
                           <td>
                             <select id='cboOperadorFuncao'>
                               <option value='in'>Contendo</option>
                               <option value='notin'>N�o Contendo</option>
                            </select>
                          </td> 
                          <td id='inputfuncao'></td>
                        </tr>
                        <tr>
                           <td>
                            <b>Subfun��o:</b>
                           </td>
                           <td>
                             <select id='cboOperadorSubFuncao'>
                               <option value='in'>Contendo</option>
                               <option value='notin'>N�o Contendo</option>
                            </select>
                          </td> 
                          <td id='inputsubfuncao'></td>
                        </tr>
                        <tr>
                           <td>
                            <b>Programa:</b>
                           </td>
                           <td>
                             <select id='cboOperadorPrograma'>
                               <option value='in'>Contendo</option>
                               <option value='notin'>N�o Contendo</option>
                            </select>
                          </td> 
                          <td id='inputprograma'></td>
                        </tr>
                        <tr>
                           <td>
                            <b>Projeto/Atividade:</b>
                           </td>
                           <td>
                             <select id='cboOperadorProjAtiv'>
                               <option value='in'>Contendo</option>
                               <option value='notin'>N�o Contendo</option>
                            </select>
                          </td> 
                          <td id='inputprojativ'></td>
                         </tr>
                         <tr>
                           <td>
                            <b>Recurso:</b>
                           </td>
                           <td>
                             <select id='cboOperadorRecurso'>
                               <option value='in'>Contendo</option>
                               <option value='notin'>N�o Contendo</option>
                            </select>
                          </td> 
                          <td id='inputrecurso'></td>
                         </tr>
                         
                         <tr>
                           <td>
                            <b>Car. Peculiar:</b>
                           </td>
                           <td>
                             <select id='cboCaracteristica'>
                               <option value='in'>Contendo</option>
                               <option value='notin'>N�o Contendo</option>
                            </select>
                          </td> 
                          <td id='inputcaracteristica'></td>
                         </tr>                         
                         
                         <tr>
                           <td>
                            <b>
                              <b>Usar Recursos da Linha</b>
                           </td>
                           <td id='ctnInputCodigoLinha' colspan='2'>
                           </td> 
                         </tr>   
                      </table>
                    </fieldset>
                  </td>
                </tr>
                <tr id='fldOutrasOpcoes' style='display: none'>
                  <td>
                    <fieldset>
                      <legend><b>Outras Op��es</b></legend>
                      <table>
                        <tr>
                          <td>
                            <input type='checkbox' id='desdobrarlinha'>
                            <label for="desdobrarlinha"><b>Detalhamento Anal�tico</b></label>
                          </td>
                        </tr>
                      </table>
                    </fieldset>
                  </td>
                </tr>
                <tr>
                  <td valign="top">
                    <fieldset >
                      <legend><b>Observa��o</b></legend>
                      <textarea rows="4" style='width: 100%' id='txtObservacao'></textarea>
                    </fieldset>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td align="center">
            <input type='button' id='btnSalvarFiltros' value='Salvar'>
            <input type='button' id='btnImportarFiltros' value='Importar'  style='display: none'>
            <input type='button' id='btnExcluirFiltros' value='Excluir' style='display: none'>
          </td>
        </tr>
      </table>
    </center>
    </form>
  </body>
</html>  
<script>
var sUrlRPC  = 'con4_configuracaorelatorioRPC.php';
var lReaOnly = <?=$lReadOnly;?>;
var lUsuario = <?=$lUsuario;?>;

function js_init() {

   oTxtConta  = new DBTextField('txtConta','oTxtConta');
   oTxtConta.addEvent("onKeyPress", "return js_mask(event,\"0-9\")");
   oTxtConta.show($('inputconta'));
   
   oTxtNivel  = new DBTextField('txtNivel','oTxtNivel','');
   oTxtNivel.addStyle("width", "30px");
   oTxtNivel.addEvent("onKeyPress", "return js_mask(event,\"0-9\")");
   oTxtNivel.show($('inputnivel'));
   
   
   oGridContas               = new DBGrid('gridcontas');
   oGridContas.nameInstance  = 'oGridContas';
   oGridContas.setHeader(new Array("Conta", "Exclus�o","N�vel", "A��o"));
   oGridContas.setCellWidth(new Array("40%","20%","20%","20%"));
   oGridContas.show($('ctnGridContas'));
   oGridContas.clearAll(true);
   
   oTxtOrgao  = new DBTextField('txtOrgao','oTxtOrgao');
   oTxtOrgao.addEvent("onKeyPress", "return js_mask(event,\"0-9|,| \")");
   oTxtOrgao.show($('inputorgao'));
   
   oTxtUnidade = new DBTextField('txtunidade','oTxtUnidade');
   oTxtUnidade.addEvent("onKeyPress", "return js_mask(event,\"0-9|,|-\")");
   oTxtUnidade.show($('inputunidade'));
   
   oTxtFuncao = new DBTextField('txtFuncao','oTxtFuncao');
   oTxtFuncao.addEvent("onKeyPress", "return js_mask(event,\"0-9|,| \")");
   oTxtFuncao.show($('inputfuncao'));
   
   oTxtSubFuncao = new DBTextField('txtSubFuncao','oTxtSubFuncao');
   oTxtSubFuncao.addEvent("onKeyPress", "return js_mask(event,\"0-9|,| \")");
   oTxtSubFuncao.show($('inputsubfuncao'));
   
   oTxtPrograma = new DBTextField('txtPrograma','oTxtPrograma');
   oTxtPrograma.addEvent("onKeyPress", "return js_mask(event,\"0-9|,| \")");
   oTxtPrograma.show($('inputprograma'));
   
   oTxtProjAtiv = new DBTextField('txtProjAtiv','oTxtProjAtiv');
   oTxtProjAtiv.addEvent("onKeyPress", "return js_mask(event,\"0-9|,| \")");
   oTxtProjAtiv.show($('inputprojativ'));
   
   oTxtRecurso = new DBTextField('txtRecurso','oTxtRecurso');
   oTxtRecurso.addEvent("onKeyPress", "return js_mask(event,\"0-9|,| \")");
   oTxtRecurso.show($('inputrecurso'));

   oTxtCaracteristica = new DBTextField('txtCaracteristica','oTxtCaracteristica');
   oTxtCaracteristica.addEvent("onKeyPress", "return js_mask(event,\"0-9|,| \")");
   oTxtCaracteristica.show($('inputcaracteristica'));
   
   oTxtCodigoLinha = new DBTextField('txtCodigoLinha','oTxtCodigoLinha','');
   oTxtCodigoLinha.addEvent("onKeyPress", "return js_mask(event,\"0-9|,| \");");
   oTxtCodigoLinha.addStyle("width", "100%");
   oTxtCodigoLinha.show($('ctnInputCodigoLinha'));
   
   getFiltrosPadrao();
}

function addConta() {

   if (oTxtConta.getValue() == "") {
     
     alert('Informe a conta.');
     return false;
     
   }
   var iContaInformada = oTxtConta.getValue();
        
   while(iContaInformada.search(/\./)!='-1') {
     iContaInformada=iContaInformada.replace(/\./,'');
   }
   var iMaxCampo = 15;
   if (iContaInformada.substring(0,1) == 3) {
     iMaxCampo = 15;
   }
   if (iContaInformada.length < iMaxCampo) {
      
     for (var i = iContaInformada.length; i < iMaxCampo; i++ ) {
      iContaInformada += "0";
     }
   }
   var aContas = oGridContas.aRows;

     
     oGridContas.clearAll(false);
     var aNovaConta = new Array();
     aNovaConta[0]  = iContaInformada;
     var sExclusao  = "N�o";
     if ($('chkExclusao').checked) {
      sExclusao  = "Sim";
     }
     
     aNovaConta[1]  = sExclusao;
     aNovaConta[2]  = oTxtNivel.getValue();
     if (lReaOnly) {
      aNovaConta[3]  = "";
     } else {
      aNovaConta[3]  = "<input type='button' value='Excluir' onclick='js_excluir("+iContaInformada+")' style='width:100%'>";
     }
     oGridContas.addRow(aNovaConta);
     oGridContas.renderRows();
     $('chkExclusao').checked = false;
     oTxtConta.setValue('');
     $('txtConta').focus();
}

function js_excluir(iConta) {
  
  var aContas = oGridContas.aRows;
  for (var i= 0; i < aContas.length; i++) {
     
    if (iConta == aContas[i].aCells[0].getValue().trim()) {
      delete oGridContas.aRows[i];         
    }
  }
  var aContas = oGridContas.aRows;
  oGridContas.clearAll(true);
  for (var i = 0;i < aContas.length; i++) {
   
   if (aContas[i]) {
       
    var aNovaConta = new Array();
    aNovaConta[0]  = aContas[i].aCells[0].getContent();
    aNovaConta[1]  = aContas[i].aCells[1].getContent();
    aNovaConta[2]  = aContas[i].aCells[2].getContent();
    if (lReaOnly) {
      aNovaConta[3]  = "";
    } else {
      aNovaConta[3]  = "<input type='button' value='Excluir' onclick='js_excluir("+aNovaConta[0]+")' style='width:100%'>";
    }
    oGridContas.addRow(aNovaConta);
   }
  }
  oGridContas.renderRows();
}

function js_salvarFiltro() {

   var aContas     = oGridContas.aRows;
   var oParam      = new Object();
   oParam.exec     = 'salvarParametros';
   if (lUsuario) {
     oParam.exec     = 'salvarParametrosUsuario';
   }
   oParam.linha          = <?=$oGet->o116_codseq?>;
   oParam.relatorio      = <?=$oGet->o116_codparamrel?>;
   oParam.filters        = new Object();
   oParam.filters.contas = new Array();
   oParam.filters.desdobrarlinha = false;
   if ($('desdobrarlinha')) {
     oParam.filters.desdobrarlinha = $('desdobrarlinha').checked;
   }
   for (var i = 0;i < aContas.length; i++) {
     
      var oConta = new Object();
      oConta.estrutural = aContas[i].aCells[0].getContent();
      oConta.nivel = aContas[i].aCells[2].getValue().trim();
      oConta.exclusao   = aContas[i].aCells[1].getContent()=='Sim'?true:false;
      oParam.filters.contas.push(oConta);
   }
   
   oParam.filters.orgao          = new Object();
   oParam.filters.orgao.operator = $F('cboOperadorOrgao');
   oParam.filters.orgao.valor    = oTxtOrgao.getValue();
   
   oParam.filters.unidade          = new Object();
   oParam.filters.unidade.operator = $F('cboOperadorUnidade');
   oParam.filters.unidade.valor    = oTxtUnidade.getValue();
   
   oParam.filters.funcao          = new Object();
   oParam.filters.funcao.operator = $F('cboOperadorFuncao');
   oParam.filters.funcao.valor    = oTxtFuncao.getValue();
   
   oParam.filters.subfuncao          = new Object();
   oParam.filters.subfuncao.operator = $F('cboOperadorSubFuncao');
   oParam.filters.subfuncao.valor    = oTxtSubFuncao.getValue();
   
   oParam.filters.programa          = new Object();
   oParam.filters.programa.operator = $F('cboOperadorPrograma');
   oParam.filters.programa.valor  = oTxtPrograma.getValue();
   
   oParam.filters.projativ          = new Object();
   oParam.filters.projativ.operator = $F('cboOperadorProjAtiv');
   oParam.filters.projativ.valor  = oTxtProjAtiv.getValue();
   
   oParam.filters.recurso          = new Object();
   oParam.filters.recurso.operator = $F('cboOperadorRecurso');
   oParam.filters.recurso.valor  = oTxtRecurso.getValue();

   oParam.filters.caracteristica          = new Object();
   oParam.filters.caracteristica.operator = $F('cboCaracteristica');
   oParam.filters.caracteristica.valor  = oTxtCaracteristica.getValue();
   
   oParam.filters.numerolinharecurso       = new Object();
   oParam.filters.numerolinharecurso.valor = oTxtCodigoLinha.getValue();
   oParam.filters.observacao = encodeURIComponent(tagString($F('txtObservacao')));
   js_divCarregando('Aguarde, salvando filtros', "msgbox");
   var oAjax   = new Ajax.Request(
                         sUrlRPC, 
                         {
                          method    : 'post', 
                          parameters: 'json='+Object.toJSON(oParam), 
                          onComplete: js_retornoSalvar
                          }
                        );
  
  
}

function js_retornoSalvar(oAjax) {
  
  js_removeObj("msgbox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {
    alert(oRetorno.message.urlDecode());
  } else {
    getFiltrosPadrao();
  }
} 
  
function getFiltrosPadrao()  {
  
  var oParam       = new Object();
  oParam.exec      = 'getParametrosPadrao';
  if (lUsuario) {
     oParam.exec   = 'getParametrosUsuario';
  }
  oParam.linha     = <?=$oGet->o116_codseq?>;
  oParam.relatorio = <?=$oGet->o116_codparamrel?>;
  js_divCarregando('Aguarde, procurando filtros',"msgbox");
  var oAjax   = new Ajax.Request(
                         sUrlRPC, 
                         {
                          method    : 'post', 
                          parameters: 'json='+Object.toJSON(oParam), 
                          onComplete: js_retornoGetParametros
                          }
                        );
}
function js_retornoGetParametros(oAjax) {
  
  js_removeObj("msgbox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {
    alert(oRetorno.message.urlDecode());
  } else {
  
    var oFiltro = oRetorno.filter;
    oGridContas.clearAll(true);
    oFiltro.contas.each(function(oConta, id) { 
     
        var sExclusao  = "N�o";
        if (oConta.exclusao) {
          sExclusao  = "Sim";
        }
        var aNovaConta = new Array();
        aNovaConta[0]  = oConta.estrutural;
        aNovaConta[1]  = sExclusao;
        aNovaConta[2]  = oConta.nivel;
        if (lReaOnly) {
          aNovaConta[3]  = "";
        } else {
          aNovaConta[3]  = "<input type='button' value='Excluir' onclick='js_excluir("+oConta.estrutural+")' style='width:100%'>";
        }
        oGridContas.addRow(aNovaConta);
      });
    oGridContas.renderRows();
    
    if (oFiltro.desdobrarlinha) {
      $('desdobrarlinha').checked = true;
    }
    $('cboOperadorOrgao').value = oFiltro.orgao.operador;
    oTxtOrgao.setValue(oFiltro.orgao.valor);
    
    $('cboOperadorUnidade').value = oFiltro.unidade.operador;
    oTxtUnidade.setValue(oFiltro.unidade.valor);
    
    $('cboOperadorFuncao').value = oFiltro.funcao.operador;
    oTxtFuncao.setValue(oFiltro.funcao.valor);
    
    $('cboOperadorSubFuncao').value = oFiltro.subfuncao.operador;
    oTxtSubFuncao.setValue(oFiltro.subfuncao.valor);
    
    $('cboOperadorPrograma').value = oFiltro.programa.operador;
    oTxtPrograma.setValue(oFiltro.programa.valor);
    
    $('cboOperadorProjAtiv').value = oFiltro.projativ.operador;
    oTxtProjAtiv.setValue(oFiltro.projativ.valor);
    
    $('cboOperadorRecurso').value = oFiltro.recurso.operador;
    oTxtRecurso.setValue(oFiltro.recurso.valor);

    $('cboCaracteristica').value = oFiltro.caracteristica.operador;
    oTxtCaracteristica.setValue(oFiltro.caracteristica.valor);
     
    if (oFiltro.recursocontalinha) {
    
      oTxtCodigoLinha.setValue(oFiltro.recursocontalinha);
      if (oFiltro.recursocontalinha != "") {
        
        oTxtRecurso.setReadOnly(true);
        $('cboOperadorRecurso').disabled = true;
      }
    }
    if (oFiltro.observacao) {
      $('txtObservacao').value = oFiltro.observacao.urlDecode();
    }
    if (lUsuario && oRetorno.lDesdobraLinha) {
      $('fldOutrasOpcoes').style.display  = '';
    }
  }
}  
$('btnIncluirConta').observe("click", addConta);
$('btnSalvarFiltros').observe("click", js_salvarFiltro);
js_init();
if (lReaOnly) {
  
  $('form1').disable();
  
}

function js_excluirFiltroUsuario() {
  
  if (!confirm('Confirma  exclus�o do Filtro?')) {
    return false;
  }
  var oParam       = new Object();
  oParam.exec      = 'excluirParametrosOrcamentoUsuario';
  oParam.linha     = <?=$oGet->o116_codseq?>;
  oParam.relatorio = <?=$oGet->o116_codparamrel?>;
  js_divCarregando('Aguarde, excluindo filtros',"msgbox");
  var oAjax   = new Ajax.Request(
                         sUrlRPC, 
                         {
                          method    : 'post', 
                          parameters: 'json='+Object.toJSON(oParam), 
                          onComplete: js_retornoExcluirParametro
                          }
                        );
}

function js_retornoExcluirParametro(oAjax) {
  
  js_removeObj("msgbox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {
    alert(oRetorno.message.urlDecode());
  } else {
  
    alert('Filtro excluido com sucesso.');
    getFiltrosPadrao();
  }
  
}
function js_importarFiltroUsuario() {
  
  if (!confirm('Confirma a importa��o do filtro padr�o?\nSua configura��o manual ser� perdida.')) {
    return false;
  }
  var oParam       = new Object();
  oParam.exec      = 'importarParametros';
  oParam.linha     = <?=$oGet->o116_codseq?>;
  oParam.relatorio = <?=$oGet->o116_codparamrel?>;
  js_divCarregando('Aguarde, importando filtros',"msgbox");
  var oAjax   = new Ajax.Request(
                         sUrlRPC, 
                         {
                          method    : 'post', 
                          parameters: 'json='+Object.toJSON(oParam), 
                          onComplete: js_retornoImportarParametro
                          }
                        );
}

function js_retornoImportarParametro(oAjax) {
  
  js_removeObj("msgbox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {
    alert(oRetorno.message.urlDecode());
  } else {
    getFiltrosPadrao();
  }
  
}
if (lUsuario) {

  $('btnImportarFiltros').style.display = '';
  $('btnImportarFiltros').observe("click", js_importarFiltroUsuario);
  $('btnExcluirFiltros').observe("click", js_excluirFiltroUsuario);
  $('btnExcluirFiltros').style.display  = '';
}
</script>