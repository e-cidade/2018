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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet = db_utils::postMemory($_GET);
$iCodigoCredito = $oGet->iCodigoCredito;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php db_app::load("estilos.css, grid.style.css, scripts.js, strings.js, prototype.js, datagrid.widget.js"); ?>
<style type="text/css">
  #gridgridCredito td {
    padding:0 5px;
  }
</style>
</head>
<body bgcolor="#cccccc" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<fieldset style="padding-right:13px;margin:10px;" id="ctnDadosCredito">
  <legend>    
    <strong>Uso do crédito</strong>
  </legend>
  <input type="hidden" id="iCodigoCredito" value="<?php echo $iCodigoCredito; ?>" />
  <div id="ctnGridCredito"></div>
</fieldset>

<script type="text/javascript">

var sUrlRPC       = 'cai2_consultacreditos.RPC.php';  
var aAlinhamentos = new Array();
var aHeader       = new Array();
var aWidth        = new Array();

/**
 * Array com headers dos dados da credito
 */
aHeader[0] = 'Numpre';
aHeader[1] = 'Parcela';
aHeader[2] = 'Destino';
aHeader[3] = 'Tipo';
aHeader[4] = 'Rec. Crédito';
aHeader[5] = 'Valor';
aHeader[6] = 'Data';

/**
 * Tamanho das colunas dos dados da credito
 */
aWidth[0] = '10%';
aWidth[1] = '5%';
aWidth[2] = '25%';
aWidth[3] = '25%';
aWidth[4] = '10%';
aWidth[5] = '10%';
aWidth[6] = '10%';

/**
 * Alinhamento das colunas dos dados da credito
 */
aAlinhamentos[0] = 'center';
aAlinhamentos[1] = 'center';
aAlinhamentos[2] = 'center';
aAlinhamentos[3] = 'center';
aAlinhamentos[4] = 'center';
aAlinhamentos[5] = 'right';
aAlinhamentos[6] = 'right';

/**
 * Monta html da grid dos dados do credito
 */
oGridCredito = new DBGrid('gridCredito');
oGridCredito.sName = 'gridCredito';
oGridCredito.nameInstance = 'oGridCredito';
oGridCredito.setCellWidth( aWidth);
oGridCredito.setCellAlign( aAlinhamentos);
oGridCredito.setHeader( aHeader);
oGridCredito.show( $('ctnGridCredito') );

js_getDadosCredito();

function js_getDadosCredito() {

  js_divCarregando("Buscando dados do crédito.\nAguarde ...", 'msgBox');
  var oParametros  = new Object();
  
  oParametros.exec = 'destino';  
  oParametros.iCodigoCredito = $F('iCodigoCredito');
   
  var oAjax = new Ajax.Request(
    sUrlRPC, 
    {
      method     : 'post',
      parameters : 'json=' + Object.toJSON(oParametros),
      onComplete : js_retornoDadosCredito
    }
  );   
}

function js_retornoDadosCredito(oAjax) {

  js_removeObj('msgBox');

  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode();

  /**
   * Erro 
   */   
  if (oRetorno.iStatus > 1) {

    alert(sMensagem);
    return false;
  }

  /**
   * Nao encontrou destino 
   */
  if ( oRetorno.aCreditos == undefined ) {
    return;
  }

  /**
   * Total de creditos encontrados 
   */
  var iTotalCreditos = oRetorno.aCreditos.length;

  /**
   * Limpa grid 
   */
  oGridCredito.clearAll(true);

  /**
   * Percorre o array de creditos e preenche grid
   */
  for ( iIndice = 0; iIndice < iTotalCreditos; iIndice++ ) {
  
    var oCredito = oRetorno.aCreditos[iIndice];
    var aLinha = [];
    aLinha[0] = oCredito.iNumpre;
    aLinha[1] = oCredito.iNumpar;
    aLinha[2] = oCredito.sDestino.urlDecode();
    aLinha[3] = oCredito.sTipo.urlDecode();
    aLinha[4] = oCredito.sReceita;
    aLinha[5] = oCredito.nValor;
    aLinha[6] = oCredito.sData;

    oGridCredito.addRow(aLinha);
  }

  /**
   * Rendereriza grid 
   */
  oGridCredito.renderRows(); 
}

/**
 * Redireciona iframe pai e informa o credito a ser pesquisado
 * 
 * @param integer $iCredito 
 * @access public
 * @return void
 */
function js_detalheCredito(iCredito) {

  js_divCarregando("Buscando dados do crédito.\nAguarde ...", 'msgBox');
  parent.location = 'func_origemabatimento.php?iAbatimento=' + iCredito;
  js_removeObj('msgBox');
}

</script>
         
</body>
</html>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
