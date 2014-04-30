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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load("scripts.js, strings.js, prototype.js, dbcomboBox.widget.js, datagrid.widget.js");
    db_app::load('estilos.css, grid.style.css')
  ?>
  <style>
  #ctnGridOpcoesPesquisa td {
    padding: 3px;
  }
  
  .camposGrid {
    display: block;
  }
  
  </style>
</head>
<body bgcolor="#CCCCCC">
  <form name='form1'>
    <fieldset style="width: 850px; margin:25px auto 10px auto;">
      <legend><strong>Inclusão de Inconsistências</strong></legend>
      
      <table width="100%">
        <tr>
          <td>  
            <strong>Módulo</strong>
          </td>
          <td>
            <div id= "cboModulo"></div>
          </td>
        </tr>
      
        <tr>
          <td>  
            <strong>Tabela</strong>
          </td>
          <td>
            <div id= "cboTabela"></div>
          </td>
        </tr>
      
      </table>
      
      <fieldset>
        <legend><strong>Opções de Pesquisa:</strong></legend>
        
        <div id="ctnGridOpcoesPesquisa"></div>
                        
        <table>
          <tr>
            <td>Ordenação:</td>
            <td>
              <?php
                $aCampos = array('Ordenação'=>'Ordenação');
                db_select('sCampoOrdem', $aCampos, true, 1);
                
                echo '&nbsp';
                
                $aOrdem = array("asc"=>"Crescente", "desc"=>"Decrescente");
                db_select('sOrdem', $aOrdem, true, 1);
              ?>
            </td>
          </tr>
          
        </table>
      </fieldset>
      
      <div style="text-align: center;margin-top:5px;">
        <input type="button" name="btnPesquisar" id="btnPesquisar" value="Pesquisar" onclick="js_pesquisar()"/>
      </div>
      
      <fieldset>
        <legend><strong>Resultados:</strong></legend>
        
        <div id="ctnGridResultados"></div>
        
      </fieldset>
      
    </fieldset>
    
    <div style="text-align: center">
      <input type="button" name="btnProcessar" id="btnProcessar" value="Processar" onclick="js_processar()"/>
    </div>
    
  </form>

  <?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
</body>
</html>

<script type="text/javascript">

var aListaCampos     = new Array();
var aCampos 				 = new Array();
var sCampoPrimaryKey = '';
var sUrlRpc          = 'con4_registrosinconsistentes.RPC.php';

js_montaTela ();

function js_processar() {
  
  var oParam           = new Object();
  oParam.sExec         = "incluirInconsistencia";
  oParam.iCodigoTabela = $F('sComboTabela');
  oParam.iCorreto      = 0;
  oParam.aCampos       = new Array();

  $$('.camposGrid').each(function(oElemento){
    
    if (!oElemento.checked) {
      return;
    }

    var sIdLinhaCampo = oElemento.id.split('|');
    var iSequencialCampo = $('Resultadosrow'+sIdLinhaCampo[1]+'cell3').innerHTML;

    var oCampo              = new Object();
    oCampo.iSequencialCampo = iSequencialCampo;
    oCampo.lExcluir         = $('exclui|'+sIdLinhaCampo[1]).checked;

    if (oElemento.type == 'radio') {

      oParam.iCorreto = iSequencialCampo;
      return; 
    } 

    oParam.aCampos.push(oCampo);
    
  });

  if (oParam.iCorreto == 0 || oParam.aCampos.length == 0) {

    alert('Marque um item correto e no mínimo um errado para continuar');
    return false;
  }

  js_divCarregando("Processando...", "msgBox");

  var oAjax = new Ajax.Request (
    sUrlRpc,
    { 
      method:'post',
      parameters:'json='+Object.toJSON(oParam),
      onComplete:js_retornoProcessar
    }
  );
    
}

function js_retornoProcessar(oAjax) {
  
  js_removeObj("msgBox");

  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMessage.urlDecode().replace(/\\n/g, "\n");

  /**
   * erro 
   */
  if ( oRetorno.iStatus > 1 ) {

    alert(sMensagem);
    return;
  }

  alert(sMensagem);
  window.location.href = window.location.href;
}

function js_pesquisar () {

  /**
   * Caso esteja setada false, n carrega a linha da grid no array
   */
  lContinua = false;

  aDadosPesquisa = new Array();

  iIndice = 0;

  oGridCampos.aRows.each(function(oRow){

    oDadosPesquisa = new Object();

    oDadosPesquisa.iCodigoCampo  = $(oRow.aCells[0].sId).innerHTML;
    oDadosPesquisa.sCampo        = $(oRow.aCells[1].sId).innerHTML;
    oDadosPesquisa.sTipoCampo    = $(oRow.aCells[3].sId).innerHTML;   
    oDadosPesquisa.aValores      = new Array();

    aInputs = document.getElementsByName(oDadosPesquisa.sCampo);
    
    if (oDadosPesquisa.sTipoCampo == 'integer') {
      
      for (var iIndiceElemento = 0; iIndiceElemento < aInputs.length; iIndiceElemento++) {

        if (aInputs[iIndiceElemento].value != '') {
          
          oDadosPesquisa.aValores[iIndiceElemento] = encodeURIComponent(aInputs[iIndiceElemento].value);          
          lContinua = true;
        } 
           
      }
      
    } else  {

      if (aInputs[0].value != '') {
        
        oDadosPesquisa.aValores[0] = encodeURIComponent(aInputs[0].value);
        lContinua = true;
      } 
      
    } 

    if (!lContinua) {
      return;
    } 

    aDadosPesquisa[iIndice] = oDadosPesquisa;

    iIndice++;

    lContinua = false;
    
  });

  if (aDadosPesquisa.length == 0) {
	  alert('Preencha algum filtro para continuar com sua pesquisa');
	  oGridResultados.clearAll(true);
	  return false;
  }
  
  js_divCarregando("Consultando registros do sistema", "msgBox");
  
  var oParam           = new Object();  
  oParam.sExec         = "pesquisar";
  oParam.iCodigoTabela = $F('sComboTabela');  
  oParam.aTabelas      = aDadosPesquisa;
  oParam.sCampoOrdem   = $F('sCampoOrdem');
  oParam.sOrdem        = $F('sOrdem');

  var oAjax       = new Ajax.Request (sUrlRpc,
                                      { 
                                      method:'post',
                                      parameters:'json='+Object.toJSON(oParam),
                                      onComplete:js_retornaRegistros
                                      });
  
}

function js_verificaMarcado() {

  var aErrados  = document.getElementsByName('errados');
  var aCorretos = document.getElementsByName('correto');
  
  for(var iIndiceErrados = 0; iIndiceErrados < aErrados.length; iIndiceErrados++) {
           
    $('errados|'+iIndiceErrados).disabled = false;         
    $('exclui|'+iIndiceErrados).disabled  = false;
  } 

  for(var iIndiceCorretos = 0; iIndiceCorretos < aErrados.length; iIndiceCorretos++) {

    if ( $('correto|'+iIndiceCorretos).checked ) {
            
      $('errados|'+iIndiceCorretos).disabled = true;    
      $('errados|'+iIndiceCorretos).checked  = false;      
      $('exclui|'+iIndiceCorretos).disabled  = true;
      $('exclui|'+iIndiceCorretos).checked   = false;
    }
      
  } 
  
}

function js_retornaRegistros (oAjax) {
  
  js_removeObj("msgBox");

  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.aDados.length == 0) {
    
    alert('Nenhum Registro Encontrado');
    return false;
  }

  oGridResultados.clearAll(true);

  var iCont = 0;

  oRetorno.aDados.each(function (oDados) {
	      
    aLinha    = new Array();
    aLinha[0] = "<input class='camposGrid' type='radio' name='correto' id='correto|"+iCont+"' onclick='js_verificaMarcado()' />";
    aLinha[1] = "<input class='camposGrid' type='checkbox' name='errados' id='errados|"+iCont+"' onclick='js_verificaMarcado()' />";
    aLinha[2] = "<input type='checkbox' name='exclui' checked='checked' id='exclui|"+iCont+"' disabled='disabled' />";

    iCont++;  

	  var iIndice = 3;
	  
    for ( var sCampo in aCampos ) {

    	if (oDados[sCampo]) {
        	
    	  aLinha[iIndice] = oDados[sCampo].urlDecode();      
    	} else {
        	
    		aLinha[iIndice] = ''; 
    	}
      iIndice++;
    } 
    
    oGridResultados.addRow(aLinha);  
  });

  oGridResultados.renderRows();
}

function js_montaTela () {
  
  oComboModulo = new DBComboBox('sComboModulo', 'sComboModulo');
  oComboModulo.addEvent('onChange', 'js_consultaTabelas(this.value)');
  oComboModulo.show($('cboModulo'));

  oComboTabela = new DBComboBox('sComboTabela', 'sComboTabela');
  oComboTabela.addEvent('onChange', 'js_consultaCampos(this.value)');
  oComboTabela.show($('cboTabela'));

  js_consultaModulos();
  js_gridCampos();
  
}

function js_gridCampos() {

  oGridCampos              = new DBGrid('Campos');
  oGridCampos.nameInstance = 'oGridCampos';
  oGridCampos.setHeader   (new Array('Código', 'Campos', 'Filtros', 'Tipo'));
  oGridCampos.setCellAlign(new Array('center', 'left', 'left', 'center'));
  oGridCampos.setCellWidth(new Array('10%', '35%', '55%' , '0'));
  oGridCampos.aHeaders[3].lDisplayed = false;
  oGridCampos.show        ($('ctnGridOpcoesPesquisa'));
  oGridCampos.clearAll(true);  
  
}

function js_consultaModulos() {

  js_divCarregando("Consultando módulos do sistema", "msgBox");
  
  var oParam      = new Object();  
  oParam.sExec    = "consultaModulos";  
  oParam.aTabelas = new Array();

  var oAjax       = new Ajax.Request (sUrlRpc,
                                      { 
                                      method:'post',
                                      parameters:'json='+Object.toJSON(oParam),
                                      onComplete:js_retornaModulos
                                      });
    
  
}

function js_retornaModulos(oAjax) {
  
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  oRetorno.aModulos.each(function (oModulo) {
    oComboModulo.addItem(oModulo.iCodigoModulo, oModulo.sNomeModulo);
  });
  
}

function js_consultaTabelas(iCodigoModulo) {

	$('sCampoOrdem').options.length = 0;
	$('sOrdem').selectedIndex       = 0;
  oComboTabela.clearItens();
	oGridCampos.clearAll(true);	
	$('ctnGridResultados').innerHTML = '';
	
  js_divCarregando("Buscando Tabelas Deste Módulo...", "msgBox");

  var oParam           = new Object();
  oParam.sExec         = "consultaTabelas";
  oParam.iCodigoModulo = iCodigoModulo;

  var oAjax = new Ajax.Request (sUrlRpc,{ method:'post',
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete:js_retornaTabelas});
  
}

function js_retornaTabelas(oAjax) {

  js_removeObj("msgBox");
  
  var oRetorno = eval("("+oAjax.responseText+")");

  oComboTabela.clearItens();
  
  if (oRetorno.iStatus == 1) {

    oRetorno.aTabelas.each(function (oTabela) {
      oComboTabela.addItem(oTabela.iCodigoTabela, oTabela.sNomeTabela);     
    });

    if (oRetorno.aTabelas.length > 0) {
      js_consultaCampos(oRetorno.aTabelas[0].iCodigoTabela);
    }   
  }  
}

function js_consultaCampos(iCodigoTabela) {
  
  js_divCarregando("Buscando Campos Desta Tabela...", "msgBox");
  
  var oParam           = new Object();
  oParam.sExec         = "consultaCampos";
  oParam.iCodigoTabela = iCodigoTabela;
  
  var oAjax = new Ajax.Request (sUrlRpc,{ method:'post',
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornaCampos});
  
  
}

function js_retornaCampos(oAjax) {

  js_removeObj("msgBox");
    
  var oRetorno     = eval("("+oAjax.responseText+")");
  var sElementos   = '';
  sCampoPrimaryKey = '';
  aListaCampos     = new Array();
  aCampos    = {};
  
  if (oRetorno.iStatus == 1) {

    oGridCampos.clearAll(true);

    $('sCampoOrdem').options.length = 0;

    oRetorno.aCampos.each(function(oCampo) {

      if (oCampo.lPrimaryKey) {
        sCampoPrimaryKey = oCampo.sNomeCampo;
      }
      aCampos[ oCampo.sNomeCampo ] = '';
      
      var oSelect = $('sCampoOrdem');
      var oOption = document.createElement('option');
  
      oOption.text = oCampo.sNomeCampo;
      oOption.value = oCampo.sNomeCampo;
      oSelect.add(oOption);
      
      aColunas = new Array();

      aColunas[0] = oCampo.iCodigoCampo;
      aColunas[1] = oCampo.sNomeCampo;

      $iTamanhoCampo = '';
      
      if (oCampo.iTamanhoCampo > 0 && (oCampo.sTipoCampo == 'integer' || oCampo.sTipoCampo == 'oid')) {
    	  $iTamanhoCampo = new Number(oCampo.iTamanhoCampo - 1 );
		  } else {
			  $iTamanhoCampo = oCampo.iTamanhoCampo;
		  }      
      
      if (oCampo.sTipoCampo == 'integer' || oCampo.sTipoCampo == 'oid') {    	  
        
        sElementos  = "<input maxlength='"+$iTamanhoCampo+"' type='text' name='"+oCampo.sNomeCampo+"' id='"+oCampo.sNomeCampo+"_1' value='' onblur=\"js_verificaValor('"+oCampo.sNomeCampo+"')\" onkeyup=\"js_ValidaCampos(this,1,'"+oCampo.sNomeCampo+"_1','f','f',event)\">";
        sElementos += " até ";
        sElementos += "<input maxlength='"+$iTamanhoCampo+"' type='text' name='"+oCampo.sNomeCampo+"' id='"+oCampo.sNomeCampo+"_2' value='' onblur=\"js_verificaValor('"+oCampo.sNomeCampo+"')\" onkeyup=\"js_ValidaCampos(this,1,'"+oCampo.sNomeCampo+"_2','f','f',event)\">";
        
      } else if (oCampo.sTipoCampo == 'char') {

        sElementos = "<input maxlength='"+$iTamanhoCampo+"' type='text' name='"+oCampo.sNomeCampo+"' id='"+oCampo.sNomeCampo+"' style='width: 347px;' value=''>";
        
      } else if (oCampo.sTipoCampo == 'boolean') {
        
        sElementos  = "<select name='"+oCampo.sNomeCampo+"' id='"+oCampo.sNomeCampo+"' style='width: 347px;' value=''>";
        sElementos +=   "<option value=''>Selecione</option>";
        sElementos +=   "<option value='true'>Sim</option>";
        sElementos +=   "<option value='false'>Não</option>";
        sElementos += "</select>";
        
      } else if (oCampo.sTipoCampo == 'date') {

    	  sElementos  = "<input maxlength='"+$iTamanhoCampo+"' type='text' name='"+oCampo.sNomeCampo+"' id='"+oCampo.sNomeCampo+"' style='width: 347px;' value='' onBlur='js_validaDbData(this);' onKeyUp='return js_mascaraData(this,event)' onFocus='js_validaEntrada(this);'>";
        sElementos += "<input name='"+oCampo.sNomeCampo+"_dia' id='"+oCampo.sNomeCampo+"_dia'   type='hidden' value='' size='2'  maxlength='2' >";
        sElementos += "<input name='"+oCampo.sNomeCampo+"_mes' id='"+oCampo.sNomeCampo+"_mes'   type='hidden' value='' size='2'  maxlength='2' >";
        sElementos += "<input name='"+oCampo.sNomeCampo+"_ano' id='"+oCampo.sNomeCampo+"_ano'   type='hidden' value='' size='4'  maxlength='4' >";
        
      } else {
        
        sElementos = "<input maxlength='"+$iTamanhoCampo+"' type='text' name='"+oCampo.sNomeCampo+"' id='"+oCampo.sNomeCampo+"' style='width: 347px;' value=''>";
      } 
      
      aColunas[2] = sElementos;
      aColunas[3] = oCampo.sTipoCampo;
      oGridCampos.addRow(aColunas);
    });

    oGridCampos.renderRows();

    aCabecalho    = new Array();
    aCabecalho[0] = 'Correto';
    aCabecalho[1] = 'Errado';
    aCabecalho[2] = 'Exclui';
    aCabecalho[3] = sCampoPrimaryKey;

    var iIndice = 4;
    aListaCampos.push(sCampoPrimaryKey);
    oRetorno.aCampos.each(function(oCampo) {      

      if (oCampo.lPrimaryKey) {
        return;
      }
      
      aListaCampos.push(oCampo.sNomeCampo);  
      aCabecalho[iIndice] = oCampo.sNomeCampo;
      iIndice++;
    });
    
    oGridResultados              = new DBGrid('Resultados');
    oGridResultados.nameInstance = 'oGridResultados';
    oGridResultados.allowSelectColumns(true);
    oGridResultados.setCellWidth(new Array('8%', '8%'));
    oGridResultados.setCellAlign(new Array('center', 'center'));
    oGridResultados.setHeader(aCabecalho);

    oGridResultados.aHeaders.each(function (oHeader, iIndice) {

      if (iIndice > 4) {
        oGridResultados.aHeaders[iIndice].lDisplayed = false;
      }
    });
    
    oGridResultados.show($('ctnGridResultados'));
    oGridResultados.clearAll(true); 
  } 
  
}

function js_verificaValor(sIdCampo) {

	if ($F(sIdCampo+'_1') != '' && $F(sIdCampo+'_2') != '') {

		if (parseInt($F(sIdCampo+'_1')) > parseInt($F(sIdCampo+'_2'))) {
			alert('Valor inicial não pode ser maior que o final');
			$(sIdCampo+'_1').value = '';
			$(sIdCampo+'_2').value = '';
			return false;
		}
		
	}
	
}

</script>