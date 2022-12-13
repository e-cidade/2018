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
$oRotulo->label("c60_codcon");
$oRotulo->label("c60_descr");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
     db_app::load("scripts.js, strings.js, prototype.js,datagrid.widget.js, widgets/dbautocomplete.widget.js");
     db_app::load("widgets/windowAux.widget.js, dbmessageBoard.widget.js");
     db_app::load("estilos.css,grid.style.css");
    ?>
  </head>
  <body bgcolor="#CCCCCC" style='margin-top:25px' leftmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <center>
      <form name='form1' id='form1'>
      <div style="display: table">
        <fieldset>
          <legend><b>Vincular Contas Sigfis</b></legend>
          <table>
             <tr>
               <td>
                 <b>Conta TCE/RJ:</b>
               </td>
               <td>
                  <?
                    db_input('codigocontatce', 10, $Ic60_codcon, true, "text", 3);
                    db_input('descricaocontatce',  40, $Ic60_descr, true, "text", 1);
                  ?>
               </td>
             </tr>
             <tr>
               <td>
                <?
                 db_ancora($Lc60_codcon, "js_pesquisaPlano(true)", 1);
                ?>
               </td>
               <td>
                  <?
                    db_input('c60_codcon', 10, $Ic60_codcon, true, "text", 1, 'onchange="js_pesquisaPlano(false)"');
                    db_input('c60_descr',  40, $Ic60_descr, true, "text", 1);
                  ?>
               </td>
             </tr>
             <tr>
                <td><b>Natureza do Saldo:</b></td>
                <td>
                  <? 
                    $aNaturezaSaldo = array(
                                            "C" => "Crédito",
                                            "D" => "Débito",  
                                            "M" => "Mista",  
                                           );
                   db_select("naturezasaldo", $aNaturezaSaldo, true, 1);                                           
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
  
  $('naturezasaldo').style.width = $('codigocontatce').scrollWidth;
  function js_pesquisaPlano(lShowWindow) {
     
     if (lShowWindow) {
     js_OpenJanelaIframe('', 
                         'db_iframe_conplano',
                         'func_conplanogeral.php?funcao_js=parent.js_retornoPlano|c60_codcon|c60_descr|c60_estrut'+
                         '&campofoco=chave_c61_reduz',
                         'Escolha uma Conta Contábil',
                          true);
    } else {
    
     if ($F('c60_codcon') != '') { 
        js_OpenJanelaIframe('', 
                            'db_iframe_conplano', 
                            'func_conplanogeral.php?pesquisa_chave='+$F('c60_codcon')+
                            '&funcao_js=parent.js_retornoPlano',
                            'Escolha uma Conta Contábil', false);
      } else {
      
        $('c60_codcon').value == ''; 
      }
    }
  }
  
  function js_retornoPlano()  {
    
    if (typeof(arguments[1]) == "boolean") {
      if (arguments[1]) {
      
        $('c60_descr').value  = arguments[0]; 
        $('c60_codcon').value = ''; 
      } else {
        $('c60_descr').value = arguments[2]+" - "+arguments[0]; 
      }
    } else {
    
      $('c60_descr').value  = arguments[2]+" - "+arguments[1]; 
      $('c60_codcon').value = arguments[0];
      db_iframe_conplano.hide();
      $('naturezasaldo').focus();
    }
  
  }
  
  oAutoCompleteConta = new dbAutoComplete($('c60_descr'), 'com4_pesquisaplanocontas.RPC.php');
  oAutoCompleteConta.setTxtFieldId(document.getElementById('c60_codcon'));
  oAutoCompleteConta.show();
  
  oAutoCompleteContaTCE = new dbAutoComplete($('descricaocontatce'), 'com4_pesquisaplanocontassigfis.RPC.php');
  oAutoCompleteContaTCE.setTxtFieldId(document.getElementById('codigocontatce'));
  oAutoCompleteContaTCE.show();
  oAutoCompleteContaTCE.setCallBackFunction(function (id, label){
    
    $('descricaocontatce').value = label;
    $('codigocontatce').value    = id;    
    if ($('c60_codcon').value == "") {
      js_pesquisaPlano(true);
    } else {
      $('naturezasaldo').focus();
    }
  });
   
  $('c60_codcon').observe("change", function() {
     js_pesquisaPlano(false);
  });
  $('btnSalvarVinculo').observe('click', function() {
     
     var iCodigoConta    = $F('c60_codcon');
     var iCodigoContaTCE = $F('codigocontatce');
     if (iCodigoConta == '') {
     
       alert('Informe a conta do plano de contas!');
       return false;
     }
     if (iCodigoContaTCE == '') {
       
       alert('Informe a conta do plano de contas do sigfis!');
       $('descricaocontatce').focus();
       return false;
     }
     js_divCarregando('Aguarde, vinculando Contas', 'msgBox');
     var oParam  = new Object();
     oParam.exec = 'vincularContas';
     oParam.contaplano  = iCodigoConta;
     oParam.contatce    = iCodigoContaTCE;
     oParam.origemsaldo = $F('naturezasaldo');
     var oAjax         = new Ajax.Request('con4_vincularcontassigfis.RPC.php',
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
      $('c60_codcon').value = '';
      $('c60_descr').value  = '';
      js_pesquisaPlano(true);
      
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
        sContent += "  onclick='js_removerContasVinculados()'>";
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
    oGridVinculo = new DBGrid('ctnGridVinculo');
    oGridVinculo.nameInstance = "oGridVinculo";
    oGridVinculo.setCheckbox(1);
    oGridVinculo.setHeight(160);
    oGridVinculo.setCellAlign(new Array("right", "right", "left"));
    oGridVinculo.setCellWidth(new Array("25%", "25%", "25%","25%"));
    oGridVinculo.setHeader(new Array("Codigo TCE", "Codigo E-cidade", "Estrutural", "Descrição"));
    oGridVinculo.show($('ctnVinculos'));
    js_getVinculosRecursos();               
  }
  
  /**
   * Pesquisa os vinculos já realizados
   */
  function js_getVinculosRecursos() {
    
    js_divCarregando('Aguarde, buscando Contas...', 'msgBox');
    var oParam         = new Object();
    oParam.exec        = 'getVinculos';
    var oAjax          = new Ajax.Request('con4_vincularcontassigfis.RPC.php',
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
     oRetorno.contasvinculadas.each(function(oConta, iSeq) {
         
         var aRow = new Array();
         aRow[0]  = oConta.codigotce;
         aRow[1]  = oConta.codigoecidade;
         aRow[2]  = oConta.estrutural;
         aRow[3]  = oConta.descricaoconta.urlDecode();
         oGridVinculo.addRow(aRow);
     });
     oGridVinculo.renderRows();
   }
   
   function js_removerContasVinculados() {
   
     var aContas = oGridVinculo.getSelection('object');
     if (aContas.length == 0) {
     
       alert('Nenhuma conta selecionado para retirar o vínculo.\nProcedimento cancelado');
       return false;
     }
     var aContaRemover = new Array();
     aContas.each(function (oConta, iSeq) {
       aContaRemover.push(oConta.aCells[0].getValue());
     });
    js_divCarregando('Aguarde, removendo vinculos...', 'msgBox');
    var oParam       = new Object();
    oParam.exec      = 'removerVinculos';
    oParam.aContas   = aContaRemover;
    var oAjax        = new Ajax.Request('con4_vincularcontassigfis.RPC.php',
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
     
       alert('Vínculo das contas selecionados foram removidos com sucesso!');
       js_getVinculosRecursos();
     } else {
       alert(oRetorno.message.urlDecode());
     }
   
   }
  $('btnVisualizar').observe('click', js_visualizarVinculos);
</script>