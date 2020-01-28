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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_cidadao_classe.php");
require_once("classes/db_cidadaofamilia_classe.php");
require_once("classes/db_cidadaofamiliavisita_classe.php");

$oRotuloCampos = new rotulocampo();
$oRotuloCampos->label("as05_sequencial");
$oRotuloCampos->label("as05_cidadaofamilia");
$oRotuloCampos->label("as05_datavisita");
$oRotuloCampos->label("as05_horavisita");
$oRotuloCampos->label("as05_observacao");
$oRotuloCampos->label("as04_sequencial");
$oRotuloCampos->label("as15_codigofamiliarcadastrounico");
$oRotuloCampos->label("ov02_nome");
$oRotuloCampos->label("ov02_sequencial");
$oRotuloCampos->label("z01_numcgm");
$oRotuloCampos->label("z01_nome");
$oRotuloCampos->label("as10_sequencial");
$oRotuloCampos->label("as10_data");
$oRotuloCampos->label("as10_profissionalcontato");
$oRotuloCampos->label("as05_profissional");
$oRotuloCampos->label("as02_nis");
$oRotuloCampos->label("as13_sequencial");
$aOpcoes = array("0" => "Não", "1" => "Sim");

?>
  <table >
    <tr style="display: none">
      <td>
        <b><?=$Las05_sequencial?></b>
      </td>
      <td>
        <?php
          db_input("as05_sequencial", 10, $Ias05_sequencial, true, "text", 3); 
          db_input("as04_sequencial", 10, $Ias04_sequencial, true, "text", 3);
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap="nowrap" style="font-weight: bold;">
        <? db_ancora("Cidadão: ","js_pesquisaCidadao(true, false);", $db_opcao);?>
      <td nowrap="nowrap">
        <?php
          db_input("codigoCidadao", 10, $Iov02_sequencial, true, "text", $db_opcao, 
                   "onchange='js_pesquisaCidadao(false, false);'");
          db_input("nome",          40, '', true, "text", 3);
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap="nowrap" style="font-weight: bold;">
        <?php
          db_ancora("NIS:", "js_pesquisaCidadao(true, true);", $db_opcao);
        ?>
      </td>
      <td>
      <?php 
        db_input("as02_nis", 10, $Ias02_nis, true, "text", $db_opcao, "onchange='js_pesquisaCidadao(false, true);'");
      ?>
      </td>
    </tr>
    <tr>
      <td>
        <?php
          db_ancora("<b>Profissional que Realizou a Visita: </b>", "js_pesquisaProfissionalVisita(true)", $db_opcao);
        ?>
      </td>
      <td>
        <?php
          db_input("as05_profissional", 10, $Ias05_profissional, true, "text", $db_opcao, 
                   "onChange='js_pesquisaProfissionalVisita(false);'");
          db_input("profissionalVisita", 40, "", true, "text", 3);
        ?>
      </td>
    </tr>
    <tr>
      <td>
        <?php
          db_ancora("<b>Tipo de Visita: </b>", "js_pesquisaTipoVisita(true)", $db_opcao);
        ?>
      </td>
      <td>
        <?php
          db_input("iTipoVisita", 10, $Ias13_sequencial, true, "text", $db_opcao, 
                   "onChange='js_pesquisaTipoVisita(false);'");
          db_input("sTipoVisita", 40, "", true, "text", 3);
        ?>
      </td>
    </tr>
    <tr id='localEncaminhamento' style="display: none;">
      <td>
        <?php
          db_ancora("<b>Local de Encaminhamento: </b>", "js_pesquisaLocalEncaminhamento(true)", $db_opcao);
        ?>
      </td>
      <td>
        <?php
          db_input("iCgmEncaminhamento", 10, $Ias13_sequencial, true, "text", $db_opcao, 
                   "onChange='js_pesquisaLocalEncaminhamento(false);'");
          db_input("sNomeEncaminhamento", 40, "", true, "text", 3);
          db_input("sExigeLocalEncaminhamento", 10, "", true, "hidden", 3);
          
        ?>
      </td> 
    </tr>
    <tr>
      <td>
        <b><?=@$Las05_datavisita?></b>
      </td>
      <td>
        <?php
        db_inputdata("as05_datavisita", null, null, null, true, "text", $db_opcao);
        ?>
      </td>
    </tr>
    <tr>
      <td>
        <b><?=$Las05_horavisita?></b>
      </td>
      <td>
        <?php
          db_input("as05_horavisita", 10, $Ias05_horavisita, true, "text", $db_opcao, "onBlur='js_validaHora();'"); 
        ?>
      </td>
    </tr>
    <tr>
      <td>
        <label for="aparelho_rede_eletrica">
          <b>Alguém da família utiliza algum aparelho<br> ligado na rede elétrica continuamente:</b> 
        </label>
      </td>
      <td>
        <?php
          db_select("aparelho_rede_eletrica", $aOpcoes, "", 1);
        ?>
      </td>
    </tr>
    <tr>
      <td>
        <label for="contato_telefone"><b>Entrou em contato por telefone<br> com algum membro da família: </b></label>
      </td>
      <td>
        <?php
          db_select("contato_telefone", $aOpcoes, "", 1, "onChange='js_liberaOpcoes();'");
        ?>
      </td>
    </tr>
    <tr style="display: none">
      <td>
        <b><?=$Las10_sequencial?></b>
      </td>
      <td>
        <?php
          db_input("as10_sequencial", 10, $Ias10_sequencial, true, "text", 3); 
        ?>
      </td>
    </tr>
    <tr id="profissional" style="display: none">
      <td>
        <?php
          db_ancora("<b>Profissional que Realizou o Contato: </b>", 
                    "js_pesquisaProfissionalContato(true)", 
                    $db_opcao);
        ?>
      </td>
      <td>
        <?php
          db_input("as10_profissionalcontato", 
                   10, 
                   $Ias10_profissionalcontato, 
                   true, 
                   "text", 
                   $db_opcao, 
                   "onChange='js_pesquisaProfissionalContato(false);'");
          db_input("profissionalContato", 40, "", true, "text", 3);
        ?>
      </td>
    </tr>
    <tr id="dataContato" style="display: none">
      <td>
        <b><?=@$Las10_data?></b>
      </td>
      <td>
        <?php
        db_inputdata("as10_data", null, null, null, true, "text", $db_opcao);
        ?>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <fieldset>
        <legend><b>Relatório</b></legend>
          <?php 
          db_textarea("as05_observacao", 4, 82, $Ias05_observacao, true, "text", $db_opcao);
          ?>
        </fieldset>
      </td>
    </tr>
  </table>
<script>
var sUrlRPC  = 'soc4_datascadastrounico.RPC.php';
var iDbOpcao = <?=$db_opcao;?>;
$('aparelho_rede_eletrica').style.width = '92px';
$('contato_telefone').style.width       = '92px';

/**
 * Função para busca do cidadao 
 */
function js_pesquisaCidadao(lMostra, lNis) {

  var sUrl = 'func_cidadaofamiliacompleto.php?';
  
  if (lMostra) {

    sUrl += 'funcao_js=parent.js_mostraCidadao|ov02_sequencial|ov02_nome|as02_nis|as04_sequencial'; 
    js_OpenJanelaIframe('top.corpo', 'db_iframe_cidadaofamilia', sUrl, 'Pesquisa Cidadão',true);
  } else {

    if ($F('as02_nis') != '' && lNis) {
      
      sUrl += 'pesquisa_chave='+$F('as02_nis');
      sUrl += '&lNis=true';
    }
    
    if ($F('codigoCidadao') != ''  && !lNis) {
      
      sUrl += 'pesquisa_chave='+$F('codigoCidadao');
      sUrl += '&lCidadao=true';
    }

    if (($F('as02_nis') == '' && lNis) || ($F('codigoCidadao') == '' && !lNis)) {
      sUrl += 'pesquisa_chave=';
    }
    
    sUrl += '&funcao_js=parent.js_mostraCidadao2';

    if ($F('as02_nis') != '' || $F('codigoCidadao') != '') {
     js_OpenJanelaIframe('top.corpo', 'db_iframe_cidadaofamilia', sUrl, 'Pesquisa Cidadão', false);
    } else {
      
      $('codigoCidadao').value = "";
      $('nome').value          = "";
      $('as02_nis').value     = "";
    }
  }
}
 
function js_mostraCidadao (iCidadao, sCidadao, iNis, iCodigoFamilia) {

  if (iCidadao != "") {
    
    $('codigoCidadao').value   = iCidadao;
    $('nome').value            = sCidadao;
    $('as02_nis').value        = iNis;
    $('as04_sequencial').value = iCodigoFamilia;
  }
  db_iframe_cidadaofamilia.hide();
}

function js_mostraCidadao2(lErro, iCidadao, sCidadao, iNis, iCadUnico, iFamiliaCadUnico, iCodigoFamilia, iCodigoCadUnico) {

  
  $('nome').value            = sCidadao;
  $('codigoCidadao').value   = iCidadao;
  $('as02_nis').value        = iNis;
  $('as04_sequencial').value = iCodigoFamilia;         
  
  if (lErro) {
    
    $('codigoCidadao').value = "";
    $('as02_nis').value      = "";
    $('nome').value          = iCidadao;
    
    if (iCidadao == '') {
      
      $('as02_nis').value      = iCidadao;
      $('codigoCidadao').value = iCidadao;
    }
  }
}


/**
 * Pesquisamos o profissional pelo CGM
 */
function js_pesquisaProfissionalVisita(lMostra) {

  if (lMostra) {

  	js_OpenJanelaIframe('top.corpo', 
  	  	                'db_iframe_cgm', 
  	  	                'func_cgm.php?'+
  	  	                'funcao_js=parent.js_mostraProfissionalVisita1|z01_numcgm|z01_nome', 
  	  	                'Pesquisar Código do Profissional', 
  	  	                true
  	  	               );
  } else {

  	if (document.form1.as05_profissional.value != '') {

    	js_OpenJanelaIframe('top.corpo', 
                          'db_iframe_cgm', 
                          'func_cgm.php?pesquisa_chave='+document.form1.as05_profissional.value+
                                      '&funcao_js=parent.js_mostraProfissionalVisita', 
                          'Pesquisar Código do Profissional', 
                          false
                         );
  	} else {
      document.form1.as05_profissional.value = '';
  	}
  }
}

/**
 * Retorna a busca pelo CGM
 */
function js_mostraProfissionalVisita(erro, chave2) {

  $('profissionalVisita').value = chave2;
  if (erro == true) {

  	document.form1.as05_profissional.focus();
  	document.form1.as05_profissional.value  = '';
  }
}

/**
 * Retorna a busca pelo CGM
 */
function js_mostraProfissionalVisita1(chave1, chave2) {

	document.form1.as05_profissional.value  = chave1;
	document.form1.profissionalVisita.value = chave2;
	db_iframe_cgm.hide();
}

/**
 * Busca os tipos de visita
 */
function js_pesquisaTipoVisita(lMostra) {

  var sUrl = 'func_visitatipo.php?';
  
  if (lMostra) {
    
    sUrl += 'funcao_js=parent.js_mostraTipoVisita|as13_sequencial|as13_descricao|as13_exigeencaminhamento'; 
    js_OpenJanelaIframe('top.corpo', 'db_iframe_visitatipo', sUrl, 'Pesquisa Tipo de Visita',true);
  } else {

    sUrl += 'pesquisa_chave='+$F('iTipoVisita');
    sUrl += '&funcao_js=parent.js_mostraTipoVisita2';

    if ($F('iTipoVisita') != '') {

     js_OpenJanelaIframe('top.corpo', 'db_iframe_visitatipo', sUrl, 'Pesquisa Cidadão', false);
    } else {
      
      $('iTipoVisita').value = "";
      $('sTipoVisita').value     = "";
    }
  }
}

function js_mostraTipoVisita (iTipoVisita, sVisita, sExigeEncaminhamento) {

  db_iframe_visitatipo.hide();
  if (iTipoVisita != "") {
    
    $('iTipoVisita').value = iTipoVisita;
    $('sTipoVisita').value     = sVisita;
  }

  js_validaLocalEncaminhamento(sExigeEncaminhamento);
}

function js_mostraTipoVisita2(sVisita, lErro, sExigeEncaminhamento) {

  $('sTipoVisita').value               = sVisita;
  $('sExigeLocalEncaminhamento').value = sExigeEncaminhamento;
  if (lErro) {
    $('iTipoVisita').value = "";
  }
  js_validaLocalEncaminhamento(sExigeEncaminhamento);
}

/**
 * Valida se mostra os campos de local de Encaminhamento
 */
function js_validaLocalEncaminhamento(sExigeEncaminhamento) {

  $('localEncaminhamento').style.display = 'none';
  $('iCgmEncaminhamento').value  = "";
  $('sNomeEncaminhamento').value = "";
  
  if (sExigeEncaminhamento == 't') {

    $('localEncaminhamento').style.display = 'table-row';
    js_pesquisaLocalEncaminhamento(true);
  }
}

/**
 * Pesquisamos o local de encaminhamento pelo CGM
 */
function js_pesquisaLocalEncaminhamento(lMostra) {

  var sUrl = 'func_cgm.php?';
  if (lMostra) {

    sUrl += 'funcao_js=parent.js_mostraLocalEncaminhamento|z01_numcgm|z01_nome';
  	js_OpenJanelaIframe('top.corpo', 'db_iframe_local', sUrl, 'Pesquisar local de encaminhamento', true );
  } else if ($F('iCgmEncaminhamento') != '') {

    sUrl += 'pesquisa_chave='+$F('iCgmEncaminhamento');
    sUrl += '&funcao_js=parent.js_mostraLocalEncaminhamento1';
    
    js_OpenJanelaIframe('top.corpo', 'db_iframe_local', sUrl, 'Pesquisar local de encaminhamento', false);
    
	} else {
		
	  $('iCgmEncaminhamento').value   = '';
	  $('sNomeEncaminhamento').value  = '';
	}
}

function js_mostraLocalEncaminhamento(iCgm, sNome) {

	$('iCgmEncaminhamento').value  = iCgm;
  $('sNomeEncaminhamento').value = sNome;
  db_iframe_local.hide();
}

function js_mostraLocalEncaminhamento1(lErro, sNome) {

  $('sNomeEncaminhamento').value = sNome;
  if (lErro) {

  	$('iCgmEncaminhamento').focus();
  	$('iCgmEncaminhamento').value  = '';
  }
}


/**
 * Pesquisamos o profissional que fez o contato pelo CGM
 */
function js_pesquisaProfissionalContato(lMostra) {

  if (lMostra == true) {

  	js_OpenJanelaIframe('top.corpo', 
  	  	                'db_iframe_cgm', 
  	  	                'func_cgm.php?'+
  	  	                'funcao_js=parent.js_mostraProfissionalContato1|z01_numcgm|z01_nome',
  	  	                'Pesquisar Código do Profissional', 
  	  	                true
  	  	               );
  } else {

  	if (document.form1.as10_profissionalcontato.value != '') {

    	js_OpenJanelaIframe('top.corpo', 
                          'db_iframe_cgm', 
                          'func_cgm.php?pesquisa_chave='+document.form1.as10_profissionalcontato.value+
                                      '&funcao_js=parent.js_mostraProfissionalContato', 
                          'Pesquisar Código do Profissional', 
                          false
                         );
  	} else {
      document.form1.as10_profissionalcontato.value = '';
  	}
  }
}

/**
 * Retorna a busca pelo CGM
 */
function js_mostraProfissionalContato(erro, chave2) {

  document.form1.profissionalContato.value = chave2;
  if (erro == true) {

  	document.form1.as10_profissionalcontato.focus();
  	document.form1.as10_profissionalcontato.value = '';
  }
}

/**
 * Retorna a busca pelo CGM
 */
function js_mostraProfissionalContato1(chave1, chave2) {

	document.form1.as10_profissionalcontato.value = chave1;
	document.form1.profissionalContato.value      = chave2;
	db_iframe_cgm.hide();
}

/*
 * Inclui informações da visita a uma família
 */
function js_salvarVisita(iOpcao) {

  var oParametro   = new Object();
  oParametro.exec  = 'salvarVisita';

  if ($F('as05_sequencial') != '') {
    oParametro.iCodigoVisita = $F('as05_sequencial');
  }

  if($F("sExigeLocalEncaminhamento") == "t" && $F('iCgmEncaminhamento') == '') {

    var sMsg  = 'Ao selecionar um Tipo de Visita que exige encaminhamento, é obrigatório informar o ';
        sMsg += 'Local de Encaminhamento '; 
    alert(sMsg);
    return false;
  }
  
  oParametro.iCodigoCidadaoFamilia = $F('as04_sequencial');
  oParametro.iProfissionalVisita   = $F('as05_profissional');
  oParametro.dDataVisita           = $F('as05_datavisita');
  oParametro.sHoraVisita           = $F('as05_horavisita');
  oParametro.sAparelhoRedeEletrica = $F('aparelho_rede_eletrica');
  oParametro.sContatoTelefone      = $F('contato_telefone');
  oParametro.sObservacao           = encodeURIComponent(tagString($F('as05_observacao')));
  oParametro.iTipoVisita           = $F('iTipoVisita');
  oParametro.iLocalEncaminhamento  = '';
  
  if ($F('iCgmEncaminhamento') != '') {
    oParametro.iLocalEncaminhamento  = $F('iCgmEncaminhamento');
  }

  /**
   * Caso tenha sido feito contato telefonico, setamos os dados a serem salvos
   */
  if (oParametro.sContatoTelefone == '1') {

    if ($F('as10_sequencial') != '') {
      oParametro.iCodigoVisitaContato = $F('as10_sequencial');
    }
    oParametro.iProfissionalContato = $F('as10_profissionalcontato');
    oParametro.sDataContato         = $F('as10_data');
  }
  
  js_divCarregando("Aguarde... Salvando informações da visita", "msgBox");
  var oAjax = new Ajax.Request (
  	                            sUrlRPC,
  	                            {
  	                             method:     'post',
   	  	                         parameters: 'json='+Object.toJSON(oParametro),
  	                             onComplete: js_retornoSalvarVisita
  	                            }
  	                           );
}

/*
 * Salva as informações da visita à família
 */
function js_retornoSalvarVisita(oResponse) {

	js_removeObj("msgBox");
	var oRetorno = eval("("+oResponse.responseText+")");

	if (oRetorno.status == 1) {

		alert ('Dados salvos com sucesso');
		/**
     * Caso trata-se de uma inclusao ou alteracao, apresentamos a opcao de imprimir o relatorio
     */ 
    if (iDbOpcao == 1 || iDbOpcao == 2) {

      if(confirm('Deseja imprimir um relatório com os registros desta visita?')) {

        var sLocation  = "soc2_visitafamilia002.php?";
        sLocation     += "&sOrigem=procedimento";
        sLocation     += "&iCodigoVisita="+oRetorno.iCodigoVisita;
        sLocation     += "&iCodigoVisitaContato="+oRetorno.iCodigoVisitaContato;
        jan            = window.open(sLocation, 
                                     '', 
                                     'width='+(screen.availWidth-5)+
                                     ',height='+(screen.availHeight-40)+
                                     ',scrollbars=1,location=0');
        jan.moveTo(0,0);
      }
    }
		js_limparDadosVisita();
	} else {
		alert(oRetorno.message.urlDecode());
	}
}

/**
 * Exclui as informacoes de uma visita
 */
function js_excluirVisita() {

  var oParametro = new Object();
  oParametro.exec = 'excluirVisita';
  oParametro.iCodigoVisita = $F('as05_sequencial');
  
  /**
   * Caso tenha sido feito contato telefonico, passamos o codigo do contato para exclusao
   */
  if ($F('contato_telefone') == '1') {

    if ($F('as10_sequencial') != '') {
      oParametro.iCodigoVisitaContato = $F('as10_sequencial');
    }
  }

  js_divCarregando('Aguarde... Excluindo as informações da visita.', 'msgBox');
  var oAjax = new Ajax.Request(
                                sUrlRPC,
                                {
                                  method:     'post',
                                  parameters: 'json='+Object.toJSON(oParametro),
                                  onComplete: js_retornaExcluirVisita
                                }
                              );
}

/**
 * Retorno da exclusao da visita
 */
function js_retornaExcluirVisita(oResponse) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.status == 1) {

    alert('Dados excluidos com sucesso');
    js_limparDadosVisita();
  } else {
    
    alert(oRetorno.message.urlDecode());
    return false;
  }
}

/**
 * Limpamos os campos sempre ao recarregar a pagina
 * @todo usar os id
 */
function js_limparDadosVisita() {

  document.form1.reset();
  js_validaLocalEncaminhamento('f');
	$('profissional').style.display               = "none";
  $('dataContato').style.display                = "none";
}

/*
 * Valida os campos
 */
function js_validarCampos(iOpcao) {

  /**
   * iOpcao identifica se a origem trata-se de:
   * 1 - Inclusao
   * 2 - Alteracao
   * 3 - Exclusao
   * Repassado como parametro para a funcao js_salvarVisita()
   */
	var lStatusErro = false;
	if (document.form1.as02_nis.value == '') {

		alert ('Deve ser informado o NIS do responsável da família');
		document.form1.as04_sequencial.focus();
		lStatusErro = true;
		return false;
	}
	
	if ($F('nome') == '') {

		alert ('Deve ser informado o nome do responsável da família');
		document.form1.ov02_nome.focus();
		lStatusErro = true;
		return false;
	}

	if (document.form1.as05_profissional.value == '') {

		alert ('Deve ser informado o profissional que visitou a família');
		document.form1.as05_profissional.focus();
		lStatusErro = true;
		return false;
	}
	
	if (document.form1.as05_datavisita.value == '') {

		alert ('Deve ser informada a data de visita à família');
		document.form1.as05_datavisita.focus();
		lStatusErro = true;
		return false;
	}
	
	/**
	 * Caso tenha sido feito contato por telefone, validamos os campos liberados
	 */
  if ($('contato_telefone').value == '1') {

    if (document.form1.as10_profissionalcontato.value == '') {

  		alert ('Deve ser informado o profissional que realizou o contato com a família.');
  		document.form1.as10_profissionalcontato.focus();
  		lStatusErro = true;
  		return false;
  	}

    if (document.form1.as10_data.value == '') {

  		alert ('Deve ser informada a data do contato com a família');
  		document.form1.as10_data.focus();
  		lStatusErro = true;
  		return false;
  	}
    
  }
	
	if (lStatusErro == false) {

		if (iOpcao == 3) {
			js_excluirVisita();
		} else {
		  js_salvarVisita(iOpcao);
		}
	}
}

/**
 * Validamos se eh uma hora valida
 */
function js_validaHora() {

  var iHora    = $F('as05_horavisita').substr(0, 2);
  var iMinutos = $F('as05_horavisita').substr(2, 2);

  if (iHora > 23 || iMinutos > 59) {

    alert('Formato da hora da visita inválido.');
    $('as05_horavisita').value = "00:00";
    $('as05_horavisita').focus();
    return false;
  }

  return true;
}

/**
 * Formata o campo da hora
 */
function js_formataHora() {
  new MaskedInput("#as05_horavisita", "00:00", {placeholder:"0"});
}

/**
 * Liberamos as opcoes disponiveis caso tenha sido feito contato por telefone
 */
function js_liberaOpcoes() {

  if ($F('contato_telefone') == '1') {

    $('profissional').style.display = "table-row";
    $('dataContato').style.display  = "table-row";
  } else {

    $('profissional').style.display      = "none";
    $('dataContato').style.display       = "none";
  	document.form1.z01_numcgm.value      = '';
  	document.form1.z01_nome.value        = '';
  	document.form1.as10_data.value       = '';
  }
}


/**
 * Buscamos todas as visitas cadastradas
 */
function js_pesquisaVisita(lMostra) {

  if (lMostra == true) {

    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_cidadaofamiliavisita',
                        'func_cidadaofamiliavisita.php?'+
                        'funcao_js=parent.js_mostraVisita1|as05_sequencial|as05_cidadaofamilia',
                        'Pesquisa Visita à Família',
                        true
                       );
  }
}

/**
 * Retornamos o codigo da visita e da familia e chamamos o RPC para buscar os dados da visita
 */
function js_mostraVisita1(chave1, chave2) {

  db_iframe_cidadaofamiliavisita.hide();
  var oParametro                  = new Object();
  oParametro.exec                 = 'retornaDadosVisita';
  oParametro.iCodigoFamiliaVisita = chave1; 

  js_divCarregando("Aguarde... Buscando as informações da visita", "msgBox");
  var oAjax = new Ajax.Request(
                               sUrlRPC,
                               {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornaDadosVisita
                               }
                              );
}

/**
 * Retornamos os dados e preenchemos os campos
 */
function js_retornaDadosVisita(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.status == 1) {

    $('as05_sequencial').value        = oRetorno.iCodigoVisita;
    $('as04_sequencial').value        = oRetorno.iCodigoCidadaoFamilia;
    $('codigoCidadao').value          = oRetorno.iCidadao;
    $('nome').value                   = oRetorno.sNomeCidadao.urlDecode();
    $('as02_nis').value               = oRetorno.iNis;
    $('as05_profissional').value      = oRetorno.iProfissionalVisita;
    $('profissionalVisita').value     = oRetorno.sNomeProfissionalVisita.urlDecode()
    $('as05_datavisita').value        = oRetorno.dtDataVisita;
    $('as05_horavisita').value        = oRetorno.sHoraVisita;
    $('as05_observacao').value        = oRetorno.sObservacao.urlDecode();

    $('iTipoVisita').value           = oRetorno.iTipoVisita;
    $('sTipoVisita').value           = oRetorno.sTipoVisita.urlDecode();        

    $('localEncaminhamento').style.display = 'none';      
    if (oRetorno.iCgmEncaminhamento != '') {

      $('iCgmEncaminhamento').value          = oRetorno.iCgmEncaminhamento;
      $('sNomeEncaminhamento').value         = oRetorno.sLocalEncaminhamento.urlDecode();
      $('localEncaminhamento').style.display = 'table-row';      
    }                                            
    
    
    $('aparelho_rede_eletrica').value = '0';

    if (oRetorno.lAparelhoRedeEletrica == 't') {
      $('aparelho_rede_eletrica').value = '1';
    }

    /**
     * Caso tenha sido feito contato, habilitamos os campos referentes ao contato telefonico e preenchemos os dados
     */
    if (oRetorno.sContatoTelefone == '1') {

      $('profissional').style.display     = "table-row";
      $('dataContato').style.display      = "table-row";
      $('contato_telefone').value         = '1';
      $('as10_sequencial').value          = oRetorno.iCodigoVisitaContato;
      $('as10_profissionalcontato').value = oRetorno.iProfissionalContato;
      $('profissionalContato').value      = oRetorno.sNomeProfissionalContato;
      $('as10_data').value                = oRetorno.dtDataContato;
    }
  }    
}

/**
 * Desabilita os campos select quando for exclusao
 */
function js_desabilitaCamposExcluir(iDbOpcao) {

  if (iDbOpcao == 3) {

    $('contato_telefone').readOnly        = true;
    $('aparelho_rede_eletrica').disabled  = true;
    $('contato_telefone').disabled        = true;
  }
}

js_formataHora();
js_desabilitaCamposExcluir(iDbOpcao);
js_limparDadosVisita();
</script>