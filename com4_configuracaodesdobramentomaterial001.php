<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
db_app::load("scripts.js,
              prototype.js, 
              strings.js, 
              datagrid.widget.js, 
              dbmessageBoard.widget.js, 
              widgets/windowAux.widget.js,
              widgets/dbtextField.widget.js,
              widgets/dbtextFieldData.widget.js");

db_app::load("estilos.css,
              grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="" bgcolor="#cccccc">
  <?php 
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  
  <center>
  	<fieldset style="width:535px; margin-top:5px; margin-bottom:5px;">
  		<legend><strong>Configuração de elementos</strong></legend>
  		<table>
  			<tr>
  				<td>
  				  <?php 
  				    db_ancora('<strong>Elemento: </strong>', "js_pesquisaElemento()", 1);
  				  ?>
  				</td>
  				<td>
  					<?php 
  					  db_input('e135_desdobramento', 20, "", true, 'text', 3);
  					  db_input('o56_descr', 40, "", true, 'text', 3);
  					?>
  				</td>
  			</tr>
  		</table>  	
  	</fieldset>
  	<input type="button" id="btnIncluirElemento" value="Incluir" onclick="js_configurarElemento();">
  	<fieldset style="width:535px;">
  		<legend><strong>Elementos Configurados</strong></legend>
  		<div id="ctnGridElementos"></div>
  	</fieldset>
  </center>
  
</body>
</html>

<script>

var sUrlRPC = 'com4_configuracaodesdobramentomaterial001.RPC.php';

var oDbGridElementos                = new DBGrid('ctnGridElementos');
    oDbGridElementos.nameInstance   = 'oDbGridElementos';
    oDbGridElementos.hasTotalizador = false;
    oDbGridElementos.setHeight(150);
    oDbGridElementos.allowSelectColumns(false);
    oDbGridElementos.setCellWidth(new Array("19%", "11%", "55%", "15%"));
    oDbGridElementos.setHeader(new Array('Estrutural', 'Reduzido', 'Nome', 'Ação'));
    oDbGridElementos.setCellAlign(new Array('right', 'right', 'left', 'center'));
    oDbGridElementos.show($('ctnGridElementos'));

js_atualizaGrid();

/**
 * Função responsável por buscar os elementos que devem ser exibidos na grid do formulário
 */
function js_atualizaGrid() {

  js_divCarregando('Carregando elementos configurados', 'msgBox');

  var oParam = new Object();
      oParam.sExec = 'getElementosConfigurados';

  var oAjax = new Ajax.Request(sUrlRPC,
                               {method: 'post',
                                parameters: 'json='+Object.toJSON(oParam),
                                onComplete: js_retornoAtualizaGrid});

  $('e135_desdobramento').value = '';
  $('o56_descr').value          = '';
}

/**
 * Função que exibe os dados retornados da js_atualizaGrid na grid
 */
function js_retornoAtualizaGrid(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status == 1) {

    oDbGridElementos.clearAll(true);
    oRetorno.aDados.each(function(oDado, iInd) {

      var aRowElemento    = new Array();
          aRowElemento[0] = oDado.o56_elemento;
          aRowElemento[1] = oDado.c61_reduz;
          aRowElemento[2] = oDado.o56_descr;
          aRowElemento[3] = '<input type="button" value="Excluir" onclick="js_deletaElemento('+oDado.e135_sequencial+');"';
      oDbGridElementos.addRow(aRowElemento);
    });
    oDbGridElementos.renderRows();
  }
}

/**
 * Função que exibe a lookup da orcelemento
 */
function js_pesquisaElemento() {

  var sQuery       = '&chave_o56_elemento=3';
    
  var sUrlPesquisa = 'func_orcelemento_comreduzido.php?funcao_js=parent.js_retornoPesquisaElemento|o56_elemento|o56_descr';
  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_elementosdespesa',
                      sUrlPesquisa + sQuery,
                      'Busca de elementos de despesa',
                      true);
}

/**
 *  função que exibe o retonro da js_pesquisaElemento
 */
function js_retornoPesquisaElemento() {

  db_iframe_elementosdespesa.hide();
  $('e135_desdobramento').value = arguments[0];
  $('o56_descr').value          = arguments[1];
}

/**
 * Função que dispara o comando de inserção do elemento
 */
function js_configurarElemento() {

  if ($('e135_desdobramento').value.trim() == "") {

    alert('Deve ser informado um elemento para ser configurado.');
    return false;
  }

  js_divCarregando('Aguarde, configurando elemento...', 'msgBox');

  var oParam           = new Object();
      oParam.sExec     = 'configurarElemento';
      oParam.sElemento = $F('e135_desdobramento');

  var oAjax = new Ajax.Request(sUrlRPC,
                               {method: 'post',
                                parameters: 'json='+Object.toJSON(oParam),
                                onComplete: js_retornoConfigurarElemento});
}

/**
 * Função que trata o retorno da inclusão do elemento
 */
function js_retornoConfigurarElemento(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status == 1) {

    alert ('Elemento configurado com sucesso.');
    js_atualizaGrid();
  } else {

    alert (oRetorno.message.urlDecode());
    return false;
  }
}

/**
 * Função que deleta o registro de elemento
 */
function js_deletaElemento(iSequencial) {

  if (confirm('Realmente deseja deletar o registro?')) {

    var oParam             = new Object();
        oParam.sExec       = 'deletarConfiguracaoElemento';
        oParam.iSequencial = iSequencial;

    var oAjax = new Ajax.Request(sUrlRPC,
                                 {method: 'post',
                                  parameters: 'json='+Object.toJSON(oParam),
                                  onComplete: js_retornoDeletaElemento});
  } else {
    return false;
  }
}

/**
 * Função que retorna o resultado da deleção do registro
 */
function js_retornoDeletaElemento(oAjax) {

   var oRetorno = eval('('+oAjax.responseText+')');
   if (oRetorno.status == 1) {

     alert ('Elemento excluso com sucesso.');
     js_atualizaGrid();
   } else {

     alert (oRetorno.message.urlDecode());
     js_atualizaGrid();
     return false;
   }
}
</script>