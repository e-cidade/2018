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

 
require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';
require_once 'libs/db_utils.php';
require_once 'libs/db_app.utils.php';
require_once 'std/DBNumber.php';
require_once 'dbforms/db_funcoes.php';
require_once 'libs/exceptions/ParameterException.php';
require_once 'libs/exceptions/BusinessException.php';

$oGet    = db_utils::postMemory($_GET);
$oPost   = db_utils::postMemory($_POST);
$oRotulo = new rotulocampo;
$oRotulo->label("k00_numpre");

$k00_numpre = null;

if ( !empty($oPost->k00_numpre) ) {

  try {
    $k00_numpre = $oPost->k00_numpre;
    $iNumpre    = $oPost->k00_numpre;
    
    if ( !DBNumber::isInteger($iNumpre) ) {
      throw new ParameterException("Numpre não é válido");
    }
    
    if ( !Desconto::validarProcessamento($iNumpre) ) {
    //  throw new BusinessException(" Débito com recibos válidos emitidos, desconto não pode ser cancelado.");
    }

  } catch(Exception $oErro) {

    db_msgbox( $oErro->getMessage() );
    db_redireciona("arr4_descontomanual001.php?iOpcao=3");
    exit;
  
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php db_app::load("estilos.css, grid.style.css, scripts.js, strings.js, prototype.js, datagrid.widget.js"); ?>
<style type="text/css">
  #ctnDesconto table td {
    padding:0 5px;
  }
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">

  <fieldset style="width:500px;margin:30px auto 0 auto;">

    <legend>
      <strong>Numpre do Débito</strong>
    </legend>

    <table>

      <tr>
        <td nowrap>
          <strong>Numpre:</strong>
        </td>
        <td nowrap>
          <?php db_input('k00_numpre', 8, $Ik00_numpre, true, 'text', 3); ?>
        </td>
      </tr>

    </table>

  </fieldset>

  <fieldset id="ctnDesconto" style="width:480px;margin:5px auto 0 auto;display:none;">
    <legend><strong>Cancelar desconto</strong></legend>
    <div id="gridDesconto"></div>
  </fieldset>

  <br />
  <center>
    <input type="button" id="btnCancelarDesconto" value="Cancelar Desconto" onClick="return js_cancelarDesconto();" />
    <input type="button" name="voltar" value="Voltar" onClick="js_voltar()" />
  </center>

<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>

<script type="text/javascript">
'use strict';

/**
 * Url do rpc 
 */
var sUrlRPC = 'arr4_descontomanual.RPC.php';  

/**
 * Array com headers
 */
var aHeader = new Array();
aHeader[0] = 'Abatimento';
aHeader[1] = 'Dt. Lançamento';
aHeader[2] = 'Receita';
aHeader[3] = 'Descricao Receita';
aHeader[4] = 'Valor';
aHeader[5] = 'Parcela';

/**
 * Tamanho das colunas
 */
var aWidth = new Array();
aWidth[0] = '5%';  
aWidth[1] = '25%';  
aWidth[2] = '10%';  
aWidth[3] = '30%';  
aWidth[4] = '20%';  
aWidth[5] = '10%';  

/**
 * Alinhamento das colunas
 */
var aAlinhamentos = new Array();
aAlinhamentos[0] = 'center';
aAlinhamentos[1] = 'center';
aAlinhamentos[2] = 'center';
aAlinhamentos[3] = 'left';
aAlinhamentos[4] = 'center';
aAlinhamentos[5] = 'center';

/**
 * Monta html da grid
 */
var oGridDesconto = new DBGrid('grid');
oGridDesconto.nameInstance = 'oGridDesconto';
oGridDesconto.setCellWidth( aWidth );
oGridDesconto.setCellAlign( aAlinhamentos );
oGridDesconto.allowSelectColumns(false);
oGridDesconto.setCheckbox(0);
oGridDesconto.setHeader( aHeader );

/**
 * Esonde coluna com abatimento 
 */
oGridDesconto.aHeaders[1].lDisplayed = false;

oGridDesconto.iHeight = 300;
oGridDesconto.show($('gridDesconto'));
oGridDesconto.clearAll(true);

function js_buscarDesconto() {

  if ( $F('k00_numpre') == '' ) {

    alert('Numpre não informado.');
    return false;
  }

  var oParametros     = new Object();
  oParametros.exec    = 'getDadosDesconto';  
  oParametros.iNumpre = $F('k00_numpre');

  js_divCarregando("Buscando desconto...\nAguarde", 'msgBox');
   
  var oAjax = new Ajax.Request(
    sUrlRPC, 
    {
      method     : 'post',
      parameters : 'json=' + Object.toJSON(oParametros),
      onComplete : js_retornoBuscarDesconto
    }
  );   

}

js_buscarDesconto();

function js_retornoBuscarDesconto(oAjax) {

  js_removeObj('msgBox');

  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode();

  /**
   * Erro
   */   
  if ( oRetorno.iStatus > 1 ) {

    $('ctnDesconto').style.display = 'none';
    $('btnCancelarDesconto').style.display = 'none';

    alert( sMensagem );
    js_voltar();
    return false;
  }

  var iTotalDescontos = oRetorno.aDescontos.length;

  if ( iTotalDescontos < 1 ) {
    return false;
  }

  oGridDesconto.aHeaders[1].lDisplayed = false;
  oGridDesconto.clearAll(true);

  for ( var iIndice = 0; iIndice < iTotalDescontos; iIndice++ ) {
  
    var oDesconto = oRetorno.aDescontos[iIndice];

    var aLinha = new Array();

    aLinha[0] = oDesconto.iAbatimento;
    aLinha[1] = oDesconto.sDataLancamento;
    aLinha[2] = oDesconto.iReceita;
    aLinha[3] = oDesconto.sReceita;
    aLinha[4] = oDesconto.nValor;
    aLinha[5] = oDesconto.iParcela;

    oGridDesconto.addRow(aLinha);
  }

  $('ctnDesconto').style.display = '';
  oGridDesconto.renderRows();
}

/**
 * Cancela desconto 
 * 
 * @access public
 * @return void
 */
function js_cancelarDesconto() {

  var oParametros        = new Object();
  oParametros.exec       = 'cancelaDesconto';  
  oParametros.iNumpre    = $F('k00_numpre');
  oParametros.aDescontos = new Array();

  var aDescontos = oGridDesconto.getSelection();

  if ( aDescontos.length == 0 ) {

    alert('Escolha pelo menos uma parcela');
    return false;
  }

  for (var iIndice = 0; iIndice < aDescontos.length; iIndice++ ) {

    var oDesconto = new Object();

    oDesconto.iAbatimento  = aDescontos[iIndice][0];
    oDesconto.iReceita     = aDescontos[iIndice][3];
    oDesconto.nValor       = js_strToFloat(aDescontos[iIndice][5]);
    oDesconto.iParcela     = aDescontos[iIndice][6];

    oParametros.aDescontos[iIndice] = oDesconto;
  }

  js_divCarregando("Cancelando descontos...\nAguarde", 'msgBox');
   
  var oAjax = new Ajax.Request(
    sUrlRPC, 
    {
      method     : 'post',
      parameters : 'json=' + Object.toJSON(oParametros),
      onComplete : js_retornoCancelarDesconto
    }
  );   

}

function js_retornoCancelarDesconto(oAjax) {

  js_removeObj('msgBox');

  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode();

  /**
   * Erro
   */   
  if ( oRetorno.iStatus > 1 ) {

    alert( sMensagem );
    return false;
  }

  alert( sMensagem );
  js_voltar();
}

function js_voltar() {
  window.location.href = 'arr4_descontomanual001.php?iOpcao=3';
}
</script>

</body>
</html>