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
 * LANÇAMENTOS EFETUADOS 
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

<fieldset style="padding-right:13px;" id="ctnLancamentos">
	<legend>		
		<strong>Lançamentos Efetuados</strong>
	</legend> 
  <div id="ctnGridLancamentos"></div>
</fieldset>

<input type="hidden" id="iNumpre" value="<?php echo $oGet->iNumpre; ?>" />
<input type="hidden" id="iNumpar" value="<?php echo $oGet->iNumpar; ?>" />

<script type="text/javascript">

var sUrlRPC									 = 'cai3_consultapagamentosefetuados.RPC.php';  
var aAlinhamentosLancamentos = new Array();
var aHeaderLancamentos       = new Array();
var aWidthLancamentos        = new Array();

/**
 * Array com headers dos lançamentos 
 */
aHeaderLancamentos[0] = 'Tipo lançamento';
aHeaderLancamentos[1] = 'Nota';
aHeaderLancamentos[2] = 'Série';
aHeaderLancamentos[3] = 'Data da nota';
aHeaderLancamentos[4] = 'Valor serviço';
aHeaderLancamentos[5] = 'Valor do imposto';
aHeaderLancamentos[6] = 'Imposto retido';
aHeaderLancamentos[7] = 'CNPJ';
aHeaderLancamentos[8] = 'Nome';
aHeaderLancamentos[9] = 'Status';

/**
 * Tamanho das colunas dos lançamentos
 */
aWidthLancamentos[0] = '10%';  
aWidthLancamentos[1] = '4%';  
aWidthLancamentos[2] = '3%';  
aWidthLancamentos[3] = '8%';  
aWidthLancamentos[4] = '10%';  
aWidthLancamentos[5] = '10%';  
aWidthLancamentos[6] = '10%';  
aWidthLancamentos[7] = '10%';  
aWidthLancamentos[8] = '28%';  
aWidthLancamentos[9] = '5%';  

/**
 * Alinhamento das colunas do lançamento
 */
aAlinhamentosLancamentos[0] = 'left';
aAlinhamentosLancamentos[1] = 'left';
aAlinhamentosLancamentos[2] = 'left';
aAlinhamentosLancamentos[3] = 'left';
aAlinhamentosLancamentos[4] = 'right';
aAlinhamentosLancamentos[5] = 'right';
aAlinhamentosLancamentos[6] = 'left';
aAlinhamentosLancamentos[7] = 'left';
aAlinhamentosLancamentos[8] = 'left';
aAlinhamentosLancamentos[9] = 'left';

/**
 * Monta html da grid lançamentos
 */
oGridLancamentos              = new DBGrid('datagridLancamentos');
oGridLancamentos.sName        = 'datagridLancamentos';
oGridLancamentos.nameInstance = 'oGridLancamentos';
oGridLancamentos.setCellWidth( aWidthLancamentos );
oGridLancamentos.setCellAlign( aAlinhamentosLancamentos );
oGridLancamentos.setHeader( aHeaderLancamentos );
oGridLancamentos.allowSelectColumns(true);
oGridLancamentos.show( $('ctnGridLancamentos') );
oGridLancamentos.clearAll(true);

/**
 * Busca os dados do lançamento do RPC 
 */
js_getLancamentosEfetuados();

function js_getLancamentosEfetuados() {

  var oParametros      = new Object();
  var msgDiv           = "Buscando lançamentos efetuados. \n Aguarde ...";

  oParametros.exec     = 'getLancamentosEfetuados';  
  oParametros.iNumpre  = $F('iNumpre');  
  oParametros.iNumpar  = $F('iNumpar');  
  
  js_divCarregando(msgDiv,'msgBox');
   
  var oAjaxLancamentos  = new Ajax.Request(
    sUrlRPC,
    {
      method     : 'post',
      parameters :'json='+Object.toJSON(oParametros),
      onComplete : js_retornoLancamentosEfeuados
    }
  );   
}

function js_retornoLancamentosEfeuados(oAjax) {
  
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
  if ( !oRetorno.aLancamentosEfetuados ) {
    return;
  }

  for ( var iLinha = 0; iLinha < oRetorno.aLancamentosEfetuados.length; iLinha++ ) {

		var oDados = oRetorno.aLancamentosEfetuados[iLinha];
		var aLinha = new Array();
		aLinha[0]  = oDados.dl_tipo_lancamento;
		aLinha[1]  = oDados.q21_nota;
		aLinha[2]  = oDados.q21_serie;
		aLinha[3]  = oDados.q21_datanota;
		aLinha[4]  = oDados.q21_valorser;
		aLinha[5]  = oDados.q21_valorimposto;
		aLinha[6]  = oDados.q21_retido == 't' ? 'Sim' : 'Não';
		aLinha[7]  = oDados.q21_cnpj;
		aLinha[8]  = oDados.q21_nome.urlDecode();
		aLinha[9]  = oDados.dl_status;

		oGridLancamentos.addRow(aLinha);
  }

  oGridLancamentos.renderRows();
}
</script>

</body>
</html>