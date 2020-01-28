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
 * Históricos
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

<fieldset style="padding-right:13px;" id="ctnHistoricos">
	<legend>
		<strong>Históricos</strong>
	</legend>
  <div id="ctnGridHistoricos"></div>
</fieldset>

<input type="hidden" id="iNumpre" value="<?php echo $oGet->iNumpre; ?>" />
<input type="hidden" id="iNumpar" value="<?php echo $oGet->iNumpar; ?>" />

<script type="text/javascript">

var sUrlRPC                 = 'cai3_consultapagamentosefetuados.RPC.php';  
var aAlinhamentosHistoricos = new Array();
var aHeaderHistoricos       = new Array();
var aWidthHistoricos        = new Array();

/**
 * Array com headers dos históricos 
 */
aHeaderHistoricos[0] = 'Histórico';
aHeaderHistoricos[1] = 'Data lançamento';
aHeaderHistoricos[2] = 'Hora';
aHeaderHistoricos[3] = 'Usuário';
aHeaderHistoricos[4] = 'Histórico txt';

/**
 * Tamanho das colunas dos históricos
 */
aWidthHistoricos[0] = '16%';  
aWidthHistoricos[1] = '7%';  
aWidthHistoricos[2] = '7%';  
aWidthHistoricos[3] = '25%';  
aWidthHistoricos[4] = '45%';  

/**
 * Alinhamento das colunas dos históricos
 */
aAlinhamentosHistoricos[0] = 'left';
aAlinhamentosHistoricos[1] = 'left';
aAlinhamentosHistoricos[2] = 'left';
aAlinhamentosHistoricos[3] = 'left';
aAlinhamentosHistoricos[4] = 'left';

/**
 * Monta html da grid históricos
 */
oGridHistoricos              = new DBGrid('datagridHistoricos');
oGridHistoricos.sName        = 'datagridHistoricos';
oGridHistoricos.nameInstance = 'oGridHistoricos';
oGridHistoricos.setCellWidth( aWidthHistoricos );
oGridHistoricos.setCellAlign( aAlinhamentosHistoricos );
oGridHistoricos.setHeader( aHeaderHistoricos );
oGridHistoricos.allowSelectColumns(true);
oGridHistoricos.show( $('ctnGridHistoricos') );
oGridHistoricos.clearAll(true);

/**
 * Busca os dados do lançamento do RPC 
 */
js_getHistoricos();

function js_getHistoricos() {

  var oParametros     = new Object();
  var msgDiv          = "Buscando históricos. \n Aguarde ...";

  oParametros.exec    = 'getHistoricos';  
  oParametros.iNumpre = $F('iNumpre');  
  oParametros.iNumpar = $F('iNumpar');
  
  js_divCarregando(msgDiv, 'msgBox');
   
  var oAjaxLancamentos  = new Ajax.Request(
    sUrlRPC,
    {
      method     : 'post',
      parameters :'json=' + Object.toJSON(oParametros),
      onComplete : js_retornoHistoricos
    }
  );   
}

function js_retornoHistoricos(oAjax) {
  
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
   * Verifica se existe históricos, caso não existir recolhe fieldset 
   */
  if ( !oRetorno.aHistoricos ) {
    return;
  }

  for ( var iLinha = 0; iLinha < oRetorno.aHistoricos.length; iLinha++ ) {

		var oDados = oRetorno.aHistoricos[iLinha];
		var aLinha = new Array();
		aLinha[0]  = oDados.k01_descr;
		aLinha[1]  = js_formatar(oDados.k00_dtoper, 'd');
		aLinha[2]  = oDados.k00_hora;
		aLinha[3]  = oDados.nome;
		aLinha[4]  = oDados.k00_histtxt.urlDecode();

		oGridHistoricos.addRow(aLinha);
  }

  oGridHistoricos.renderRows();
}
</script>

</body>
</html>