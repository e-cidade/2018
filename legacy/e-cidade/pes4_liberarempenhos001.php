<?php

/**
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

/**
 * Representa a tela da liberação do empenho/slips.
 * 
 * @author $Author: dbjeferson.belmiro $
 * @version $Revision: 1.29 $
 */

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet = db_utils::postMemory($_GET);

$anofolha = db_anofolha();
$mesfolha = db_mesfolha();

if (isset($oGet->iTipo) && $oGet->iTipo == 2 ) {

	$tipo     = 2;
	$anofolha = $oGet->iAno;
	$mesfolha = $oGet->iMes;
}

$oRotulo = new rotulocampo();
$oRotulo->label("rh72_projativ");
$oRotulo->label("rh72_codele");
$oRotulo->label("o56_codele");
$oRotulo->label("rh72_recurso");
$oRotulo->label("rh72_coddot");
$oRotulo->label("z01_numcgm");
$oRotulo->label("z01_nome");
?>
<html>
<head>
<?
db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js");
db_app::load("grid.style.css, estilos.css");
db_app::load("dbcomboBox.widget.js");
db_app::load("DBViewFormularioFolha/CompetenciaFolha.js");
db_app::load("DBViewFormularioFolha/ValidarFolhaPagamento.js");
	
$clrotulo  = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
?>
<body bgcolor="#cccccc">
<form name="form1" method="post" action="">
<input type="hidden" value="<?= DBPessoal::verificarUtilizacaoEstruturaSuplementar() ? "1" : "0"; ?>" id="db_complementar" name = 'db_complementar' >

<fieldset style="width: 635px; margin: 25px auto 0 auto">
	<legend>
		<b>Liberar Empenhos/Slips Para</b>
	</legend>
	<table align="center">
		<tr>
			<td width="50%">
				<b>Ano / Mês :</b>
			</td>
			<td>
			<?php
				db_input('anofolha',4,$IDBtxt23,true,'text',2,"onChange='js_validaTipoPonto(false);'");
			?>
			&nbsp;/&nbsp;
			<?php
				db_input('mesfolha',2,$IDBtxt25,true,'text',2,"onChange='js_validaTipoPonto(false);'");
			?>
			</td>
		</tr>
      
    <tr>
      <td>
        <b>Tipo:</b>
      </td>
      <td>
       <?
       
         $aTipos = array(
                         "1" => "Salário        ",
                         "2" => "Previdência    ",
                         "3" => "FGTS           ",
                        );
         
         db_select('tipo',$aTipos,true,4, "onChange='js_validaTipoPonto(true)'");
       ?>
      </td>
    </tr> 
      
    <tr>
      <td>
        <b>Ponto:</b>
      </td>
      <td>
       <?
       
         $aSigla = array();
         
         db_select('ponto',$aSigla,true,4,"onChange='js_validaTipoPonto(false)'");
       ?>
      </td>
    </tr>

    <tr style="display: none;" id="ComboContainer">
      <td align='left' title="Número da folha de pagamento">
        <strong>Número:</strong>
      </td>
      <td id="ComboContent">
      </td>
    </tr>
    
    
	</table>
	<table>		
		<tr id="tabelaEmpenhos" style="display:none;">
			<td colspan="2">
			<input type="hidden" id="empenhosAnoFolha" value="<?php echo $anofolha; ?>" />
			<input type="hidden" id="empenhosMesFolha" value="<?php echo $mesfolha; ?>" />
			
			<fieldset>
				<legend><strong>Previdências</strong></legend>
				<?php
					$sql  = "select distinct r33_codtab,              ";
					$sql .= "                r33_nome                 ";
					$sql .= "           from inssirf                  ";
					$sql .= "          where r33_anousu = {$anofolha} "; 
					$sql .= "            and r33_mesusu = {$mesfolha} ";
					$sql .= "            and r33_codtab > 2           ";
					$sql .= "            and r33_instit = ".db_getsession('DB_instit') ;
					
					$rsPrev = db_query($sql);
					
					db_multiploselect("r33_codtab", "r33_nome", "previdenciaNaoSelecionados", "previdenciaSelecionados", $rsPrev, array(), 4, 250);
				?>
			</fieldset>
			</td>
		</tr>
	</table> 
</fieldset>

<div style='display: none' id='linhaRescisoes'>
  <fieldset style="width: 635px; margin: 0px auto;" id="filtroRescisao">

    <legend>Filtrar por data de Rescisão</legend>
    <table border="0" align="center">
      <tr>
        <td>
          <strong>Data Inicial:</strong>
        </td>
        <td>
          <?php 
            db_inputdata("sDataInicial", null, null, null, true, 'text', 1);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <strong>Data Final:</strong>
        </td>
        <td>
          <?php 
            db_inputdata("sDataFinal", null, null, null, true, 'text', 1);
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center">
          <input type="button" name="filtrar" value="Filtrar" onclick="js_getRescisoes()" />
        </td>
      </tr>
    </table>
  </fieldset>

  <fieldset style="width: 615px; margin: 0px auto">
    <legend><strong>Rescisões</strong></legend>
    <div id='ctnGridRescisoes'></div> 
  </fieldset>
</div>

<div style="text-align:center; margin: 10px auto">
  <input name="gera" id="gera" type="button" value="Processar" onClick="js_verifica();">
</div>

</form>

</body>
</html>   
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?> 
<script>

// desktop, remove loading criado antes de dar location.href
if (CurrentWindow && CurrentWindow.ECIDADE_DESKTOP) {
  js_removeObj("msgBox");
}

js_periodoFolha();

$('tipo').style.width  = '100px'; 
$('ponto').style.width = '100px';

var sUrl     = 'pes1_rhempenhofolhaRPC.php';
var MENSAGEM = 'recursoshumanos/pessoal/pes4_liberarempenhos.';

js_montaCombo();

  function js_montaCombo() {
  
  	var aPonto = new Array();
  	
  	var oComboPonto = document.getElementById('ponto');
  
  	oComboPonto.addClassName('DBSelectMultiplo');
  
  	oComboPonto.options.length = 0;
    
  	if ($F('tipo') == '1') {
  
  		aPonto = new Array({chave: 'r14' , valor: 'Salário'}, 
  		   								 {chave: 'r48' , valor: 'Complementar'},
  		   							   {chave: 'r35' , valor: '13o. Salário'},
  		   							   {chave: 'r20' , valor: 'Rescisão'},
  		   							   {chave: 'r22' , valor: 'Adiantamento'},
                         {chave: 'sup' , valor: 'Suplementar'});
      
      var lDbComplementar = $('db_complementar').getValue();
      
      if (lDbComplementar != true) {
        aPonto.splice(5, 1);
      }                                    
  
  	}	else {
  
  		aPonto = new Array({chave: 'r14,r48,r20'     , valor: 'Mensal'}, 
  		                   {chave: 'r35'             , valor: '13o Salário'});
          
  	}	
  
  	for (var iIndice = 0; iIndice < aPonto.length; iIndice++) {
  		
  		var oValor    = aPonto[iIndice];
  		var oOption   = document.createElement("option");
  		
  		oOption.value = oValor.chave;
  		oOption.text  = oValor.valor;
  		
  		oComboPonto.add(oOption);
  	
  	}
  }

  function js_consultaFolhaComplementar(){

    js_divCarregando( _M( MENSAGEM + 'carregando'),'msgBox', true);
    
    var oParam           = new Object();
        oParam.iAnoFolha = $F('anofolha');
        oParam.iMesFolha = $F('mesfolha');
        
    if ($F("db_complementar") == "1"){
        oParam.sMethod   = "consultaComplementaresFechadas";
    } else {
        oParam.sMethod   = "consultaPontoComplementar";
        oParam.sSigla    = $F('ponto');
    }
  
	  new Ajax.Request( sUrl, {
	                            method    : 'post',
	                            parameters: oParam,
	                            onComplete: js_retornoFolhaPagamento
	                          }
	                  );
  }

  function js_consultaFolhaSuplementar(){
  
    js_divCarregando(_M( MENSAGEM + 'carregando'), 'msgBox', true);
  
    var oParam           = new Object();
        oParam.sMethod   = "consultaSuplementaresFechadas";
        oParam.iAnoFolha = $F('anofolha');
        oParam.iMesFolha = $F('mesfolha');
  
    new Ajax.Request( sUrl, {
                              method    : 'post',
	                            parameters: oParam,
	                            onComplete: js_retornoFolhaPagamento
	                          }
	                  );
  }

  function js_retornoFolhaPagamento(oAjax){
  
    js_removeObj("msgBox");
  
    var aRetorno = eval("("+oAjax.responseText+")");
    
    if (aRetorno.lErro) {
      
      $('gera').disabled = true;
      alert(aRetorno.sMsg.urlDecode());
      return false;
    }
    
    var iLinhasSemestre = aRetorno.aSemestre.length;
  
    if (iLinhasSemestre > 0) {
      
      var oDBComboBox = new DBComboBox('semestre', null, []);
      
      for (var iIndice = 0 ; iIndice < iLinhasSemestre; iIndice++) {
       
        var oSemestre   = aRetorno.aSemestre[iIndice];
       
        if ($F("db_complementar") == "1"){    
          oDBComboBox.addItem(oSemestre, oSemestre);
        } else {
          oDBComboBox.addItem(oSemestre.semestre, oSemestre.semestre);
        }
      }
      
      oDBComboBox.sStyle = "width: 100px;";  
      oDBComboBox.show($('ComboContent'));
      
    } else {
      
      var sLinha  = " <td> ";
          sLinha += "   <font color='red'>Sem folha.</font> ";
          sLinha += " </td> ";
      $('ComboContent').innerHTML = sLinha;
      $('gera').disabled          = true;
      
    }
  
    $('ComboContainer').style.display = '';
    
  }

  function js_validaTipoPonto(lCarregaCombo) {
  
    js_limparLayout();
    
    var iAnoInformado  = $("anofolha").getValue();
    var iMesInformado  = $("mesfolha").getValue();
    var oCompetencia   = new DBViewFormularioFolha.CompetenciaFolha(false);
    var lCompetencia   = oCompetencia.isCompetenciaValida(iAnoInformado, iMesInformado);
    
    if (!lCompetencia) {
      
      $('gera').disabled = true;
      alert(_M(MENSAGEM + 'competencia_invalida'));
      return false;
    }
  
    /**
	   * Tipo salário.
	   */
    if ($F('tipo') == '1') {
    
	    if ($F('ponto') == 'r48') {
	      js_consultaFolhaComplementar();
	    } else if ($F('ponto') == 'r20') {
	      js_getRescisoes();
	    } else if ($F('ponto') == 'sup') {
        js_consultaFolhaSuplementar(); 
      }
      
    }
    
	  /**
	   * Tipo previdência.
	   */
	  if ( $F('tipo') == '2' ) {
      
	  	$('tabelaEmpenhos').style.display = '';
	  	js_periodoFolha();
	  }
    
	  if (lCarregaCombo) 
		  js_montaCombo();

  }

  function js_periodoFolha() {
  
  	var iTipo     = $F('tipo');
    var iAno      = parseFloat($F('anofolha'), 10);
    var iMes      = parseFloat($F('mesfolha'), 10);
  	var iAnoFolha = $F('empenhosAnoFolha');
  	var iMesFolha = $F('empenhosMesFolha');
    
  	if (iTipo != 2) {
  		return false;
  	}
  
  	if($F('anofolha').length != 4 || $F('mesfolha').length < 1 || $F('mesfolha').length > 2) {
  		return false;
  	}
  
  	if ( iAno == iAnoFolha && iMes == iMesFolha) {
      
  		if (iMes < 10) {
  			$('mesfolha').value = '0'+iMes;
  		}
  		$('tabelaEmpenhos').style.display = '';
  		return false;
  	}
    
  	js_divCarregando('Pesquisando previdências','msgBox');
  	location.href = 'pes4_liberarempenhos001.php?iAno=' + $F('anofolha') + '&iMes=' + $F('mesfolha') + '&iTipo=' + iTipo;
  }
 
  function js_verifica(){
 
    if ( $F('anofolha') == '' || $F('mesfolha') == '' ) {
      alert('Ano / Mês não informado!');
      return false;
    }
    if ($F('ponto') == 'r20'  && $F('tipo') == 1) {
      
      if (oGridrescisoes.getSelection().length == 0) {
      
        alert('selecione alguma rescisão para continuar.');
        return false;
      }
    }
    
    if ( $F('ponto') == 'r48') {
      if (!$('semestre') || $F('semestre') == "0") {
        
       alert("Complementar em aberto. Execute o fechamento.");
	 	   return false;
	    } 
	  }
   
    if ($F("db_complementar") == "1" && $F('ponto') == 'r14') {
      
      var iMesFolha = $F('mesfolha'); 
      var iAnoFolha = $F('anofolha');
         
      var oFolhaPagamento = new DBViewFormularioFolha.ValidarFolhaPagamento();
      var lFolhaSalario   = oFolhaPagamento.verificarFolhaPagamentoAberta(oFolhaPagamento.TIPO_FOLHA_SALARIO, iAnoFolha, iMesFolha);
         
      if (lFolhaSalario == true){
        
        alert(_M(MENSAGEM + 'folha_salario_fechada'));
        return false;
      }
    } 
   
    js_liberarEmpenhos();
 
  }
 
function js_bloqueiaTela(lBloq){
 
  if ( lBloq ) {
    $('anofolha').disabled = true;         
    $('mesfolha').disabled = true;
    $('ponto').disabled    = true;
    $('gera').disabled     = true;
    $('tipo').disabled     = true;
    
    if ($F('ponto') == 'r48') {
      if ($('semestre')) {
        $('semestre').disabled = true;
      } 
    }     
    
  } else {
    $('anofolha').disabled = false;         
    $('mesfolha').disabled = false;
    $('ponto').disabled    = false;
    $('gera').disabled     = false;
    $('tipo').disabled     = false;
    if ($F('ponto') == 'r48') {
      if ($('semestre')) {
        $('semestre').disabled = false;
      }
    }
       
  }
  
}
 
function js_getQueryTela(sMethod) {

  var oParam       = new Object();
  oParam.exec      = sMethod;
  oParam.sMethod   = sMethod;
  oParam.iAnoFolha = $F('anofolha');
  oParam.iMesFolha = $F('mesfolha');
  oParam.sSigla    = $F('ponto');
  oParam.iTipo     = $F('tipo');
  oParam.sSemestre = "0";        
  oParam.sPrevidencia = '';

  aPrevidenciaSelecionados = $('previdenciaSelecionados').options;

  if ( aPrevidenciaSelecionados.length > 0) {

    for ( var iPrevidencia = 0; iPrevidencia < aPrevidenciaSelecionados.length; iPrevidencia++) {

	    if (iPrevidencia > 0) {
		    oParam.sPrevidencia += ',';
	    }
	    oParam.sPrevidencia += (aPrevidenciaSelecionados[iPrevidencia].value) - 2;
	    
    }
  }
  
	if ( $F('ponto') == 'r48' || $F('ponto') == 'sup') {
	  if ($('semestre')) {
	    oParam.sSemestre = $F('semestre');
	  }
	}

	if ($F('ponto') == 'r20' && $F('tipo') == 1) {
    
    var aListarescisoes = new Array(); 
    var aRescisoes = oGridrescisoes.getSelection("object")
    if (oGridrescisoes.getSelection().length == 0) {
    
      alert('selecione alguma rescisão para continuar.');
      return false;
    } else {
      aRescisoes.each(function(oRescisao, id) {
        aListarescisoes.push(oRescisao.aCells[0].getValue());
      });
    }
    oParam.aRescisoes = aListarescisoes;       
  }

  return Object.toJSON(oParam);    
}
 
function js_liberarEmpenhos() {

	var sNomeArquivo;
	
 	lErro = false;
	if ( $F('ponto') == 'r48') { 
	 
	 if (!$('semestre')) {
		 lErro = true;
	 } else if ($F('semestre') == '0') {
		 lErro = true;
	 }

	 if (lErro) {
		 alert('Sem complementar encerrada para esse período.');
		 return false;
	 }	 
	 
  }

	if ($F('tipo') == '1') { 
    sNomeArquivo = 'pes4_liberarempenhosfolha002.php';
	}else {
		sNomeArquivo = 'pes4_liberarempenhosfolha003.php';
	}	
		
  js_OpenJanelaIframe('CurrentWindow.corpo',
                      'db_iframe_liberarempenhos',
                      sNomeArquivo+'?json='+js_getQueryTela()+'&lBotao=true',
                      'Liberar Empenhos/Slip Folha - '+$F('mesfolha')+'/'+$F('anofolha'),
                      true);
                       
}

function js_getRescisoes() {
  
  $('filtroRescisao').style.display    = '';
  
  var sDataInicial = $F('sDataInicial'),
      sDataFinal   = $F('sDataFinal');  
  
  if (js_comparadata(sDataInicial, sDataFinal, '>')) {
       
    alert ("A data final deve ser menor que a data inicial.");
    return false;
  }

  if ((sDataInicial || sDataFinal) && (!sDataInicial || !sDataFinal)) {
    alert( "Campo Data " + (sDataInicial ? 'Final' : 'Inicial') + " é de preenchimento obrigatório." );
    return false;
  }
  
  $('linhaRescisoes').style.display = '';

  js_divCarregando('Pesquisando Rescisoes','msgBox');
  js_bloqueiaTela(true); 

  var sQuery  = 'sMethod=getRescisoesNaoEmpenhadas';
      sQuery += '&iAnoFolha='+$F('anofolha');
      sQuery += '&iMesFolha='+$F('mesfolha');
      sQuery += '&sDataInicial=' + sDataInicial;
      sQuery += '&sDataFinal=' + sDataFinal;
      sQuery += '&sSigla='+$F('ponto');

  var oAjax   = new Ajax.Request( sUrl, {
                                           method: 'post', 
                                           parameters: sQuery, 
                                           onComplete: js_retornoGetRescisoes
                                         }
                                 );    

}
 
function js_retornoGetRescisoes(oAjax) {

  js_removeObj('msgBox');
  js_bloqueiaTela(false);
  oGridrescisoes.clearAll(true);
  var oRetorno = eval("("+oAjax.responseText+")");
  oRetorno.sListaRescisoes.each(function (oRescisao, id) {
  
     var aLinha = new Array();
     aLinha[0]  = oRescisao.seqpes;
     aLinha[1]  = oRescisao.matricula;
     aLinha[2]  = oRescisao.nome.urlDecode();  
     aLinha[3]  = js_formatar(oRescisao.datarescisao,'d');
     oGridrescisoes.addRow(aLinha);  
  });
  oGridrescisoes.renderRows();
}

function js_montaGrid() {

  oGridrescisoes     = new DBGrid('gridRescisoes');
  oGridrescisoes.nameInstance = "oGridrescisoes";
  oGridrescisoes.setCheckbox(0);
  oGridrescisoes.setCellAlign(new Array("right","right","Left","center"));
  oGridrescisoes.setCellWidth(new Array("4%","20%","66%","20%"));
  oGridrescisoes.setHeader(new Array("Seq","Mátricula","Nome","Data"));
  oGridrescisoes.show($('ctnGridRescisoes'));
}

  /**
   * Método responsável por limpar as DIV da tela. 
   */
  function js_limparLayout() {
    
    $('gera').disabled                = false;
    $('linhaRescisoes').style.display = 'none';
    $('ComboContainer').style.display = 'none';
    $('tabelaEmpenhos').style.display = 'none';
    $('sDataInicial').value           = '';
    $('sDataFinal').value             = '';
  }

js_montaGrid();
</script>
