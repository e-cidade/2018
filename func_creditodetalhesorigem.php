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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

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
    <strong>Origem do crédito</strong>
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
aHeader[0] = 'CGM';
aHeader[1] = 'Código';
aHeader[2] = 'Origem';
aHeader[3] = 'Valor';
aHeader[4] = 'Tipo de debito';
aHeader[5] = 'Receita';

/**
 * Tamanho das colunas dos dados da credito
 */
aWidth[0] = '16.66%';  
aWidth[1] = '16.66%';
aWidth[2] = '16.66%';
aWidth[3] = '16.66%';  
aWidth[4] = '16.66%';  
aWidth[5] = '16.66%';  

/**
 * Alinhamento das colunas dos dados da credito
 */
aAlinhamentos[0] = 'left';
aAlinhamentos[1] = 'left';
aAlinhamentos[2] = 'left';
aAlinhamentos[3] = 'right';
aAlinhamentos[4] = 'left';
aAlinhamentos[5] = 'left';

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
  
  oParametros.exec = 'origem'
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
   * Nao encontrou origem 
   */
  if ( oRetorno.oCredito == undefined ) {
    return;
  }

  /**
   * Limpa grid 
   */
  oGridCredito.clearAll(true);

  var oCredito = oRetorno.oCredito;
  var aLinha = new Array();

  aLinha[0] = oCredito.sCgm;
  aLinha[1] = '<a onClick="js_detalheCredito('+ oCredito.iCodigo +');" href="#">' + oCredito.iCodigo+ '</a>';
  aLinha[2] = oCredito.sOrigem;
  aLinha[3] = oCredito.nValor;
  aLinha[4] = oCredito.sTipoDebito;
  aLinha[5] = oCredito.sReceita;

  oGridCredito.addRow(aLinha);

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