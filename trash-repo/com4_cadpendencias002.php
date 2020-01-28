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

/**
 * Carregamos as libs necess�rias
 */
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/verticalTab.widget.php");

/**
 * Int�ncia da classe que tras as informa��es da valida��o do campos pc10_numero ($Ipc10_numero)
 */
$oDaoSolicita = db_utils::getDao('solicita');
$oDaoSolicita->rotulo->label();
$oDaoSolicitaPendencia = db_utils::getDao('solicitapendencia');
$oDaoSolicitaPendencia->rotulo->label();

/**
 * Carregamos no objeto $oGet o valor do $_GET e validamos se a propriedade pc10_numero foi setada.
 * Se o teste for negativo redirecionamos para uma p�gina de erro informando que a solicita��o da
 * pesquisa n�o foi passada
 */
$oGet = db_utils::postMemory($_GET, false);

if (!isset($oGet->pc10_numero) || trim($oGet->pc10_numero) == "") {

  $sMsgErro = urlencode("Nenhuma solicita��o de compras foi informada.");
  db_redireciona('db_erros.php?fechar=true&db_erro='.$sMsgErro);
}

/**
 * Carregamos a DAO necess�ria, efetuamos a busca da solicita��o e carregamos as 
 * informa��es no objeto $oSolicitacao. Caso a pesquisa n�o retorne nada, redirecionamos para
 * uma p�gina de erro informando que a solicita��o informada n�o existe
 */
$oDaoSolicita          = db_utils::getDao('solicita');
$sCamposBuscaSolicita  = "pc10_numero, pc10_depto, descrdepto, pc10_login, nome, pc10_data, pc10_instit, ";
$sCamposBuscaSolicita .= "nomeinst, pc10_solicitacaotipo, pc52_descricao, pc10_resumo ";
$sWhereBuscaSolicita   = " pc10_numero = {$oGet->pc10_numero} ";
$sSqlBuscaSolicita     = $oDaoSolicita->sql_query_consulta(null, $sCamposBuscaSolicita, null, $sWhereBuscaSolicita);
$rsBuscaSolicita       = $oDaoSolicita->sql_record($sSqlBuscaSolicita);

if ($oDaoSolicita->numrows > 0) {
  $oSolicita = db_utils::fieldsMemory($rsBuscaSolicita, 0);
} else {

  $sMsgErro = urlencode("A solicita��o informada n�o existe.");
  db_redireciona('db_erros.php?fechar=true&db_erro='.$sMsgErro);
}
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/messageboard.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc" onload="">

<center>
	<fieldset style = "width:640px; margin-top:15px; margin-bottom:10px;">
		<legend><strong>Cadastro de Pend�ncia: </strong></legend>
		<table>
  		<tr>
  			<td><strong>Solicita��o: </strong></td>
  			<td>
  				<?php 
  				  $pc10_numero = $oGet->pc10_numero;
  				  db_input('pc10_numero', 8, $Ipc10_numero, true, "text", 3);
  				?>
  			</td>
  			<td><strong>Data: </strong></td>
  			<td>
  				<?php 
  				  $aData = explode('/', date('d/m/Y', db_getsession('DB_datausu')));
  				  db_inputdata('pc91_datainclusao', $aData[0], $aData[1], $aData[2]);
  				?>
  			</td>
  		</tr>
  		<tr>
  			<td colspan = "4">
  				<fieldset>
  					<legend><strong>Descri��o</strong></legend>
  					<?php db_textarea('pc91_pendencia', 8, 80, $Ipc91_pendencia, true, 'text', 1); ?>
  				</fieldset>
  			</td>
  		</tr>
		</table>
	</fieldset>
	<input type = "button" id = "btnAction" name = "btnIncluir" onclick = "js_incluirPendencia();" value = "Incluir">
	<fieldset style = "width:640px; margin-top:5px;">
		<legend><strong>Pend�ncias Cadastradas</strong></legend>
		<div id = "gridContainer"></div>
	</fieldset>
</center>

</body>
</html>

<script type = "text/javascript">

/**
 * Defini��o das vari�veis fixas que ser�o utilizadas pelos scripts
 */
var sUrl                       = 'com4_cadpendencias002.RPC.php';
var oGet                       = js_urlToObject (window.location.search)
var lCadastroprocessodecompras = oGet.cadastroprocessodecompras;

/**
 * Inst�ncia da grid que ser� utilizada para exibir as pend�ncias cadastradas
 */
var dbGrid                = new DBGrid('gridContainer');
    dbGrid.nameInstance   = 'dbGrid';
    dbGrid.hasTotalizador = false;
    dbGrid.setHeight(150);
    dbGrid.allowSelectColumns(false);
var aAligns    = new Array();
    aAligns[0] = 'right';
    aAligns[1] = 'left';
    aAligns[2] = 'center';
    aAligns[3] = 'left';
    aAligns[4] = 'center';
var aHeader    = new Array();
    aHeader[0] = 'C�digo';
    aHeader[1] = 'Pend�ncia';
    aHeader[2] = 'Data Inclus�o';
    aHeader[3] = 'Usu�rio';
    aHeader[4] = 'A��o';
//dbGrid.setCellWidth();
dbGrid.setCellAlign(aAligns);
dbGrid.setHeader(aHeader);
dbGrid.show($('gridContainer'));

js_atualizaGrid();

/**
 * Fun��o que busca as pend�ncias cadastradas para a solicita��o atual e chama a
 * fun��o "js_populaGrid" para exibir os resultados na grid de pend�ncias 
 */
function js_atualizaGrid() {

  js_divCarregando('Aguarde, pesquisando pendencias', 'msgBox'); // exibimos o gif de status da pesquisa
  $('pc91_pendencia').value = '';
  
  var oParam              = new Object();
      oParam.sExec        = 'getPendenciasSolicitacao';
      oParam.iSolicitacao = $F('pc10_numero');
      oParam.sPendencia   = $F('pc91_pendencia');

  var oAjax = new Ajax.Request(sUrl,
                               {method:'post',
                                parameters:'json='+Object.toJSON(oParam),
                                onComplete:js_populaGrid
                               }
                              );
}

/**
 * Fun��o que popula a grid com as pend�ncias cadastradas
 */
function js_populaGrid(oAjax) {

  js_removeObj('msgBox'); // ocultamos o gif de status da pesquisa
  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status === 1 && oRetorno.aDados !== null) {

  	dbGrid.clearAll(true);

    oRetorno.aDados.each(function (oDado, iInd) {

      var aRowPendencia     = new Array();
          aRowPendencia[0]  = oDado.pc91_sequencial;
          aRowPendencia[1]  = oDado.pc91_pendencia;
          aRowPendencia[2]  = oDado.pc91_datainclusao;
          aRowPendencia[3]  = oDado.nome;
          aRowPendencia[4]  = '<input type="button" value="A" onclick="js_alteraPendencia('+oDado.pc91_sequencial+');">';
          aRowPendencia[4] += '<input type="button" value="E" onclick="js_excluiPendencia('+oDado.pc91_sequencial+');">';
  		dbGrid.addRow(aRowPendencia);

  		dbGrid.aRows[iInd].sEvents = 'ondblclick="js_exibeDetalhesPendencia('+oDado.pc91_sequencial+');"';
    });
    
  	dbGrid.renderRows();
  }
}

/**
 * Fun��o que exibe os detalhes da pend�ncia selecionada atrav�s de clique na grid
 */
function js_exibeDetalhesPendencia(iIdPendencia) {

  js_divCarregando('Aguarde, pesquisando pendencia', 'msgBox'); // exibimos o gif de status da pesquisa

  var oParam = new Object;
      oParam.sExec        = 'buscaPendenciaUnica';
      oParam.iIdPendencia = iIdPendencia;

  var oAjax = new Ajax.Request(sUrl,
                               {method:'post',
                                parameters:'json='+Object.toJSON(oParam),
                                onComplete:js_exibePendenciaIndividualmente
                               }
                              );
}

/**
 * Fun��o que exibe em uma windowaux o retorno da fun��o js_exibeDetalhesPendencia 
 */
function js_exibePendenciaIndividualmente(oAjax) {

  js_removeObj('msgBox'); // ocultamos o gif de status da pesquisa

  var oRetorno = eval('('+oAjax.responseText+')');

  var sConteudoJanela              = '<div id="containerMessageBoard"></div>';
      sConteudoJanela             += '<fieldset><legend><strong>Pend�ncia</strong></legend>';
      sConteudoJanela             += '<textarea id="ctnrTextArea" style="background-color:#DEB887;';
      sConteudoJanela             += 'width:665px; height: 200px;" readonly="readonly""></textarea>';
      sConteudoJanela             += '</fieldset>';
  var sConteudoMessageBoardJanela  = 'Pend�ncia '+oRetorno.aDados[0].pc91_sequencial
                                     +' da Solicita��o '+oRetorno.aDados[0].pc91_solicita;
  
  var oWindowAux = new windowAux('oWindowAux',
                                 'Pend�ncia',
                                 700, 400);
      oWindowAux.setContent(sConteudoJanela);
      oWindowAux.setShutDownFunction(function() {
        oWindowAux.destroy();
      });
      oWindowAux.show();

  var oMessageBoard = new messageBoard('messageboard', 'Visualiza��o de Pend�ncia',
                                       sConteudoMessageBoardJanela, $('containerMessageBoard'));
      oMessageBoard.show();

  $('ctnrTextArea').value = oRetorno.aDados[0].pc91_pendencia;
}

/**
 * Fun��o que manda o comando de inclus�o de pend�ncia ao RPC respons�vel
 */
function js_incluirPendencia() {

  js_divCarregando('Aguarde, cadastrando pendencia', 'msgBox'); // exibimos o gif de status da requisi��o
  
  var oParam               = new Object();
      oParam.sExec         = 'incluirPendencia';
      oParam.iSolicitacao  = $F('pc10_numero');
      oParam.sDataInclusao = $F('pc91_datainclusao');
      oParam.sPendencia    = $F('pc91_pendencia');

  var oAjax = new Ajax.Request(sUrl,
                               {method:'post',
                                parameters:'json='+Object.toJSON(oParam),
                                onComplete:js_retornoInclusaoPendencia
                               }
                              );
}

/**
 * Fun��o que avalia o retorno da fun��o de inclus�o de pend�ncia. Ela analisa o resultado
 * e retorna ao usu�rio se houve sucesso ou n�o
 */
function js_retornoInclusaoPendencia(oAjax) {

  js_removeObj('msgBox'); // ocultamos o gif de status da requisi��o
  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status === 1) {
    
    alert('Pend�ncia inclusa com sucesso.');
    js_atualizaGrid();
    if (lCadastroprocessodecompras == 'true') {

      parent.location.href = 'com1_pcproc001.php';
      parent.db_iframe_cadpendencia.hide()
    }
  } else {

    alert(oRetorno.message);
    return false;
  }
}

/**
 * Fun��o que efetua a exclus�o da pend�ncia.
 */
function js_excluiPendencia(iIdPendencia) {

  if (confirm('Deseja realmente excluir a pend�ncia?')) {

    js_divCarregando('Aguarde, excluindo pendencia', 'msgBox'); // exibimos o gif de status da requisi��o
    
    var oParam              = new Object();
        oParam.sExec        = 'excluirPendencia';
        oParam.iIdPendencia = iIdPendencia;
    var oAjax = new Ajax.Request(sUrl,
                             {method:'post',
                              parameters:'json='+Object.toJSON(oParam),
                              onComplete: js_retornoExcluiPendencia
                             }
                            );
  } else {
  	return false;
  }
}


/**
 * Fun��o que avalia o retorno da requisi��o de exclus�o de pend�ncia.
 */
function js_retornoExcluiPendencia(oAjax) {

  js_removeObj('msgBox'); // ocultamos o gif de status da requisi��o
  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status === 1) {

    alert('Pend�ncia exclu�da com sucesso.');
    js_atualizaGrid();
  } else {

    alert(oRetorno.message);
    return false;
  }
}

/**
 * Fun��o que tr�s os dados para a altera��o e altera o bot�o 'incluir' para 'alterar'
 */
function js_alteraPendencia(iIdPendencia) {

  js_divCarregando('Aguarde, pesquisando pendencia', 'msgBox'); // exibimos o gif de status da pesquisa
  var oParam = new Object;
      oParam.sExec        = 'buscaPendenciaUnica';
      oParam.iIdPendencia = iIdPendencia;

  var oAjax = new Ajax.Request(sUrl,
                               {method:'post',
                                parameters:'json='+Object.toJSON(oParam),
                                onComplete:js_retornoAlteraPendencia
                               }
                              );
}

function js_retornoAlteraPendencia(oAjax) {

  js_removeObj('msgBox'); // ocultamos o gif de status da requisi��o
  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status === 1) {

		/**
		 * Alteramos o bot�o de a��o do formul�rio e inclu�mos os valores para altera��o no formul�rio
		 */
    $('btnAction').setAttribute("onclick","js_salvarAlteracaoPendencia("+oRetorno.aDados[0].pc91_sequencial+");");
    $('btnAction').value         = "Alterar";
    $('pc10_numero').value       = oRetorno.aDados[0].pc91_solicita;
    $('pc91_datainclusao').value = oRetorno.aDados[0].pc91_datainclusao;
    $('pc91_pendencia').value    = oRetorno.aDados[0].pc91_pendencia;
  }
}

function js_salvarAlteracaoPendencia(iIdPendencia) {

  js_divCarregando('Aguarde, pesquisando pendencia', 'msgBox'); // exibimos o gif de status da pesquisa
  var oParam                  = new Object();
      oParam.sExec            = 'alterarPendencia';
      oParam.iCodigoPendencia = iIdPendencia;
      oParam.sPendencia       = $F('pc91_pendencia');

  var oAjax = new Ajax.Request(sUrl,
                               {method:'post',
                                parameters:'json='+Object.toJSON(oParam),
                                onComplete:js_retornoSalvarAlteracaoPendencia
                               }
                              );
}

function js_retornoSalvarAlteracaoPendencia(oAjax) {

  js_removeObj('msgBox'); // ocultamos o gif de status da requisi��o
  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status === 1) {

    alert('Pend�ncia alterada com sucesso.');
    $('btnAction').setAttribute("onclick","js_incluirPendencia();");
    $('btnAction').value = "Incluir";
    js_atualizaGrid();
  } else {

    alert(oRetorno.message);
    return false;
  }
}

</script>