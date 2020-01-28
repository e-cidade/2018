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

/**
 * 
 * @author I
 * @revision $Author: dbalberto $
 * @version $Revision: 1.15 $
 */
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

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
	
$clrotulo  = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
?>
<body bgcolor="#cccccc">
<form name="form1" method="post" action="">

<fieldset style="width: 650px; margin: 25px auto 0 auto">
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
				db_input('anofolha',4,$IDBtxt23,true,'text',2,"onChange='js_validaTipoPonto(true);js_periodoFolha();'");
			?>
			&nbsp;/&nbsp;
			<?php
				db_input('mesfolha',2,$IDBtxt25,true,'text',2,"onChange='js_validaTipoPonto(true);js_periodoFolha();'");
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
    
		<tr id='linhaComplementar' style='display:none'>
			<td>&nbsp;</td>
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

<div style="text-align:center; margin: 10px auto">
	<input name="gera" id="gera" type="button" value="Processar" onClick="js_verifica();">
</div>

</form>

<div style='display: none' id='linhaRescisoes'>
  <fieldset style="width: 650px; margin: 20px auto">
    <legend><strong>Rescisões</strong></legend>
    <div id='ctnGridRescisoes'></div> 
  </fieldset>
</div>

</body>
</html>   
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?> 
<script>

js_periodoFolha();

$('tipo').style.width  = '100px'; 
$('ponto').style.width = '100px';

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

function js_consultaPontoComplementar(){
	
	js_divCarregando('Consultando ponto complementar...','msgBox');
	js_bloqueiaTela(true);
	var sQuery  = 'sMethod=consultaPontoComplementar';
	    sQuery += '&iAnoFolha='+$F('anofolha');
	    sQuery += '&iMesFolha='+$F('mesfolha');
	    sQuery += '&sSigla='+$F('ponto'); 
	    sQuery += '&lNaoExibeComplementarZero=true';  
	  
	var oAjax   = new Ajax.Request( sUrl, {
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

function js_validaTipoPonto(lCarregaCombo) {
 
	if ( $F('ponto') == 'r48') {
	
	  js_consultaPontoComplementar();
	  $('linhaRescisoes').style.display = 'none';
	  
	} else if ($F('ponto') == 'r20' && $F('tipo') == 1) {
		
	  $('linhaComplementar').style.display = 'none';
	  js_getRescisoes();
	   
	} else {
	
	  $('linhaRescisoes').style.display = 'none';
	  $('linhaComplementar').style.display = 'none';
	  
	}

	/**
	* Tipo previdência
	*/
	if ( $F('tipo') == '2' ) {
	 
		$('tabelaEmpenhos').style.display = '';
		js_periodoFolha();
	 
	} else {
		
		$('tabelaEmpenhos').style.display = 'none';
	 
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
	location.href = 'pes4_liberarempenhos001.php?iAno=' + iAno + '&iMes=' + iMes + '&iTipo=' + iTipo;
 }
 
 function js_verifica(){
 
   if ( $F('anofolha') == '' || $F('mesfolha') == '' ) {
     alert('Ano / Mês não informado!');
     return false;
   }
   if ($F('ponto') == 'r20'  && $F('tipo') == 1) {
     
     if (oGridrescisoes.getSelection().lenght == 0) {
     
       alert('selecione alguma rescisão para continuar.');
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

	if ( $F('ponto') == 'r48' ) {
	  if ($('semestre')) {
	    oParam.sSemestre = $F('semestre');
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
		
  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_liberarempenhos',
                      sNomeArquivo+'?json='+js_getQueryTela()+'&lBotao=true',
                      'Liberar Empenhos/Slip Folha - '+$F('mesfolha')+'/'+$F('anofolha'),
                      true);
                       
}

function js_getRescisoes() {
  
  $('linhaRescisoes').style.display = '';
  js_divCarregando('Pesquisando Rescisoes','msgBox');
  js_bloqueiaTela(true); 
  var sQuery  = 'sMethod=getRescisoesNaoEmpenhadas';
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