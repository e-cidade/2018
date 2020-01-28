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

$oRotulo = new rotulocampo();
$oRotulo->label("db74_sequencial");
$oRotulo->label("db74_descricao");

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
<div class='container'>
    <form method="post" name='form1'>
      <fieldset>
        <legend>Cadastro de Logradouro</legend>
        <table class='form-container'>
          <tr>
            <td class='bold'>Estado:</td>
            <td id="cboEstados"></td>
        </tr>
        <tr>
          <td class='bold'>Município:</td>
          <td id="cboMunicipios"></td>
        </tr>
        <tr>
          <td class='bold'>Logradouro:</td>
          <td>
            <?php
              db_input('db74_sequencial', 10, $Idb74_sequencial, true, 'hidden', 1);
              db_input('db74_descricao', 65, $Idb74_descricao, true, 'text', 1);
            ?>
          </td>
        </tr>
        <tr>
          <td id='lancadorBairro' colspan="2">
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" id='salvar' name='salvar' value='Salvar' />
    <?php if ($iOpcao == 2) {?>
      <input type="button" id='pesquisar' name='pesquisar' value='Pesquisar' onclick="js_pesquisaLogradouro(true);" />
    <?php }?>
  </form>
</div>
<script type="text/javascript">
var iOpcao     = <?=$iOpcao;?>;
var sUrlRpc    = 'con4_cadender.RPC.php';
var sMunicipio = '<?=$sMunicipio;?>';
var sEstado    = '<?=$sEstado;?>';

var oCboEstados         = document.createElement('select');
oCboEstados.id          = 'oCboEstados';
oCboEstados.name        = 'oCboEstados';
oCboEstados.style.width = '100%';
$('cboEstados').appendChild(oCboEstados);

var oCboMunicipios         = document.createElement('select');
oCboMunicipios.id          = 'oCboMunicipios';
oCboMunicipios.name        = 'oCboMunicipios';
oCboMunicipios.style.width = '100%';
$('cboMunicipios').appendChild(oCboMunicipios);

oCboEstados.add(new Option('Selecione um estado', ''));
oCboMunicipios.add(new Option('Selecione um município', ''));


$('oCboEstados').observe("change", function(event) {
  js_pesquisaMunicipios();
});

$('oCboMunicipios').observe("change", function(event) {
  oLancadorBairro.clearAll();
  oLancadorBairro.setParametro('iMunicipio', $F('oCboMunicipios'));
});

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
 * Salvar
 */
$('salvar').observe("click", function () {

  var oParametro           = new Object();
  oParametro.sExecucao     = 'salvarLogradouro' ;
  oParametro.iCodigoEstado = $F('oCboEstados');
  oParametro.iMunicipio    = $F('oCboMunicipios');
  oParametro.aBairros      = new Array();
  oParametro.aBairros      = oLancadorBairro.getRegistros();
  oParametro.iLogradouro   = $F('db74_sequencial');
  oParametro.sLogradouro   = $F('db74_descricao');

  if ($F('oCboEstados') == '') {

    alert('Informe o estado.');
    return false;
  }

  if ($F('oCboMunicipios') == '') {

    alert('Informe o município.');
    return false;
  }

  if (oParametro.sLogradouro == '') {

    alert('Digite o nome do logradouro.');
    return false;
  }

  if (oParametro.aBairros.length == 0) {

    alert('Escolha pelo menos um bairro.');
    return false;
  }

  js_divCarregando("Aguarde, salvando logradouro.", "msgBox");
  new Ajax.Request('con4_cadender.RPC.php',
      {method:'post',
       parameters: 'json='+Object.toJSON(oParametro),
       onComplete: js_retornoSalvar
      }
     );
});

function js_retornoSalvar(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');
  alert(oRetorno.sMensagem.urlDecode());

  if (iOpcao == 1) {
    js_limpaCampos();
  }

  if (iOpcao == 2) {

    $('salvar').disabled = true;
    js_pesquisaLogradouro(true);
  }
}

/**
 * Limpa os campos da tela
 */
function js_limpaCampos() {

  $('db74_sequencial').value = '';
  $('db74_descricao').value  = '';
  oLancadorBairro.clearAll();
}

/**
 * Abre janela para pesquisar o logradouro.
 */
function js_pesquisaLogradouro(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_cadenderrua','func_cadenderrua.php?funcao_js=parent.js_mostracadenderrua1|db74_sequencial|db74_descricao','Pesquisa',true);
  }
}
function js_mostracadenderrua1(chave1,chave2) {

  if (chave1 != '') {
    $('salvar').disabled = false;
  } else {
    $('salvar').disabled = true;
  }

  document.form1.db74_sequencial.value = chave1;
  document.form1.db74_descricao.value  = chave2;
  db_iframe_cadenderrua.hide();

  //Busca os estados.
  js_pesquisaEstados();

  //Busca os bairros
  js_pesquisaBairros();

  //Seleciona o estado
  var iMunicipio = js_buscaMunicipio(oLancadorBairro.getRegistros()[0].sCodigo);
  js_buscaEstado(iMunicipio);

  js_pesquisaMunicipios();
  oCboMunicipios.value    = iMunicipio;
  oCboMunicipios.disabled = true;
  oLancadorBairro.setParametro('iMunicipio', $F('oCboMunicipios'));
}

/**
 * Pesquisa os bairros vinculados ao logradouro selecionado
 */
function js_pesquisaBairros() {

  var oParametro             = new Object();
      oParametro.sExecucao   = 'getBairroLogradouro';
      oParametro.iLogradouro = $F('db74_sequencial');

  var oDadosRequisicao              = new Object();
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete   = js_retornoPesquisaBairros;
      oDadosRequisicao.asynchronous = false;

  js_divCarregando("Aguarde, pesquisando os bairros vinculados ao logradouro selecionado.", "msgBox");
  new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
 * Retorno dos bairros vinculados
 */
function js_retornoPesquisaBairros(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  oLancadorBairro.clearAll();

  if (oRetorno.aBairros.length > 0) {

    oRetorno.aBairros.each(function(oBairro, iSeq) {

      $('txtCodigooLancadorBairro').value    = oBairro.iSequencial;
      $('txtDescricaooLancadorBairro').value = oBairro.sDescricao.urlDecode();
      oLancadorBairro.lancarRegistro();
      $('txtCodigooLancadorBairro').value    = '';
      $('txtDescricaooLancadorBairro').value = '';
    });
  }
}

/**
 * Pesquisa o estado vinculado ao município selecionado
 */
function js_buscaEstado(iMunicipio) {

  var oParametro            = new Object();
      oParametro.sExecucao  = 'getEstadoMunicipio';
      oParametro.iMunicipio = iMunicipio;

  var oDadosRequisicao              = new Object();
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete   = js_retornoBuscaEstado;
      oDadosRequisicao.asynchronous = false;

  new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
 * Retorno o estado vinculado ao município
 */
function js_retornoBuscaEstado(oResponse) {

  var oRetorno = eval('('+oResponse.responseText+')');

  if (oCboMunicipios.length > 0) {
    js_limpaMunicipios();
  }
  oCboEstados.value    = oRetorno.iEstado;
  oCboEstados.disabled = true;
}

/**
 * Pesquisa o município vinculado ao bairro selecionado
 */
function js_buscaMunicipio(iBairro) {

  var oParametro                  = new Object();
      oParametro.sExecucao        = 'getMunicipioBairro';
      oParametro.iBairro          = iBairro;
  var iCodigoBairro               = '';
  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete =  function (oResponse) {

        var oRetorno = eval('('+oResponse.responseText+')');
        iCodigoBairro =  oRetorno.iMunicipio;
      };
      oDadosRequisicao.asynchronous = false;
  new Ajax.Request(sUrlRpc, oDadosRequisicao);
  return iCodigoBairro;
}

/**
 * Retorno o município vinculado ao bairro
 */
function js_retornoBuscaMunicipio(oResponse) {

  var oRetorno = eval('('+oResponse.responseText+')');

  return oRetorno.iMunicipio;
}

if (iOpcao == 1) {
  js_pesquisaEstados();
} else if (iOpcao == 2) {

  $('salvar').disabled    = true;
  oCboEstados.disabled    = true;
  oCboMunicipios.disabled = true;
  js_pesquisaLogradouro(true);
}

var oLancadorBairro                = new DBLancador("oLancadorBairro");
    oLancadorBairro.iGridHeight    = 100;
    oLancadorBairro.sTextoFieldset = 'Adicionar Bairros';
    oLancadorBairro.setLabelAncora("Bairro");
    oLancadorBairro.setNomeInstancia("oLancadorBairro");
    oLancadorBairro.setParametrosPesquisa("func_cadenderbairro.php",
                                       ["db73_sequencial", "db73_descricao"],
                                       'iMunicipio='+oCboMunicipios.value
                                      );
    oLancadorBairro.show($("lancadorBairro"));
</script>