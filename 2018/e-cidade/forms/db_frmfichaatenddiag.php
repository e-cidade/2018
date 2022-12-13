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

//MODULO: saude

$clrotulo = new rotulocampo;
//Prontuário
$clrotulo->label("sd24_i_codigo");
$clrotulo->label("z01_v_nome");
$clrotulo->label("sd24_t_diagnostico");
$clrotulo->label("sd24_c_digitada");

//ProntCid
$clprontcid->rotulo->label();

//sau_cid
$clrotulo->label("sd70_c_cid");
$clrotulo->label("sd70_c_nome");

$lBotaoNovaConsulta = true;
if( $_SESSION['DB_itemmenu_acessado'] == 6828) {
  $lBotaoNovaConsulta = false;
}

?>
<br><br>
<form name="form1" method="post" action="">
  <table>
    <tr>
      <td>
      <fieldset>
        <legend>
          <label class="bold">Diagnóstico</label>
        </legend>
        <table>
          <!-- Prontuário -->
          <tr>
            <td nowrap title="<?=@$Tsd24_i_codigo?>">
              <?=@$Lsd24_i_codigo?>
            </td>
            <td>
              <?php
              db_input( 'sd24_i_codigo', 10, $Isd24_i_codigo, true, 'text', 3 );
              db_input( 'z01_v_nome',    48, $Iz01_v_nome,    true, 'text', 3 );
              ?>
            </td>
          </tr>

          <!-- DIAGNOSTICO -->
          <tr>
            <td valign="top" nowrap title="<?=@$Tsd24_t_diagnostico?>">
              <?=@$Lsd24_t_diagnostico?>
            </td>
            <td colspan="2">
              <?php
              $sd24_t_diagnostico = !isset( $sd24_t_diagnostico ) ? ' ' : $sd24_t_diagnostico;
              db_textarea( 'sd24_t_diagnostico', 1, 59, $sd24_t_diagnostico, true, 'text', $db_opcao, "onKeypress=js_keypress();" );
              ?>
            </td>
          </tr>

        </table>
      </fieldset>
      </td>
    </tr>
    <tr>
      <td align="center">
        <input name="gravaemite" type="button" id="btn_gravaemite" value="Emite FAA" onclick="js_gravaemite('emite');">
        <input name="prontuario" type="button" id="prontuario" value="Prontuário" onclick="js_prontuarioMedico();">

        <?php
          if ($lBotaoNovaConsulta) {
            echo '<input name="novaconsulta" type="button" id="btn_novaconsulta"
                         value="Nova Consulta" onclick="js_novaconsulta();">';
          }

          selectModelosFaa($oSauConfig->s103_i_modelofaa);
        ?>
      </td>
    </tr>

  </table>
</form>

<!--
/**
 * Funções JavaScript
 */
-->
<script>

/**
 * Função Ajax
 * strAction: incluir, excluir, alterar
 * strParam : parametros
 * strURL   : url chmada
 * strRetorno: função para retorno do ajax
 */
function js_ajax( strAction, strParam, strURL ){

  strURL = strURL == undefined ? 'sau4_fichaatendabas004_1.php' : strURL;

	js_divCarregando( "Aguarde....", "msgBox" );

  new Ajax.Request(
             strURL,
             {
               method    : 'post',
               parameters: strParam+'&strAction='+strAction,
               onComplete: js_retornoOpcao
             }
            );
}

/**
 * Retorno Ajax
 */
function js_retornoOpcao( ajxRetorno ) {

	var objRetorno  = eval("("+ajxRetorno.responseText+")");
	var obj         = document.form1;
	var strInsert;

	js_removeObj("msgBox");

  alert( objRetorno.mensagem.urlDecode() );

	if( objRetorno.erro == false ) {

		switch( objRetorno.action ) {

			case 'incluir':

				strInsert  = "'" + objRetorno.codigo + "', ";
				strInsert += "'" + obj.sd70_c_cid.value + "', ";
				strInsert += "'" + obj.sd70_c_nome.value + "', ";
				strInsert += "'" + (obj.sd55_b_principal.value == 't' ? 'SIM' : 'NÃO' ) + "' ";
				strInsert  = 'js_incluirlinhas( ' + strInsert + ' )';
				eval( strInsert );
				js_recuperardados( '', '', '', '');

				break;

			case 'excluir':

				var tab = criatabela.document.getElementById('tab');
				tab.deleteRow( objRetorno.id_tab );

				break;

			case 'gravar':

				document.getElementById("btn_gravaemite").value   = "Emite FAA";
				document.getElementById("btn_gravaemite").onclick = function(){ js_gravaemite('emite') };

				break;
		}
	}
}

/**
 * Botão Cancelar
 */
function js_cancelar() {

	js_recuperardados( '', '', '', '');
	js_refresh();
	js_alterarexcluir('incluir');
}

/**
 * Botão Voltar
 */
function js_voltar() {

	if( parent.iframe_a4 == undefined ) {
		parent.mo_camada('a1');
	} else {
		parent.mo_camada('a3');
	}
}

/**
 * onKeypress - para Diagnóstico, Atendida e Faturada
 */
function js_keypress() {

	document.getElementById("btn_gravaemite").value   = "Gravar";
	document.getElementById("btn_gravaemite").onclick = function(){ js_gravaemite('grava') };
}

/**
 * Botão Grava / Emite FAA
 */
function js_gravaemite( opcao ) {

	switch( opcao ) {

		case 'emite':

		  js_emitirFaa();
			break;

		case 'grava':

			var strAction = 'gravar';
			var strParam  = '';
			var strURL    = 'sau4_fichaatendabas004_2.php';

			strParam += 'sd24_i_codigo='+$F('sd24_i_codigo');
			strParam += '&sd24_t_diagnostico='+ encodeURIComponent(tagString($F('sd24_t_diagnostico')));

			js_ajax( strAction, strParam, strURL );
			break;
	}
}

function js_emitirFaa() {

  if ($F('sd24_i_codigo') != '') {

    var oParam               = new Object();
    oParam.exec              = 'gerarFAATXT';
    oParam.sChaveProntuarios = $F('sd24_i_codigo');
    oParam.iModelo           = $F('s103_i_modelofaa');
    js_webajax(oParam, 'js_retornoEmissaofaa', 'sau4_ambulatorial.RPC.php');
  } else {
    alert('Nenhuma FAA para gerar.');
  }
}

function js_retornoEmissaofaa (oAjax) {

  oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.iStatus == 2) {

    message_ajax(oRetorno.sMessage.urlDecode());
    return false;
  } else {

    if (oRetorno.iTipo == 1) {
      js_emitiefaaPDF (oRetorno);
    } else {
      js_emitirfaaTXT (oRetorno);
    }
  }
}

function js_emitiefaaPDF (oDados) {

  sChave = '?chave_sd29_i_prontuario='+oDados.sChaveProntuarios;
  var WindowObjectReference;
  var strWindowFeatures = "menubar=yes,location=no,resizable=yes,scrollbars=yes,status=yes";
  sArquivo = js_getArquivoFaa($F('s103_i_modelofaa'));
  WindowObjectReference = window.open(sArquivo+sChave,"CNN_WindowName", strWindowFeatures);
}

function js_emitirfaaTXT (oRetorno) {

  iTop    = 20;
  iLeft   = 5;
  iHeight = screen.availHeight-210;
  iWidth  = screen.availWidth-35;
  sChave  = 'sSessionNome='+oRetorno.sSessionNome;

  js_OpenJanelaIframe ('', 'db_iframe_visualizador', 'sau2_fichaatend002.php?'+sChave,
                       'Visualisador', true, iTop, iLeft, iWidth, iHeight
                      );
}

function js_getArquivoFaa(iCodModelo) {

  oSel = $('sArquivoFaa');
  for (var iCont = 0; iCont < oSel.length; iCont++) {

    if (iCodModelo == oSel.options[iCont].value) {
      return oSel.options[iCont].text;
    }
  }
}

/**
 * Botão Nova Consulta
 */
function js_novaconsulta() {

	parent.document.formaba.a2.disabled = true;

	if( parent.iframe_a4 == undefined ) {

		parent.iframe_a1.js_pesquisaprontuarios();
		parent.mo_camada('a1');
	} else {

    parent.document.formaba.a3.disabled = true;
    parent.iframe_a1.location.href='sau4_fichaatendabas001.php';
    parent.mo_camada('a1');
	}
}

function js_prontuarioMedico() {

  if (document.form1.sd24_i_codigo.value == '') {
    alert('Informe a FAA!');
  } else {

	  iTop  = (screen.availHeight - 800) / 2;
	  iLeft = (screen.availWidth - 800) / 2;
	  sUrl  = 'sau4_relatorioprontuariomedico.iframe.php?';
	  sUrl += 'iFaa='+document.form1.sd24_i_codigo.value;

	  js_OpenJanelaIframe('', 'db_iframe_relatorioprontuario', sUrl, 'Prontuário Médico', true, '20', iLeft, 700, 320);
  }
}
</script>