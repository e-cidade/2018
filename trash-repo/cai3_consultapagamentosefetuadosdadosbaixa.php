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
 
/**
 * DADOS DA BAIXA 
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

<fieldset style="padding-right:13px;" id="ctnDadosBaixa">
	<legend>		
		<strong>Dados da baixa</strong>
	</legend>
  <div id="ctnGridDadosBaixa"></div>
</fieldset>

<input type="hidden" id="iNumpre" value="<?php echo $oGet->iNumpre; ?>" />
<input type="hidden" id="iNumpar" value="<?php echo $oGet->iNumpar; ?>" />

<script type="text/javascript">

var sUrlRPC            = 'cai3_consultapagamentosefetuados.RPC.php';  
var aAlinhamentosBaixa = new Array();
var aHeaderBaixa       = new Array();
var aWidthBaixa        = new Array();

/**
 * Array com headers dos dados da baixa 
 */
aHeaderBaixa[0] = 'Arq. retorno';
aHeaderBaixa[1] = 'Banco';
aHeaderBaixa[2] = 'Agência';
aHeaderBaixa[3] = 'Conta';
aHeaderBaixa[4] = 'Dt efet pgto';
aHeaderBaixa[5] = 'Dt baixa';
aHeaderBaixa[6] = 'Dt proc';
aHeaderBaixa[7] = 'Vlr total pago';
aHeaderBaixa[8] = 'Obervações';
aHeaderBaixa[9] = 'Processo';

/**
 * Tamanho das colunas dos dados da baixa
 */
aWidthBaixa[0] = '13%';  
aWidthBaixa[1] = '4%';
aWidthBaixa[2] = '5%';
aWidthBaixa[3] = '7%';  
aWidthBaixa[4] = '7%';  
aWidthBaixa[5] = '8%';  
aWidthBaixa[6] = '8%';  
aWidthBaixa[7] = '8%';  
aWidthBaixa[8] = '35%';  
aWidthBaixa[9] = '5%';  

/**
 * Alinhamento das colunas dos dados da baixa
 */
aAlinhamentosBaixa[0] = 'left';
aAlinhamentosBaixa[1] = 'left';
aAlinhamentosBaixa[2] = 'left';
aAlinhamentosBaixa[3] = 'left';
aAlinhamentosBaixa[4] = 'center';
aAlinhamentosBaixa[5] = 'center';
aAlinhamentosBaixa[6] = 'center';
aAlinhamentosBaixa[7] = 'right';
aAlinhamentosBaixa[8] = 'left';
aAlinhamentosBaixa[9] = 'left';

/**
 * Monta html da grid dos dados da baixa
 */
oGridBaixa              = new DBGrid('datagridBaixa');
oGridBaixa.sName        = 'datagridBaixa';
oGridBaixa.nameInstance = 'oGridBaixa';
oGridBaixa.setCellWidth( aWidthBaixa );
oGridBaixa.setCellAlign( aAlinhamentosBaixa );
oGridBaixa.setHeader( aHeaderBaixa );

/**
 * Busca os dados do lançamento do RPC 
 * Cria função e já executa
 */
(function js_getDadosBaixa() {

  var oParametros     = new Object();
  var msgDiv          = "Buscando dados da baixa. \n Aguarde ...";

  oParametros.exec    = 'getDadosBaixa';  
  oParametros.iNumpre = $F('iNumpre');  
  oParametros.iNumpar = $F('iNumpar');
  
  js_divCarregando(msgDiv, 'msgBox');
   
  var oAjaxLancamentos  = new Ajax.Request(
    sUrlRPC,
    {
      method     : 'post',
      parameters :'json=' + Object.toJSON(oParametros),
      onComplete : js_retornoDadosBaixa
    }
  );   
})();

function js_retornoDadosBaixa(oAjax) {
  
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
   * Verifica se existe dados da baixa
   */
  if ( !oRetorno.aDadosBaixa ) {
    return false;
  }

	/**
	 * Verifica se o pagamen to foi feito pelo caixa  
	 */
	if (oRetorno.aDadosBaixa[0].tipo_pagamento == 'caixa') {

		var sMensagemPagamento  = 'Pagamento do debito(Numpre <strong>' + $F('iNumpre') + '</strong> e parcela <strong>' + $F('iNumpar') + '</strong> ) ';
		    sMensagemPagamento += 'efetuado no <strong>caixa ' + oRetorno.aDadosBaixa[0].caixa + '</strong> da prefeitura </strong>';

		oGridBaixa.show( $('ctnGridDadosBaixa') );
		oGridBaixa.clearAll(true);
		oGridBaixa.addRow( [sMensagemPagamento] );
		oGridBaixa.aRows[0].aCells[0].setUseColspan(true, 11);
		oGridBaixa.aRows[0].aCells[0].sStyle  = "text-align:center; padding:15px 0;";
		oGridBaixa.allowSelectColumns(false);
		oGridBaixa.renderRows();

		return;
	}

	/**
	 * Pagamento pelo banco  
	 */
	oGridBaixa.allowSelectColumns(true);
	oGridBaixa.show( $('ctnGridDadosBaixa') );
	oGridBaixa.clearAll(true);
	
  for ( var iLinha = 0; iLinha < oRetorno.aDadosBaixa.length; iLinha++ ) {

		var oDados = oRetorno.aDadosBaixa[iLinha];
		var aLinha = new Array();

		aLinha[0] = oDados.codret+" - "+oDados.arquivo.urlDecode();
		aLinha[1] = oDados.banco;          
		aLinha[2] = oDados.agencia;        
		aLinha[3] = oDados.conta;          
		aLinha[4] = js_formatar(oDados.data_efetivo_pagamento, 'd');   
		aLinha[5] = js_formatar(oDados.data_baixa, 'd');       
		aLinha[6] = js_formatar(oDados.data_processo, 'd');        
		aLinha[7] = oDados.valor_total_pago; 
		aLinha[8] = oDados.observacao_pagamento.urlDecode();     
		aLinha[9] = oDados.processo.urlDecode();       
		oGridBaixa.addRow(aLinha);
  }      
         
  oGridBaixa.renderRows();
	return true;
}        
</script>
         
</body>
</html>