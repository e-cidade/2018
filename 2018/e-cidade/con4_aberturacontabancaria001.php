<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<html>
  <head>
    <?php
    db_app::load("scripts.js");
    db_app::load("prototype.js");
    db_app::load("strings.js");
    db_app::load("webseller.js");

    db_app::load("widgets/windowAux.widget.js");
    db_app::load("DBtab.style.css, estilos.css");
    db_app::load("datagrid.widget.js");
    ?>
  </head>
  <body style='margin-top:25px; height: 100%;' >
  	<div style="width:1600px" class='container'>
  		<div style="width:800px; float:left">
  			<fieldset>
  				<legend>Plano Contas Anterior</legend>
  				<strong>Estrutural:</strong>
  				<?php db_input('sPesquisaContasPlanoAnterior', 25, null, true, 'text', 1); ?>
  				<input type="button" value="Pesquisar" onclick="js_pesquisarDadosContaPlanoAnterior()" />
  				<br><br>
  				<div id="ctnGridContaPlanoAnterior"></div>
  			</fieldset>
  		</div>
  		<div style="width:800px; float:right">
  			<fieldset>
  				<legend>Plano Contas PCASP</legend>
  				<strong>Estrutural:</strong>
  				<?php db_input('sPesquisaContaPcasp', 25, null, true, 'text', 1); ?>
  				<input type="button" value="Pesquisar" onclick="pesquisaContasPcasp();" />
  				<br><br>
  				<div id="ctnGridContaPcasp"></div>
  			</fieldset>
  		</div>
  	  <input type="button" value="Processar" onclick="vincularPlanos();" />
  	</div>
  </body>
  <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</html>
<script>
var sUrl               = 'con4_aberturacontaspcasp.RPC.php';
var sCaminhoMensangens = 'configuracao.configuracao.con4_aberturacontabancaria001.';
var iAltura            = document.body.getHeight() / 1.4;
var aContasAnteriores  = null;

$('sPesquisaContasPlanoAnterior').maxLength = 15;
$('sPesquisaContasPlanoAnterior').observe('keyup', function() {
  $('sPesquisaContasPlanoAnterior').value = $('sPesquisaContasPlanoAnterior').value.replace(/[^0-9]/, '');
});

$('sPesquisaContasPlanoAnterior').observe('keydown', function() {
  $('sPesquisaContasPlanoAnterior').value = $('sPesquisaContasPlanoAnterior').value.replace(/[^0-9]/, '');
});

$('sPesquisaContaPcasp').maxLength = 15;
$('sPesquisaContaPcasp').observe('keyup', function() {
  $('sPesquisaContaPcasp').value = $('sPesquisaContaPcasp').value.replace(/[^0-9]/, '');
});

$('sPesquisaContaPcasp').observe('keydown', function() {
  $('sPesquisaContaPcasp').value = $('sPesquisaContaPcasp').value.replace(/[^0-9]/, '');
});

/**
 * Grid Conta Orçamentária
 */
var aHeaderContaPlanoAnterior = new Array ( 'Código', 'Estrutural', 'Descrição', 'Reduzido', 'Ano' );
var aWidthContaPlanoAnterior  = new Array ( '10%', '20%', '60%', '10%' );

oGridContaPlanoAnterior              = new DBGrid('oGridContaPlanoAnterior');
oGridContaPlanoAnterior.nameInstance = 'oGridContaPlanoAnterior';
oGridContaPlanoAnterior.setCheckbox(0);
oGridContaPlanoAnterior.setHeader(aHeaderContaPlanoAnterior);
oGridContaPlanoAnterior.setCellWidth(aWidthContaPlanoAnterior);
oGridContaPlanoAnterior.setHeight( iAltura );
oGridContaPlanoAnterior.aHeaders[5].lDisplayed = false;
oGridContaPlanoAnterior.selectSingle = function ( oCheckbox, sRow, oRow ) {

  if ( sRow != null ) {
    
    oRow = oGridContaPlanoAnterior.getRowById(sRow);
    if ( oRow === false ) {
      return;
    }
  }
  
  sRow  = oRow.sId;
  
  var aClasses = oRow.getClassName().split();
  var sClasse  = '';

  var lMarcado      = oCheckbox.checked;
  oRow.isSelected   = false;
  oCheckbox.checked = false;
  
  if ( lMarcado ) {
    
    sClasse           += 'marcado';
    oRow.isSelected    = true;
    oCheckbox.checked  = true;
  }

  aClasses.each(function( oClasse ) {

    if ( oClasse == 'bold' ) {
      sClasse += ' bold';
    }
  });
  
  $(sRow).className = sClasse;
};

oGridContaPlanoAnterior.show($('ctnGridContaPlanoAnterior'));

/**
 * Grid Conta PCASP
 */
var aHeaderPcasp = new Array ( 'Código', 'Estrutural', 'Descrição', 'Ano' );
var aWidthPcasp  = new Array ( '10%', '30%', '60%' );

oGridPcasp              = new DBGrid('oGridPcasp');
oGridPcasp.nameInstance = 'oGridPcasp';
oGridPcasp.setCheckbox( 0 );
oGridPcasp.setSelectAll( false );
oGridPcasp.setHeader( aHeaderPcasp );
oGridPcasp.setCellWidth( aWidthPcasp );
oGridPcasp.setHeight( iAltura );
oGridPcasp.aHeaders[4].lDisplayed = false;
oGridPcasp.selectSingle = function (oCheckbox,sRow,oRow) {
  
  if (sRow != null) {
    
    oRow = oGridPcasp.getRowById(sRow);
    if (oRow === false) {
      return;
    }
  }
  sRow  = oRow.sId;
  itens = document.getElementsByClassName("checkboxoGridPcasp");
  
  var lMarcado = oCheckbox.checked;
  if (lMarcado) {
    
    for (var i = 0;i < itens.length;i++) {
  
      itens[i].checked                         = false;
      $('oGridPcasprowoGridPcasp'+i).className = 'normal';
      oGridPcasp.aRows[i].isSelected           = false;
    }
    
    $(sRow).className = 'marcado';
    oRow.isSelected   = true;
    oCheckbox.checked = true;
  }
  
  return true;
};

oGridPcasp.show($('ctnGridContaPcasp'));

function js_pesquisarDadosContaPlanoAnterior() {

  js_divCarregando(_M(sCaminhoMensangens+'buscando_contas'), "msgBox");

  var oParametro         = new Object();
  oParametro.sExecucao   = 'getContasPlanoAnterior';
  oParametro.sEstrutural = $F('sPesquisaContasPlanoAnterior');

  var oAjax = new Ajax.Request(sUrl,
                               {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornoDadosContaPlanoAnterior
                               }
                              );

}

/**
 * Retorno para a grid ContaPlanoAnterior
 */
function js_retornoDadosContaPlanoAnterior(oResponse) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.iStatus != 1) {

    alert( oRetorno.sMensagem.urlDecode() );
    $('sPesquisaContasPlanoAnterior').value = '';
    return false;
  }
  
  oGridContaPlanoAnterior.clearAll(true);

  oRetorno.aContasAnterior.each(function (oContaPlanoAnterior, iSeq) {

 		var aLinha     = new Array();
      	aLinha[0]  = oContaPlanoAnterior.iCodCon;
      	aLinha[1]  = oContaPlanoAnterior.estrutural;
      	aLinha[2]  = oContaPlanoAnterior.c60_descr.urlDecode();
      	aLinha[3]  = oContaPlanoAnterior.c61_reduz;
      	aLinha[4]  = oContaPlanoAnterior.iAno;

    oGridContaPlanoAnterior.addRow(aLinha);
      	
    if ( oContaPlanoAnterior.c61_reduz == 0 ) {
      oGridContaPlanoAnterior.aRows[iSeq].setClassName( 'bold' );
    }
  });
  oGridContaPlanoAnterior.renderRows();
}

/**
 * Pesquisa as contas do PCASP
 */
function pesquisaContasPcasp() {

  var oParametro             = new Object();
      oParametro.sExecucao   = 'getContasPCASP';
      oParametro.sEstrutural = $('sPesquisaContaPcasp').value;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = retornoPesquisaContasPcasp;

  js_divCarregando( _M( sCaminhoMensangens+'buscando_contas_pcasp' ), "msgBox" );
  new Ajax.Request( sUrl, oDadosRequisicao );
}

/**
 * Retorno das contas do PCASP para preenchimento da Grid
 */
function retornoPesquisaContasPcasp( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '('+oResponse.responseText+')' );

  if ( oRetorno.iStatus != 1 ) {

    alert( oRetorno.sMensagem.urlDecode() );
    $('sPesquisaContaPcasp').value = '';
    return false;
  }

  oGridPcasp.clearAll( true );
  oRetorno.aContasPcasp.each(function( oPcasp, iSeq ) {

    var aLinha    = new Array();
        aLinha[0] = oPcasp.c60_codcon;
        aLinha[1] = oPcasp.c60_estrut;
        aLinha[2] = oPcasp.c60_descr.urlDecode();
        aLinha[3] = oPcasp.c60_anousu;

    oGridPcasp.addRow( aLinha );
  });

  oGridPcasp.renderRows();
}

/**
 * Vincula as contas selecionadas na grid das contas do ano anterior com a conta PCASP selecionada na Grid
 */
function vincularPlanos() {

  aContasAnteriores = oGridContaPlanoAnterior.getSelection( 'object' );
  aContasPcasp      = oGridPcasp.getSelection( 'object' );

  if ( aContasAnteriores.length == 0 ) {
  
    alert( _M( sCaminhoMensangens+'sem_contas_anteriores_selecionadas' ) );
    return false;
  }
  
  if ( aContasPcasp.length == 0 ) {
  
    alert( _M( sCaminhoMensangens+'sem_conta_pcasp_selecionada' ) );
    return false;
  }

  var lSomenteSintetica = true;
  var aContasOrcamento  = new Array();
  
  aContasAnteriores.each(function( oConta, iSeq ) {

    var oContaOrcamentaria              = new Object();
        oContaOrcamentaria.iCodigoConta = parseInt( oConta.aCells[1].getContent() );
        oContaOrcamentaria.iAnoConta    = parseInt( oConta.aCells[5].getContent() );

    if ( oConta.aCells[4].getContent() != 0 ) {
      lSomenteSintetica = false;
    }

    aContasOrcamento.push( oContaOrcamentaria );
  });

  var oContaPcasp = new Object();
  aContasPcasp.each(function( oConta, iSeq ) {

    oContaPcasp.iCodigoConta = parseInt( oConta.aCells[1].getContent() );
    oContaPcasp.iAnoConta    = parseInt( oConta.aCells[4].getContent() );
  });

  if ( lSomenteSintetica ) {

    alert( _M( sCaminhoMensangens+'somente_contas_sinteticas' ) );
    return false;
  }
  
  if ( ( !lSomenteSintetica && confirm( _M( sCaminhoMensangens+'confirma_vinculo' ) ) ) ) {
    
    var oParametro                  = new Object();
        oParametro.sExecucao        = 'vincularContas';
        oParametro.oContaPcasp      = oContaPcasp;
        oParametro.aContasOrcamento = aContasOrcamento;
  
    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON( oParametro );
        oDadosRequisicao.onComplete = retornoVincularPlanos;
  
    js_divCarregando( _M( sCaminhoMensangens+'vinculando_contas' ), "msgBox" );
    new Ajax.Request( sUrl, oDadosRequisicao );
  }
}

/**
 * Retorno do vínculo dos planos
 */
function retornoVincularPlanos( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '('+oResponse.responseText+')' );

  alert( oRetorno.sMensagem.urlDecode() );

  if ( oRetorno.iStatus == 1 ) {

    oGridContaPlanoAnterior.clearAll( true );
    js_pesquisarDadosContaPlanoAnterior();
    oGridPcasp.clearAll( true );
    pesquisaContasPcasp();
  }
}
</script>