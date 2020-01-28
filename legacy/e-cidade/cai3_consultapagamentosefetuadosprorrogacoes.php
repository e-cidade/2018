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
 * CONSULTA DE PRORROGAÇÕES DE VENCIMENTOS EFETUADOS 
 */

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_utils.php';
require_once 'libs/db_app.utils.php';
require_once 'dbforms/db_funcoes.php';

$oGet = db_utils::postMemory($_GET);

?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php db_app::load("estilos.css, grid.style.css, scripts.js, strings.js, prototype.js, datagrid.widget.js"); ?>
</head>
<body bgcolor="#cccccc" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<fieldset id="ctnProrrogacoes" style="padding-right:13px;">
	<legend>
		<strong> Prorrogações de Vencimentos Efetuadas:</strong>
	</legend>
	<div id="ctnGridProrrogacoes"></div>
</fieldset>

<input type="hidden" id="iNumpre" value="<?php echo $oGet->iNumpre; ?>" />
<input type="hidden" id="iNumpar" value="<?php echo $oGet->iNumpar; ?>" />

<script type="text/javascript">

var sUrlRPC                   = 'cai3_consultapagamentosefetuados.RPC.php';  
var aAlinhamentosProrrogacoes = new Array();
var aHeaderProrrogacoes       = new Array();
var aWidthProrrogacoes        = new Array();

/**
 * Array com headers dos prorrogações 
 */
aHeaderProrrogacoes[0] = 'Data inicial';
aHeaderProrrogacoes[1] = 'Data final';
aHeaderProrrogacoes[2] = 'Dias';

/**
 * Tamanho das colunas dos prorrogações
 */
aWidthProrrogacoes[0] = '33%';  
aWidthProrrogacoes[1] = '33%';  
aWidthProrrogacoes[2] = '33%';  

/**
 * Alinhamento das colunas dos prorrogações
 */
aAlinhamentosProrrogacoes[0] = 'left';
aAlinhamentosProrrogacoes[1] = 'left';
aAlinhamentosProrrogacoes[2] = 'left';

/**
 * Monta html da grid prorrogações
 */
oGridProrrogacoes              = new DBGrid('datagridProrrogacoes');
oGridProrrogacoes.sName        = 'datagridProrrogacoes';
oGridProrrogacoes.nameInstance = 'oGridProrrogacoes';
oGridProrrogacoes.setCellWidth( aWidthProrrogacoes );
oGridProrrogacoes.setCellAlign( aAlinhamentosProrrogacoes );
oGridProrrogacoes.setHeader( aHeaderProrrogacoes );
oGridProrrogacoes.allowSelectColumns(true);
oGridProrrogacoes.show( $('ctnGridProrrogacoes') );
oGridProrrogacoes.clearAll(true);

/**
 * Busca os dados das prorrogações no RPC 
 */
js_getProrrogacoes();

function js_getProrrogacoes() {

  var oParametros     = new Object();
  var msgDiv          = "Buscando prorrogações de vencimentos efetuados. \n Aguarde ...";

  oParametros.exec    = 'getProrrogacoes';  
  oParametros.iNumpre = $F('iNumpre');  
  oParametros.iNumpar = $F('iNumpar');
  
  js_divCarregando(msgDiv, 'msgBox');
   
  var oAjaxLancamentos  = new Ajax.Request(
    sUrlRPC,
    {
      method     : 'post',
      parameters :'json=' + Object.toJSON(oParametros),
      onComplete : js_retornoProrrogacoes
    }
  );   
}

function js_retornoProrrogacoes(oAjax) {

  js_removeObj('msgBox');
  var oRetorno  = eval("("+oAjax.responseText+")");

  var sMensagem = oRetorno.sMensagem.urlDecode().replace(/\\n/g,'\n'); 

  /**
   * Ocorreu erro no RPC  
   */
  if (oRetorno.iStatus > 1) {
    
    alert(sMensagem);
    return;
  } 

  /**
   * Verifica se existe prorrogações, caso não existir recolhe fieldset 
   */
  if ( !oRetorno.aProrrogacoes ) {
    return;
  }

  for ( var iLinha = 0; iLinha < oRetorno.aProrrogacoes.length; iLinha++ ) {

		var oDados = oRetorno.aProrrogacoes[iLinha];
		var aLinha = new Array();
		aLinha[0]  = js_formatar(oDados.k00_dtini, 'd');
		aLinha[1]  = oDados.k00_dtfim == '' ? 'Hoje' : js_formatar(oDados.k00_dtfim, 'd');
		aLinha[2]  = oDados.dia;

		oGridProrrogacoes.addRow(aLinha);
  }

  oGridProrrogacoes.renderRows();
}
	
</script>

</body>
</html>