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
require_once("libs/db_sessoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
$oRotulo = new rotulocampo();
$oRotulo->label("o56_codele");
$oRotulo->label("o56_elemento");
$oRotulo->label("o56_descr");
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
  <body bgcolor="#CCCCCC" style='margin-top:25px' leftmargin="0" marginwidth="0" marginheight="0">
    <center>
    	<form name='form1' id='form1'>
    		<fieldset style="width:516px; margin-bottom:10px;">
    			<legend><strong>Vincular Despesas Sigfis</strong></legend>
    			<table>
    				<tr>
    					<td><strong>Despesa Sigfis: </strong></td>
    					<td>
    					  <?php 
    					    db_input('codigodespesatce', 10, $Io56_elemento , true, 'text', 3);
    					    db_input('descricaodespesatce', 40, $Io56_descr, true, 'text', 1);
    					  ?>
    					</td>
    				</tr>
    				<tr>
    					<td>
    						<?php 
    						  db_ancora($Lo56_codele, 'js_pesquisa_despesa(true);', 1);
    						?>
    					</td>
    					<td>
    					  <?php 
    					    db_input('o56_codele', 10, $Io56_codele, true, "text", 1, 'onchange="js_pesquisa_despesa(false);"');
    					    db_input('o56_descr',  40, $Io56_descr, true, "text", 3);
    					  ?>
    					</td>
    				</tr>
    			</table>
    		</fieldset>
    		<input type="button" name="btnSalvar" id="btnSalvar" onclick="js_salvarVinculo();" value="Salvar">
    		<input type="button" name="btnVisualizaVinculos" id="btnVisualizaVinculos" onclick="js_visualizarVinculos();" value="Visualizar Vínculos">
    	</form>
    </center>
  </body>
</html>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?> 
<script>

/**
 * Iniciamos o componente que efetua o auto complete do campo descricaodespesatce
 */
oAutoCompleteDespesaTCE = new dbAutoComplete($('descricaodespesatce'), 'con4_pesquisadespesasigfis.RPC.php');
oAutoCompleteDespesaTCE.setTxtFieldId(document.getElementById('codigodespesatce'));
oAutoCompleteDespesaTCE.show();

/**
 * Função que exibe a tela de pesquisa de despesas
 */
function js_pesquisa_despesa(mostra) {

  if (mostra === true) {

    js_OpenJanelaIframe('', 'db_iframe_orcelemento',
                        'func_orcelemento.php?funcao_js=parent.js_mostraOrcelemento1|o56_codele|o56_descr',
                        'Pesquisa de Despesas', true, '10');
  } else {

    if ($F('o56_codele') !== '') {

      js_OpenJanelaIframe('', 'db_iframe_orcelemento',
                          'func_orcelemento.php?pesquisa_chave='+$F('o56_codele')+
                          '&funcao_js=parent.js_mostraOrcelemento',
                          'Pesquisa de Despesas', false);
    } else {
      $('o56_descr').value = '';
    }
  }
}

/**
 * Função que mostra o retorno da função js_pesquisa_despesa no formulário
 */
function js_mostraOrcelemento(chave, erro) {

  $('o56_descr').value = chave;
  if (erro === true) {

    $('o56_codele').value = '';
    $('o56_codele').focus();
  }
}

/**
 * Função que mostra o retorno da função js_pesquisa_despesa no formulário
 */
function js_mostraOrcelemento1(chave1, chave2) {

  $('o56_codele').value = chave1;
  $('o56_descr').value  = chave2;
  db_iframe_orcelemento.hide();
}

/**
 * Função que salva o vínculo da despesa do TCE
 */
function js_salvarVinculo() {

  /**
   * Validamos o formulário
   */
  if ($F('codigodespesatce').trim() === "") {

    alert('O campo Despesa Sigfis é de preenchimento obrigatório');
    return false;
  }

  if ($F('o56_codele').trim() === "") {

    alert('O campo Elemento é de preenchimento obrigatório');
    return false;
  }

  /**
   * Submetemos os dados ao RPC
   */
  js_divCarregando('Aguarde, vinculando despesa', 'msgBox');
  
  var oParam            = new Object();
      oParam.exec       = 'vincularDespesa';
      oParam.despesatce = $F('codigodespesatce');
      oParam.despesa    = $F('o56_codele');

  var oAjax = new Ajax.Request('con4_vinculadespesasigfis.RPC.php', 
                               {method: 'POST',
                                parameters: 'json='+Object.toJSON(oParam),
                                onComplete: js_retornoSalvarVinculo});
}

/**
 * Função que tratao retorno da função js_salvarVinculo
 */
function js_retornoSalvarVinculo(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    alert('Vínculo realizado com sucesso.');
    $('o56_codele').value = '';
    $('o56_descr').value  = '';
  } else {

    alert(oRetorno.message.urlDecode());
    $('o56_codele').value = '';
    $('o56_descr').value  = '';
  }
}

/**
 * Função disparada ao se clicar no botão 'Visualizar Vínculos'
 */
function js_visualizarVinculos() {

	/**
	 * Configuramos a WindowAux
	 */
  var iLarguraWindowAux = 600;
  var iAlturaWindowAux  = 400;
  
  oWindowAuxVinculosDespesaTCE = new windowAux("mostraVinculosDespesaTCE", "Vínculos de Despesa TCE", 
                                               iLarguraWindowAux, iAlturaWindowAux);
  var sConteudoWindowAux  = '<div id = "ctnMsgBoardVinculosDespesaTCE"></div>';
      sConteudoWindowAux += '<fieldset style="margin-bottom:10px;">';
      sConteudoWindowAux += '  <legend><strong>Vínculos Realizados</strong></legend>';
      sConteudoWindowAux += '  <div id = "ctnGridVinculosDespesaTCE"></div>';
      sConteudoWindowAux += '</fieldset>';
      sConteudoWindowAux += '<center>';
      sConteudoWindowAux += '  <input type="button" name="btnExcluirVinculo" id="btnExcluirVinculo" value="Excluir"';
      sConteudoWindowAux += '   onclick="js_removerDespesasVinculadas();">';
      sConteudoWindowAux += '  <input type="button" name="btnFecharWindow" id="btnFecharWindow" value="Fechar"';
      sConteudoWindowAux += '   onclick="oWindowAuxVinculosDespesaTCE.destroy();">';
      sConteudoWindowAux += '</center>';
  oWindowAuxVinculosDespesaTCE.setContent(sConteudoWindowAux);
  oWindowAuxVinculosDespesaTCE.setShutDownFunction(function(){
    oWindowAuxVinculosDespesaTCE.destroy();
  });

  /**
   * Configuramos a MesageBoard exibida dentro da WindowAux
   */
  var sIdMsgBoard       = 'msgBoardVinculoDespesaTCE';
  var sTituloMsgBoard   = 'Vínculos já realizados';
  var sConteudoMsgBoard = 'Para excluir um vínculo realizado, selecione o vínculo e clique em Excluir.';
  var oWhereAddMsgBoard = $('ctnMsgBoardVinculosDespesaTCE');
  var oMsgBoardVinculoDespesaTCE = new DBMessageBoard(sIdMsgBoard, sTituloMsgBoard, 
                                                      sConteudoMsgBoard, oWhereAddMsgBoard);
  oWindowAuxVinculosDespesaTCE.show();

  /**
   * Configuramos a grid exibida dentro da WindowAux
   */
  oGridVinculoDespesaTCE = new DBGrid('ctnGridVinculosDespesaTCE');
  oGridVinculoDespesaTCE.nameInstance = 'oGridVinculoDespesaTCE';
  oGridVinculoDespesaTCE.setCheckbox(1);
  oGridVinculoDespesaTCE.setHeight(160);
  oGridVinculoDespesaTCE.setCellAlign(new Array('right', 'right', 'left'));
  oGridVinculoDespesaTCE.setCellWidth(new Array('25%', '25%', '50%	'));
  oGridVinculoDespesaTCE.setHeader(new Array('Despesa TCE', 'Despesa', 'Descrição'));
  oGridVinculoDespesaTCE.show($('ctnGridVinculosDespesaTCE'));
  js_getVinculosDespesasTCE();
}

/**
 * Punção que pesquisa os vínculos já realizados
 */
function js_getVinculosDespesasTCE() {

  js_divCarregando('Aguarde, buscando Despesas...', 'msgBox');
  var oParam = new Object();
      oParam.exec = 'getVinculos';
  var oAjax = new Ajax.Request('con4_vinculadespesasigfis.RPC.php',
                               {method: 'POST',
                                parameters: 'json='+Object.toJSON(oParam),
                                onComplete: js_retornoGetVinculosDespesasTCE});
}

/**
 * Preenche a grid com as despesas retornadas pela js_getVinculosDespesasTCE
 */
function js_retornoGetVinculosDespesasTCE(oAjax) {

  js_removeObj('msgBox');
  oGridVinculoDespesaTCE.clearAll(true);
  var oRetorno = eval('('+oAjax.responseText+")");
  oRetorno.despesavinculada.each(function(oDespesa, iSeq) {

    var aRow    = new Array();
        aRow[0] = oDespesa.codigotce;
        aRow[1] = oDespesa.codigoecidade;
        aRow[2] = oDespesa.descricao.urlDecode();
        oGridVinculoDespesaTCE.addRow(aRow);
  });
  oGridVinculoDespesaTCE.renderRows();
}

/**
 * Função que varre a grid procurando quais os vínculos que devem ser removidos
 */
function js_removerDespesasVinculadas() {

  /**
   * Verificamos se há vínculos selecionados para serem exclusos. Em caso positivo criamos
   * um array que armazena as linhas que devem ser exlusas.
   */
  var aDespesas = oGridVinculoDespesaTCE.getSelection('object');
  if (aDespesas.length == 0) {

    alert('Nenhum vínculo foi selecionado para ser excluído.');
    return false;
  }
  var aDespesasParaRemover = new Array();
  aDespesas.each(function(oDespesa, iSeq) {
    aDespesasParaRemover.push(oDespesa.aCells[0].getValue());
  });

  /**
   * Passada a verificação submetemos as informações ao RPC
   */
  js_divCarregando('Aguarde, removendo vínculos...', 'msgBox');

  var oParam           = new Object();
      oParam.exec      = 'removerVinculos';
      oParam.aDespesas = aDespesasParaRemover;
  var oAjax            = new Ajax.Request('con4_vinculadespesasigfis.RPC.php',
                                          {method: 'POST',
                                           parameters: 'json='+Object.toJSON(oParam),
                                           onComplete: js_retornoRemoverDespesasVinculadas});
}

function js_retornoRemoverDespesasVinculadas(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status == 1) {
  
    alert('Vínculo das despesas selecionadas removido com sucesso!');
    js_getVinculosDespesasTCE();
  } else {
    
    alert(oRetorno.message.urlDecode());
    return false;
  }
}
</script>