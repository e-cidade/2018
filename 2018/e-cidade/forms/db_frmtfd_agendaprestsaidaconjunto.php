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

$oDaoTfdPedidoTfd->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label('z01_nome');
$oRotulo->label('tf09_i_codigo');
$oRotulo->label('z01_v_nome');
$oRotulo->label('tf10_i_centralagend');
$oRotulo->label('tf10_i_prestadora');
?>
<div class="container">
  <form name="form1" method="post" action="">
    <fieldset>
      <legend>Pedido TFD</legend>
      <table>
        <tr>
          <td nowrap title="<?=@$Ttf10_i_centralagend?>">
            <?php
            db_ancora( @$Ltf10_i_centralagend, "js_pesquisatf10_i_centralagend(true);", $db_opcao );
            ?>
          </td>
          <td nowrap>
            <?php
            $sScript = " onchange='js_pesquisatf10_i_centralagend(false);'";
            db_input( 'tf10_i_centralagend', 10, $Itf10_i_centralagend, true, 'text', $db_opcao, $sScript );
            db_input( 'z01_nome',            50, $Iz01_nome,            true, 'text',         3 );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ttf10_i_prestadora?>">
            <?php
            db_ancora( @$Ltf10_i_prestadora, "js_pesquisatf10_i_prestadora(true);", $db_opcao );
            ?>
          </td>
          <td nowrap>
            <?php
            $sScript = " onchange='js_pesquisatf10_i_prestadora(false);'";
            db_input( 'tf10_i_prestadora',        10, $Itf10_i_prestadora,         true, 'text',  $db_opcao, $sScript );
            db_input( 'z01_nome2',                50, $Iz01_nome,                  true, 'text',          3 );
            db_input( 'tf16_i_prestcentralagend',  2, @$Itf16_i_prestcentralagend, true, 'hidden',        3 );
            db_input( 'tf09_i_numcgm',             2,                          '', true, 'hidden',        3 );

            if( isset( $tf10_i_prestadora ) ) {
              $prestInicial = $tf10_i_prestadora;
            }
            db_input( 'prestInicial', 10, @$IprestInicial, true, 'hidden', 3 );
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="3">
            <fieldset class="separator">
              <legend>Endereço da Prestadora</legend>
              <?php
              db_input( 'senderecoprestadora', 70, @$Isenderecoprestadora, true, 'text', 3 );
              ?>
            </fieldset>
          </td>
        </tr>
      </table>
    </fieldset>
  </div>
  <div class="container">
    <fieldset style='width: 1200px;'>
      <legend>Pacientes</legend>
      <table width="100%">
        <tr>
          <td>
            <div id='grid_pacientes'></div>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="gravar" type="button" id="gravar" value="Gravar" onclick="js_agendarPrestadora();" >
    <input name="fechar" type="button" id="fechar" value="Fechar" onclick="js_fechar();" >
  </div>
</form>
<script>
/*===========================================
 *           INICIALIZAR GRID
 *===========================================*/
var oDBGridPedidostfd = js_cria_datagrid();
var sUrl              = 'tfd4_pedidotfd.RPC.php';

if (<?=isset($Pedidos)?> && '<?=$Pedidos?>' != '') {

	js_getPedidosTfd("<?=$Pedidos?>");
	tf16_i_login       = <?=db_getsession("DB_id_usuario")?>;
	tf16_c_datasistema = '<?=date("d/m/Y", db_getsession("DB_datausu"))?>';
	tf16_c_horasistema = '<?=db_hora()?>';
	setDate();
}

/*===========================================
 *       MÉTODOS PARA USO EM GERAL
 *===========================================*/
function js_fechar() {
  parent.db_iframe_saida.hide();
}

function js_ajax(oParam, jsRetorno) {

	var objAjax = new Ajax.Request(
                         sUrl, 
                         {
                          method    : 'post',
                          asynchronous: false,
                          parameters: 'json='+Object.toJSON(oParam),
                          onComplete: function(objAjax) {
                          				var evlJS = jsRetorno+'(objAjax);';
                                  return eval(evlJS);
                          			}
                         }
                        );
}

/*===========================================
 *      FUNÇÕES INERENTES AO GRID
 *===========================================*/
function js_cria_datagrid() {

  oDBGrid                = new DBGrid('grid_pacientes');
  oDBGrid.nameInstance   = 'oDBGridPedidostfd';
  oDBGrid.hasTotalizador = false;
  oDBGrid.setCellWidth(new Array('5%', '35%', '8%', '5%', '9%', '15%', '8%', '8%', '8%'));
  oDBGrid.setHeight(180);

  var aHeader = new Array();
  aHeader[0] = 'Pedido';
  aHeader[1] = 'Paciente';
  aHeader[2] = 'Data Consulta';
  aHeader[3] = 'Hora C.';
  aHeader[4] = 'Prot./Agend.';
  aHeader[5] = 'Referência Local';
  aHeader[6] = 'Sala';
  aHeader[7] = 'Sequência';
  aHeader[8] = 'Médico';
  oDBGrid.setHeader(aHeader);

  var aAligns = new Array();
  aAligns[0] = 'center';
  aAligns[1] = 'left';
  aAligns[2] = 'center';
  aAligns[3] = 'center';
  aAligns[4] = 'center';
  aAligns[5] = 'left';
  aAligns[6] = 'left';
  aAligns[7] = 'left';
  aAligns[8] = 'left';

  oDBGrid.setCellAlign(aAligns);
  oDBGrid.show($('grid_pacientes'));
  oDBGrid.clearAll(true);
  return oDBGrid;
}

/*===========================================
 *     SELEÇÃO DO CONTEUDO DO GRID
 *===========================================*/
function js_getPedidosTfd(sPedidos) {
  
  oDBGrid.clearAll(true);

	var oParam      = new Object();
	oParam.exec     = 'getPedidosTfdDeLista';
  oParam.sPedidos = sPedidos;

	js_ajax(oParam, 'js_retornogetPedidosTfd');
}

function js_retornogetPedidosTfd(oRetorno) {
	  
  oRetorno          = eval("("+oRetorno.responseText+")");
	if(oRetorno.iStatus != 1) {

	  alert('Pedido TFD não encontrado.');
	  return false;
	} else {

	  var iI = 0;
	  oRetorno.oPedidos.each( function (oPedidosTfd) {
	      
      var aLinha = new Array();
      aLinha[0]  = '<label id="tfd' + iI + '">' + oPedidosTfd.tf01_i_codigo + '</label>';
      aLinha[1]  = '<label id="pac' + iI + '">' + oPedidosTfd.paciente.urlDecode() + '</label>';

      var oDataConsulta  = new wsDate(oPedidosTfd.tf16_d_dataagendamento.urlDecode());
      var sDataConsulta  = oDataConsulta.getDate() != undefined ? oDataConsulta.getDate() : '';
      aLinha[2]  = getInput('data', 10, 10,'dc' + iI, sDataConsulta, '');
      var sHoraConsulta = oPedidosTfd.tf16_c_horaagendamento.urlDecode();
      aLinha[3]  = getInput('hora', 5, 5,'hc' + iI, sHoraConsulta, '');
      var sProtocolo = oPedidosTfd.tf16_c_protocolo.urlDecode();
      aLinha[4]  = getInput('text', 10, 10,'pa' + iI, sProtocolo, '');
      var sRefLocal = oPedidosTfd.tf16_c_local.urlDecode();
      aLinha[5]  = getInput('text', 20, 20,'rl' + iI, sRefLocal, '');
      var sSala = oPedidosTfd.tf16_sala.urlDecode();
      aLinha[6]  = getInput('text', 10, 10,'sl' + iI, sSala, '');
      var sSeq = oPedidosTfd.tf16_sequencia.urlDecode();
      aLinha[7]  = getInput('text', 10, 10,'sq' + iI, sSeq, '');
      var sMed  = oPedidosTfd.tf16_c_medico.urlDecode();
      aLinha[8]  = getInput('text', 10, 10,'md' + iI, sMed, '');

      oDBGridPedidostfd.addRow(aLinha);
      iI++;
	  });

	  oDBGridPedidostfd.renderRows();
	}
}

function setDate() {

	var sDataConsulta = '';
	for (iI = 0; iI < oDBGridPedidostfd.getNumRows(); iI++) {

		sDataConsulta = $('dc' + iI).value; 
    $('dc' + iI + '_dia').value = sDataConsulta.substring(0, 2);
    $('dc'+iI+'_mes').value     = sDataConsulta.substring(3, 5);
    $('dc'+iI+'_ano').value     = sDataConsulta.substring(6, 10);
	}
}

/*===========================================
 *      PESQUISA CENTRAL AGENDAMENTO
 *===========================================*/
function js_pesquisatf10_i_centralagend(lMostra) {

  if(lMostra == true) {

	  js_OpenJanelaIframe('','db_iframe_tfd_centralagendamento','func_tfd_centralagendamento.php?'+
	                      'funcao_js=parent.js_mostratfd_centralagendamento1|tf09_i_codigo|z01_nome','Pesquisa',true);
	} else {

	  if(document.form1.tf10_i_centralagend.value != '') {

	    js_OpenJanelaIframe('','db_iframe_tfd_centralagendamento','func_tfd_centralagendamento.php?pesquisa_chave='+
	                        document.form1.tf10_i_centralagend.value+
	                        '&funcao_js=parent.js_mostratfd_centralagendamento','Pesquisa',false);
	  } else {
	    document.form1.z01_nome.value = ''; 
	  }
	}
}

function js_mostratfd_centralagendamento (chave, erro) {

  document.form1.z01_nome.value = chave; 
  if (erro == true) {

	  document.form1.tf10_i_centralagend.focus(); 
	  document.form1.tf10_i_centralagend.value = ''; 
	}
}

function js_mostratfd_centralagendamento1(chave1, chave2) {

  document.form1.tf10_i_centralagend.value = chave1;
	document.form1.z01_nome.value            = chave2;
  db_iframe_tfd_centralagendamento.hide();
}

/*===========================================
 *        PESQUISA DA PRESTADORA
 *===========================================*/
function js_pesquisatf10_i_prestadora(lMostra) {

	var sEnder = 'z01_ender|z01_numero|z01_compl|z01_bairro|z01_munic|z01_uf';
  if (document.form1.tf10_i_centralagend.value == '') {

	  alert('Escolha uma central de agendmento primeiro');
	  return false;
	}

	sChave = 'chave_tf10_i_centralagend='+document.form1.tf10_i_centralagend.value;
	if (lMostra == true) {

	  js_OpenJanelaIframe('','db_iframe_tfd_prestadoracentralagend','func_tfd_prestadoracentralagend.php?'+sChave+
	                      '&funcao_js=parent.js_mostratfd_prestadora|tf10_i_prestadora|z01_nome|tf10_i_codigo|' +
	                      'z01_numcgm|' + sEnder,
	                      'Pesquisa',true);
	} else {

	  if (document.form1.tf10_i_prestadora.value != '') {

	    js_OpenJanelaIframe('','db_iframe_tfd_prestadoracentralagend','func_tfd_prestadoracentralagend.php?'+sChave+
	                        '&funcao_js=parent.js_mostratfd_prestadora|tf10_i_prestadora|z01_nome|tf10_i_codigo|' +
	                        'z01_numcgm|' + sEnder +
	                        '&chave_tf10_i_prestadora='+document.form1.tf10_i_prestadora.value+'&nao_mostra=true', 
	                        'Pesquisa',false);
	  }
  }
}

function js_mostratfd_prestadora(chave1, chave2, chave3, chave4, chave5, chave6, chave7, chave8, chave9, chave10) {

  if (chave1 == '') {

	  chave3 = '';
	  chave4 = '';
	}

	document.form1.tf10_i_prestadora.value         = chave1;
	document.form1.z01_nome2.value                 = chave2;
	document.form1.tf16_i_prestcentralagend.value  = chave3;
	document.form1.tf09_i_numcgm.value             = chave4;

	if (chave1 != '') {
		
	  document.form1.senderecoprestadora.value       = chave5 + ', n°' + chave6 + " ";
	  document.form1.senderecoprestadora.value      += chave7 + ' - ' + chave8 +  ' - ' + chave9 + ' - ' + chave10;
  } else {
	  document.form1.senderecoprestadora.value = '';
	}

	db_iframe_tfd_prestadoracentralagend.hide();
}

/*===========================================
 *  GERADOR DE INPUTS E METODOS AUXILIARES
 *===========================================*/
function getInput(sType, iSize, iMaxLen, sId, sValue, sJs) {

	var sInput  = '<input type="';
	sInput     += sType != 'hidden' ? 'text' : sType;
	sInput     += '" id="';
	sInput     += sId;
	sInput     += '" name="';
	sInput     += sId;
	sInput     += '" value="';
	sInput     += sValue;
	sInput     += '" size="';
	sInput     += iSize;
	sInput     += '" maxlength="';
	sInput     += iMaxLen;
	sInput     += '" ';
	sInput     += sJs;

	if (sType == 'hora') {
		
	  sInput += ' onBlur="js_validaHora(this);" ';
		sInput += '>';
	} else if(sType == 'data') {
		
	  sInput += ' onfocus="js_validaEntrada(this);" onkeyup="return js_mascaraData(this,event);" ';
	  sInput += ' onblur="js_validaDbData(this);" ';
		sInput += '>';
	  sInput += '<input type="hidden" name="' + sId + '_dia" id="' + sId + '_dia">'; 
	  sInput += '<input type="hidden" name="' + sId + '_mes" id="' + sId + '_mes">'; 
	  sInput += '<input type="hidden" name="' + sId + '_ano" id="' + sId + '_ano">'; 
  } else {
	  
		sInput += ' onKeyUp="js_upper(this);" ';
		sInput += '>';
  }

	return sInput;
}

function js_upper(oInput) {
  oInput.value = oInput.value.toUpperCase();
}

function js_validaHora(oInput) {

	var sHora = oInput.value;
	var iLoca = sHora.indexOf(':');

	if (oInput.value.length <= 3) {

		if (sHora.length == 3 && iLoca == 2) {
			
		  oInput.value = sHora + '00';
		  js_validaHora(oInput);
		} else if (sHora.length == 2 && iLoca == -1) {

			oInput.value = sHora + ':00';
      js_validaHora(oInput);
		} else {
	    oInput.value = '';
	  }
	} else if (oInput.value.length > 3) {

    if (iLoca == -1 && sHora.length == 4) {	  

      oInput.value = sHora.substring(0, 2) + ':' + sHora.substring(2, 4);
      sHora        = oInput.value;
      iLoca        = 2;
    } else if (iLoca == -1) {
   	  oInput.value = '';
    }

    if (iLoca == 2 && sHora.length == 5 &&
    	  js_isNumber(sHora.substring(0, 2)) && js_isNumber(sHora.substring(3, 5)) &&
    	  parseInt(sHora.substring(0, 2)) < 24 && parseInt(sHora.substring(0, 2)) >= 0 &&
    	  parseInt(sHora.substring(3, 5)) < 60 && parseInt(sHora.substring(3, 5)) >= 0) {
      return;
    } else if (iLoca == 1 && sHora.length == 4 &&
    	  js_isNumber(sHora.substring(0, 1)) && js_isNumber(sHora.substring(2, 4)) &&
    	  parseInt(sHora.substring(0, 1)) >= 0 &&
    	  parseInt(sHora.substring(3, 5)) < 60 && parseInt(sHora.substring(3, 5)) >= 0) {

    	oInput.value = '0' + oInput.value;
      return;
    } else {
      oInput.value = '';
    }
  }
}

function js_isNumber(iValue) {
    
  var nonNumbers = /\D/;
  if (nonNumbers.test(iValue)) {
    return false;
  } else {
    return true;
  }
}

function js_dadosValidos() {

  if ($F('tf10_i_centralagend') == '') {

    alert('Informe a Central.');
    return false;
  }

  if ($F('tf10_i_prestadora') == '') {

	  alert('Informe a Prestadora.');
	  return false;
	}

	return true;
}

function js_agendarPrestadora() {

	var iI       = 0;
	var oParam   = new Object();
	var aPedidos = new Array();
	
	if (js_dadosValidos() && js_dadosGridValidos()) {

		for (iI = 0; iI < oDBGridPedidostfd.getNumRows(); iI++) {
			
		  var oPedido                        = new Object();
		  oPedido.tf16_i_pedidotfd           = $('tfd' + iI).innerHTML;  
		  oPedido.tf16_c_protocolo           = $F('pa' + iI); 
		  oPedido.tf16_d_dataagendamento_dia = $F('dc' + iI).substring(0,2);
		  oPedido.tf16_d_dataagendamento_mes = $F('dc' + iI).substring(3,5); 
		  oPedido.tf16_d_dataagendamento_ano = $F('dc' + iI).substring(6,10); 
		  oPedido.tf16_d_dataagendamento     = $F('dc' + iI);
		  oPedido.tf16_c_horaagendamento     = $F('hc' + iI);
		  oPedido.tf16_c_local               = $F('rl' + iI);
		  oPedido.tf16_c_medico              = $F('md' + iI);  
		  oPedido.tf16_sequencia             = $F('sq' + iI);  
		  oPedido.tf16_sala                  = $F('sl' + iI);
			aPedidos[iI]                       = oPedido;
		}

		oParam.aPedidos                 = aPedidos;
		oParam.iNumReg                  = iI;
		oParam.tf16_i_login             = tf16_i_login;
		oParam.tf16_i_prestcentralagend = $F('tf10_i_prestadora');
		oParam.tf16_d_datasistema       = tf16_c_datasistema;
		oParam.tf16_d_datasistema_dia   = tf16_c_datasistema.substring(0,2);
		oParam.tf16_d_datasistema_mes   = tf16_c_datasistema.substring(3,5); 
		oParam.tf16_d_datasistema_ano   = tf16_c_datasistema.substring(6,10); 
		oParam.tf16_c_horasistema       = tf16_c_horasistema; 

		js_mandarDados(oParam);
	}
}

function js_dadosGridValidos() {

	for (iI = 0; iI < oDBGridPedidostfd.getNumRows(); iI++) {

	  if ($F('dc' + iI) == '') {

	    alert ('Data da consulta não preenchida.\n' + getStringPedido(iI));
      return false;
	  }

	  if ($F('hc' + iI) == '') {

		  alert ('Hora da consulta não preenchida.\n' + getStringPedido(iI));
	    return false;
		}
  }

	return true;
}

function js_mandarDados(oParam) {
	  
  oParam.exec = 'atualizaPrestadoraTFD';
	js_ajax(oParam, 'js_retornoMandaDados');
}

function js_retornoMandaDados(oRetorno) {

	oRetorno = eval("("+oRetorno.responseText+")");
  if	(oRetorno.iStatus != 2) {

	  if (oRetorno.aSaidaCadastrada.length > 0) {

		  var sPedidos = '';
		  for (iI = 0; iI < oRetorno.aSaidaCadastrada.length; iI++) {

        sPedidos += sPedidos != '' ? ',' : '';
        sPedidos += oRetorno.aSaidaCadastrada[iI];
        js_marcarSaida(oRetorno.aSaidaCadastrada[iI]);
	  	}

		  parent.document.form1.pesquisar.click();
		  sPedidos  = 'O(s) pedido(s) TFD '+ sPedidos + ' (vermelho), não podem alterar o agendamento com a prestadora.';
		  sPedidos += '\nERRO: Pedidos já possuem saída agendada.';
		  sPedidos += '\n\nOBS: Os demais pedidos foram agendados.';

		  alert(sPedidos);
    } else {

	    parent.document.form1.pesquisar.click();
	    alert('Cadastro efetuado com sucesso.');
    }
	} else {
    alert(oRetorno.sMessage.urlDecode());
  }
}

function js_marcarSaida(iPedTfd) {

	var iI = 0, iM = 0;
	for (iI = 0; iI < oDBGridPedidostfd.getNumRows(); iI++) {

    if ($('tfd' + iI) == iPedTfd) {
    	iM = iI;
    	break;
    }
	}

  $('pac' + iM).style.color = "#DE0000";
  $('tfd' + iM).style.color = "#DE0000";  
  $('pa' + iM).style.color  = "#DE0000"; 
  $('dc' + iM).style.color  = "#DE0000";
  $('hc' + iM).style.color  = "#DE0000";
  $('rl' + iM).style.color  = "#DE0000";
  $('md' + iM).style.color  = "#DE0000";  
  $('sq' + iM).style.color  = "#DE0000";  
  $('sl' + iM).style.color  = "#DE0000";
}

function getStringPedido(iI) {
	return 'Pedido TFD: ' + $('tfd' + iI).innerHTML +  '\nPaciente: ' + $('pac' + iI).innerHTML;
}

$('tf10_i_centralagend').className = 'field-size2';
$('z01_nome').className            = 'field-size8';
$('tf10_i_prestadora').className   = 'field-size2';
$('z01_nome2').className           = 'field-size8';
$('senderecoprestadora').className = 'field-size-max';
</script>