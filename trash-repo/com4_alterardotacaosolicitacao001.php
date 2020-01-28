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
require_once("libs/db_conecta.php");
include_once("libs/db_sessoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
$oRotuloLabel = new rotulocampo();
$oRotuloLabel->label("l20_codigo");
$oRotuloLabel->label("pc10_numero");
$oRotuloLabel->label("pc10_data");
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0"> 
    <?
      db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js");
      db_app::load("widgets/dbmessageBoard.widget.js, widgets/windowAux.widget.js, datagrid.widget.js");
      db_app::load("classes/DBViewSolicitacaoDotacao.classe.js");
      db_app::load("estilos.css, grid.style.css");
    ?>
  </head>
  <body style="background-color: #cccccc; margin-top: 25px">
      
    <center> 
      <div style="display: table;" id='pesquisa-solicitacoes'>
        <fieldset> 
          <legend><b>Filtros para Pesquisa</legend>
            <table>
              <tr>
                <td>
                  <?
                   db_ancora("<b>Licitação:</b>", "js_pesquisaLicitacao(true)", 1);  
                  ?>
                </td>
                <td colspan="4">
                   <?
                    db_input("l20_codigo", 10, $Il20_codigo, true, 'text', 3);
                   ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?
                    db_ancora("<b>Solicitação:</b>", "js_pesquisaSolicitacao(true, 'inicio')", 1);
                  ?>
                </td>
                <td>
                  <?
                    db_input("pc10_numeroInicial", 10, $Ipc10_numero, true, 'text', 1);
                   ?>
                </td>
                <td>
                  <?
                    db_ancora("<b>até:</b>", "js_pesquisaSolicitacao(true, 'fim')", 1);
                  ?>
                </td>
                <td>
                  <?
                    db_input("pc10_numeroFinal", 10, $Ipc10_numero, true, 'text', 1);
                   ?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Data da Solicitação:</b>
                </td>
                <td>
                  <?
                    db_inputdata('pc10_datainicial', @$pc10_datainicial_dia, @$pc10_datainicial_mes, @$pc10_datainicial_ano,
                                 true, 'text', 1);
                  ?>
                </td>
                <td>
                  <b>até</b>
                </td>
                <td>
                  <?
                    db_inputdata('pc10_datafinal', @$pc10_datafinal_dia, @$pc10_datafinal_mes, @$pc10_datafinal_ano, true,
                                 'text', 1);
                  ?>
                </td>
              </tr>
            </table>
        </fieldset>
      </div>
      <input style='margin-top: 10px;' type="button" name='Alterar' value='Alterar' onclick="validarDados();">
    </center>
  </body>
</html>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
var sUrl = "com4_alteradotacaosolicitacao.RPC.php";
function js_pesquisaLicitacao(lMostra) {
   
   if (lMostra) {
     
     js_OpenJanelaIframe('top.corpo',
                         'db_iframe_licitacao',
                         'func_liclicita.php?funcao_js=parent.js_mostraLicitacao|l20_codigo',
                         'Pesquisa',
                         true
                        );
  }
}

function js_mostraLicitacao(codigo) {

   $("l20_codigo").value = codigo;  
   db_iframe_licitacao.hide();
}

function js_pesquisaSolicitacao(lMostra, sParam) {

  var sNomeFuncao;
  var qry = "&nada=true";
  if (lMostra == true) {
  
    sNomeFuncao = 'js_mostraSolicitacaoInicial1';
    if (sParam == 'fim') {
      sNomeFuncao = 'js_mostraSolicitacaoFim1';
    }
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_solicita',
                        'func_solicita.php?funcao_js=parent.'+sNomeFuncao+'|pc10_numero'+qry,
                        'Pesquisa',
                        true
                       );
  } else {
  
    sNomeFuncao = 'js_mostraSolicitacaoInicial';
    if (sParam == 'fim') {
      sNomeFuncao = 'js_mostraSolicitacaoFim';
    }
    if(document.form1.pc10_numero.value!="") {
    
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_solicita',
                          'func_solicita.php?funcao_js=parent.'+sNomeFuncao+
                          '&pesquisa_chave='+$("pc10_numeroInicial").value+qry,
                          'Pesquisa',
                          false
                         );
    } else {
      $("pc10_numeroInicial").value = "";
    }
  }
}

function js_mostraSolicitacaoInicial1(codigo) {

  $("pc10_numeroInicial").value = codigo;
  db_iframe_solicita.hide();
}

function js_mostraSolicitacaoInicial (codigo,erro) {

  if (erro == true) {
    $("pc10_numeroInicial").value = "";
  }
}

function js_mostraSolicitacaoFim1(codigo){

  $("pc10_numeroFinal").value = codigo;
  db_iframe_solicita.hide();
}

function js_mostraSolicitacaoFim (codigo,erro) {

  if (erro == true) {
    $("pc10_numeroFinal").value = "";
  }
}

function validarDados() {

  var iNumeroSolicitacaoInicial = new Number($F('pc10_numeroInicial'));
  var iNumeroSolicitacaoFinal   = new Number($F('pc10_numeroFinal'));
  var iDataSolicitacaoInicial   = $F('pc10_datainicial');
  var iDataSolicitacaoFinal     = $F('pc10_datafinal');

  if (iNumeroSolicitacaoInicial == "" && iNumeroSolicitacaoFinal != "") {
    
    alert("Para escolher um intervalo final de solicitações, antes déve escolher o intervalo inicial.");
    $('pc10_numeroFinal').value = "";
    return false;    
  } 

  if (iNumeroSolicitacaoInicial != "" && iNumeroSolicitacaoFinal != "") {
    
    if (iNumeroSolicitacaoInicial > iNumeroSolicitacaoFinal) {
    
      if (confirm("O intervalo inicial da solicitação esta maior que o intervalo final.\n Deseja inverter?")) {
        
        var iAux                  = iNumeroSolicitacaoFinal;
        iNumeroSolicitacaoFinal   = iNumeroSolicitacaoInicial;
        iNumeroSolicitacaoInicial = iAux;
        
        $('pc10_numeroInicial').value = iNumeroSolicitacaoInicial;
        $('pc10_numeroFinal').value   = iNumeroSolicitacaoFinal;
      } else {
      
        $('pc10_numeroInicial').value = "";
        $('pc10_numeroFinal').value = "";
      }
    }
  }
  if (iDataSolicitacaoInicial != "") {
    if (js_comparadata(iDataSolicitacaoInicial, iDataSolicitacaoFinal, '>')) {
    
      alert('Data Inicial esta maior que a data final.');
      return false;
    }
  }
  oWindowSolicitacoes = new windowAux('wndSolicitacoes', "Lista de Solicitações", 800, 450);
  oWindowSolicitacoes.setShutDownFunction(function () {
    oWindowSolicitacoes.destroy();
  });
  var sContent  = "<div id='ctnSolicitacao'>";
      sContent += "  <fieldset>";
      sContent += "    <legend><b>Solicitações</b></legend>";
      sContent += "    <div id='ctnGridSolicitacoes'></div>";
      sContent += "  </fieldset>";
      sContent += "<div>";
  oWindowSolicitacoes.setContent(sContent);
  var sMensagem  = 'Clique duplo na solicitação para visualizar as seus itens e dotações.';
      sMensagem += "<br>&nbsp;<strong>  * </strong> Solicitação possui um ou mais itens sem dotação."; 
  oMessageBoard = new DBMessageBoard('msgBoardSolicitacao', 
                                     'Solicitações Retornadas',
                                     sMensagem,
                                     oWindowSolicitacoes.getContentContainer()
                                     );    
  oWindowSolicitacoes.show();
  
  oGridSolicitacoes              = new DBGrid('Solicitacoes');
  oGridSolicitacoes.nameInstance = 'oGridSolicitacoes';
  oGridSolicitacoes.setCellWidth(new Array( '25px',
                                            '35px',
                                            '50px',
                                            '150px'
                                           ));
  
  oGridSolicitacoes.setCellAlign(new Array( 'right'  ,
                                            'center'  ,
                                            'left',
                                            'left'  
                                           ));
  
  oGridSolicitacoes.setHeader(new Array( 'Solicitação',
                                         'Data de Emissão',
                                         'Dotações',
                                         'Resumo'
                                        ));
                                       
  oGridSolicitacoes.setHeight(250);
  oGridSolicitacoes.show($('ctnGridSolicitacoes'));
  js_pesquisarSolicitacoes();
}

function js_pesquisarSolicitacoes () {
      
  var iNumeroSolicitacaoInicial = $F('pc10_numeroInicial');
  var iNumeroSolicitacaoFinal   = $F('pc10_numeroFinal');
  var dtDataSolicitacaoInicial  = $F('pc10_datainicial');
  var dtDataSolicitacaoFinal    = $F('pc10_datafinal');   
  var iLicitacao                = $F("l20_codigo");
  
  var msgDiv                   = "Carregando Lista de Solicitações. \n Aguarde ...";
  js_divCarregando(msgDiv,'msgBox');
  
  var oParam     = new Object();
  oParam.exec    = 'pesquisarSolicitacoes';
  
  oParam.filtros                           = new Object();
  oParam.filtros.iNumeroSolicitacaoInicial = iNumeroSolicitacaoInicial; 
  oParam.filtros.iNumeroSolicitacaoFinal   = iNumeroSolicitacaoFinal; 
  oParam.filtros.dtDataSolicitacaoInicial  = dtDataSolicitacaoInicial; 
  oParam.filtros.dtDataSolicitacaoFinal    = dtDataSolicitacaoFinal;
  oParam.filtros.iLicitacao                = iLicitacao;
  
  var  aAjax = new Ajax.Request(sUrl, 
                           {method:'post',
                            parameters: 'json='+Object.toJSON(oParam),
                            onComplete: js_retornoPesquisaSolicitacoes
                           });
                   
}

function js_retornoPesquisaSolicitacoes(oAjax) {

  js_removeObj('msgBox');
  var oRetorno      = eval("("+oAjax.responseText+")");
  
  if (oRetorno.aSolicitacoes.length == 0) {
    alert("Não existem solicitações para os filtros.");
    return false;
  }

  oGridSolicitacoes.clearAll(true);
  oRetorno.aSolicitacoes.each( function (oDado, iInd) {       

      var sDotacao     = oDado.dotacoes;
      var sSolicitacao = oDado.solicitacao + "&nbsp;";

      /*
         validamos se as solicitaçoes possuem ao menos um item sem dotacao
         se possuir teremos um asterisco na descrição
      */
      if ((oDado.lIemSemDotacao == 1 || oDado.lIemSemDotacao == "1") && oDado.dotacoes != '') {

        sSolicitacao = "<strong>*</strong> " + oDado.solicitacao + "&nbsp;";
      }
      
      if (sDotacao == null || sDotacao == '') {

        sDotacao = "<label><strong>Selecione Dotação</strong></label>";
      }
    
      aRow     = new Array();  
                                                                  
      aRow[0]  = sSolicitacao;
      aRow[1]  = oDado.dtEmis;
      aRow[2]  = "&nbsp;" + sDotacao;
      aRow[3]  = oDado.resumo.urlDecode();
      oGridSolicitacoes.addRow(aRow);
      oGridSolicitacoes.aRows[iInd].sEvents += "ondblclick='js_montaWindowDetalhes("+oDado.solicitacao+")'";
   });
          
  oGridSolicitacoes.renderRows();  
}

function js_montaWindowDetalhes(iCodigoSolicitacao) {

  oViewSolicitacaoDotacao = new DBViewSolicitacaoDotacao(iCodigoSolicitacao, "oViewSolicitacaoDotacao");
  oViewSolicitacaoDotacao.getDotacoes();
  oViewSolicitacaoDotacao.onBeforeSave(js_pesquisarSolicitacoes);
  
}
</script>