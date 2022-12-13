<?
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
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$oRotulo = new rotulocampo;
$oRotulo->label("v50_inicial");
$oRotulo->label("v71_processoforo");
$oRotulo->label("v56_codsit");
$oRotulo->label("v52_descr"); 
$oRotulo->label("v56_obs"); 

?>
<html>
<head>
  <?php
    db_app::load('scripts.js, prototype.js, datagrid.widget.js, strings.js, DBHint.widget.js');
    db_app::load('estilos.css, grid.style.css');
  ?>
</head>
</head>
<body bgcolor="#CCCCCC">
<form class="container" name="form1" id="form1" action="">
  <fieldset style="margin:30px auto 10px auto; width: 650px">
  
    <legend>
      <strong>
      <?php 
        if (isset($oGet->iTipoAcao)) {
          
          if ($oGet->iTipoAcao == 1) {
            echo 'Incluir movimento da(s) inicial(is)';
          } else if ($oGet->iTipoAcao == 2) {
            echo 'Alterar movimento da(s) inicial(is)';
          } else {
            echo 'Excluir movimento da(s) inicial(is)';
          }
        }
      ?>
      </strong>
    </legend>
    
    <div id="gridIniciais">
    
    </div>
  	
  </fieldset>
  
  <?php 
    if(isset($oGet->iTipoAcao) and $oGet->iTipoAcao == '3') {
      $sStyle = 'display: none;';
    } else {
      $sStyle = '';
    }
  ?>
  <fieldset>
    <legend><strong>Situação</strong></legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tv56_codsit?>">
        <?
          db_ancora(@$Lv56_codsit,"js_pesquisaSituacao(true);", 1);
        ?>
        </td>
        <td>
        <?
          db_input('v56_codsit', 10, $Iv56_codsit, true, 'text', 1, " onchange='js_pesquisaSituacao(false);'");
          db_input('v52_descr' , 38, $Iv52_descr,  true, 'text', 3, '')
        ?>
        </td>
      </tr>
      <tr>
        <td colspan="2"> 
        <fieldset class="separator">
          <legend>Observações</legend>
        	<?
            db_textarea('v56_obs', 3, 80, $Iv56_obs, true, 'text', 1); 
          ?>
        </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  
    <input type="button" name="salvar" id="salvar" value="Salvar" onclick="js_processar()"/>
    <input type="button" name="voltar" id="voltar" value="Voltar" onclick="js_voltar()"/>
  <?
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>
</form>

<script>
  
var sUrl = 'jur4_inicialmov001.RPC.php';

js_initTable();

js_pesquisaInicial();

function js_processar() {

  aIniciais = new Array();

  if (oDataGrid.getSelection().length == 0) {
    alert(_M('tributario.juridico.jur4_inicialmov002.selecione_inicial')); 
    return false;
  }
  
	oDataGrid.getSelection().each(function (aRow, iIndiceInicial) {

		aIniciais.push(aRow[0]); 
		
	});

	js_divCarregando(_M('tributario.juridico.jur4_inicialmov002.processando_iniciais'), 'msgbox');

	var oParam             = new Object();

	var oGet               = js_urlToObject();

	if (oGet.iTipoAcao == 1) {
		//inclusao
	  
	  oParam.sExec           = 'salvarMovimentacoes';
		
	} else if (oGet.iTipoAcao == 2) {
		//alteracao
		
	  oParam.sExec           = 'alterarMovimentacoes';
	  
	} else if (oGet.iTipoAcao == 3) {
		//alteracao
		
	  oParam.sExec           = 'excluirMovimentacoes';
	  
	}
	
	oParam.iCodigoSituacao = $F('v56_codsit');
	oParam.sObservacoes    = $F('v56_obs').urlEncode();
	oParam.aIniciais       = aIniciais;

	var oDadosRequisicao        = new Object();
	oDadosRequisicao.method     = 'POST';
	oDadosRequisicao.parameters = 'json='+Object.toJSON(oParam);
	oDadosRequisicao.onComplete = js_retornaConfirmacao;
	

	var oAjax = new Ajax.Request(sUrl, oDadosRequisicao);
  
}

function js_retornaConfirmacao (oAjax) {

  js_removeObj('msgbox');

	var oRetorno = eval("("+oAjax.responseText+")");

	var oGet     = js_urlToObject();

	if (oRetorno.iStatus == 1) {

		alert(_M('tributario.juridico.jur4_inicialmov002.movimentos_enviados_sucesso'));

	} else {

	  alert(oRetorno.sMessage.urlDecode().replace(/\\n/g,'\n'));
		
	}

	window.location = 'jur4_inicialmov001.php?iTipoAcao=' + oGet.iTipoAcao;
  
}

function js_pesquisaInicial() {

  var oParam = new Object();
  
  var oGet  = js_urlToObject();

  oParam.sExec               = 'getIniciais';
  oParam.iCodigoInicial      = oGet.iCodigoInicial;
  oParam.iCodigoProcessoForo = oGet.iCodigoProcessoForo; 

  js_divCarregando(_M('tributario.juridico.jur4_inicialmov002.pesquisando_iniciais'), 'msgbox');
  
  var oAjax = new Ajax.Request(sUrl, 
                        		  {
                               method    : 'POST',
                        		   parameters: 'json='+Object.toJSON(oParam),
                        		   onComplete: js_retornaIniciais
                        		  });

}

var aDadosGrid = new Array();


function js_retornaIniciais(oAjax) {

  js_removeObj('msgbox');

	var oRetorno = eval("("+oAjax.responseText+")");

	var oGet     = js_urlToObject();

	// oGet.iTipoAcao:1 = incluir oGet.iTipoAcao:2 = alterar 
	if (oGet.iTipoAcao == 1) {
		lChecked = true;
	} else {
		lChecked = false;
	}

	if (oRetorno.iStatus == 1) {

		oDataGrid.clearAll(true);

	  for (var iIndice = 0; iIndice < oRetorno.aIniciais.length; iIndice++) {

	    var oDados = oRetorno.aIniciais[iIndice];
	    aRow = new Array();

	    aRow[0] = oDados.iNumeroInicial;
	    aRow[1] = oDados.dDataInicial;
	    aRow[2] = oDados.iSituacao;
	    aRow[3] = oDados.sProcessoForo;
	    aRow[4] = oDados.sObservacaoMovimentacao.urlDecode().substr(0, 10);

	    oDataGrid.addRow(aRow, null, null, lChecked);

      var oDadosHint = {iCodigoMovimentacao: oDados.iCodigoMovimentacao, sObservacao : oDados.sObservacaoMovimentacao.urlDecode(), sCelula : oDataGrid.aRows[iIndice].aCells[5].sId};
      aDadosGrid.push( oDadosHint );
	    
	  }

	  oDataGrid.renderRows();

	  for ( var iIndiceCelula = 0; iIndiceCelula < aDadosGrid.length;  iIndiceCelula++) {
		  
		  var oDadosHint           = aDadosGrid[iIndiceCelula];
		  
		  var oCelulaObservacao    = $(oDadosHint.sCelula);
		  
		  var oDBHint 					   = eval("oDBHint_"+iIndiceCelula+" = new DBHint('oDBHint_"+iIndiceCelula+"')");

		  var sTextoHint = '';

		  sTextoHint  = '<strong>Código Movimentação:</strong>';
		  sTextoHint += oDadosHint.iCodigoMovimentacao 
		  sTextoHint += '<br/><strong>Observações:</strong>';
		  sTextoHint += oDadosHint.sObservacao;  

      oDBHint.setText			 (sTextoHint);
      oDBHint.setShowEvents(["onmouseover"]);
      oDBHint.setHideEvents(["onmouseout"]);
      oDBHint.setPosition	 ('B', 'L');
      oDBHint.make				 (oCelulaObservacao);
	  }
	}
}

function js_initTable() {
	
  oDataGrid              = new DBGrid('gridResultados');
  oDataGrid.nameInstance = 'oDataGrid';
  oDataGrid.setCheckbox (0);
  oDataGrid.setCellAlign(new Array('center', 'center', 'center', 'left', 'left'));
  oDataGrid.setCellWidth(new Array('15%', '15%', '20%', '30%', '20%'));
  oDataGrid.setHeader   (new Array('Inicial', 'Data Inicial', 'Situação', 'Processo Foro', 'Últ. Movimentação'));
  oDataGrid.setHeight(150);
  oDataGrid.show($('gridIniciais'));

}

function js_pesquisaSituacao (lMostra) {

  if (lMostra) {
    js_OpenJanelaIframe('top.corpo', 'db_iframe_situacao', 'func_situacao.php?funcao_js=parent.js_retornaSituacao|0|1', 'Pesquisa', lMostra);
  } else {
    js_OpenJanelaIframe('top.corpo', 'db_iframe_situacao', 'func_situacao.php?pesquisa_chave='+$F('v56_codsit')+'&funcao_js=parent.js_retornaSituacaoHide', 'Pesquisa', lMostra);
  }
  
}

function js_retornaSituacaoHide (sDescricao, lErro) {

  $('v52_descr') .setValue(sDescricao);    
  if (lErro) {
    $('v56_codsit').setValue('');
  }   
    
}

function js_retornaSituacao (iCodigoSituacao, sDescricao) {

  $('v56_codsit').setValue(iCodigoSituacao);
  $('v52_descr') .setValue(sDescricao);

  db_iframe_situacao.hide();
  
}

function js_voltar() {

  var oGet = js_urlToObject();

  window.location = 'jur4_inicialmov001.php?iTipoAcao=' + oGet.iTipoAcao;
  
}

String.prototype.urlEncode = function() {

	var sString = this;
	
	encodeURIComponent( tagString( sString ) );
	
	return sString;
	
}

</script>
</body>
</html>
<script>

$("v56_codsit").addClassName("field-size2");
$("v52_descr").addClassName("field-size7");

</script>