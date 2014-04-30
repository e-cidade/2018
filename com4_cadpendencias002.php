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
 * Carregamos as libs necessárias
 */
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/verticalTab.widget.php");

/**
 * Intância da classe que tras as informações da validação do campos pc10_numero ($Ipc10_numero)
 */
$oDaoSolicita = db_utils::getDao('solicita');
$oDaoSolicita->rotulo->label();
$oDaoSolicitaPendencia = db_utils::getDao('solicitapendencia');
$oDaoSolicitaPendencia->rotulo->label();

/**
 * Carregamos no objeto $oGet o valor do $_GET e validamos se a propriedade pc10_numero foi setada.
 * Se o teste for negativo redirecionamos para uma página de erro informando que a solicitação da
 * pesquisa não foi passada
 */
$oGet = db_utils::postMemory($_GET, false);

if (!isset($oGet->pc10_numero) || trim($oGet->pc10_numero) == "") {

  $sMsgErro = urlencode("Nenhuma solicitação de compras foi informada.");
  db_redireciona('db_erros.php?fechar=true&db_erro='.$sMsgErro);
}

/**
 * Carregamos a DAO necessária, efetuamos a busca da solicitação e carregamos as 
 * informações no objeto $oSolicitacao. Caso a pesquisa não retorne nada, redirecionamos para
 * uma página de erro informando que a solicitação informada não existe
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

  $sMsgErro = urlencode("A solicitação informada não existe.");
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
		<legend><strong>Cadastro de Pendência: </strong></legend>
		<table>
  		<tr>
  			<td><strong>Solicitação: </strong></td>
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
  					<legend><strong>Descrição</strong></legend>
  					<?php db_textarea('pc91_pendencia', 8, 80, $Ipc91_pendencia, true, 'text', 1); ?>
  				</fieldset>
  			</td>
  		</tr>
		</table>
	</fieldset>
	<input type = "button" id = "btnAction" name = "btnIncluir" onclick = "js_incluirPendencia();" value = "Incluir">
	<fieldset style = "width:640px; margin-top:5px;">
		<legend><strong>Pendências Cadastradas</strong></legend>
		<div id = "gridContainer"></div>
	</fieldset>
</center>

</body>
</html>

<script type = "text/javascript">

/**
 * Definição das variáveis fixas que serão utilizadas pelos scripts
 */
var sUrl                       = 'com4_cadpendencias002.RPC.php';
var oGet                       = js_urlToObject (window.location.search)
var lCadastroprocessodecompras = oGet.cadastroprocessodecompras;

/**
 * Instância da grid que será utilizada para exibir as pendências cadastradas
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
    aHeader[0] = 'Código';
    aHeader[1] = 'Pendência';
    aHeader[2] = 'Data Inclusão';
    aHeader[3] = 'Usuário';
    aHeader[4] = 'Ação';
//dbGrid.setCellWidth();
dbGrid.setCellAlign(aAligns);
dbGrid.setHeader(aHeader);
dbGrid.show($('gridContainer'));

js_atualizaGrid();

/**
 * Função que busca as pendências cadastradas para a solicitação atual e chama a
 * função "js_populaGrid" para exibir os resultados na grid de pendências 
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
 * Função que popula a grid com as pendências cadastradas
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
 * Função que exibe os detalhes da pendência selecionada através de clique na grid
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
 * Função que exibe em uma windowaux o retorno da função js_exibeDetalhesPendencia 
 */
function js_exibePendenciaIndividualmente(oAjax) {

  js_removeObj('msgBox'); // ocultamos o gif de status da pesquisa

  var oRetorno = eval('('+oAjax.responseText+')');

  var sConteudoJanela              = '<div id="containerMessageBoard"></div>';
      sConteudoJanela             += '<fieldset><legend><strong>Pendência</strong></legend>';
      sConteudoJanela             += '<textarea id="ctnrTextArea" style="background-color:#DEB887;';
      sConteudoJanela             += 'width:665px; height: 200px;" readonly="readonly""></textarea>';
      sConteudoJanela             += '</fieldset>';
  var sConteudoMessageBoardJanela  = 'Pendência '+oRetorno.aDados[0].pc91_sequencial
                                     +' da Solicitação '+oRetorno.aDados[0].pc91_solicita;
  
  var oWindowAux = new windowAux('oWindowAux',
                                 'Pendência',
                                 700, 400);
      oWindowAux.setContent(sConteudoJanela);
      oWindowAux.setShutDownFunction(function() {
        oWindowAux.destroy();
      });
      oWindowAux.show();

  var oMessageBoard = new messageBoard('messageboard', 'Visualização de Pendência',
                                       sConteudoMessageBoardJanela, $('containerMessageBoard'));
      oMessageBoard.show();

  $('ctnrTextArea').value = oRetorno.aDados[0].pc91_pendencia;
}

/**
 * Função que manda o comando de inclusão de pendência ao RPC responsável
 */
function js_incluirPendencia() {

  js_divCarregando('Aguarde, cadastrando pendencia', 'msgBox'); // exibimos o gif de status da requisição
  
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
 * Função que avalia o retorno da função de inclusão de pendência. Ela analisa o resultado
 * e retorna ao usuário se houve sucesso ou não
 */
function js_retornoInclusaoPendencia(oAjax) {

  js_removeObj('msgBox'); // ocultamos o gif de status da requisição
  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status === 1) {
    
    alert('Pendência inclusa com sucesso.');
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
 * Função que efetua a exclusão da pendência.
 */
function js_excluiPendencia(iIdPendencia) {

  if (confirm('Deseja realmente excluir a pendência?')) {

    js_divCarregando('Aguarde, excluindo pendencia', 'msgBox'); // exibimos o gif de status da requisição
    
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
 * Função que avalia o retorno da requisição de exclusão de pendência.
 */
function js_retornoExcluiPendencia(oAjax) {

  js_removeObj('msgBox'); // ocultamos o gif de status da requisição
  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status === 1) {

    alert('Pendência excluída com sucesso.');
    js_atualizaGrid();
  } else {

    alert(oRetorno.message);
    return false;
  }
}

/**
 * Função que trás os dados para a alteração e altera o botão 'incluir' para 'alterar'
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

  js_removeObj('msgBox'); // ocultamos o gif de status da requisição
  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status === 1) {

		/**
		 * Alteramos o botão de ação do formulário e incluímos os valores para alteração no formulário
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

  js_removeObj('msgBox'); // ocultamos o gif de status da requisição
  var oRetorno = eval('('+oAjax.responseText+')');
  if (oRetorno.status === 1) {

    alert('Pendência alterada com sucesso.');
    $('btnAction').setAttribute("onclick","js_incluirPendencia();");
    $('btnAction').value = "Incluir";
    js_atualizaGrid();
  } else {

    alert(oRetorno.message);
    return false;
  }
}

</script>