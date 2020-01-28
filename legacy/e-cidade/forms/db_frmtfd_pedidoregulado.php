<?
/*
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

//MODULO: TFD
$oDaoCgsUnd->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("tf29_i_prontuario");
$oRotulo->label("tf30_i_encaminhamento");
$oRotulo->label("tf01_i_cgsund");
$oRotulo->label("s115_c_cartaosus");
$oRotulo->label("j13_codi");
$oRotulo->label("tf01_i_rhcbo");
$oRotulo->label("rh70_estrutural");
$oRotulo->label("rh70_descr");
$oRotulo->label("sd02_i_codigo");
$oRotulo->label("sd03_i_codigo");
$oRotulo->label("z01_nome");
$oRotulo->label("tf34_i_especmedico");
?>
<form name="form1" method="post" action="">
<center>
<fieldset class="separator" style='width: 92%;'> <legend><b>Pedidos de TFD</b></legend>
  <table border="0" width="90%">
    <tr>
    <td>
     <b>Início:</b>
    </td>
      <td>
        <?
        db_inputdata('data1', @$dia1, @$mes1, @$ano1, true, 'text', 1, "");
        ?>
      </td>
    </tr>
    <tr>
    <td>
      <b>Fim:</b>
    </td>
      <td>
        <?
        db_inputdata('data2', @$dia2, @$mes2, @$ano2, true, 'text', 1, "onchange=js_validaData();");
        ?>
      </td>
    </tr>
   <tr>
    <td nowrap title="<?=@$Ttf01_i_rhcbo?>">
      <?
      db_ancora(@$Ltf01_i_rhcbo, "js_pesquisatf01_i_rhcbo(true);", $db_opcao);
      ?>
    </td>
    <td colspan="2">
      <?
      db_input('rh70_estrutural', 10, @$Irh70_estrutural, true, 'text', $db_opcao,
               " onchange='js_pesquisatf01_i_rhcbo(false);'"
              );
      db_input('tf01_i_rhcbo', 10, @$Itf01_i_rhcbo, true, 'hidden', 3);
      db_input('rh70_descr', 50, @$Irh70_descr, true, 'text', 3, '');
      ?>
    </td>
  </tr>
    <tr>
      <td colspan="2" align="center">
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisa" onclick="js_getPedidosTfdDataRhcbo();" >
      </td>
    </tr>
  </table>
  </fieldset>
  <fieldset style='width: 92%;'> <legend><b>Pacientes</b></legend>
  <table border="0" width="100%">
    <tr>
      <td>
        <div id='grid_pacientes' style='width: 100%;'></div>
      </td>
    </tr>
  </table>
 </fieldset>

<fieldset class="separator" style='width: 92%;'> <legend><b>Regulador</b></legend>
  <table border="0" width="90%">
    <tr>
      <td>
        <b>Regulador:</b>
      </td>
      <td>
        <?
          db_input('sd03_i_codigo', 5, $Isd03_i_codigo, true, 'text', 3, " onchange='js_pesquisa_medico(false);'");
          db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '');
        ?>
      </td>
    </tr>
    <tr>
      <td>
        <?
          db_ancora("<b>Especialidade:</b>", "js_pesquisa_especmedico(true); ","");
        ?>
      </td>
      <td>
        <?
          db_input('tf34_i_especmedico', 5, $Itf34_i_especmedico, true, 'hidden');
          db_input('rh70_estrutural2', 5, $Irh70_estrutural, true, 'text', "",
                   " onchange='js_pesquisa_especmedico(false);' ");
          db_input('rh70_descr2', 40, $Irh70_descr, true, 'text', 3);

          db_input('sPedidosSelecionados', 20, '', true, 'hidden', 1, '');
          db_input('sPedidosExcluidos', 20, '', true, 'hidden', 1, '');
          $sd03_i_codigo = $oDados->sd03_i_codigo;
          db_input('sd03_i_codigo', 2, '', true, 'hidden', 1, '');
          // número de linhas no grid
          $numero = 0;
          db_input('numero', 2, '', true, 'hidden', 1, '');
        ?>
      </td>
    </tr>
    <tr>
      <td><b>Unidade:</b></td>
      <td>
        <?
          db_input ('sd02_i_codigo', 5, $Isd02_i_codigo, true, 'text', 3, '');
          db_input ('descrdepto', 40, $Isd02_i_codigo, true, 'text', 3, '');
        ?>
      </td>
    </tr>
  </table>
</fieldset>

 <table border="0">
    <tr>
      <td align="right">
        <input name="confirmar" type="submit" id="confirmar" value="Confirmar" onclick="return js_validaEnvio();" >
        <input name="rel1" type="button" id="rel1" value="Relatório Regulados" onclick="js_mandaDados(1);">
        <input name="rel2" type="button" id="rel2" value="Relatório Não Regulados" onclick="js_mandaDados(2);">
        <input name="limpar" type="button" id="limpar" value="Limpar" onclick="js_limpar();" >
      </td>
    </tr>
  </table>
</center>
</form>
<script>

oDBGridPedidostfd = js_cria_datagrid();
sUrl = 'tfd4_pedidotfd.RPC.php';

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

function js_validaEnvio() {

  if ($F('tf34_i_especmedico') == null || $F('tf34_i_especmedico') == '') {

    alert('Informe a especialidade do regulador.');
    return false;

  }

  var iContChecked = 0;
  iTam             = document.form1.numero.value;
  if (iTam > 0) {

    var sPedidosSelecionados  = '';
    var sPedidosExcluidos     = '';
    var sSep                  = '';
    var sSep2                 = '';

    for (iCont = 0; iCont < iTam; iCont++) {

      if ($("ckbox"+iCont).checked) {

        iPedido = $F("ckbox"+iCont);
        sPedidosSelecionados += sSep+iPedido;
        sSep                  = ',';
        iContChecked++;

      } else {

        if ($F('ckjasel'+iCont) == 'true') {

          /* marco o check box para poder obter o valor dele */
          $("ckbox"+iCont).checked = true;
          iPedido = $F("ckbox"+iCont);
          $("ckbox"+iCont).checked = false;
          sPedidosExcluidos += sSep2+iPedido;
          sSep2              = ',';

        }

      }

    }
    if (iContChecked == 0) {

      alert('Selecione ao menos 1 pedido! ');
      return false;

    }

    $('sPedidosSelecionados').value = sPedidosSelecionados;
    $('sPedidosExcluidos').value    = sPedidosExcluidos;

  } else {

    alert('Selecione ao menos 1 pedido! ');
    return false;

  }

  return true;

}

function js_formataData(dData) {

  if (dData == undefined || dData.length != 10) {
    return dData;
  }
  return dData.substr(8,2)+'/'+dData.substr(5,2)+'/'+dData.substr(0,4);

}

function js_getPedidosTfdDataRhcbo() {

  oDBGrid.clearAll(true);

  if (!js_validaData()) {
	  return false;
  }

  var oParam          = new Object();
	oParam.exec         = 'getPedidosTfdDataRhcboRegulado';
	oParam.dDataIni     = $F('data1');
	oParam.dDataFim     = $F('data2');

  if ($F('rh70_estrutural') != '') {
	  oParam.sRhcbo = $F('rh70_estrutural');
  }

  js_ajax(oParam, 'js_retornogetPedidosTfdDataRhcbo');

}

function js_retornogetPedidosTfdDataRhcbo(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    alert('Nenhum pedido de TFD encontrado.');
    return false;

  } else {

    iCont = 0;
    oRetorno.oPedidos.each(
      function (oPedidosTfd) {

        var aLinha         = new Array();
        var sDisabled      = '';
        var sChecked       = '';
        var sJaSelecionado = '';

        // verifico se já foi agendada a saída. Se sim, o checkbox deve ficar desabilitado para não ser desmarcado
        if (oPedidosTfd.tf17_i_codigo != '') {
          sDisabled = ' disabled ';
        }
        if (oPedidosTfd.tf34_i_codigo != '') {

          sChecked       = ' checked ';
          sJaSelecionado = 'true';

        } else {
          sJaSelecionado = 'false';
        }

        aLinha[0]  = oPedidosTfd.tf04_c_abreviatura.urlDecode();
        aLinha[1]  = oPedidosTfd.tf01_i_codigo;
        aLinha[2]  = js_formataData(oPedidosTfd.tf01_d_datapedido.urlDecode());
        aLinha[3]  = oPedidosTfd.emergencia.urlDecode();
        aLinha[4]  = js_formataData(oPedidosTfd.tf01_d_datapreferencia.urlDecode());
        aLinha[5]  = oPedidosTfd.paciente.urlDecode();
        aLinha[6]  = '<input type="checkbox" value="'+oPedidosTfd.tf01_i_codigo+'" ';
        aLinha[6] += 'id="ckbox'+iCont+'" name="ckbox" '+sChecked+sDisabled;
        aLinha[6] += ' onclick="js_verificaProcedimentos(this, '+sJaSelecionado+')">';
        aLinha[6] += '<input type="hidden" value="'+sJaSelecionado+'" ';
        aLinha[6] += 'id="ckjasel'+iCont+'" name="ckboxjasel">';

        oDBGridPedidostfd.addRow(aLinha);
        oDBGridPedidostfd.aRows[ iCont ].aCells[5].addClassName( 'elipse' );
        iCont++;

    });
    $('numero').value = iCont;
    oDBGridPedidostfd.renderRows();

  }

}

/**** Bloco de funções do grid início */
function js_cria_datagrid() {

        oDBGrid = new DBGrid('grid_pacientes');
        oDBGrid.nameInstance = 'oDBGridPedidostfd';
        oDBGrid.hasTotalizador = false;
        oDBGrid.setCellWidth(new Array('7.5%', '7.5%', '12.5%', '10%', '12.5%', '35%', '15%'));
        oDBGrid.setHeight(180);

        var aHeader = new Array();
        aHeader[0] = 'Tipo';
        aHeader[1] = 'Pedido';
        aHeader[2] = 'Data';
        aHeader[3] = 'Urgência';
        aHeader[4] = 'Preferência';
        aHeader[5] = 'Paciente';
        aHeader[6] = 'Confirmado';
        oDBGrid.setHeader(aHeader);

        var aAligns = new Array();
        aAligns[0] = 'center';
        aAligns[1] = 'center';
        aAligns[2] = 'center';
        aAligns[3] = 'center';
        aAligns[4] = 'center';
        aAligns[5] = 'left';
        aAligns[6] = 'center';

        oDBGrid.setCellAlign(aAligns);
        oDBGrid.show($('grid_pacientes'));
        oDBGrid.clearAll(true);

        return oDBGrid;

}


/**** Bloco de funções dados do RHCBO início */

function js_pesquisatf01_i_rhcbo(mostra) {

  if (mostra==true) {

	  js_OpenJanelaIframe('','db_iframe_rhcbo','func_rhcbosaude.php?funcao_js=parent.js_mostrarhcbo1|rh70_estrutural|'+
                        'rh70_descr|rh70_sequencial','Pesquisa',true
                       );

  } else {

    if (document.form1.rh70_estrutural.value != '') {

      js_OpenJanelaIframe('','db_iframe_rhcbo','func_rhcbosaude.php?pesquisa_chave='+
                          document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostrarhcbo','Pesquisa',false
                         );

    } else {

      document.form1.tf01_i_rhcbo.value = '';
      document.form1.rh70_descr.value = '';

    }

  }

}

function js_mostrarhcbo(chave1, chave2, chave3, erro) {

  document.form1.rh70_estrutural.value = chave1;
  document.form1.rh70_descr.value      = chave2;
  document.form1.tf01_i_rhcbo.value    = chave3;

  if (erro==true) {
    document.form1.rh70_estrutural.focus();
  }

}

function js_mostrarhcbo1(chave1, chave2, chave3) {

  document.form1.rh70_estrutural.value = chave1;
  document.form1.rh70_descr.value      = chave2;
  document.form1.tf01_i_rhcbo.value    = chave3;

  db_iframe_rhcbo.hide();

}


/* Bloco de funções dados do RHCBO fim ****/


function js_validaData() {

  if (document.form1.data1.value != ""  && document.form1.data2.value != "") {

    aIni = document.form1.data1.value.split('/');
	  aFim = document.form1.data2.value.split('/');
	  dIni = new Date(aIni[2], aIni[1], aIni[0]);
	  dFim = new Date(aFim[2], aFim[1], aFim[0]);

	  if (dFim < dIni) {

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

function js_verificaProcedimentos(oCheck, sJaSel) {

  if (sJaSel == 'true' || !oCheck.checked) {
    return true;
  }

  if ($F('tf34_i_especmedico') == null || $F('tf34_i_especmedico') == '') {

    alert('Selecione a especialidade do regulador primeiro.');
    oCheck.checked = false;
	  return false;

  }

  var oParam          = new Object();
	oParam.exec         = 'verificaProcedimentosEspecMedico';
	oParam.iEspecMedico = $F('tf34_i_especmedico');
	oParam.iPedido      = oCheck.value;
  oParam.sIdCkBox     = oCheck.id;

  js_ajax(oParam, 'js_retornoVerificaProcedimentosEspecMedico');

}

function js_retornoVerificaProcedimentosEspecMedico(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    alert('Este pedido de TFD possui procedimentos que não são abrangidos pela '+
          'especialidade do regulador selecionada.'
         );
    $(oRetorno.sIdCkBox).checked = false;

  }

}

function js_mandaDados(iTipo) {

  if (!js_validaData()) {
	  return false;
  }

  sDataini = 'dataini='+$F('data1');
  sDatafim = '&datafim='+$F('data2');
  iRhcbo   = '&codigoespec='+$F('rh70_estrutural');
  iRhdescr = '&especialidade='+$F('rh70_descr');

  oJan = window.open('tfd2_pedidosregulados001.php?'+sDataini+sDatafim+iRhcbo+iRhdescr+'&iTipo='+iTipo,'',
                     'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                    );
  oJan.moveTo(0,0);

}

function js_limpaGrid() {
  oDBGrid.clearAll(true);
}

function js_limpar() {

  oDBGrid.clearAll(true);
  $('data1').value = '';
  $('data2').value = '';
  $('rh70_estrutural').value = '';
  $('rh70_descr').value = '';

}

function js_pesquisa_especmedico(lMostra) {

	  if ($('sd03_i_codigo').value == null || $('sd03_i_codigo').value == '') {

	    alert('Médico não informado');
	    return false;

	  }
	  sParam  = 'funcao_js=parent.js_mostraespecmedico|sd27_i_codigo|rh70_estrutural|rh70_descr|sd02_i_codigo|descrdepto';
	  if (lMostra == false) {

	    if ($('rh70_estrutural').value != null && $('rh70_estrutural').value != '') {

	      sParam += '&nao_mostra=true';
	      sParam += '&chave_rh70_estrutural='+$('rh70_estrutural').value;

	    } else {

	      $('tf11_especmedico').value = '';
	      $('rh70_estrutural').value  = '';
	      $('rh70_descr.value').value = '';
	      $('sd02_i_codigo').value    = '';
	      $('descrdepto').value       = '';
	      return false;

	    }

	  }

	  sParam += '&chave_sd04_i_medico='+$('sd03_i_codigo').value
	  sUrl    = 'func_especmedico.php?'+sParam;
	  js_OpenJanelaIframe('', 'db_iframe_medicos', sUrl, 'Pesquisa', lMostra);

	}

	function js_mostraespecmedico(chave1, chave2, chave3, chave4, chave5) {

	  if (chave1 == '') {

	    $('tf34_i_especmedico2').value = '';
	    $('rh70_estrutural2').value  = '';
	    $('rh70_descr').value       = chave2;
	    $('sd02_i_codigo').value    = '';
	    $('descrdepto').value       = '';
	    return false;

	  }
	  $('tf34_i_especmedico').value = chave1;
	  $('rh70_estrutural2').value  = chave2;
	  $('rh70_descr2').value       = chave3;
	  $('sd02_i_codigo').value    = chave4;
	  $('descrdepto').value       = chave5;
	  db_iframe_medicos.hide();

	}
</script>