<?php
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

$oRotulo = new rotulocampo;
$oRotulo->label("db71_descricao");

/**
 * Buscamos o municipio e estado que o departamento esta vinculado, para comparar com o estado e em seguida o municipio,
 * setando valores padroes nos select
 */
$sMunicipio      = "";
$sEstado         = "";
$iDepartamento   = db_getsession("DB_coddepto");
$oDaoDbDepart    = new cl_db_depart();
$sCamposDbDepart = "munic, uf";
$sWhereDbDepart  = "coddepto = {$iDepartamento}";
$sSqlDbDepart    = $oDaoDbDepart->sql_query_dados_depart(null, $sCamposDbDepart, null, $sWhereDbDepart);
$rsDbDepart      = $oDaoDbDepart->sql_record($sSqlDbDepart);

if ($oDaoDbDepart->numrows > 0) {
  
  $oDadosDepartamento = db_utils::fieldsMemory($rsDbDepart, 0);
  $sMunicipio         = $oDadosDepartamento->munic;
  $sEstado            = $oDadosDepartamento->uf;
}

?>
<form id="frmBairros" method="post" action="" class="container">
  <fieldset class="container"  style="width: 25%">
    <legend class="bold">Cadastro de Bairros</legend>
    <table>
      <tr>
        <td><label class="bold">Estado:</label></td>
        <td id="cboEstados"></td>
      </tr>
      <tr>
        <td><label class="bold">Município:</label></td>
        <td id="cboMunicipios"></td>
      </tr>
      <tr>
        <td><label class="bold">Bairro:</label></td>
        <td id="inputCodigoBairro" style="display: none;"></td>
        <td id="inputDescricaoBairro"></td>
      </tr>
       <tr>
        <td nowrap><label class="bold">Sigla do Bairro:</label></td>
        <td id="inputSiglaBairro"></td>
      </tr>
    </table>
  </fieldset>
  <input id="btnSalvar" type="button" value="Salvar" />
  <input id="btnPesquisar" type="button" value="Pesquisar" disabled="disabled" />
</form>
<script>
var iOpcao     = <?=$iOpcao;?>;
var sUrlRpc    = 'con4_cadender.RPC.php';
var sMunicipio = '<?=$sMunicipio;?>';
var sEstado    = '<?=$sEstado;?>';

/**
 * Criamos os elementos select para estado e municipio, e input para o bairro a ser criado
 */
var oCboEstados             = document.createElement('select');
    oCboEstados.id          = 'oCboEstados';
    oCboEstados.name        = 'oCboEstados';
    oCboEstados.style.width = '100%';

var oCboMunicipios             = document.createElement('select');
    oCboMunicipios.id          = 'oCboMunicipios';
    oCboMunicipios.name        = 'oCboMunicipios';
    oCboMunicipios.style.width = '100%';

var oInputCodigoBairro             = document.createElement('input');
    oInputCodigoBairro.id          = 'oInputCodigoBairro';
    oInputCodigoBairro.name        = 'oInputCodigoBairro';
    oInputCodigoBairro.style.width = '100px';

var oInputDescricaoBairro             = document.createElement('input');
    oInputDescricaoBairro.id          = 'oInputDescricaoBairro';
    oInputDescricaoBairro.name        = 'oInputDescricaoBairro';
    oInputDescricaoBairro.style.width = '400px';

var oInputSiglaBairro             = document.createElement('input');
    oInputSiglaBairro.id          = 'oInputSiglaBairro';
    oInputSiglaBairro.name        = 'oInputSiglaBairro';
    oInputSiglaBairro.style.width = '100px';
    oInputSiglaBairro.maxLength   = 2;

oCboEstados.add(new Option('Selecione um estado', ''));
oCboMunicipios.add(new Option('Selecione um município', ''));

$('cboEstados').appendChild(oCboEstados);
$('cboMunicipios').appendChild(oCboMunicipios);
$('inputCodigoBairro').appendChild(oInputCodigoBairro);
$('inputDescricaoBairro').appendChild(oInputDescricaoBairro);
$('inputSiglaBairro').appendChild(oInputSiglaBairro);

$('btnSalvar').observe("click", function(event) {
  js_salvarBairro();
});

$('btnPesquisar').observe("click", function(event) {
  js_pesquisaBairros(true);
});

$('oCboEstados').observe("change", function(event) {
  js_pesquisaMunicipios();
});

$('oInputDescricaoBairro').observe("keyup", function(event) {
  $('oInputDescricaoBairro').value = $('oInputDescricaoBairro').value.toUpperCase();
});

$('oInputSiglaBairro').observe("keyup", function(event) {
  $('oInputSiglaBairro').value = $('oInputSiglaBairro').value.toUpperCase();
});

if (iOpcao == 2) {

  $('btnSalvar').disabled    = true;
  $('btnPesquisar').disabled = false;
  oCboEstados.disabled       = true;
  oCboMunicipios.disabled    = true;
  js_pesquisaBairros(true);
}

/**
 * Pesquisamos os estados cadastrados
 */
function js_pesquisaEstados() {

  var oParametro           = new Object();
      oParametro.sExecucao = 'getEstados';
      oParametro.iPais     = 1;

  var oDadosRequisicao              = new Object();
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete   = js_retornoPesquisaEstados;
      oDadosRequisicao.asynchronous = false;

  js_divCarregando("Aguarde, pesquisando os estados.", "msgBox");
  new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
 * Retorno dos estados
 */
function js_retornoPesquisaEstados(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.aEstados.length > 0) {

    oRetorno.aEstados.each(function(oEstado, iSeq) {

      oCboEstados.add(new Option(oEstado.sDescricao.urlDecode(), oEstado.iSequencial));
      if (!empty(sEstado) && sEstado == oEstado.sSigla.urlDecode() && iOpcao == 1) {
        
        oCboEstados.options[oEstado.iSequencial].selected = true;
        js_pesquisaMunicipios();
      }
    });
  }
}

/**
 * Pesquisa os municipios vinculados ao estado selecionado
 */
function js_pesquisaMunicipios() {

  if (oCboEstados.value == '') {
    js_limpaMunicipios();
  }

  var oParametro           = new Object();
      oParametro.sExecucao = 'getMunicipios';
      oParametro.iEstado   = oCboEstados.value;

  var oDadosRequisicao              = new Object();
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete   = js_retornoPesquisaMunicipios;
      oDadosRequisicao.asynchronous = false;

  js_divCarregando("Aguarde, pesquisando os municípios vinculados ao estado selecionado.", "msgBox");
  new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
 * Retorno dos municipios vinculados
 */
function js_retornoPesquisaMunicipios(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oCboMunicipios.length > 0) {
    js_limpaMunicipios();
  }

  if (oRetorno.aMunicipios.length > 0) {

    oRetorno.aMunicipios.each(function(oMunicipio, iSeq) {

      oCboMunicipios.add(new Option(oMunicipio.sDescricao.urlDecode(), oMunicipio.iSequencial));
      if (!empty(sMunicipio) && sMunicipio == oMunicipio.sDescricao.urlDecode() && iOpcao == 1) {
        oCboMunicipios.options[iSeq+1].selected = true;
      }
    });
  }
}

/**
 * Valida se todos os campos foram preenchidos
 */
function js_validaPreenchimentoCampos() {

  if (oCboEstados.value == '') {

    alert('Selecione um estado.');
    return false;
  }

  if (oCboMunicipios.value == '') {

    alert('Selecione um Município.');
    return false;
  }

  if (oInputDescricaoBairro.value == '') {

    alert('Informe o nome do bairro a ser cadastrado.');
    return false;
  }
  return true;
}

/**
 * Salva o bairro
 */
function js_salvarBairro() {

  if (js_validaPreenchimentoCampos()) {

    var oParametro               = new Object();
        oParametro.sExecucao     = 'salvarBairro';
        oParametro.iMunicipio    = oCboMunicipios.value;
        oParametro.sBairro       = oInputDescricaoBairro.value;
        oParametro.sSiglaBairro  = oInputSiglaBairro.value;
        oParametro.iCodigoBairro = oInputCodigoBairro.value;

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoSalvarBairro;

    js_divCarregando("Aguarde, salvando bairro.", "msgBox");
    new Ajax.Request(sUrlRpc, oDadosRequisicao);
  }
}

/**
 * Retorno do salvar o bairro
 */
function js_retornoSalvarBairro(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  alert(oRetorno.sMensagem.urlDecode());
  if (oRetorno.iStatus == 1) {

    if (iOpcao == 1) {
      js_limpaCampos();
    }

    if (iOpcao == 2) {
      js_pesquisaBairros(true);
    }
  }
}

/**
 * Limpa o combo dos municipios
 */
function js_limpaMunicipios() {

  if (oCboMunicipios.length > 0) {

    iTotalMunicipios = oCboMunicipios.length;

    for (var iContador = 0; iContador < iTotalMunicipios; iContador++) {
      oCboMunicipios.options.remove(iContador);
    }
    oCboMunicipios.add(new Option('Selecione um município', ''));
  }
}

/**
 * Limpa os campos da tela
 */
function js_limpaCampos() {

  oInputDescricaoBairro.value = '';
  oInputSiglaBairro.value     = '';
}

/**
 * Pesquisa os bairros cadastrados. Funcao chamada quando for acessada alteracao
 */
function js_pesquisaBairros(lMostra) {

  var sUrl  = 'func_cadenderbairro.php';
      sUrl += '?funcao_js=parent.js_mostraBairros|db73_sequencial|db73_descricao|db72_sequencial|db71_sequencial|db73_sigla';
      sUrl += '&lMunicipioEstado';

  js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_cadenderbairro', sUrl, 'Pesquisa Bairros', true);
}

/**
 * Retorno da busca pelos bairros
 */
function js_mostraBairros() {

  $('btnSalvar').disabled = false;

  js_pesquisaEstados();
  oCboEstados.value = arguments[3];

  js_pesquisaMunicipios();
  oCboMunicipios.value        = arguments[2];
  oInputCodigoBairro.value    = arguments[0];
  oInputDescricaoBairro.value = arguments[1];
  oInputSiglaBairro.value     = arguments[4];
  db_iframe_cadenderbairro.hide();
}

if (iOpcao == 1) {
  js_pesquisaEstados();
}
</script>