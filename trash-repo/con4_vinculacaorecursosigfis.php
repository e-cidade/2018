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
$oRotulo->label("o15_codtri");
$oRotulo->label("o15_codigo");
$oRotulo->label("o15_descr");
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
          <legend><b>Vincular Recursos Sigfis</b></legend>
          <table>
            <tr>
              <td>
                <b>Recurso SigFis:</b>
              </td>
              <td>
                 <?
                  db_input('codigorecursotce', 10, $Io15_codtri, true, "text", 3);
                  db_input('descricaorecursotce',  40, $Io15_descr, true, "text", 1);
                ?>
              </td>
            </tr> 
            <tr>
              <td>
                <b><?
                db_ancora($Lo15_codigo, 'js_pesquisa_recursos(true);', 1)?></b>
              </td>
              <td>
                 <?
                  db_input('o15_codigo', 10, $Io15_codigo, true, "text", 1);
                  db_input('o15_descr',  40, $Io15_descr, true, "text", 3);
                ?>
              </td>
            </tr> 
          </table>
        </fieldset>
      </div>    
      <input type="button" value='Salvar' id='btnSalvarVinculo'>
      <input type="button" value='Visualizar Vínculos' id='btnVisualizar'>
    </center>
  </body>
</html>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>    
<script>
oAutoCompleteRecursoTCE = new dbAutoComplete($('descricaorecursotce'), 'con4_pesquisarecursosigfis.RPC.php');
oAutoCompleteRecursoTCE.setTxtFieldId(document.getElementById('codigorecursotce'));
oAutoCompleteRecursoTCE.show();

function js_pesquisa_recursos(mostra){
  if(mostra == true) {
    js_OpenJanelaIframe('', 
                       'db_iframe_orctiporec',
                       'func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr',
                       'Pesquisa de Recursos', true, '10');
  } else {
   
    if ($F('o15_codigo') != "") {
     
      js_OpenJanelaIframe('', 
                          'db_iframe_orctiporec',
                          'func_orctiporec.php?pesquisa_chave='+$F('o15_codigo')+
                          '&funcao_js=parent.js_mostraorctiporec',
                          'Pesquisa de Recursos',
                          false);
                        
    } else {
      $('o15_descr').value  = ''
    }
  }
}

function js_mostraorctiporec(chave, erro) {
 
  document.form1.o15_descr.value = chave; 
  if (erro == true) { 
 
    document.form1.o15_codigo.focus(); 
    document.form1.o15_codigo.value = ''; 
  }
}

function js_mostraorctiporec1(chave1,chave2){

  document.form1.o15_codigo.value = chave1;
  document.form1.o15_descr.value = chave2;
  db_iframe_orctiporec.hide();
}

$('o15_codigo').observe("change", function() {
     js_pesquisa_recursos(false);
});
$('btnSalvarVinculo').observe('click', function() {
     
     var iCodigoRecurso    = $F('o15_codigo');
     var iCodigoRecursoTCE = $F('codigorecursotce');
     if (iCodigoRecurso == '') {
     
       alert('Informe o código do recurso do e-cidade!');
       js_pesquisa_recursos(true);
       return false;
     }
     if (iCodigoRecursoTCE == '') {
       
       alert('Informe o código do recurso do SigFis!');
       $('descricaorecursotce').focus();
       return false;
     }
     js_divCarregando('Aguarde, vinculando Recurso', 'msgBox');
     var oParam         = new Object();
     oParam.exec        = 'vincularRecursos';
     oParam.recurso     = iCodigoRecurso;
     oParam.recursotce  = iCodigoRecursoTCE;
     var oAjax          = new Ajax.Request('con4_vincularrecursossigfis.RPC.php',
                                         {method: 'POST',
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete:js_retornoVinculo 
                                         } 
                                         ) 
  });
  
  function js_retornoVinculo(oAjax) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {
 
      alert('Vínculo realizado com sucesso');
      $('o15_codigo').value = '';
      $('o15_descr').value  = '';
    } else {
      alert(oRetorno.message.urlDecode());  
    }
  }
  /**
   * Crioa uma janela com os recursos já vinculados
   */
  function js_visualizarVinculos() {

    var sContent  = "<center>";
        sContent += "<fieldset>";
        sContent += "  <legend><b>Vinculos Realizados</b></legend>";
        sContent += "  <div id='ctnVinculos'></div>";
        sContent += "</fieldset>";
        sContent += "<br><input type='button' name='btnExcluir' id='btnConfirmar' value='Excluir' ";
        sContent += "  onclick='js_removerRecursosVinculados()'>";
        sContent += "&nbsp;<input type='button' name='btnFechar' id='btnFechar' value='Fechar'>";
        sContent += "</center>";

    /**
     * Monta o WINDOWAUX
     */
    var oWindowVinculo= new windowAux("winAuxVinculo", "Vínculos Realizados", 600, 400);
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
    oGridVinculo.setHeader(new Array("Recurso TCE", "Recurso ", "Descrição"));
    oGridVinculo.show($('ctnVinculos'));
    js_getVinculosRecursos();               
  }
  
  /**
   * Pesquisa os vinculos já realizados
   */
  function js_getVinculosRecursos() {
    
    js_divCarregando('Aguarde, buscando Recursos...', 'msgBox');
    var oParam         = new Object();
    oParam.exec        = 'getVinculos';
    var oAjax          = new Ajax.Request('con4_vincularrecursossigfis.RPC.php',
                                         {method: 'POST',
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete:js_retornogetRecursosVinculados 
                                         } 
                                         ) 
  
  
  }
  /**
   * preenche a datagrid com os recursos
   */
  function js_retornogetRecursosVinculados (oAjax) {
     
     js_removeObj('msgBox');
     oGridVinculo.clearAll(true);
     var oRetorno = eval('('+oAjax.responseText+")");
     oRetorno.recursosvinculados.each(function(oRecurso, iSeq) {
         
         var aRow = new Array();
         aRow[0]  = oRecurso.codigotce;
         aRow[1]  = oRecurso.codigoecidade;
         aRow[2]  = oRecurso.descricao.urlDecode();
         oGridVinculo.addRow(aRow);
     });
     oGridVinculo.renderRows();
   }
   
   function js_removerRecursosVinculados() {
   
     var aRecursos = oGridVinculo.getSelection('object');
     if (aRecursos.length == 0) {
     
       alert('Nenhum recurso selecionado para retirar o vínculo.\nProcedimento cancelado');
       return false;
     }
     var aRecursosRemover = new Array();
     aRecursos.each(function (oRecurso, iSeq) {
       aRecursosRemover.push(oRecurso.aCells[0].getValue());
     });
    js_divCarregando('Aguarde, removendo vinculos...', 'msgBox');
    var oParam       = new Object();
    oParam.exec      = 'removerVinculos';
    oParam.aRecursos = aRecursosRemover;
    var oAjax        = new Ajax.Request('con4_vincularrecursossigfis.RPC.php',
                                         {method: 'POST',
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete:js_retornocancelavinculo 
                                         } 
                                         ); 
     
   }
   
   function js_retornocancelavinculo(oAjax) {
     
     js_removeObj('msgBox');
     var oRetorno = eval('('+oAjax.responseText+')');
     if (oRetorno.status == 1) {
     
       alert('Vínculo dos recursos selecionados foram removidos com sucesso!');
       js_getVinculosRecursos();
     } else {
       alert(oRetorno.message.urlDecode());
     }
   
   }
  $('btnVisualizar').observe('click', js_visualizarVinculos);
</script>