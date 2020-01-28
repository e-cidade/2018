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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("model/Dotacao.model.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_pcproc_classe.php");
require_once("classes/db_pcparam_classe.php");
require_once("classes/db_solicita_classe.php");
require_once("classes/db_pctipocompra_classe.php");
require_once("classes/db_emptipo_classe.php");
require_once("classes/db_empautoriza_classe.php");
include("classes/db_cflicita_classe.php");
$clpcproc = new cl_pcproc;
$clcflicita = new cl_cflicita;
$clpcparam = new cl_pcparam;
$clpctipocompra = new cl_pctipocompra;
$clsolicita = new cl_solicita;
$clemptipo = new cl_emptipo;
$clempautoriza = new cl_empautoriza;
$clempautoriza->rotulo->label();
$clpcproc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc12_tipo");
$clrotulo->label("e54_codtipo");
$clrotulo->label("e54_autori");
$clrotulo->label("e54_destin");
$clrotulo->label("e54_numerl");
$clrotulo->label("e54_tipol");
$clrotulo->label("pc10_numero");
$clrotulo->label("pc10_resumo");
$clrotulo = new rotulocampo;
$clrotulo->label("ac16_sequencial");
$clrotulo->label("ac16_resumoobjeto");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js,dbautocomplete.widget.js");
db_app::load("dbmessageBoard.widget.js, prototype.js, dbtextField.widget.js, dbcomboBox.widget.js");
db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <br>
  <br>
  <center>
    <table style='' border='0'>
      <tr>
        <td width="100%">
        <fieldset>
          <legend><b>Informe o  Acordo</b></legend>
          <table width="100%">
              <tr>
                <td>
                  <?
                   db_ancora($Lac16_sequencial, "js_pesquisaac16_sequencial(true);", 1);
                 ?>
                </td>
                <td>
                   <span id='ctnTxtCodigoAcordo'></span>
                   <span id='ctnTxtDescricaoAcordo'></span>
                </td>
              </tr>
              <tr>
                <td colspan="3" style="text-align: center">
                  <input type="button" value='Pesquisar' id='btnPesquisarAutorizacoes'>
                </td>
              </tr>
            </table>
            </fieldset>
          </td>
        </tr>
        <tr>
         <tr>
           <td>
             <fieldset>
               <legend><b>Autorizações do Acordo</b></legend>
               <div id='ctnGridAutorizacoes'>
               </div>
             </fieldset>
           </td>
         </tr>
         <tr>
           <td colspan="2" style="text-align: center;">
             <input type="button" value='Anular' id='btnAnularAutorizacoes' onclick="js_anularAutorizacoes()">
           </td>
         </tr> 
      </table>
    </center>
  </body>
</html>
<script type="text/javascript">
var sUrlRpc = 'con4_contratosmovimentacoesfinanceiras.RPC.php';
/**
 * Pesquisa acordos
 */
function js_pesquisaac16_sequencial(lMostrar) {

  if (lMostrar == true) {
    
    var sUrl = 'func_acordo.php?funcao_js=parent.js_mostraacordo1|ac16_sequencial|ac16_resumoobjeto&iTipoFiltro=4';
    js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_acordo', 
                        sUrl,
                        'Pesquisar Acordo',
                        true);
  } else {
  
    if (oTxtCodigoAcordo.getValue() != '') { 
    
      var sUrl = 'func_acordo.php?descricao=true&pesquisa_chave='+oTxtCodigoAcordo.getValue()+
                 '&funcao_js=parent.js_mostraacordo&iTipoFiltro=4';
                 
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_acordo',
                          sUrl,
                          'Pesquisar Acordo',
                          false);
     } else {
       oTxtCodigoAcordo.setValue('');
     }
  }
}

/**
 * Retorno da pesquisa acordos
 */
function js_mostraacordo(chave1,chave2,erro) {
 
  if (erro == true) {
   
    oTxtCodigoAcordo.setValue('');
    oTxtDescricaoAcordo.setValue('');
    $('oTxtDescricaoAcordo').focus(); 
  } else {
  
    oTxtCodigoAcordo.setValue(chave1);
    oTxtDescricaoAcordo.setValue(chave2);
  }
}

/**
 * Retorno da pesquisa acordos
 */
function js_mostraacordo1(chave1,chave2) {

  oTxtCodigoAcordo.setValue(chave1);
  oTxtDescricaoAcordo.setValue(chave2);
  db_iframe_acordo.hide();
}
function js_pesquisarAutorizacoesContrato() {

  if (oTxtCodigoAcordo.getValue() == "") {
    
    alert('Informe um acordo!');
    return false;
  } 
  js_divCarregando('Aguarde, pesquisando autorizações...', 'msgbox');
  oGridAutorizacoes.clearAll(true);
  var oParam     = new Object();
  oParam.exec    = 'getAutorizacoesAcordo';
  oParam.iAcordo = oTxtCodigoAcordo.getValue();
  var oAjax      = new Ajax.Request(sUrlRpc,
                                    {method:'post',
                                     parameters:'json='+Object.toJSON(oParam),
                                     onComplete: js_retornoGetAutorizacoesAcordo
                                    } 
                                   )
}
function js_retornoGetAutorizacoesAcordo(oAjax) {

  js_removeObj('msgbox');
  var oRetorno = eval("("+oAjax.responseText+")");
  oGridAutorizacoes.clearAll(true);
  if (oRetorno.status == 1) {
    
    oRetorno.autorizacoes.each(function (oAutorizacao, iLinha) {
    
      if (oAutorizacao.dataanulacao == '' && oAutorizacao.codigoempenho == '') {
        var aLinha = new Array();      
        aLinha[0]  = oAutorizacao.codigo;
        aLinha[1]  = "<a href='#' onclick='js_visualizarAutorizacao("+oAutorizacao.codigo+");return false'>";
        aLinha[1]  += oAutorizacao.codigo+"</a>";
        aLinha[2]  = js_formatar(oAutorizacao.dataemissao, 'd');
        aLinha[3]  = js_formatar(oAutorizacao.e54_valor, 'f');
        oGridAutorizacoes.addRow(aLinha);
      }
    });
    oGridAutorizacoes.renderRows();
  }
}

function js_main() {

   oTxtCodigoAcordo = new DBTextField('oTxtCodigoAcordo', 'oTxtCodigoAcordo','', 10);
   oTxtCodigoAcordo.addEvent("onChange",";js_pesquisaac16_sequencial(false);");
   oTxtCodigoAcordo.show($('ctnTxtCodigoAcordo'));
   
   oTxtDescricaoAcordo = new DBTextField('oTxtDescricaoAcordo', 'oTxtDescricaoAcordo','', 80);
   oTxtDescricaoAcordo.show($('ctnTxtDescricaoAcordo'));
   oTxtDescricaoAcordo.setReadOnly(true);
   
   oGridAutorizacoes = new DBGrid('oGridAutorizacoes');
   oGridAutorizacoes.nameInstance = 'oGridAutorizacoes';
   oGridAutorizacoes.setCheckbox(0);
   oGridAutorizacoes.setCellAlign(new Array('right', 'right', "center", 'right'));
   oGridAutorizacoes.setHeader(new Array("cod", 'Autorização', 'Data', 'Valor'));
   oGridAutorizacoes.aHeaders[1].lDisplayed = false;
   oGridAutorizacoes.setHeight(250);
   oGridAutorizacoes.show($('ctnGridAutorizacoes'));
   
   $('btnPesquisarAutorizacoes').onclick = js_pesquisarAutorizacoesContrato;
}
function js_visualizarAutorizacao(iAutorizacao) {
  js_OpenJanelaIframe('', 
                      'db_iframe_autorizacao', 
                      'func_empempenhoaut001.php?e54_autori='+iAutorizacao,
                      'Dados da Autorizacao',
                        true
                        );
}

function js_anularAutorizacoes() {

  var aLinhas = oGridAutorizacoes.getSelection("object");
  if (aLinhas.length == 0) {
    
    alert('Nenhuma autorização de empenho selecionada.');
    return false;
  }
  if (!confirm("Confirma a anulação das autorizações selecionadas?")) {
    return false;
  }
  js_divCarregando('Aguarde, anulando autorizações selecionadas...', 'msgbox');
  var oParam           = new Object();
  oParam.exec          = "anularAutorizacoes";
  oParam.aAutorizacoes = new Array();
  aLinhas.each(function(iAut, iSeq){
     oParam.aAutorizacoes.push(iAut.aCells[0].getValue());
  });
  var oAjax = new Ajax.Request(sUrlRpc,
                              {method:'post',
                               parameters:'json='+Object.toJSON(oParam),
                               onComplete:js_retornoAnularAutorizacoes
                              }
                              );
                              
}

function js_retornoAnularAutorizacoes(oResponse) {
  
  js_removeObj('msgbox');
  var oRetorno = eval("("+oResponse.responseText+")");
  if (oRetorno.status == 1)  {

    alert('Autorizações anuladas com sucesso!');
    js_pesquisarAutorizacoesContrato();
  } else {
    alert(oRetorno.message.urlDecode());
  }
}
js_main()
</script>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
