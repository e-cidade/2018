<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
 * Carregamos no objeto $oGet o valor do $_GET e validamos se a propriedade pc10_numero foi setada.
 * Se o teste for negativo redirecionamos para uma página de erro informando que a solicitação da
 * pesquisa não foi passada
 */
$oGet = db_utils::postMemory($_GET, false);

if (!isset($oGet->pc10_numero) || trim($oGet->pc10_numero) == "") {

  $sMsgErro = urlencode("Pesquisa sem parâmetros.");
  db_redireciona('db_erros.php?fechar=true&db_erro='.$sMsgErro);
}

/**
 * Carregamos a DAO necessária, efetuamos a busca da solicitação e carregamos as
 * informações no objeto $oSolicitacao. Caso a pesquisa não retorne nada, redirecionamos para
 * uma página de erro informando que a solicitação informada não existe
 */
$oDaoSolicita          = new cl_solicita();
$sCamposBuscaSolicita  = "pc10_numero, pc10_depto, descrdepto, pc10_login, nome, pc10_data, pc10_instit, ";
$sCamposBuscaSolicita .= "nomeinst, pc10_solicitacaotipo, pc52_descricao, pc10_resumo, ";
$sCamposBuscaSolicita .= "case when  ";
$sCamposBuscaSolicita .= "     (select pc11_liberado from solicitem where pc11_numero = {$oGet->pc10_numero} limit 1) = 't'";
$sCamposBuscaSolicita .= "      then 'Liberado'";
$sCamposBuscaSolicita .= " else 'Não Liberado' end as situacao,pc67_sequencial,";
$sCamposBuscaSolicita .= "case when pc67_sequencial is null then 'Não' else 'Sim' end as anulada ";
$sWhereBuscaSolicita   = " pc10_numero = {$oGet->pc10_numero} ";

$sSqlBuscaSolicita     = $oDaoSolicita->sql_query_consulta(null, $sCamposBuscaSolicita, null, $sWhereBuscaSolicita);
$rsBuscaSolicita       = $oDaoSolicita->sql_record($sSqlBuscaSolicita);

if ($oDaoSolicita->numrows > 0) {

  $oSolicita = db_utils::fieldsMemory($rsBuscaSolicita, 0);

  if ($oSolicita->pc10_instit != db_getsession('DB_instit')) {

    db_msgbox("Solicitação {$oGet->pc10_numero} pertence a instituição: {$oSolicita->nomeinst}. Procedimento abortado.");
    echo "<script>parent.db_iframe_consulta_solicitacao.hide();</script>";
    exit;
  }

} else {

  $sMsgErro = urlencode("A solicitação informada não existe.");
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

$sTipoSolicitacao = $oSolicita->pc52_descricao;

/**
 * se a solicitação for tipo 6 - compilação de registro de preço
 * buscamos os dados para informar
 */
if ($oSolicita->pc10_solicitacaotipo == 5) {

  $oDaoSolicitaVinculo   = new cl_solicitavinculo();
  $sSqlDadoRegistroPreco = $oDaoSolicitaVinculo->sql_query_file(null, "pc53_solicitapai",
      null,
      "pc53_solicitafilho = {$oSolicita->pc10_numero}"
  );
  $rsDadosRegistroPreco = $oDaoSolicitaVinculo->sql_record($sSqlDadoRegistroPreco);
  if ($oDaoSolicitaVinculo->numrows > 0) {

    $oDadosSolicitaRegistroPreco = db_utils::fieldsMemory($rsDadosRegistroPreco, 0);
    $sTipoSolicitacao .= " - Nº {$oDadosSolicitaRegistroPreco->pc53_solicitapai} ";
  }
}

?>
<html>
<head>
<title>Dados do Cadastro de Veículos</title>
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
<body style="background-color: #CCCCCC;" >

	<fieldset>
		<legend><strong>Dados da Solicitação</strong></legend>
		<table>
			<tr>
				<td><strong>Solicitação: </strong></td>
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
				<td><strong>Usuário Solicitante: </strong></td>
				<td class="valores" width="30" align="right">
					<?php echo $oSolicita->pc10_login; ?>
				</td>
				<td class="valores" width="300" align="left" colspan="2">
					<?php echo $oSolicita->nome; ?>
				</td>
				<td><strong>Data Solicitação: </strong></td>

				<td class="valores" colspan="2" align="left">
					<?php echo db_formatar($oSolicita->pc10_data, 'd'); ?>
				</td>
			</tr>

			<tr>
				<td><strong>Instituição: </strong></td>
				<td class="valores" align="right">
					<?php echo $oSolicita->pc10_instit; ?>
				</td>
				<td class="valores" align="left" colspan="2">
					<?php echo $oSolicita->nomeinst; ?>
				</td>

				<td><strong>Tipo Solicitação: </strong></td>
				<td class="valores" align="right">
					<?php echo $oSolicita->pc10_solicitacaotipo; ?>
				</td>
				<td class="valores" align="left">
					<?php echo $sTipoSolicitacao; ?>
				</td>
			</tr>

			<tr>
				<td><strong>Resumo: </strong></td>
				<td class="valores" colspan="7" align="left" width="100">
					<?php echo $oSolicita->pc10_resumo; ?>
				</td>
			</tr>
			<tr>
				<td><b>Situação:</b></td>
				<td class="valores" colspan="3"><?=$oSolicita->situacao;?></td>
        <td><strong>Anulada: </strong></td>
        <td class="valores" colspan="3"><?php echo $oSolicita->anulada; ?></td>
      </tr>
		</table>
	</fieldset>

	<?php
	  /**
	   * Configuramos e exibimos as "abas verticais" (componente verticalTab)
	   */
	  $oVerticalTab = new verticalTab('detalhesSolicitacao', 500);
	  $sGetUrl      = "&numero={$oGet->pc10_numero}";

	  $oVerticalTab->add('dadosItensDotacoes', 'Itens/Dotações',
	                     "com3_consultaitens001.php?solicitacao=1{$sGetUrl}");

	  $oVerticalTab->add('dadosOrcamentosSolicitacoes', 'Orçamentos de Solicitações',
	                     "com3_consultaitens001.php?solicitacao=2{$sGetUrl}");

	  $oVerticalTab->add('dadosProcessosCompras', 'Processos de Compras',
	                     "com3_consultaitens001.php?solicitacao=3{$sGetUrl}");

	  $oVerticalTab->add('dadosOrcamentosProcessos', 'Orçamentos de Processos',
	                     "com3_consultaitens001.php?solicitacao=4{$sGetUrl}");

	  $oVerticalTab->add('dadosLicitacao', 'Licitação',
	                     "com3_consultaitens001.php?solicitacao=7{$sGetUrl}");

	  $oVerticalTab->add('dadosAutorizacoesEmpenho', 'Autorizações de Empenho',
	                     "com3_consultaitens001.php?solicitacao=5{$sGetUrl}");

    $oVerticalTab->add('dadosPendecias', 'Pendências',
                       "com3_consultapendencias001.php?pc10_numero={$oGet->pc10_numero}");

    if (!empty($oSolicita->pc67_sequencial)) {

      $oVerticalTab->add(
        'dadosAnulacao',
        'Dados da Anulação',
        "com3_consultanulacao001.php?pc10_numero={$oGet->pc10_numero}");
    }

	  $oVerticalTab->show();
	?>

</body>
</html>
<script>
var sUrl      = 'com4_cadpendencias002.RPC.php';

/**
* Função que exibe os detalhes da pendência selecionada através de clique na grid
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
 * Função que exibe em uma windowaux o retorno da função js_exibeDetalhesPendencia
 */
function js_exibePendenciaIndividualmente(oAjax) {


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
</script>