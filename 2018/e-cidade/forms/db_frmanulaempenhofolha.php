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
 * Representa a tela da anulação do empenho gerado.
 * 
 * @author $Author: dbmarcos $
 * @version $Revision: 1.20 $
 */

$clrotulo  = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
?>
<form name="form1" method="post" action="">
  <input type="hidden" value="<?php echo DBPessoal::verificarUtilizacaoEstruturaSuplementar() ? '1' : '0'; ?>" id="db_complementar" name = 'db_complementar' >

  <fieldset style="width: 350px; margin: 20px auto 5px auto">
    <legend align="center">
      <strong>Anular Empenhos Gerados</strong>
    </legend>
		<table align="center">
			<tr>
				<td>
					<strong>Ano / Mês :</strong>
				</td>
				<td>
					<?
						$anofolha = db_anofolha();
						db_input('anofolha',4,$IDBtxt23,true,'text',2,"onChange='js_validaTipoPonto(false)'");
					?>
					&nbsp;/&nbsp;
					<?
						$mesfolha = db_mesfolha();
						db_input('mesfolha',2,$IDBtxt25,true,'text',2,"onChange='js_validaTipoPonto(false)'");
					?>
				</td>
			</tr>

			<tr>
				<td>
					<strong>Tipo:</strong>
				</td>
				<td>
					<?
						$aTipos = array("1" => "Salário        ",
                            "2" => "Previdência    ",
                            "3" => "FGTS           ");
						db_select('tipo', $aTipos, true, 1, "onChange='js_validaTipoPonto(true)'; style='width:103px;'");
					?>
				</td>
			</tr>

			<tr>
				<td>
					<strong>Ponto:</strong>
				</td>
				<td>
					<?
						$aSigla = array();
						db_select('ponto',$aSigla,true,4,"onChange='js_validaTipoPonto(false)'; style='width:103px;'");
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
	</fieldset>

  <fieldset id="filtroRescisao" style="display: none; width: 350px; margin: 10px auto">

      <legend align="center">Filtrar por data de Rescisão</legend>
      <table border="0" width="300px" align="center">
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

</form>

<div style='display: none' id='linhaRescisoes'>
  <fieldset style="margin: 25px auto; width: 650px">
    <legend><strong>Rescisões</strong></legend>
    <div id='ctnGridRescisoes'></div>
  </fieldset>
</div>

	<div style="text-align: center;">
		<input name="gera" id="gera" type="button" value="Processar" onClick="js_anularEmpenhos();">
	</div>

<script>

  var sUrl     = 'pes1_rhempenhofolhaRPC.php';
  var MENSAGEM = 'recursoshumanos/pessoal/db_frmanulaempenhofolha.';

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
  
		  aPonto = new Array({chave: 'r14,r48,r20' , valor: 'Mensal'},
		                     {chave: 'r35'         , valor: '13o Salário'});
          
  	}	
  
  	for (var iIndice = 0; iIndice < aPonto.length; iIndice++) {
  		
  		var oValor    = aPonto[iIndice];
  		var oOption   = document.createElement("option");
  		
  		oOption.value = oValor.chave;
  		oOption.text  = oValor.valor;
  		
  		oComboPonto.add(oOption);
  	}
  }
  
  function js_consultaFolhaPagamento(iTipoFolha){
  
    js_divCarregando(_M( MENSAGEM + 'carregando'), 'msgBox', true);
  
    var oParam            = new Object();
        oParam.iAnoFolha  = $F('anofolha');
        oParam.iMesFolha  = $F('mesfolha');
        oParam.iTipoFolha = iTipoFolha;
        
    if ($F("db_complementar") == "1"){
        oParam.sMethod    = "getFolhasPagamentoEmpenhas";
    } else {
        oParam.sMethod    = "consultaPontoComplementar";
        oParam.sSigla     = $F('ponto');
    }
  
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
      
      oDBComboBox.sStyle = "width: 105px;";  
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

  function js_validaTipoPonto(lMontaCombo){

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
	      js_consultaFolhaPagamento(3);
	    } else if ($F('ponto') == 'r20') {
	      js_getRescisoes();
	    } else if ($F('ponto') == 'sup') {
        js_consultaFolhaPagamento(6); 
      }
      
    }

    if (lMontaCombo)
      js_montaCombo();
  }

  function js_anularEmpenhos(){
  
    if ($F('ponto') == 'r48') {
      if (!$('semestre') || $F('semestre') == "0") {
        
        alert('Complementar em aberto. Execute o fechamento');
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

    if (!confirm('Confirma a anulação dos Empenhos Gerados?')) {
      return false;
    }
    js_divCarregando(' Anulando Empenhos...','msgBox');
    js_bloqueiaTela(true);
  
    var oAjax   = new Ajax.Request(
                                   sUrl,
                                   {
                                   method: 'post',
                                   parameters: js_getQueryTela('anularEmpenho'),
                                   onComplete: js_retornoAnularEmpenhos
                                   }
                                  );

  }

function js_retornoAnularEmpenhos(oAjax){

  js_removeObj("msgBox");
  js_bloqueiaTela(false);

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.lErro == true) {
    alert(oRetorno.sMsg.urlDecode());
  } else {
    alert('Empenhos anulados com sucesso!');
  }
}

function js_bloqueiaTela(lBloq){

  if ( lBloq ) {
    $('anofolha').disabled = true;
    $('mesfolha').disabled = true;
    $('ponto').disabled    = true;
    $('gera').disabled     = true;

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

    if ($F('ponto') == 'r48') {
      if ($('semestre')) {
        $('semestre').disabled = false;
      }
    }

  }

}

function js_getQueryTela(sMethod) {

  var sQuery = "sMethod="+sMethod;
  sQuery += "&iAnoFolha="+$F('anofolha');
  sQuery += "&iMesFolha="+$F('mesfolha');
  sQuery += "&sSigla="+$F('ponto');
  sQuery += "&iTipo="+$F('tipo');

  if ( $F('ponto') == 'r48' || $F('ponto') == 'sup' ) {
    if ($('semestre')) {
      sQuery += "&sSemestre = "+$F('semestre');
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
    sQuery+= "&aRescisoes="+aListarescisoes;
  }

  return sQuery+"&multipart=true";
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
  js_divCarregando('Pesquisando Rescisões','msgBox');
  js_bloqueiaTela(true);
  var sQuery  = 'sMethod=getRescisoesEmpenhadas';
      sQuery += '&iAnoFolha='+$F('anofolha');
      sQuery += '&iMesFolha='+$F('mesfolha');
      sQuery += '&sDataInicial=' + $F('sDataInicial');
      sQuery += '&sDataFinal='   + $F('sDataFinal');
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
  oGridrescisoes.setHeader(new Array("Seq","Matrícula","Nome","Data"));
  oGridrescisoes.show($('ctnGridRescisoes'));
}

  /**
   * Método responsável por limpar as DIV da tela. 
   */
  function js_limparLayout() {
    
    $('gera').disabled                    = false;
    $('filtroRescisao').style.display     = 'none';
    $('linhaRescisoes').style.display     = 'none';
    $('ComboContainer').style.display     = 'none';
    $('sDataInicial').value               = '';
    $('sDataFinal').value                 = '';
  }

js_montaGrid();
</script>