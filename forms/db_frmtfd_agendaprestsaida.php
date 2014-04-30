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

//MODULO: TFD
$oDaocgs_und->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("tf29_i_prontuario");
$clrotulo->label("tf30_i_encaminhamento");
$clrotulo->label("tf01_i_cgsund");
$clrotulo->label("s115_c_cartaosus");
$clrotulo->label("j13_codi");
$clrotulo->label("tf01_i_rhcbo");
?>
<form name="form1" method="post" action="">
  <center>
    <fieldset style='width: 96%;'> 
      <legend><b>Pedido TFD:</b></legend> 
      <table>
        <tr>
          <td title="Data Inicial." nowrap>
            <b>Inicio:</b>
          </td>
          <td nowrap> 
            <?
            $aData = split( "/", strftime("%d/%m/%Y", mktime(0, 0, 0, date("m"), date("d") - 30, date("Y"))));
            $dia1  = $aData[0];
            $mes1  = $aData[1];
            $ano1  = $aData[2];
            db_inputdata('data1', @$dia1, @$mes1, @$ano1, true, 'text', 1, ""); 
            ?>
          </td>
          <td title="Data Final." style="padding-left: 8px;" nowrap>
            <b>Fim:</b>
          </td>
          <td nowrap colspan="3"> 
            <?
            $dia2 = date("d");
            $mes2 = date("m");
            $ano2 = date("Y");
            db_inputdata('data2', @$dia2, @$mes2, @$ano2, true, 'text', 1, "onchange=js_validaData();");
            ?>
          </td>
        </tr>
        <tr>
          <td title="Quantidade de registros.">
            <b>Registros:</b>
          </td>
          <td title="Quantidade de registros.">
            <?
            $iNumeroResgistros = 0;
            db_input('iNumeroResgistros', 10, @$Itf01_i_rhcbo, true, 'text', $db_opcao);
            ?>
          </td>
          <td title="Prestadora." style="padding-left: 8px;">
            <b>Prestadora:</b>
          </td>
          <td title="Prestadora.">
            <?
            $aPrestadora = Array(0=>'SEM PRESTADORA', 1=>'COM PRESTADORA', 2=>'TODOS');
            db_select('sPrestadora', $aPrestadora, @$IsPrestadora, $db_opcao, '');
            ?>
          </td>
          <td title="Ordenar." style="padding-left: 8px;">
            <b>Ordenar:</b>
          </td>
          <td title="Ordenar.">
            <?
            $aOrdenar = Array(0=>'PEDIDO', 1=>'URGÊNCIA');
            db_select('sOrdenar', $aOrdenar, @$IsOrdenar, $db_opcao, '');
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=@$Ttf01_i_rhcbo?>" nowrap>
            <? db_ancora(@$Ltf01_i_rhcbo, "js_pesquisatf01_i_rhcbo(true);", $db_opcao); ?>
          </td>
          <td colspan="5"> 
            <?
            db_input('rh70_estrutural', 10, @$Irh70_estrutural, true, 'text', $db_opcao,
                     " onchange='js_pesquisatf01_i_rhcbo(false);'"
                    );
            db_input('tf01_i_rhcbo', 10, @$Itf01_i_rhcbo, true, 'hidden', 3);
            db_input('rh70_descr', 59, @$Irh70_descr, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <input name="pesquisar" type="button" id="pesquisar" style="margin-top: 4px;"
                   value="Pesquisa" onclick="js_getPedidosTfdDataRhcbo();" >
          </td>
        </tr>
      </table>
    </fieldset>
    <fieldset style='width: 96%;'> <legend><b>Pacientes:</b></legend> 
      <table width="99%">
        <tr>
          <td>  
            <div id='grid_pacientes' style='width: 100%;'></div>
          </td>
        </tr>
      </table>
    </fieldset>
    <table>
      <tr>
        <td style="padding: 8px;">
          <input name="prestadora" type="button" id="prestadora" value="Prestadora" 
                 onclick="js_selecionarPrestadora();" >
        </td>
        <td style="padding: 8px;">
          <input name="relatorio" type="button" id="relatorio" value="Relatório" onclick="js_mandaDados();" >
        </td>
        <td style="padding: 8px;">
          <input name="limpar" type="button" id="limpar" value="Limpar" onclick="js_limpar();" >
        </td>
      </tr>
    </table>
  </center>
</form>
<script>

var lPermissaoCgs     = <?echo db_permissaomenu(db_getsession('DB_anousu'), 1000004, 1045411).';';?>
var oDBGridPedidostfd = js_cria_datagrid();
var sUrl              = 'tfd4_pedidotfd.RPC.php';

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

/**** Bloco de funções do grid início */
function js_cria_datagrid() {

  oDBGrid = new DBGrid('grid_pacientes');
  oDBGrid.nameInstance = 'oDBGridPedidostfd';
  oDBGrid.hasTotalizador = false;
  oDBGrid.setCellWidth(new Array('5%', '5%', '10%', '10%', '30%', '30%', '0%'));
  oDBGrid.setHeight(180);
  oDBGrid.allowSelectColumns(true);

  var oMarcar = '<input type="button" name="oMarcar" id="oMarcar" value="M" onclick="js_marcar();">';
  var aHeader = new Array();
  aHeader[0] = oMarcar;
  aHeader[1] = 'Pedido';
  aHeader[2] = 'Data do Pedido';
  aHeader[3] = 'Urgência';
  aHeader[4] = 'Paciente';
  aHeader[5] = 'Prestadora';
  aHeader[6] = 'Data Agend.';
  aHeader[7] = 'Hora Agend.';
  aHeader[8] = 'Opções';
  oDBGrid.setHeader(aHeader);

  var aAligns = new Array();
  aAligns[0] = 'center';
  aAligns[1] = 'center';
  aAligns[2] = 'center';
  aAligns[3] = 'center';
  aAligns[4] = 'left';
  aAligns[5] = 'left';
  aAligns[6] = 'center';
  aAligns[7] = 'center';
  aAligns[8] = 'center';

  oDBGrid.setCellAlign(aAligns);
  oDBGrid.show($('grid_pacientes'));
  oDBGrid.clearAll(true);
        
  //escondo algumas colunas
  //oDBGrid.showColumn(false, 6);
        
  //oDBGrid.drawSelectCol(true);
  //oDBGrid.showSelectCol(true);
  return oDBGrid;
  
}

function js_getPedidosTfdDataRhcbo() {

  if(!js_validaData()) {
	  return false;
  }
  
  oDBGrid.clearAll(true);

  var oParam = new Object();
	oParam.exec = 'getPedidosTfdDataRhcbo';
	oParam.dDataIni      = $F('data1');
	oParam.dDataFim      = $F('data2');
	oParam.iTipo         = $F('sPrestadora'); 
	oParam.iOrdem        = $F('sOrdenar');
	oParam.iNumRegistros = $F('iNumeroResgistros');

  if($F('rh70_estrutural') != '') {
	  oParam.sRhcbo = $F('rh70_estrutural');
  }

  js_ajax(oParam, 'js_retornogetPedidosTfdDataRhcbo');

}

function js_retornogetPedidosTfdDataRhcbo(oRetorno) {
  
  oRetorno = eval("("+oRetorno.responseText+")");

  if(oRetorno.iStatus != 1) {

    alert('Nenhum pedido de TFD encontrado.');
    return false;

  } else {

	  var iI = 0;
    oRetorno.oPedidos.each(
      function (oPedidosTfd) {
        
        var aLinha = new Array();       
        aLinha[0]  = '<input type="checkbox" name="ck' + iI + '" id="ck' + iI + '">'; 
        aLinha[1]  = '<label id="tfd' + iI + '">' + oPedidosTfd.tf01_i_codigo + '</label>';
        aLinha[2]  = js_formataData(oPedidosTfd.tf01_d_datapedido.urlDecode());
        aLinha[3]  = oPedidosTfd.emergencia.urlDecode();
        aLinha[4]  =  oPedidosTfd.paciente.urlDecode();
        aLinha[5]  = oPedidosTfd.nomePrestadora.urlDecode();
        if (oPedidosTfd.codPrestadora != '') {

          aLinha[5] += '<input type="hidden" name="prest' + iI + '" id="prest' + iI;
          aLinha[5] += '" value="' + oPedidosTfd.codPrestadora + '">';

        } else {

          aLinha[5] += '<input type="hidden" name="prest' + iI + '" id="prest' + iI;
          aLinha[5] += '" value="-1">';

        }
        aLinha[6]  = js_formataData(oPedidosTfd.tf16_d_dataagendamento.urlDecode());
        aLinha[7]  = oPedidosTfd.tf16_c_horaagendamento.urlDecode();
        aLinha[8]  = '<input type="button" value="Prest" '+
                      'onclick="js_prestadora('+oPedidosTfd.tf01_i_codigo+', '+
                      oPedidosTfd.z01_i_cgsund+', \''+oPedidosTfd.z01_v_nome.urlDecode()+'\');">'+
                      '&nbsp;&nbsp;<input type="button" value="Saída" '+
                      'onclick="js_saida('+oPedidosTfd.tf01_i_codigo+', '+
                      oPedidosTfd.z01_i_cgsund+', \''+oPedidosTfd.z01_v_nome.urlDecode()+'\');">' +
                      '&nbsp;&nbsp;<input type="button" value="Prot" '+
                      'onclick="js_protocolo('+oPedidosTfd.tf01_i_codigo+');">';

        oDBGridPedidostfd.addRow(aLinha);
        iI++;

    });
    oDBGridPedidostfd.renderRows();

  }

}

/**** Bloco de funções botão Prestadora (início) */
function js_prestadora(iPedido, iCgs, sNome) {

  sChave = '&tf01_i_cgsund='+iCgs+'&z01_v_nome='+sNome+'&tf16_i_pedidotfd='+iPedido;
  js_OpenJanelaIframe('','db_iframe_prestadora','tfd4_tfd_agendamentoprestadora001.php?'+
                      sChave,'Agendamento com a Prestadora', true);


}
/* Bloco de funções botão Prestadora (fim) ****/

/**** Bloco de funções botão Saída (início) */
function js_saida(iPedido, iCgs, sNome) {

  sChave = '&tf01_i_cgsund='+iCgs+'&z01_v_nome='+sNome+'&tf17_i_pedidotfd='+iPedido;
  js_OpenJanelaIframe('','db_iframe_saida','tfd4_tfd_agendasaida001.php?'+
                      sChave,'Agendamento com a Prestadora', true);

}
/* Bloco de funções botão Saída (fim) ****/

/**** Bloco de funções botão Protocolo(início) */
function js_protocolo(tf01_i_codigo) {

  sChave = 'tf01_i_pedidotfd=' + tf01_i_codigo;
  jan = window.open('tfd2_protocolopedidotfd002.php?'+sChave, '', 
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                   );

}
/* Bloco de funções botão Protocolo (fim) ****/

/**** Bloco de funções dados do RHCBO início */

function js_pesquisatf01_i_rhcbo(mostra) {

  if(mostra==true) {

	  js_OpenJanelaIframe('','db_iframe_rhcbo','func_rhcbosaude.php?funcao_js=parent.js_mostrarhcbo1|rh70_estrutural|'+
                        'rh70_descr|rh70_sequencial','Pesquisa',true);

  } else {

    if(document.form1.rh70_estrutural.value != '') {

      js_OpenJanelaIframe('','db_iframe_rhcbo','func_rhcbosaude.php?pesquisa_chave='+
                          document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostrarhcbo','Pesquisa',false);

    } else {

      document.form1.tf01_i_rhcbo.value = ''; 
      document.form1.rh70_descr.value = '';
     
    }

  }

}

function js_mostrarhcbo(chave1,chave2,chave3,erro) {

  document.form1.rh70_estrutural.value = chave1; 
  document.form1.rh70_descr.value = chave2;
  document.form1.tf01_i_rhcbo.value = chave3;

  if(erro==true) {
    document.form1.rh70_estrutural.focus(); 
  }

}

function js_mostrarhcbo1(chave1,chave2,chave3) {

  document.form1.rh70_estrutural.value = chave1;
  document.form1.rh70_descr.value = chave2;
  document.form1.tf01_i_rhcbo.value = chave3;

  db_iframe_rhcbo.hide();

}


/* Bloco de funções dados do RHCBO fim ****/


function js_validaData() {

  if(document.form1.data1.value != ""  && document.form1.data2.value != "") {

    aIni = document.form1.data1.value.split('/');
	  aFim = document.form1.data2.value.split('/');
	  dIni = new Date(aIni[2], aIni[1], aIni[0]);
	  dFim = new Date(aFim[2], aFim[1], aFim[0]);

	  if(dFim < dIni) {
			
	    alert("Data final nao pode ser menor que a data inicial.");
	    document.form1.data2.value = '';
	    return false;

	  }
	  return true;
    
    } else{

	    alert('Preencha o período.');
	    return false

    }
	
}

function js_mandaDados() {

	var lMarcou  = 0;
	var iTam     = oDBGridPedidostfd.getNumRows();
	var oF       = document.form1;
	var sPedidos = 'Pedidos=';
	var sDataini = '&dataini='+oF.data1.value;
	var sDatafim = '&datafim='+oF.data2.value;
  var iRhcbo   = '&codigoespec='+oF.rh70_estrutural.value;
	var iRhdescr = '&especialidade='+oF.rh70_descr.value;    
  
  if (iTam == 0) {

		alert("Selecione registros antes de gerar o relatório.");  
	  return;

	} 
  for (var iI = 0; iI < iTam; iI++) {

    if ($('ck'+iI).checked == true)  {

		  if (sPedidos != 'Pedidos=') {
			  sPedidos += ",";
			}
			sPedidos += $('tfd' + iI).innerHTML;
			lMarcou   = 1;
			  
	  }

  }
	if (lMarcou != 1) {

		alert("Nenhum registro marcado, favor marcar algum.");
	  return;

  }
  
  oJan = window.open('tfd2_agendaprestsaida001.php?'+sPedidos+sDataini+sDatafim+iRhcbo+iRhdescr,'',
                     'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  oJan.moveTo(0,0);
	 
}

function js_selecionarPrestadora() {

	var sPedidos    = 'Pedidos=';
  var sPrestadora = '&pedPrestadora=';
  var codPrest    = -1;
  var iPedPrest   = -1
  var lMarcou     = 0;
  var iTam        = oDBGridPedidostfd.getNumRows();
	for (var iI = 0; iI < iTam; iI++) {

	  if ($('ck'+iI).checked == true)  {

		  if (codPrest != -1 && $('prest' + iI).value != -1 && codPrest != $('prest' + iI).value) {

		   alert("Existem prestadoras diferentes.");
			 return;

		  }
		  if (sPedidos != 'Pedidos=') {
			  sPedidos += ",";
		  }
		  sPedidos += $('tfd' + iI).innerHTML;
		  lMarcou   = 1;
		  if ($('prest' + iI).value != codPrest) {
			  
		    codPrest  = $('prest' + iI).value;
		    iPedPrest = $('tfd' + iI).innerHTML;

			}
		  
		}

	}
	if (lMarcou != 1) {

	  alert("Nenhum registro marcado para informar uma prestadora.");
    return;

	}
	if (iPedPrest != -1) {

		sPrestadora += iPedPrest;
	  sPedidos    += sPrestadora;

  }
	js_OpenJanelaIframe('','db_iframe_saida','tfd4_tfd_agendasaida002.php?'+
		                	sPedidos,'Prestadora', true);
	
}

function js_formataData(dData) {
	  
  if (dData == undefined || dData.length != 10) {
	  return dData;
	}
	return dData.substr(8,2) + '/'+dData.substr(5,2) + '/'+dData.substr(0,4);

}

function js_marcar() {

  for(var iI = 0; iI < oDBGridPedidostfd.getNumRows(); iI++) {
    $('ck'+iI).checked = ($('oMarcar').value == 'M') ? 1 : 0;  
  }
  if ($('oMarcar').value == 'M') {
	  $('oMarcar').value = 'D';
  } else {
	  $('oMarcar').value = 'M';
  }
	
}

function js_limpar() {
  
  oDBGrid.clearAll(true);
  $('data1').value             = '';
  $('data2').value             = '';
  $('iNumeroResgistros').value = '0';
  $('rh70_estrutural').value   = '';
  $('rh70_descr').value        = '';

}

</script>