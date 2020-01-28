<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("model/pessoal/Ferias.model.php");

$oGet = db_utils::postMemory($_GET);

if(!isset($oGet->matricula) || $oGet->matricula == '') {
  db_redireciona('db_erros.php?db_erro=Matrícula não informada.');
}


?>

<html>
<head>

<?php 
  db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js, DBHint.widget.js');
  db_app::load('estilos.css, grid.style.css');
?>
</head>

<body>
  
  <fieldset>
  	<legend><strong>Gozo de Férias</strong></legend>
  	
    <div id="gridFerias"></div>
  	
  	
  </fieldset>

	<center>
		<input type="button" name="fechar" id="fechar" value="Fechar" onclick="js_fechar()" />
	</center>
	
<script>

js_consultaFeriasMatricula(<?php echo $oGet->matricula;?>);
js_initTable();

function js_consultaFeriasMatricula(iMatricula) {

	var oParam = new Object();
	
	oParam.sExec      = 'consultaFeriasMatricula';
	oParam.iMatricula = iMatricula;

	js_divCarregando('Aguarde, pesquisando férias registradas.', 'msgbox');
	var oAjax = new Ajax.Request(
	    												 'pes4_cadastroferias.RPC.php', 
															 {
																method: 'POST',
																parameters: 'json='+Object.toJSON(oParam),
																onComplete: js_montaGridFerias
															 });
}

function js_montaGridFerias(oAjax) {
  
  var oRetorno = eval("("+oAjax.responseText+")");

  var iIndiceLinha = 0;

  var aGridHint = new Array();

  var iIndiceGridHint = 0;
  
  js_removeObj('msgbox');

  if (oRetorno.iStatus == 1) {

		oDataGrid.clearAll(true);
		
		for (var iFerias = 0; iFerias < oRetorno.aFerias.length; iFerias++) {

			with (oRetorno.aFerias[iFerias]) {

				aLinha 		= new Array();

				aLinha[0]  = dPeriodoAquisitivoInicial + '-' + dPeriodoAquisitivoFinal;
				aLinha[1]  = dPeriodoEspecificoInicial + '-' + dPeriodoEspecificoFinal;
				aLinha[2]  = iDiasDireito;
				aLinha[3]  = iFaltas;
				aLinha[4]  = '';
				aLinha[5]  = '';
				aLinha[6]  = '';
				aLinha[7]  = '';
				aLinha[8]  = '';
				aLinha[9]  = '';
				
				oDataGrid.addRow(aLinha);

				oDataGrid.aRows[iIndiceLinha].sStyle = 'font-weight: bold;background-color:#CCCCCC; border: 1px solid #CCCCCC;';
				
				iIndiceLinha++;
				
				for(var iPeriodos = 0; iPeriodos < aPeriodos.length; iPeriodos++) {
					
				  with(aPeriodos[iPeriodos]) {
					  
  					aLinhaPeriodo	= new Array();

  					aLinhaPeriodo[0]  = '';
  					aLinhaPeriodo[1]  = '';
  					aLinhaPeriodo[2]  = '';
  					aLinhaPeriodo[3]  = '';
  					aLinhaPeriodo[4]  = iDiasGozo;
  					aLinhaPeriodo[5]  = iDiasAbono;
  					aLinhaPeriodo[6]  = dPeriodoInicial + ' - ' + dPeriodoFinal;
  					aLinhaPeriodo[7]  = iAnoPagamento   + ' / ' + (iMesPagamento < 10 ? '0' + iMesPagamento : iMesPagamento);
  					aLinhaPeriodo[8]  = sTipoPonto;

  					if (sObservacao.urlDecode().length > 60) {
  					  aLinhaPeriodo[9]  = sObservacao.urlDecode().substr(0, 50) + '...';
  					} else {
  						aLinhaPeriodo[9]  = sObservacao.urlDecode();
  					}
  
  					oDataGrid.addRow(aLinhaPeriodo);

  					oGridHint 						     = new Object();
  					oGridHint.iIdLinha         = iIndiceLinha;
  					oGridHint.sObservacao      = sObservacao.urlDecode();
  					aGridHint[iIndiceGridHint] = oGridHint;
  					iIndiceGridHint++;
				
				  }
					iIndiceLinha++;
				}
			}
		}


		oDataGrid.renderRows();
		
		oDataGrid.setNumRows(iFerias);

		for(var iHint = 0; iHint < aGridHint.length; iHint++) {
			with(aGridHint[iHint]) {
			  var oCelulaObservacao = $(oDataGrid.aRows[iIdLinha].aCells[9].sId);
        var oDBHint = eval("oDBHint_"+iIdLinha+'_'+iHint+" = new DBHint('oDBHint_"+iIdLinha+'_'+iHint+"')");

  			oDBHint.setWidth(350);
        oDBHint.setText(sObservacao);
        oDBHint.setShowEvents(["onmouseover"]);
        oDBHint.setHideEvents(["onmouseout"]);
        oDBHint.setPosition('B', 'L');
        oDBHint.make(oCelulaObservacao);
			}	
		}

  } else {

		alert(oRetorno.sMessage.urlDecode()); 
		parent.db_iframe_ferias.hide();  
		
  }
  
}

function js_initTable () {
  
  oDataGrid 						 = new DBGrid('gridFerias');
  oDataGrid.nameInstance = 'oDataGrid';
  oDataGrid.setCellAlign(new Array('center', 'center', 'center', 'center', 'center', 'center', 'center', 'center', 'center', 'left'));
  oDataGrid.setCellWidth(new Array('10%', '10%', '6%', '6%', '6%', '6%', '10%', '10%', '8%', '28%'));
  oDataGrid.setHeader   (new Array('Período Aquisitivo',
      														 'Período Específico',
      														 'Dias de Direito',
      														 'Faltas',
      														 'Dias de Gozo',
      														 'Dias de Abono',
      														 'Período de Gozo',
      														 'Ano/Mês de Pagamento',
      														 'Ponto',
      														 'Observações'));
  oDataGrid.setHeight(500);
  oDataGrid.show($('gridFerias'));
}

function js_fechar() {
  parent.db_iframe_ferias.hide();
}
</script>  
</body>
</html>