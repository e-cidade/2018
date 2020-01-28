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

	$clrotulo  = new rotulocampo;
	$clrotulo->label('DBtxt23');
	$clrotulo->label('DBtxt25');
?>
<form name="form1" method="post" action="">
  <fieldset style="margin: 25px auto 10px auto; width: 650px">
    <legend>
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
						db_input('anofolha',4,$IDBtxt23,true,'text',2,"onChange='js_validaTipoPonto(true)'");
					?> 
					&nbsp;/&nbsp; 
					<?
						$mesfolha = db_mesfolha();
						db_input('mesfolha',2,$IDBtxt25,true,'text',2,"onChange='js_validaTipoPonto(true)'");
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
						db_select('tipo', $aTipos, true, 1, "onChange='js_validaTipoPonto(true)'");
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
			
			<tr id='linhaComplementar' style='display:none'> 
			</tr>
			
		</table> 
	</fieldset>
	
	<div style="text-align: center;">
		<input name="gera" id="gera" type="button" value="Processar" onClick="js_anularEmpenhos();">
	</div>
</form>

<div style='display: none' id='linhaRescisoes'>
	<fieldset style="margin: 25px auto; width: 650px">
		<legend><strong>Rescisões</strong></legend>
		<div id='ctnGridRescisoes'></div> 
	</fieldset>
</div>

<script>

var sUrl = 'pes1_rhempenhofolhaRPC.php';

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
		   							   {chave: 'r22' , valor: 'Adiantamento'});

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
 
function js_consultaPontoComplementar(){

  js_divCarregando('Consultando ponto complementar...','msgBox');
  js_bloqueiaTela(true);
  
  var sQuery  = 'sMethod=consultaPontoComplementar';
      sQuery += '&iAnoFolha='+$F('anofolha');
      sQuery += '&iMesFolha='+$F('mesfolha');
      sQuery += '&sSigla='+$F('ponto');   
      sQuery += '&iTipo='+$F('tipo');   
      
	var oAjax   = new Ajax.Request( sUrl, 
		  													  {
                                    method: 'post', 
                                    parameters: sQuery, 
                                    onComplete: js_retornoPontoComplementar
                                  }
                                );      

}

function js_retornoPontoComplementar(oAjax){

  js_removeObj("msgBox");
  js_bloqueiaTela(false);
  
  var aRetorno = eval("("+oAjax.responseText+")");
  var sExpReg  = new RegExp('\\\\n','g');
   
 
  if ( aRetorno.lErro ) {
    alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
    return false;
  }

  var sLinha          = "";
  var iLinhasSemestre = aRetorno.aSemestre.length;
  
  if ( iLinhasSemestre > 0 ) {
  
  
    sLinha += " <td align='left' title='Nro. Complementar'> ";
    sLinha += "   <strong>Nro. Complementar:</strong>       ";
    sLinha += " </td>                                       ";
    sLinha += " <td>                                        ";
    sLinha += "   <select id='semestre' name='semestre'>    ";
    sLinha += "     <option value = '0'>Todos</option>      ";
    
    for ( var iInd=0; iInd < iLinhasSemestre; iInd++ ) {
      with( aRetorno.aSemestre[iInd] ){
        sLinha += " <option value = '"+semestre+"'>"+semestre+"</option>";
      }  
    }
    
    sLinha += " </td>                                       ";
  
  } else {
  
    sLinha += " <td colspan='2' align='center'>                                ";
    sLinha += "   <font color='red'>Sem complementar para este período.</font> ";
    sLinha += " </td>                                                          ";
  
  }
  
  $('linhaComplementar').innerHTML     = sLinha;
  $('linhaComplementar').style.display = '';

}

function js_validaTipoPonto(lMontaCombo){
 
  if ( $F('ponto') == 'r48') {
  
    js_consultaPontoComplementar();
    $('linhaRescisoes').style.display = 'none';
  } else if ($F('ponto') == 'r20' && $F('tipo') == 1) {
    $('linhaComplementar').style.display = 'none';
    js_getRescisoes(); 
  } else {
  
    $('linhaComplementar').style.display = 'none';
    $('linhaRescisoes').style.display = 'none';
  }

  if (lMontaCombo)
  js_montaCombo();
   
}
 
function js_verifica(){

  if ( $F('anofolha') == '' || $F('mesfolha') == '' ) {
    alert('Ano / Mês não informado!');
    return false;
  }
  
  js_consultaEmpenhos();    

}
 
 
function js_consultaEmpenhos(){

  js_divCarregando('Verificando empenhos existentes...','msgBox');
  js_bloqueiaTela(true); 
      
  var oAjax   = new Ajax.Request( sUrl, {
                                           method: 'post', 
                                           parameters: js_getQueryTela('consultarEmpenhos'), 
                                           onComplete: js_retornoConsultaEmpenhos
                                         }
                                 );         

} 
 
function js_retornoConsultaEmpenhos(oAjax){

  js_removeObj("msgBox");
  js_bloqueiaTela(false);
 
  var aRetorno = eval("("+oAjax.responseText+")");
  var sExpReg  = new RegExp('\\\\n','g');
 
  if ( aRetorno.lErro ) {
    alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
    return false;
  } else {
     
    if ( aRetorno.lExiste ) {
      
      if (confirm('Empenhos já gerados para este período.\nReprocessar?')) {
        js_geraEmpenhos();
      }
      
    } else {
      js_geraEmpenhos();
    }
          
  }

} 
 

function js_anularEmpenhos(){

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
       
  if ( $F('ponto') == 'r48' ) {
    if ($('semestre')) {
      sQuery += "&sSemestre = "+$F('semestre');
    }
  }
  if ($F('ponto') == 'r20' && $F('tipo') == 1) {
    
    var aListarescisoes = new Array(); 
    var aRescisoes = oGridrescisoes.getSelection("object")
    if (oGridrescisoes.getSelection().lenght == 0) {
    
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
  
  $('linhaRescisoes').style.display = '';
  js_divCarregando('Pesquisando Rescisoes','msgBox');
  js_bloqueiaTela(true); 
  var sQuery  = 'sMethod=getRescisoesEmpenhadas';
      sQuery += '&iAnoFolha='+$F('anofolha');
      sQuery += '&iMesFolha='+$F('mesfolha');
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
js_montaGrid();
</script>