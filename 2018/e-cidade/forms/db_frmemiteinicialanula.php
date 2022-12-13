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

$clrotulo = new rotulocampo;
$clrotulo->label("v50_inicial");
?>

<form class="container" name="form1" id="form1" method="post" action="">
<fieldset>
  <legend>Procedimentos - Inicial/Anulação</legend>

  <table class="form-container">
    <tr>
      <td title="<?=$Tv50_inicial?>">
        <?php
          db_ancora('Inicial:', 'js_pesquisaInicialInicio(true)', 1); 
        ?>
      </td>
      <td>
        <?php 
          db_input('v50_inicialinicio', 10, $Iv50_inicial, true, 'text', 1, 'onchange="js_acertoNumeracao(1)" tabindex="1"');        
        ?>
      </td>
      <td title="<?=$Tv50_inicial?>">
        <?php
          db_ancora('até', 'js_pesquisaInicialFinal(true)', 1); 
        ?>
      </td>
      <td>
        <?php 
          db_input('v50_inicialfinal', 10, $Iv50_inicial, true, 'text', 1, 'onchange="js_acertoNumeracao(2)" tabindex="2"');        
        ?>
      </td>
    </tr>
  </table>
</fieldset>

  <input type="button" name="pesquisar" id="pesquisar" value="Pesquisar" onclick="js_pesquisar()" >
</form>
<script>
var sUrl = 'jur1_emiteinicialanula001.RPC.php';

function js_initTable() {
	
  oDataGrid = new DBGrid('gridResultados');
  
  oDataGrid.nameInstance = 'oDataGrid';
  oDataGrid.setCellAlign(new Array('left'));
  oDataGrid.setCellWidth(new Array('100%'));
  oDataGrid.setHeader   (new Array('Inicial'));
  oDataGrid.setHeight(200);
  oDataGrid.show($('gridResultados'));

}

function js_processar() {

  var aIniciais = $$('.inicial:checked');

  var aIniciaisSelecionadas = new Array();

  var oParam = new Object();

  oParam.sExec = 'anulaIniciais';
  
  for (var iIndiceInicial = 0; iIndiceInicial < aIniciais.length; iIndiceInicial++) {
    aIniciaisSelecionadas[iIndiceInicial] = aIniciais[iIndiceInicial].value;
  }

  oParam.aIniciaisSelecionadas = aIniciaisSelecionadas;

  oParam.sObservacao           = $F('observacao');

  js_divCarregando(_M('tributario.juridico.db_frmemiteinicialanula.anulando_iniciais'), 'msgbox');

	var oAjax = new Ajax.Request(sUrl, 
														  {
		  												 method    : 'POST',
															 parameters: 'json='+Object.toJSON(oParam),
															 onComplete: js_retornaAnulacao
															});

  
}

function js_retornaAnulacao (oAjax) {
  
	js_removeObj('msgbox');

	var oRetorno = eval("("+oAjax.responseText+")");

	if (oRetorno.iStatus == 1) {

	  oWindowIniciais.destroy();

	  $('pesquisar').disabled = false;

	  alert(_M('tributario.juridico.db_frmemiteinicialanula.anulacao_efetuada_sucesso'));
	  
	  window.location = 'jur1_emiteinicial004.php';
	  
	} else if (oRetorno.iStatus == 2){

	  $('processar').disabled = true;

	  oWindowIniciais.destroy();

	  js_montaJanelaErros();

	  js_initTableErros(oRetorno);
	  
	} else {
		
	  alert(oRetorno.sMessage.urlDecode());

	  window.location = 'jur1_emiteinicial004.php';
	  		
	}
  
}

function js_pesquisar() {
	
	var oParam = new Object();

	oParam.sExec = 'getCertidoes';

	oParam.iCodigoInicialInicio = $F('v50_inicialinicio');  

	oParam.iCodigoInicialFinal  = $F('v50_inicialfinal');
		
	js_divCarregando(_M('tributario.juridico.db_frmemiteinicialanula.pesquisando_certidoes'), 'msgbox');

	var oAjax = new Ajax.Request(sUrl, 
														  {
		  												 method    : 'POST',
															 parameters: 'json='+Object.toJSON(oParam),
															 onComplete: js_retornaCertidoes
															});
	
}

function js_retornaCertidoes(oAjax) {

	js_removeObj('msgbox');

	var oRetorno = eval("("+oAjax.responseText+")");


	if (oRetorno.iStatus == 2) {

		alert(oRetorno.sMessage);

		return false;

	} else {

		$('pesquisar').disabled = true;

	  js_montaJanela();

		js_initTable();
	  
    oDataGrid.clearAll(true);

    var iNumeroLinha   = 0;

    var iTotalIniciais = 0;
   
    for ( var iIndice in oRetorno.aDadosIniciais ) {

      iTotalIniciais++;

      oInicial     = oRetorno.aDadosIniciais[iIndice];
      oDataGrid.addRow(["<input type='checkbox' class='inicial' value='" + oInicial.iNumeroInicial + "'checked='checked'><span class='cabecalho'>&nbsp;&nbsp;<strong>Inicial : " + oInicial.iNumeroInicial + "</strong></span>"]);
      
      oDataGrid.aRows[iNumeroLinha].setClassName('marcado');

      iNumeroLinha++;
      
      for( var iIndiceCertidoes = 0; iIndiceCertidoes < oInicial.aCertidoes.length; iIndiceCertidoes++) {

        var iCertidao = oInicial.aCertidoes[iIndiceCertidoes];
        
        oDataGrid.addRow(["&nbsp;&nbsp;&nbsp;&nbsp;Certidao: " + iCertidao]);

        iNumeroLinha++;
        
      }
       
    }

    oDataGrid.renderRows();

    $('gridResultadosnumrows').innerHTML = iTotalIniciais;
    
	}

}

function js_montaJanelaErros() {

	var sContent = "";

  sContent += '<div style="margin:0 auto;">                                                                                   ';                                                                                                     
	sContent += '  <div id="cabecalho_processamento" >                                                                          ';
	sContent += '  </div>                                                                                                       ';
	sContent += '  <div style="margin:10px auto;">                                                                              ';
	sContent += '    <fieldset>                                                                                                 ';
	sContent += '      <legend><strong>Processamento</strong></legend>                                                          ';
	sContent +=	'      <div id="gridProcessamento"></div>                                                                       ';
	sContent += '    </fieldset>                                                                                                ';
	sContent += '    <div style="margin: 10px auto; text-align: center;">                                                       ';
	sContent += '      <input type="button" name="fechar" id="fechar" value="Fechar" onclick="js_fecharJanelaProcessamento()" > ';
	sContent += '    </div>                                                                                                     ';
	sContent += '  </div>                                                                                                       ';
	sContent += '</div>';
	
	oWindowErros  = new windowAux('wnderros', 'Lista das operações realizadas.', 550, 450);

	oWindowErros.setShutDownFunction(js_fecharJanelaProcessamento);
	oWindowErros.setContent(sContent);


  var iWidth  = (document.body.clientWidth  - oWindowErros.getWidth()) / 2;
  var iHeight = (document.body.clientHeight - oWindowErros.getHeight()) / 2;
	
  oWindowErros.show(iHeight,iWidth);

  oMessageBoard = new DBMessageBoard('msgboard1', 
                                     'Operações executadas.',
                                     '- Descrição das operações executadas.',
                                     $('cabecalho_processamento') );

  
	$('window'+oWindowErros.idWindow+'_btnclose').observe("click", js_fecharJanela);

}

function js_fecharJanelaProcessamento(){

  $('pesquisar').disabled = false;

  oWindowErros.destroy();

  window.location = 'jur1_emiteinicial004.php';
  
} 

function js_initTableErros(oErros) {
	
  oDatagridProcessamento = new DBGrid('gridProcessamento');
  
  oDatagridProcessamento.nameInstance = 'oDataGrid';
  oDatagridProcessamento.setCellAlign(new Array('left', 'left'));
  oDatagridProcessamento.setCellWidth(new Array('20%', '80%'));
  oDatagridProcessamento.setHeader   (new Array('Inicial', 'Erro'));
  oDatagridProcessamento.show($('gridProcessamento'));

  
  oDatagridProcessamento.clearAll(true);

  for (var iIndice = 0; iIndice < oErros.aLogIniciais.length; iIndice++) {
    
    var oLog = oErros.aLogIniciais[iIndice];

    aRow    = new Array();

    aRow[0] = oLog.iInicial;

    aRow[1] = oLog.sResposta.urlDecode();

    oDatagridProcessamento.addRow(aRow);
        
  }

  oDatagridProcessamento.renderRows();

}

function js_montaJanela() {
	
	var sContent = "";
                                                                                                                      
	sContent += '<div style="margin: 0 auto; text-align: center; width: 600px;">                                                                                               ';
	sContent += '<div id="cabecalho_iniciais">                                                                                                                                 ';
	sContent += '</div>                                                                                                                                                        ';
	sContent += '  <div style="margin:10px auto;">                                                                                                                             ';
	sContent += '    <fieldset>                                                                                                                                                ';
	sContent += '      <legend><strong>Iniciais</strong></legend>                                                                                                              ';
	sContent +=	'      <div id="gridResultados"></div>                                                                                                                         ';
	sContent += '    </fieldset>                                                                                                                                               ';
	sContent += '    <fieldset>                                                                                                                                                ';
	sContent += '      <legend><strong>Observações</strong></legend>                                                                                                           ';
	sContent +=	'      <textarea onkeyup="js_maxlenghttextarea(this,event,600);" name="observacao" id="observacao" style="width:570; height:150px;" maxlength="600"></textarea>';
	sContent += '      <div style="float:right;">                                                                                                                              ';
  sContent += '          <span id="observacaoerrobar" style="float:left;color:red;font-weight:bold"></span>                                                                  ';
  sContent += '          <b> Caracteres Digitados : </b>                                                                                                                     ';
  sContent += '          <input id="observacaoobsdig" type="text" disabled="" value="0" size="3" name="observacaoobsdig" style="background-color:#FFF;color:#000;">          ';
  sContent += '            <b> - Limite 600 </b>                                                                                                                             ';
  sContent += '      </div>                                                                                                                                                  ';
	sContent += '    </fieldset>                                                                                                                                               ';
	sContent += '    <div style="margin: 10px auto;">                                                                                                                          ';
	sContent += '      <input type="button" name="processar" id="processar" value="Processar" onclick="js_processar()" >                                                       ';
	sContent += '      <input type="button" name="fechar" id="fechar" value="Fechar" onclick="js_fecharJanela()" >                                                             ';
	sContent += '    </div>                                                                                                                                                    ';
	sContent += '  </div>                                                                                                                                                      ';
	sContent += '</div>                                                                                                                                                        ';
	
	oWindowIniciais  = new windowAux('wndexerc', 'Lista de Iniciais com Certidões', 630, 620);
	oWindowIniciais.setShutDownFunction(js_fecharJanela);
	oWindowIniciais.setContent(sContent);


  var iWidth  = (document.body.clientWidth  - oWindowIniciais.getWidth()) / 2;
  var iHeight = (document.body.clientHeight - oWindowIniciais.getHeight()) / 2;
	
	oWindowIniciais.show(iHeight,iWidth);

	oMessageBoard = new DBMessageBoard('msgboard1', 
                                     'Iniciais.',
                                     '- Lista de iniciais com certidões.',
                                     $('cabecalho_iniciais') );
	
	$('window'+oWindowIniciais.idWindow+'_btnclose').observe("click", js_fecharJanela);

}

function js_fecharJanela(){
	
  oWindowIniciais.destroy();

  $('pesquisar').disabled = false;
  
} 


function js_acertoNumeracao(iTipo) {

  var iInicialInicio = new Number($F('v50_inicialinicio'));
  var iInicialFinal  = new Number($F('v50_inicialfinal'));

  if(iTipo == 1) {

    if (iInicialFinal == '' || iInicialInicio > iInicialFinal) {
      $('v50_inicialfinal').value = iInicialInicio; 
    }
    
  } else {
    
    if (iInicialInicio == '' || iInicialFinal < iInicialInicio) {
      $('v50_inicialinicio').value = iInicialFinal;
    }
    
  }

}
function js_pesquisaInicialInicio(lMostra) {

	if (lMostra) {

		js_OpenJanelaIframe('top.corpo', 'db_iframe_inicialinicio', 'func_inicial.php?funcao_js=parent.js_retornaInicialInicio|0', 'Pesquisa', true);
		
	} 
	
}

function js_retornaInicialInicio(iCodigoInicial) {
	
	document.form1.v50_inicialinicio.value = iCodigoInicial;

	if (document.form1.v50_inicialfinal.value == '' || document.form1.v50_inicialfinal.value < document.form1.v50_inicialinicio.value) {
		document.form1.v50_inicialfinal.value = iCodigoInicial;
	}	
	
	db_iframe_inicialinicio.hide();
	
}

function js_pesquisaInicialFinal(lMostra) {

	if (lMostra) {

		js_OpenJanelaIframe('top.corpo', 'db_iframe_inicialfinal', 'func_inicial.php?funcao_js=parent.js_retornaInicialFinal|0', 'Pesquisa', true);
		
	} 
	
}

function js_retornaInicialFinal(iCodigoInicial) {
	
	document.form1.v50_inicialfinal.value = iCodigoInicial;

  if (document.form1.v50_inicialinicio.value == '' || document.form1.v50_inicialinicio.value > document.form1.v50_inicialfinal.value) {
  	document.form1.v50_inicialinicio.value = iCodigoInicial;
  }	
	
	db_iframe_inicialfinal.hide();
	
}

$('v50_inicialinicio').focus();


</script>