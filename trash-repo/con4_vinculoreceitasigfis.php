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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_app.utils.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$oRotulo = new rotulocampo();
$oRotulo->label("o57_codfon");
$oRotulo->label("o57_fonte");
$oRotulo->label("o57_descr");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
                   dbmessageBoard.widget.js, datagrid.widget.js, widgets/dbautocomplete.widget.js");
      db_app::load("estilos.css,grid.style.css");
    ?>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" style='margin-top:25px' leftmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <center>
    <form name='form1' id='form1'>
      <div style="display: table">
        <fieldset>
          <legend><b>Vincular Receitas Sigfis</b></legend>
          <table>
            <tr>
              <td>
                <b>Receita SigFis:</b>
              </td>
              <td>
                 <?
                  db_input('codigoreceitatce', 10, $Io57_fonte, true, "text", 3);
                  db_input('descricaoreceitatce',  40, $Io57_descr, true, "text", 1);
                ?>
              </td>
            </tr> 
            <tr>
              <td>
                <b><?
                db_ancora($Lo57_codfon, 'js_pesquisa_receita(true);', 1)?></b>
              </td>
              <td>
                 <?
                  db_input('o57_codfon', 10, $Io57_codfon, true, "text", 1);
                  db_input('o57_descr',  40, $Io57_descr, true, "text", 3);
                ?>
              </td>
            </tr> 
          </table>
        </fieldset>
      </div>    
      <input type="button" value='Salvar' id='btnSalvarVinculo'>
      <input type="button" value='Visualizar Vínculos' id='btnVisualizar'>
    </form>
    </center>
  </body>
</html>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>    
<script>
oAutoCompleteReceitaTCE = new dbAutoComplete($('descricaoreceitatce'), 'con4_pesquisareceitasigfis.RPC.php');
oAutoCompleteReceitaTCE.setTxtFieldId(document.getElementById('codigoreceitatce'));
oAutoCompleteReceitaTCE.show();

function js_pesquisa_receita(mostra) {
  if(mostra == true) {
    js_OpenJanelaIframe('', 
                       'db_iframe_orctiporec',
                       'func_orcfontes.php?funcao_js=parent.js_mostraorctiporec1|o57_codfon|o57_descr',
                       'Pesquisa de Receitas', true, '10');
  } else {
   
    if ($F('o57_codfon') != "") {
     
      js_OpenJanelaIframe('', 
                          'db_iframe_orctiporec',
                          'func_orcfontes.php?pesquisa_chave='+$F('o57_codfon')+
                          '&funcao_js=parent.js_mostraorctiporec&lPesquisaCodigo=true',
                          'Pesquisa de Receita',
                          false);
    } else {
      $('o57_descr').value  = '';
    }
  }
}

function js_mostraorctiporec(chave, erro) {
 
  document.form1.o57_descr.value = chave; 
  if (erro == true) { 
 
    document.form1.o57_codfon.focus(); 
    document.form1.o57_codfon.value = ''; 
  }
}

function js_mostraorctiporec1(chave1,chave2){

  document.form1.o57_codfon.value = chave1;
  document.form1.o57_descr.value = chave2;
  db_iframe_orctiporec.hide();
}

$('o57_codfon').observe("change", function() {
	js_pesquisa_receita(false);
});

$('btnSalvarVinculo').observe('click', function() {
  
  var iCodigoReceita    = $F('o57_codfon');
  var iCodigoReceitaTCE = $F('codigoreceitatce');

  if (iCodigoReceitaTCE == "") {

  	alert('Informe o código da receita Sigfis!');
    $('descricaoreceitatce').focus();
    return false;
  }
  if (iCodigoReceita == '') {
  
    alert('Informe o código da receita!');
  	$('o57_codfon').focus();
    return false;
  }
  js_divCarregando('Aguarde, vinculando Receita', 'msgBox');
  var oParam         = new Object();
  oParam.exec        = 'vincularReceita';
  oParam.receita     = iCodigoReceita;
  oParam.receitatce  = iCodigoReceitaTCE;
  var oAjax          = new Ajax.Request('con4_vinculareceitasigfis.RPC.php',
                                        {method: 'POST',
                                         parameters:'json='+Object.toJSON(oParam),
                                         onComplete:js_retornoVinculo 
                                        }); 
});

function js_retornoVinculo(oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    alert('Vínculo realizado com sucesso');
    js_limpaCampos();
  } else {
    
    alert(oRetorno.message.urlDecode());
    js_limpaCampos();
  }
}

function js_limpaCampos() {

	$('o57_codfon').value          = '';
  $('o57_descr').value           = '';
}

/**
 * Cria uma janela com as Receitas já vinculadas
 */

function js_visualizarVinculos() {

	var sContent  = "<center>";
  sContent     += "<fieldset>";
  sContent     += "  <legend><b>Vinculos Realizados</b></legend>";
  sContent     += "  <div id='ctnVinculos'></div>";
  sContent     += "</fieldset>";
  sContent     += "<br><input type='button' name='btnExcluir' id='btnConfirmar' value='Excluir' ";
  sContent     += "  onclick='js_removerReceitasVinculados()'>";
  sContent     += "&nbsp;<input type='button' name='btnFechar' id='btnFechar' value='Fechar'>";
  sContent     += "</center>";

  var oWindowVinculo = new windowAux("winAuxVinculo", "Vínculos Realizados", 600, 400);
  oWindowVinculo.setContent(sContent);
  oWindowVinculo.allowCloseWithEsc(false);
  oWindowVinculo.setShutDownFunction(function () {
    oWindowVinculo.destroy();
  });

  $('btnFechar').observe('click', function() {
    oWindowVinculo.destroy();
  });

  /**
   * MsgBoard com um help da windowAux
   */
  var sHelpMsgBoard = "Para excluir um vínculo realizado, selecione o vínculo e clique em Excluir.";
  var oMessageBoard = new DBMessageBoard("msgBoardVinculo", 
                                         "Vínculos já realizados",
                                          sHelpMsgBoard,
                                          oWindowVinculo.getContentContainer()
                                         );
  oMessageBoard.show();
  oWindowVinculo.show();
  oGridVinculo = new DBGrid('ctnProcCompras');
  oGridVinculo.nameInstance = "oGridVinculo";
  oGridVinculo.setCheckbox(1);
  oGridVinculo.setHeight(160);
  oGridVinculo.setCellAlign(new Array("right", "right", "left"));
  oGridVinculo.setCellWidth(new Array("25%", "25%","50%"));
  oGridVinculo.setHeader(new Array("Receita TCE", "Receita ", "Descrição"));
  oGridVinculo.show($('ctnVinculos'));
  js_getVinculosReceitas();         
  
} 

/**
 * Pesquisa os vinculos já realizados
 */
function js_getVinculosReceitas() {
  
  js_divCarregando('Aguarde, buscando Recursos...', 'msgBox');
  var oParam         = new Object();
  oParam.exec        = 'getVinculos';
  var oAjax          = new Ajax.Request('con4_vinculareceitasigfis.RPC.php',
                                       {method: 'POST',
                                        parameters:'json='+Object.toJSON(oParam),
                                        onComplete: js_retornoGetReceitaVinculados
                                       }); 
}

/**
 * preenche a datagrid com os recursos
 */
function js_retornoGetReceitaVinculados(oAjax) {
   
   js_removeObj('msgBox');
   oGridVinculo.clearAll(true);
   var oRetorno = eval('('+oAjax.responseText+")");
   oRetorno.receitavinculada.each(function(oReceita, iSeq) {
       
       var aRow = new Array();
       aRow[0]  = oReceita.codigotce;
       aRow[1]  = oReceita.codigoecidade;
       aRow[2]  = oReceita.descricao.urlDecode();
       oGridVinculo.addRow(aRow);
   });
   oGridVinculo.renderRows();
}

function js_removerReceitasVinculados() {
  
  var aReceitas = oGridVinculo.getSelection('object');
  if (aReceitas.length == 0) {
  
    alert('Nenhuma receita selecionada para retirar o vínculo.\nProcedimento cancelado');
    return false;
  }
  var aReceitasRemover = new Array();
  aReceitas.each(function (oRseceita, iSeq) {
  	aReceitasRemover.push(oRseceita.aCells[0].getValue());
  });
 js_divCarregando('Aguarde, removendo vinculos...', 'msgBox');
 var oParam       = new Object();
 oParam.exec      = 'removerVinculos';
 oParam.aReceitas = aReceitasRemover;
 var oAjax        = new Ajax.Request('con4_vinculareceitasigfis.RPC.php',
                                      {method: 'POST',
                                       parameters:'json='+Object.toJSON(oParam),
                                       onComplete:js_retornoCancelaVinculo 
                                      }); 
}
function js_retornoCancelaVinculo(oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status == 1) {
  
    alert('Vínculo das receceitas selecionadas foram removidas com sucesso!');
    js_getVinculosReceitas();
  } else {
    alert(oRetorno.message.urlDecode());
  }

}
$('btnVisualizar').observe('click', js_visualizarVinculos);

</script>