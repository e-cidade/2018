<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include("classes/db_termoanuproc_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

db_postmemory($_POST);
db_postmemory($_GET);

$usuario = $usu;


?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="estilos.css" >
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<style>
.table_header2  {
       font-weight:bold;text-align:left;
       padding:1px;
       border-bottom:1px outset white;
       border-right:1px outset white;           
       background-color:#EEEFF2;    
       cursor: default;   
}
.linhagrid2{
            border-right:1px inset black;
            border-bottom:1px inset black;
            cursor:default;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            text-align:right;
            background-color: #FFFFFF;
 }
</style>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/datagrid.widget.js"></script>
<script type="text/javascript">
 
	function js_consultaParcelamento() {
   js_divCarregando("Aguarde, buscando registros","msgBox");
   
   strJson = '{"exec":"getDadosParcelamento", "parcel":"<?=$parcel?>"}';
   
   var url     = 'cai4_anulaparcRPC.php';
   var oAjax   = new Ajax.Request( url, {
                                          method: 'post', 
                                          parameters: 'json='+strJson, 
                                          onComplete: js_saida
                                        }
                                 );

	}
	
function js_saida(oAjax) {

	  var aRetorno = eval("(" + oAjax.responseText + ")");
	
	  if ( aRetorno.iStatus == 2 ){
	     js_removeObj("msgBox");
	     alert(aRetorno.sMensagem.urlDecode());
	     parent.db_iframe_anulaparc1.hide();
	     return false ;
	  }

	  $('iTipoAnulacao').value   = aRetorno.iTipoAnulacao;
    if (aRetorno.iTipoAnulacao == 2) {
      document.getElementById("celPosicaoOrigem").innerHTML = "Origens ( até a data corrente)";
    } else {
      document.getElementById("celPosicaoOrigem").innerHTML = "Origens ( até a data do termo)";
    }    

    
	  document.getElementById('v21_sequencial').value = aRetorno.aListaSimulacao[0].v21_sequencial;
		oDBGridSimulaAnulacao.clearAll(true);
	  
		oDBGridSimulaAnulacao.clearAll(true);
		var aLinha = new Array();
		
		if (aRetorno.aListaSimulacao) {
		  
		  $('imprimir').disabled     = false;
		  $('aListaSimulacao').value = Object.toJSON(aRetorno.aListaSimulacao);
		  $('oTotal').value          = Object.toJSON(aRetorno.oTotal);
		  
			for (var iInd = 0; iInd < aRetorno.aListaSimulacao.length; iInd++) {
				with ( aRetorno.aListaSimulacao[iInd] ){

					aLinha[0]  = v23_numpre;
					aLinha[1]  = v23_numpar;
					aLinha[2]  = v23_receit.urlDecode();
					aLinha[3]  = js_formatar(v23_dtoper,'d','');
					aLinha[4]  = js_formatar(v23_dtvenc,'d','');
					aLinha[5]  = js_formatar(v23_valor,'f','');
					aLinha[6]  = js_formatar(grid_valor_corrigido_origem,'f','');
					aLinha[7]  = js_formatar(grid_valor_juros_origem,'f','');
					aLinha[8]  = js_formatar(grid_valor_multa_origem,'f','');
					aLinha[9]  = js_formatar(grid_valor_total_origem,'f','');
					aLinha[10] = js_formatar(v23_vlrabatido,'f','');;	
					aLinha[11] = js_formatar(grid_valor_historico_retorno,'f','');
					aLinha[12] = js_formatar(grid_valor_corrigido_retorno,'f','');
					aLinha[13] = js_formatar(grid_valor_juros_retorno,'f','');
					aLinha[14] = js_formatar(grid_valor_multa_retorno,'f','');
					aLinha[15] = js_formatar(grid_valor_total_retorno,'f','');
					
				}
				
				oDBGridSimulaAnulacao.addRow(aLinha);
			}
    
      $('valor_total_historico_origem').innerHTML  = js_formatar(aRetorno.oTotal.valor_total_historico_origem,'f',''); 
      $('valor_total_corrigido_origem').innerHTML  = js_formatar(aRetorno.oTotal.valor_total_corrigido_origem,'f','');
      $('valor_total_juros_origem').innerHTML      = js_formatar(aRetorno.oTotal.valor_total_juros_origem,'f','');
      $('valor_total_multa_origem').innerHTML      = js_formatar(aRetorno.oTotal.valor_total_multa_origem,'f','');
      $('valor_total_desconto_origem').innerHTML   = js_formatar(aRetorno.oTotal.valor_total_desconto_origem,'f','');
      $('valor_total_geral_origem').innerHTML      = js_formatar(aRetorno.oTotal.valor_total_geral_origem,'f','');
      
      $('valor_parcelas_pagas').innerHTML          = js_formatar(aRetorno.oTotal.valor_parcelas_pagas,'f','');
      $('valor_parcelas_abertas').innerHTML        = js_formatar(aRetorno.oTotal.valor_parcelas_abertas,'f','');
      $('perc_abatimento').innerHTML               = js_formatar(aRetorno.oTotal.perc_abatimento,'f','')+"%";
      $('perc_retorno').innerHTML                  = js_formatar(aRetorno.oTotal.perc_retorno,'f','')+"%";
      $('qtd_total_parcelas').innerHTML            = aRetorno.oTotal.qtd_total_parcelas;
      $('qtd_parcelas_pagas').innerHTML            = aRetorno.oTotal.qtd_parcelas_pagas;
      
      $('valor_total_historico_retorno').innerHTML = js_formatar(aRetorno.oTotal.valor_total_historico_retorno,'f','');
      $('valor_total_corrigido_retorno').innerHTML = js_formatar(aRetorno.oTotal.valor_total_corrigido_retorno,'f','');
      $('valor_total_juros_retorno').innerHTML     = js_formatar(aRetorno.oTotal.valor_total_juros_retorno,'f','');
      $('valor_total_multa_retorno').innerHTML     = js_formatar(aRetorno.oTotal.valor_total_multa_retorno,'f','');
      $('valor_desconto_retorno').innerHTML        = js_formatar(aRetorno.oTotal.valor_desconto_retorno,'f','');
      $('valor_total_geral_retorno').innerHTML     = js_formatar(aRetorno.oTotal.valor_total_geral_retorno,'f','');
               

			oDBGridSimulaAnulacao.renderRows();
			
		} else {
		  
		  $('imprimir').disabled     = true;
		  $('aListaSimulacao').value = '';
      $('oTotal').value          = '';
      
		}
		
		$('TotalForCol5').innerHTML  = js_formatar(oDBGridSimulaAnulacao.sum(5,false).toFixed(2),'f');
		$('TotalForCol6').innerHTML  = js_formatar(oDBGridSimulaAnulacao.sum(6,false).toFixed(2),'f');
		$('TotalForCol7').innerHTML  = js_formatar(oDBGridSimulaAnulacao.sum(7,false).toFixed(2),'f');
		$('TotalForCol8').innerHTML  = js_formatar(oDBGridSimulaAnulacao.sum(8,false).toFixed(2),'f');			
		$('TotalForCol9').innerHTML  = js_formatar(oDBGridSimulaAnulacao.sum(9,false).toFixed(2),'f');
		$('TotalForCol10').innerHTML = js_formatar(oDBGridSimulaAnulacao.sum(10,false).toFixed(2),'f');
    $('TotalForCol11').innerHTML = js_formatar(oDBGridSimulaAnulacao.sum(11,false).toFixed(2),'f');
	  $('TotalForCol12').innerHTML = js_formatar(oDBGridSimulaAnulacao.sum(12,false).toFixed(2),'f');
		$('TotalForCol13').innerHTML = js_formatar(oDBGridSimulaAnulacao.sum(13,false).toFixed(2),'f');
		$('TotalForCol14').innerHTML = js_formatar(oDBGridSimulaAnulacao.sum(14,false).toFixed(2),'f');
		$('TotalForCol15').innerHTML = js_formatar(oDBGridSimulaAnulacao.sum(15,false).toFixed(2),'f');

	 	js_removeObj("msgBox");

	 	var iTamanhoInicial    = 0;
    var iTamanhoOrigem     = 0;
    var iTamanhoAbatimento = 0;
    var iTamanhoRetorno    = 0;

    if($('tableanulacaoheader') == null) {
      
      for (var i = 0; i < 5; i++) {
         iTamanhoInicial += $('gridanulacao').rows[0].cells[i].scrollWidth+1;
      }
      		
      for (var i = 5; i < 10; i++) {
         iTamanhoOrigem += $('gridanulacao').rows[0].cells[i].scrollWidth;
      }
      
      for (var i = 10; i < 11; i++) {
         iTamanhoAbatimento += $('gridanulacao').rows[0].cells[i].scrollWidth-1;
      }
      
      for (var i = 11; i < 16; i++) {
         iTamanhoRetorno += $('gridanulacao').rows[0].cells[i].scrollWidth+4.7;
      }
      
    } else {

      for (var i = 0; i < 5; i++) {
         iTamanhoInicial += $('tableanulacaoheader').rows[0].cells[i].scrollWidth+1;
      }
      		
      for (var i = 5; i < 10; i++) {
         iTamanhoOrigem += $('tableanulacaoheader').rows[0].cells[i].scrollWidth+1;
      }
      
      for (var i = 10; i < 11; i++) {
         iTamanhoAbatimento += $('tableanulacaoheader').rows[0].cells[i].scrollWidth-1;
      }

      for (var i = 11; i < 16; i++) {
         iTamanhoRetorno += $('tableanulacaoheader').rows[0].cells[i].scrollWidth+5;
      }
      
    }
        
    $('celPosicaoInicial').style.width     = iTamanhoInicial;
		$('celPosicaoOrigem').style.width      = iTamanhoOrigem;
		$('celPosicaoAbatimentos').style.width = iTamanhoAbatimento;
		$('celPosicaoRetorno').style.width     = iTamanhoRetorno;	 	
	 	
}

		

	function js_frmSimulaAnulacao(){
		oDBGridSimulaAnulacao = new DBGrid('anulacao');
		oDBGridSimulaAnulacao.nameInstance = 'oDBGridSimulaAnulacao';
		oDBGridSimulaAnulacao.hasTotalizador = true;
		
		aHeader     = new Array();
		aHeader[0]  = 'Numpre';
		aHeader[1]  = 'Parcela';
		aHeader[2]  = 'Receita';
		aHeader[3]  = 'Dt. Operacao';
		aHeader[4]  = 'Dt. Vencimento';
		aHeader[5]  = 'Vlr. Hist.';
		aHeader[6]  = 'Vlr. Cor.';
		aHeader[7]  = 'Vlr. Juros';
		aHeader[8]  = 'Vlr. Multa';
		aHeader[9]  = 'Total';
		aHeader[10] = 'Vlr. Abatimento';
		aHeader[11] = 'Vlr. Hist.';
		aHeader[12] = 'Vlr. Cor.';
		aHeader[13] = 'Vlr. Juros';
		aHeader[14] = 'Vlr. Multa';
		aHeader[15] = 'Total';		

		oDBGridSimulaAnulacao.setHeader(aHeader);
		oDBGridSimulaAnulacao.setHeight(250);

		var aAligns = new Array();
		aAligns[0]  = 'center';
		aAligns[1]  = 'center';
		aAligns[2]  = 'center';
		aAligns[3]  = 'center';
		aAligns[4]  = 'center';
		aAligns[5]  = 'right';
		aAligns[6]  = 'right';
		aAligns[7]  = 'right';
		aAligns[8]  = 'right';
		aAligns[9]  = 'right';
		aAligns[10] = 'right';
		aAligns[11] = 'right';
		aAligns[12] = 'right';
		aAligns[13] = 'right';
		aAligns[14] = 'right';
		aAligns[15] = 'right';

		oDBGridSimulaAnulacao.setCellAlign(aAligns);
		oDBGridSimulaAnulacao.show($('frmSimula'));

  }

	function js_anula(){
	  var usuario = <?=$usuario?>;
	  var parcel  = <?=$parcel?>;
	  var v21_sequencial = document.getElementById('v21_sequencial').value;
	  js_OpenJanelaIframe('top.corpo','db_iframe_anulaparc1conf','cai4_anulaparc001.php?v21_sequencial='+v21_sequencial+'&usuario='+usuario+'&parcel='+parcel,'Pesquisa',true);
	}
	
	function js_imprimir(){
	
	  sQuery  = '?parcel='+$('parcel').value;
	  sQuery += '&iTipoAnulacao='+$('iTipoAnulacao').value;
	  sQuery += '&oTotal='+$('oTotal').value;
	  
    jan = window.open('cai4_relanulaparc002.php'+sQuery,
                      '',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
	  
	}
	
</script>
<form name="form1" method="post" action="">
<input type="hidden" name="v21_sequencial" id="v21_sequencial" value="0">
<table width="100%" border="0">
	<tr>	
		<td>
		
			<fieldset>
				<legend><b>Simulação de Anulação do Parcelamento <?=$parcel?></b></legend>
				
				<table cellpadding="0" cellspacing="0" width="100%">
				  <tr>
				    <td width="32%">
				    
				      <fieldset>
				        <legend><b>Posição das dividas Parceladas</b></legend>
				        <table width="100%" cellpadding="0" cellspacing="0">
				         <tr>
				           <td class="table_header2" width="40%">Valor Histórico </td>
				           <td class='linhagrid2' id="valor_total_historico_origem"></td>
				         </tr>
				         <tr>
				           <td class="table_header2">Valor Corrigido </td>
				           <td class='linhagrid2' id="valor_total_corrigido_origem"></td>
				         </tr>
				         <tr>
				           <td class="table_header2">Valor Juros </td>
				           <td class='linhagrid2' id="valor_total_juros_origem"></td>
				         </tr>				
				         <tr>
				           <td class="table_header2">Valor Multa </td>
				           <td class='linhagrid2' id="valor_total_multa_origem"></td>
				         </tr>
				         <tr>
				           <td class="table_header2">Valor Desconto </td>
				           <td class='linhagrid2' id="valor_total_desconto_origem"></td>
				         </tr>				  
				         <tr>
				           <td class="table_header2">Valor Total </td>
				           <td class='linhagrid2' id="valor_total_geral_origem"></td>
				         </tr>
				        </table>
				      </fieldset>
				       
				    </td>
				    <td width="32%">
				      
				      <fieldset>
				        <legend><b>Posição dos Valores Pagos</b></legend>
				        <table width="100%" cellpadding="0" cellspacing="0">
				         <tr>
				           <td class="table_header2" width="40%">Valor das Parcelas Pagas </td>
				           <td class='linhagrid2' id="valor_parcelas_pagas"></td>
				         </tr>
				         <tr>
				           <td class="table_header2">Valor das Parcelas não Pagas </td>
				           <td class='linhagrid2' id="valor_parcelas_abertas"></td>
				         </tr>
				         <tr>
				           <td class="table_header2">% Abatimento </td>
				           <td class='linhagrid2' id="perc_abatimento"></td>
				         </tr>				
				         <tr>
				           <td class="table_header2">% de retorno </td>
				           <td class='linhagrid2' id="perc_retorno"></td>
				         </tr>
				         <tr>
				           <td class="table_header2">Total de Parcelas (Qtd) </td>
				           <td class='linhagrid2' id="qtd_total_parcelas"></td>
				         </tr>				  
				         <tr>
				           <td class="table_header2">Total de Parcelas Pagas (Qtd) </td>
				           <td class='linhagrid2' id="qtd_parcelas_pagas"></td>
				         </tr>
				        </table>
				      </fieldset>
				      
				    </td>
				    <td width="32%">
				      
				      <fieldset>
				        <legend><b>Posição dos Valores de Retorno</b></legend>
				        <table cellpadding="0" cellspacing="0" width="100%">
				         <tr>
				           <td class="table_header2" width="40%">Valor Histórico </td>
				           <td class='linhagrid2' id="valor_total_historico_retorno"></td>
				         </tr>
				         <tr>
				           <td class="table_header2">Valor Corrigido </td>
				           <td class='linhagrid2' id="valor_total_corrigido_retorno"></td>
				         </tr>
				         <tr>
				           <td class="table_header2">Valor Juros </td>
				           <td class='linhagrid2' id="valor_total_juros_retorno"></td>
				         </tr>				
				         <tr>
				           <td class="table_header2">Valor Multa </td>
				           <td class='linhagrid2' id="valor_total_multa_retorno"></td>
				         </tr>
				         <tr>
				           <td class="table_header2">Valor Desconto </td>
				           <td class='linhagrid2' id="valor_desconto_retorno"></td>
				         </tr>				  
				         <tr>
				           <td class="table_header2">Valor Total </td>
				           <td class='linhagrid2' id="valor_total_geral_retorno"> </td>
				         </tr>
				        </table>
				      </fieldset>
				      
				    </td>
				  </tr>
				</table>
				
				<br>
				
				<table cellpadding="0" cellspacing="0" width="100%" border=0>
				 <tr>
				   <td>
				   
				    <fieldset>
				      <legend><b>Posição dos Valores Pagos</b></legend>
				      
				      <table cellpadding="0" cellspacing="0" width="100%" border=0 >
				       <tr>
				        <td class='table_header' id='celPosicaoInicial'     bgcolor="#EEEFF2"> &nbsp; </td>
				        <td class='table_header' id='celPosicaoOrigem'      bgcolor="#EEEFF2"> <b> </b></td>
				        <td class='table_header' id='celPosicaoAbatimentos' bgcolor="#EEEFF2"> <b>Abatimentos                    </b></td>
				        <td class='table_header' id='celPosicaoRetorno'     bgcolor="#EEEFF2"> <b>Retorno ( até a data corrente) </b></td>
				       </tr>
				       <tr>
				        <td id="frmSimula" colspan="4" bgcolor="#EEEFF2">
				          <script type="text/javascript">
                	 	js_frmSimulaAnulacao();
                	  js_consultaParcelamento();
                  </script>
				        </td>
				       </tr>				       
				      </table>
				       
				    </fieldset>
				    
				   </td>
				 </tr>  
				</table>
				
			</fieldset>
		</td>
	</tr>
	<tr>
		<td align="center">
		  <input type="hidden" id="aListaSimulacao" value="">
		  <input type="hidden" id="oTotal"          value="">
		  <input type="hidden" id="parcel"          value="<?=$parcel?>">
		  <input type="hidden" id="iTipoAnulacao"   value="">
		  
		  <?
        $disabled    = "disabled";
        $mostrabotao = db_permissaomenu(db_getsession("DB_anousu"),81,2537);
        if ($mostrabotao == "true") {
        	$disabled = ""; 
        }
        
		  ?>
			<input type="button" id="confirmar" value="Confirmar" onclick="js_anula();" <?=@$disabled?>> 
			
			<input type="button" id="imprimir"  value="Imprimir" onclick="js_imprimir();" disabled>
    </td>			
	</tr>
</table>
</form>
</html>