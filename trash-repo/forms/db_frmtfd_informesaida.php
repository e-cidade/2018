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
 
$oDaoTfdAgendaSaida->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("tf03_i_codigo");
$oRotulo->label("tf03_c_descr");
?>
<form class='container' name="form1" method="post" action="">
  <fieldset > 
    <legend><b>Per�odo do Agendamento:</b></legend> 
    <table class='' >
      <tr>
        <td class='field-size1' title="Data de agendamento com a Prestadora." nowrap>
          <b>�nicio:</b>
        </td>
        <td style="padding-right: 10px;"  nowrap="nowrap" >
          <?
          $aData = explode('/', date('d/m/Y', db_getsession("DB_datausu")));
          $iDia1 = $aData[0];
          $iMes1 = $aData[1];
          $iAno1 = $aData[2];
          db_inputdata('data1', $iDia1, $iMes1, $iAno1, true, 'text', 1, "");
          ?>
        </td>
        <td nowrap="nowrap">
          <b>Fim:</b>
        </td>
        <td  title="Data de agendamento com a Prestadora." style="padding-right: 8px;" nowrap>
          <?
          $iDia2 = $aData[0];
          $iMes2 = $aData[1];
          $iAno2 = $aData[2];
          db_inputdata('data2', $iDia2, $iMes2, @$iAno2, true, 'text', 1, "");?>
        </td>
      </tr>
      <tr>
        <td  nowrap="nowrap" title="Destino dos pedidos." nowrap>
          <?db_ancora("<b>Destino:</b>", "js_pesquisadestino(true);", 1);?>
        </td>
        <td colspan="3" nowrap="nowrap">
          <?db_input('tf03_i_codigo', 10, $Itf03_i_codigo, true, 'hidden', 3, '');?>
          <?db_input('tf03_c_descr', 60, $Itf03_c_descr, true, 'text', 1, '');?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input type="button" name="pesquisar" id="pesquisar" value="Pesquisar" onclick="js_pesquisar();">
</form>
<center>

  <fieldset style="width:1300px;"> 
    <legend><b>Pacientes:</b></legend> 
    <div id='grid_pacientes' style='width: 100%;'>
            <!-- GRID DOS PACIENTES  --> 
    </div>
  </fieldset>

  <input type="button" name="gravar" id="gravar" value="Gravar" onclick="js_gravar();">
  <input type="button" name="relatorio" id="relatorio" value="Relat�rio de Viagens" onclick="js_relatorio();">
  
</center>
<script>
var oGridPacientes  = new DBGrid('grid_pacientes');
oGridPacientes.nameInstance = 'oGridPacientes';
oGridPacientes.hasTotalizador = false;
oGridPacientes.setCellWidth(new Array('4%','4%', '23%', '6%', '5%', '13%', '20%', '7%', '5%', '8%', '5%'));
oGridPacientes.setHeight(180);
oGridPacientes.setCheckbox(0);
oGridPacientes.allowSelectColumns(true);

var aHeader = new Array();
aHeader[0]  = 'Pedido';
aHeader[1]  = 'CGS';
aHeader[2]  = 'Paciente';
aHeader[3]  = 'Dt Agend.';
aHeader[4]  = 'H. Agend.';
aHeader[5]  = 'Destino';
aHeader[6]  = 'Prestadora';
aHeader[7]  = 'Dt Sa�da';
aHeader[8]  = 'H. Sa�da';
aHeader[9]  = 'Local Sa�da';
aHeader[10] = 'Viajou';
oGridPacientes.setHeader(aHeader);

var aAligns = new Array();
aAligns[0]  = 'center';
aAligns[1]  = 'center';
aAligns[2]  = 'left';
aAligns[3]  = 'center';
aAligns[4]  = 'center';
aAligns[5]  = 'left';
aAligns[6]  = 'left';
aAligns[7]  = 'center';
aAligns[8]  = 'center';
aAligns[9]  = 'left';
aAligns[10] = 'center';

oGridPacientes.setCellAlign(aAligns);
oGridPacientes.show($('grid_pacientes'));
oGridPacientes.clearAll(true);

var sURL               = 'tfd4_pedidotfd.RPC.php';
var tf17_i_login       = <?=db_getsession("DB_id_usuario")?>;
var tf17_d_datasistema = '<?=date("d/m/Y", db_getsession("DB_datausu"))?>';
var tf17_c_horasistema = '<?=db_hora()?>';

/* AUTOCOMPLETE DESTINO */
oAutoCompleteDestino  = new dbAutoComplete($('tf03_c_descr'), 'sau4_autocompletesaude.RPC.php');
oAutoCompleteDestino.setTxtFieldId($('tf03_c_descr'));
oAutoCompleteDestino.setHeightList(180);
oAutoCompleteDestino.show();
oAutoCompleteDestino.setCallBackFunction(function(iId, sLabel) {

                                           $('tf03_i_codigo').value = iId;
                                           $('tf03_c_descr').value  = sLabel;
                                          }
		                                     );
oAutoCompleteDestino.setQueryStringFunction(function() {
  
                                              var oParamComplete    = new Object();
	                                            oParamComplete.exec   = 'DesinoPedidoTFD';
	                                            oParamComplete.string = $('tf03_c_descr').value;
	                                            return 'json='+Object.toJSON(oParamComplete); 
                                             } 
                                           );
/* FIM AUTOCOMPLETE DESTINO */

function js_ajax(oParam, sCarregando, jsRetorno){ 
	
	var objAjax = new Ajax.Request(
                         sURL, 
                         {
                          method    : 'post', 
                          parameters: 'json='+Object.toJSON(oParam),
                          onCreate  : function(){
                          				js_divCarregando( sCarregando, 'msgbox');
                          			},
                          onComplete: function(objAjax){
                          				var evlJS = jsRetorno+'( objAjax )';
                          				js_removeObj('msgbox');
                          				eval( evlJS );
                          			}
                         }
                        );
    
}


function js_pesquisadestino() {

	var sCampos = 'tf03_i_codigo|tf03_c_descr';
	js_OpenJanelaIframe('', 'db_iframe_tfd_destino', 'func_tfd_destino.php?funcao_js=parent.js_mostradestino|'+ sCampos,
	                    'Pesquisa',true);

}

function js_mostradestino(tf03_i_codigo, tf03_c_descr) {

	$('tf03_i_codigo').value  = tf03_i_codigo; 
  $('tf03_c_descr').value   = tf03_c_descr; 
  db_iframe_tfd_destino.hide();

}

function js_dadosValidos() {

  if ($('data1').value == '') {

	  alert('Informe a Data Inicial.');
		return false;
  }
	
	dWsDate1     = new wsDate($('data1').value);
	
	if ($F('data2') != "" && dWsDate1.thisHigher($('data2').value)) {
			  
		alert('Data Inicial superior a data final.');
		return false;
	}
	return true;
	
}

function js_pesquisar() {

  if (!js_dadosValidos()) {
    return;
  }
	var oParam           = new Object();
	oParam.exec          = 'getPacientesSaidaData';	
	oParam.dataInicial   = $('data1').value;
	oParam.dataFinal     = $('data2').value;
	oParam.sd03_i_codigo = $('tf03_i_codigo').value;
	oParam.saida         = 1; // somente os com saida agendada
	js_ajax(oParam, 'Aguarde, Selecionando os Pacientes...', 'js_preencherGridPacientes');
	
}

function js_preencherGridPacientes(oAjaxRetorno) {

	oGridPacientes.clearAll(true);
	var oRetorno = eval("("+oAjaxRetorno.responseText+")");	
  if (oRetorno.iStatus == 1) {

	  if (oRetorno.oPedido != undefined && oRetorno.oPedido.length > 0) {
	     	
	    oRetorno.oPedido.each(function (oPedido, iIterator) {
	        	     		
        var aLinha = new Array();
        aLinha[0]  = oPedido.pedido;
        aLinha[1]  = oPedido.cgs;
        aLinha[2]  = oPedido.paciente.urlDecode();
        sData      = oPedido.dataagendamento.urlDecode();
        sData      = sData != '' ? new wsDate(sData).getDate() : '';
        aLinha[3]  = sData;
        aLinha[4]  = oPedido.horaagendamento.urlDecode(); 
        aLinha[5]  = oPedido.destino.urlDecode(); 
        aLinha[6]  = oPedido.prestadora.urlDecode(); 
        sData      = oPedido.datasaida.urlDecode(); 
        sData      = sData != '' ? new wsDate(sData).getDate() : '';
        aLinha[7]  = sData;
        aLinha[8]  = oPedido.horasaida.urlDecode(); 
        aLinha[9]  = oPedido.localsaida.urlDecode();
        aLinha[10] = '<input id="viajou'+iIterator+'" type="checkbox" checked name="viajou" value="1" />';
        oGridPacientes.addRow(aLinha);
        
     	});
      oGridPacientes.renderRows();
	  }
  } else {
    alert(oRetorno.sMessage.urlDecode());
  }

}

function js_ajuda(paciente) {
	
	var sUrl  = 'tfd4_tfd_ajudacustopedido001.php?';
	    sUrl += '&tf01_i_cgsund=' + $('cgs' + paciente).innerHTML;
		  sUrl += '&z01_v_nome=' + $('nomepaciente' + paciente).innerHTML;
			sUrl += '&tf14_i_pedidotfd=' + $('pedido' + paciente).innerHTML;  
 js_OpenJanelaIframe('', 'db_iframe_ajuda', sUrl, 'Ajuda de Custo', true);
			
}


/**
 * @todo Refatornando
 */
function js_gravar() {

  var aSelecionados = oGridPacientes.getSelection("object");

  var aPedidos = new Array();

  for (var iContadorLinha = 0; iContadorLinha < aSelecionados.length; iContadorLinha++) {

    var oDados           = new Object();
    oDados.iPedido       = aSelecionados[iContadorLinha].aCells[0].getValue();
    oDados.lViajou       = aSelecionados[iContadorLinha].aCells[11].getValue() == null ? false : true;

    aPedidos.push(oDados);
  }

  if (aPedidos.length == 0) {
    
    alert("Nenhum paciente selecionado.");
    return false;
  }
  
  var oParametros = new Object();
  oParametros.exec         = "atualizartfagendasaida";
  oParametros.aPedidos     = aPedidos;
  oParametros.dtSistema    = tf17_d_datasistema;
  oParametros.sHoraSistema = tf17_c_horasistema;
  oParametros.iLogin       = tf17_i_login;

  js_divCarregando("Aguarde, salvando dados...", "msgBox");
  
  new Ajax.Request("tfd4_pedidotfd.RPC.php",
                    {
                      method:'post',
                      parameters:'json='+Object.toJSON(oParametros),
                      onComplete: js_retornoGravar
                    }
                  );
}

function js_retornoGravar(oAjaxRetorno) {

  js_removeObj("msgBox");
  
	var oRetorno = eval("("+oAjaxRetorno.responseText+")");	

  if (oRetorno.iStatus == 2) {
    alert(oRetorno.sMessage.urlDecode());
  }
  alert("Agendamentos de sa�da atualizados com sucesso!");
  js_pesquisar();
  
}

function js_relatorio() {

	if (!js_dadosValidos()) {
	  return;
	}
	var sArquivo    = 'tfd2_relatoriodeviagens001.php?';
	var sVariaveis  = 'dataInicial=' + $('data1').value;
  sVariaveis     += '&dataFinal=' + $('data2').value;
  sVariaveis     += '&destino=' + $('tf03_i_codigo').value;
  sVariaveis     += '&saida=' + 1; // Somente os com saida agendada
	var oJan        = window.open(sArquivo + sVariaveis,'',
                               'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+
                               ',scrollbars=1,location=0 '
                               );
  oJan.moveTo(0, 0);

}

</script>