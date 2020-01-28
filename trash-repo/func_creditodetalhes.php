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

if ($oGet->sTipoHistorico == 'regracompensacao') {

  db_app::load('estilos.css');

  $oDaoAbatimentoRegraCompensacao = db_utils::getDao('abatimentoregracompensacao');

  $sWhere = "k156_abatimento = {$oGet->iCodigoCredito}";

  $sCampos  = "k154_descricao,                     ";
  $sCampos .= "k00_descr as dl_Tipo_Débito_Origem, ";
  $sCampos .= "k155_percmaxuso,                    ";
  $sCampos .= "k155_tempovalidade,                 ";
  $sCampos .= "k155_automatica,                    ";    
  $sCampos .= "k155_permitetransferencia           ";

  $sSql = $oDaoAbatimentoRegraCompensacao->sql_query(null, $sCampos, null, $sWhere);

  $rsDadosAbatimentoRegraCompensacao = $oDaoAbatimentoRegraCompensacao->sql_record($sSql);

  db_lovrot($sSql, 10);
  exit;

} elseif($oGet->sTipoHistorico == 'origem') {

  $sLabel = 'Origem do crédito';

} elseif($oGet->sTipoHistorico == 'destino') {

  $sLabel = 'Uso do crédito';
}
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
    <strong><?php echo $sLabel; ?></strong>
  </legend>
  <input type="hidden" id="iCodigoCredito" value="<?php echo $iCodigoCredito; ?>" />
  <input type="hidden" id="sTipoHistorico" value="<?php echo $oGet->sTipoHistorico; ?>" />
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
aHeader[0] = 'Código';
aHeader[1] = 'Origem';
aHeader[2] = 'Valor';
aHeader[3] = 'Tipo de debito';
aHeader[4] = 'Receita';

/**
 * Tamanho das colunas dos dados da credito
 */
aWidth[0] = '20%';  
aWidth[1] = '20%';
aWidth[2] = '20%';
aWidth[3] = '20%';  
aWidth[4] = '20%';  

/**
 * Alinhamento das colunas dos dados da credito
 */
aAlinhamentos[0] = 'center';
aAlinhamentos[1] = 'left';
aAlinhamentos[2] = 'right';
aAlinhamentos[3] = 'left';
aAlinhamentos[4] = 'left';

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
  
  oParametros.exec = $F('sTipoHistorico');  
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
    var aLinha = new Array();

    aLinha[0] = '<a onClick="js_detalheCredito('+ oCredito.iCodigo +');" href="#">' + oCredito.iCodigo+ '</a>';
    aLinha[1] = oCredito.sOrigem;
    aLinha[2] = oCredito.nValor;
    aLinha[3] = oCredito.sTipoDebito;
    aLinha[4] = oCredito.sReceita;

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