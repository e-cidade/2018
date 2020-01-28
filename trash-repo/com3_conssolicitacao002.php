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
 * Carregamos no objeto $oGet o valor do $_GET e validamos se a propriedade pc10_numero foi setada.
 * Se o teste for negativo redirecionamos para uma p�gina de erro informando que a solicita��o da
 * pesquisa n�o foi passada
 */
$oGet = db_utils::postMemory($_GET, false);

if (!isset($oGet->pc10_numero) || trim($oGet->pc10_numero) == "") {
  
  $sMsgErro = urlencode("Pesquisa sem par�metros.");
  db_redireciona('db_erros.php?fechar=true&db_erro='.$sMsgErro);
}

/**
 * Carregamos a DAO necess�ria, efetuamos a busca da solicita��o e carregamos as 
 * informa��es no objeto $oSolicitacao. Caso a pesquisa n�o retorne nada, redirecionamos para
 * uma p�gina de erro informando que a solicita��o informada n�o existe
 */
$oDaoSolicita          = db_utils::getDao('solicita');
$sCamposBuscaSolicita  = "pc10_numero, pc10_depto, descrdepto, pc10_login, nome, pc10_data, pc10_instit, ";
$sCamposBuscaSolicita .= "nomeinst, pc10_solicitacaotipo, pc52_descricao, pc10_resumo, ";
$sCamposBuscaSolicita .= "case when  ";
$sCamposBuscaSolicita .= "     (select pc11_liberado from solicitem where pc11_numero = {$oGet->pc10_numero} limit 1) = 't'";
$sCamposBuscaSolicita .= "      then 'Liberado'";
$sCamposBuscaSolicita .= " else 'N�o Liberado' end as situacao";
$sWhereBuscaSolicita   = " pc10_numero = {$oGet->pc10_numero} ";

$sSqlBuscaSolicita     = $oDaoSolicita->sql_query_consulta(null, $sCamposBuscaSolicita, null, $sWhereBuscaSolicita);
$rsBuscaSolicita       = $oDaoSolicita->sql_record($sSqlBuscaSolicita);

if ($oDaoSolicita->numrows > 0) {
  $oSolicita = db_utils::fieldsMemory($rsBuscaSolicita, 0);
} else {
  
  $sMsgErro = urlencode("A solicita��o informada n�o existe.");
  db_redireciona('db_erros.php?fechar=true&db_erro='.$sMsgErro);
}

/**
 * Buscamos os dados do Processo Administrativo
 */
$oDaoProcessoAdministrativo   = db_utils::getDao("solicitaprotprocesso");
$sWhereProcessoAdministrativo = " pc90_solicita = {$oSolicita->pc10_numero}";
$sSqlProcessoAdministrativo   = $oDaoProcessoAdministrativo->sql_query_file(null, "pc90_numeroprocesso", null, $sWhereProcessoAdministrativo);
$rsProcessoAdministrativo     = $oDaoProcessoAdministrativo->sql_record($sSqlProcessoAdministrativo);
$sProcessoAdministrativo      = "";

if ($oDaoProcessoAdministrativo->numrows > 0) {
  $sProcessoAdministrativo = db_utils::fieldsMemory($rsProcessoAdministrativo, 0)->pc90_numeroprocesso;
}

?>
<html>
<head>
<title>Dados do Cadastro de Ve�culos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/messageboard.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
<style type='text/css'>
.valores {background-color:#FFFFFF}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

	<fieldset>
		<legend><strong>Dados da Solicita��o</strong></legend>
		<table>
			<tr>
				<td><strong>Solicita��o: </strong></td>
				<td class="valores" align="left" >
					<?php echo $oSolicita->pc10_numero; ?>
				</td>
        <td width="70" >
          <strong>Processo Administrativo (P.A.)</strong>
        </td>
        <td class="valores" align="right" width="44">
          <?php echo $sProcessoAdministrativo;?>
        </td>
				<td nowrap="nowrap"><strong>Departamento: </strong></td>
				<td class="valores" width="30" align="right">
					<?php echo $oSolicita->pc10_depto; ?>
				</td>
				
				<td class="valores" width="300" align="left">
					<?php echo $oSolicita->descrdepto; ?>
				</td>
			</tr>
			<tr>
				<td><strong>Usu�rio Solicitante: </strong></td>
				<td class="valores" width="30" align="right">
					<?php echo $oSolicita->pc10_login; ?>
				</td>
				<td class="valores" width="300" align="left" colspan="2">
					<?php echo $oSolicita->nome; ?>
				</td>
				<td><strong>Data Solicita��o: </strong></td>
				
				<td class="valores" colspan="2" align="left">
					<?php echo db_formatar($oSolicita->pc10_data, 'd'); ?>
				</td>
			</tr>
			<tr>
				<td><strong>Institui��o: </strong></td>
				<td class="valores" align="right">
					<?php echo $oSolicita->pc10_instit; ?>
				</td>
				<td class="valores" align="left" colspan="2">
					<?php echo $oSolicita->nomeinst; ?>
				</td>
				<td><strong>Tipo Solicita��o: </strong></td>
				<td class="valores" align="right">
					<?php echo $oSolicita->pc10_solicitacaotipo; ?>
				</td>
				<td class="valores" align="left">
					<?php echo $oSolicita->pc52_descricao; ?>
				</td>
			</tr>
			<tr>
				<td><strong>Resumo: </strong></td>
				<td class="valores" colspan="7" align="left" width="100">
					<?php echo $oSolicita->pc10_resumo; ?>
				</td>
			</tr>
			<tr>
				<td><b>Situa��o:</b></td>
				<td class="valores" colspan="7"><?=$oSolicita->situacao;?></td>
			</tr>
		</table>
	</fieldset>
	
	<?php 
	  /**
	   * Configuramos e exibimos as "abas verticais" (componente verticalTab)
	   */
	  $oVerticalTab = new verticalTab('detalhesSolicitacao', 350);
	  $sGetUrl      = "&numero={$oGet->pc10_numero}";
	  
	  $oVerticalTab->add('dadosItensDotacoes', 'Itens/Dota��es', 
	                     "com3_consultaitens001.php?solicitacao=1{$sGetUrl}");
	  
	  $oVerticalTab->add('dadosOrcamentosSolicitacoes', 'Or�amentos de Solicita��es', 
	                     "com3_consultaitens001.php?solicitacao=2{$sGetUrl}");
	  
	  $oVerticalTab->add('dadosProcessosCompras', 'Processos de Compras', 
	                     "com3_consultaitens001.php?solicitacao=3{$sGetUrl}");
	  
	  $oVerticalTab->add('dadosOrcamentosProcessos', 'Or�amentos de Processos', 
	                     "com3_consultaitens001.php?solicitacao=4{$sGetUrl}");
	  
	  $oVerticalTab->add('dadosLicitacao', 'Licita��o', 
	                     "com3_consultaitens001.php?solicitacao=7{$sGetUrl}");
	  
	  $oVerticalTab->add('dadosAutorizacoesEmpenho', 'Autoriza��es de Empenho', 
	                     "com3_consultaitens001.php?solicitacao=5{$sGetUrl}");
	  
    $oVerticalTab->add('dadosPendecias', 'Pend�ncias', 
                       "com3_consultapendencias001.php?pc10_numero={$oGet->pc10_numero}");
	  
	  $oVerticalTab->show();
	?>

</body>
</html>
<script>
var sUrl      = 'com4_cadpendencias002.RPC.php';

/**
* Fun��o que exibe os detalhes da pend�ncia selecionada atrav�s de clique na grid
*/
function js_exibeDetalhesPendencia(iIdPendencia) {

  //js_divCarregando('Aguarde, pesquisando pendencia', 'msgBox'); // exibimos o gif de status da pesquisa

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
</script>