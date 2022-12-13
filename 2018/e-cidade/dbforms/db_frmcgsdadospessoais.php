<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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

$oSaudeConfiguracao = new SaudeConfiguracao();
$lCNSObrigatorio    = $oSaudeConfiguracao->obrigarCns();
?>
<fieldset class="separator">
  <legend>Controles CGS</legend>

  <table class="form-container">
    <tr>
      <td>
        <label for="cadastroInativo">Cadastro Inativo:</label>
      </td>
      <td>
        <input id="cadastroInativo" type="checkbox" />
      </td>
    </tr>

    <tr>
      <td>
        <label for="cgsMunicipio">CGS do Município:</label>
      </td>
      <td>
        <select id="cgsMunicipio" style="width: 66%;">
          <option value="t">SIM</option>
          <option value="f">NÃO</option>
        </select>
      </td>
    </tr>
  </table>
</fieldset>

<fieldset class="separator">
  <legend>Dados do Usuário</legend>
  <table class="form-container">
    <tr>
      <td>
        <label for="cns">CNS:</label>
      </td>
      <td>
        <input id="cns" type="text" value="" class="field-size3" maxlength="15" />
      </td>
    </tr>

    <tr>
      <td>
        <label for="nome">Nome:</label>
      </td>
      <td>
        <input id="nome" type="text" value="" class="field-size7" maxlength="255" />
      </td>
    </tr>

    <tr>
      <td>
        <label for="nomeMae">Nome da Mãe:</label>
      </td>
      <td>
        <input id="nomeMae" type="text" value="" class="field-size7 mae-usuario" maxlength="255" />
      </td>
      <td>
        <input id="desconheceMae" type="checkbox" class="mae-usuario" />
      </td>
      <td>
        <label for="desconheceMae">Desconhece a mãe</label>
      </td>
    </tr>

    <tr>
      <td>
        <label for="nomePai">Nome do Pai:</label>
      </td>
      <td>
        <input id="nomePai" type="text" value="" class="field-size7 pai-usuario" maxlength="40" />
      </td>
      <td>
        <input id="desconhecePai" type="checkbox" class="pai-usuario" />
      </td>
      <td>
        <label for="desconhecePai">Desconhece o pai</label>
      </td>
    </tr>

    <tr>
      <td>
        <label for="sexo">Sexo:</label>
      </td>
      <td class="field-size6">
        <select id="sexo">
          <option value="M">MASCULINO</option>
          <option value="F">FEMININO</option>
          <option value="N">NÃO INFORMADO</option>
        </select>
      </td>
    </tr>

    <tr>
      <td>
        <label for="racaCor">Raça/Cor:</label>
      </td>
      <td>
        <select id="racaCor">
          <option value="NÃO DECLARADA">NÃO DECLARADA</option>
          <option value="BRANCA">BRANCA</option>
          <option value="PRETA">PRETA</option>
          <option value="PARDA">PARDA</option>
          <option value="AMARELA">AMARELA</option>
          <option value="INDÍGENA">INDÍGENA</option>
          <option value="SEM INFORMACAO">SEM INFORMAÇÃO</option>
        </select>
      </td>
    </tr>

    <tr id="linhaEtnia" style="display: none;">
      <td id="colunaEtnia" class="field-size2"></td>
      <td>
        <input id="codigoEtnia"    type="hidden" value="" />
        <input id="descricaoEtnia" type="text"   value="" class="readonly field-size7" readonly="readonly" />
      </td>
    </tr>

    <tr>
      <td>
        <label for="fatorRH">Fator RH:</label>
      </td>
      <td>
        <select id="fatorRH">
          <option value="0"></option>
          <option value="1">POSITIVO</option>
          <option value="2">NEGATIVO</option>
        </select>
      </td>
    </tr>

    <tr>
      <td>
        <label for="tipoSangue">Tipo de Sangue:</label>
      </td>
      <td>
        <select id="tipoSangue">
          <option value="0"></option>
          <option value="1">A</option>
          <option value="2">B</option>
          <option value="3">O</option>
          <option value="4">AB</option>
        </select>
      </td>
    </tr>
  </table>
</fieldset>

<fieldset class="separator">
  <legend>Dados de Nascimento</legend>
  <table class="form-container">
    <tr>
      <td class="field-size2">
        <label for="dataNascimento">Data de Nascimento:</label>
      </td>
      <td>
        <input id="dataNascimento" type="text" value="" class="field-size2" />
      </td>
    </tr>

    <tr>
      <td class="field-size2">
        <label for="nacionalidade">Nacionalidade:</label>
      </td>
      <td>
        <select id="nacionalidade" style="width: 66%;">
          <option value="0">BRASILEIRO</option>
          <option value="1">NATURALIZADO</option>
          <option value="2">ESTRANGEIRO</option>
        </select>
      </td>
    </tr>

    <tr>
      <td class="field-size2">
        <label for="paisOrigem">País de Origem:</label>
      </td>
      <td>
        <select id="paisOrigem" style="width: 66%;" disabled="disabled"></select>
      </td>
    </tr>

    <tr>
      <td id="colunaMunicipioNascimento" class="field-size2"></td>
      <td>
        <input id="municipioNascimento" type="text" value="" class="readonly field-size7" readonly />
      </td>
    </tr>

    <tr>
      <td class="field-size2">
        <label for="ufNascimento">UF de Nascimento:</label>
      </td>
      <td>
        <input id="ufNascimento" type="text" value="" class="readonly field-size2" readonly />
      </td>
    </tr>

    <tr>
      <td class="field-size2">
        <label for="codigoIbge">IBGE:</label>
      </td>
      <td>
        <input id="codigoIbge" type="text" value="" class="readonly field-size2" readonly />
      </td>
    </tr>
  </table>
</fieldset>

<fieldset class="separator">
  <legend>Óbito</legend>
  <table class="form-container">
    <tr>
      <td>
        <label for="dataObito">Data de Óbito:</label>
      </td>
      <td>
        <input id="dataObito" type="text" value="" class="field-size2" />
      </td>
    </tr>
  </table>
</fieldset>

<input id="cnsObrigatorio" type="hidden" value="<?=$lCNSObrigatorio ? 1 : 0?>" />

<script>

  var oInputDataNascimento = new DBInputDate( $('dataNascimento') );
  var oInputDataObito      = new DBInputDate( $('dataObito') );

  var oAncoraMunicipio     = new DBAncora('Município de Nascimento:', '#', true );
      oAncoraMunicipio.onClick( buscaMunicipioNascimento );
      oAncoraMunicipio.show( $('colunaMunicipioNascimento') );

  var oAncoraEtnia = new DBAncora( 'Etnia:', '#', true );
      oAncoraEtnia.onClick( buscaEtnias );
      oAncoraEtnia.show( $('colunaEtnia') );

  var dadosPessoais = {
    "cns"                  : $("cns"),
    "codigo_cartao_sus"    : null,
    "nome"                 : $("nome"),
    "nomeMae"              : $("nomeMae"),
    "nomePai"              : $("nomePai"),
    "sexo"                 : $("sexo"),
    "racaCor"              : $("racaCor"),
    "codigo_etnia"         : $("codigoEtnia"),
    "label_etnia"          : $("descricaoEtnia"),
    "fatorRH"              : $("fatorRH"),
    "tipoSangue"           : $("tipoSangue"),
    "dataNascimento"       : oInputDataNascimento,
    "nacionalidade"        : $("nacionalidade"),
    "paisOrigem"           : $("paisOrigem"),
    "municipioNascimento"  : $("municipioNascimento"),
    "ufNascimento"         : $("ufNascimento"),
    "codigoIbge"           : $("codigoIbge"),
    "dataObito"            : oInputDataObito,
    "cadastroInativo"      : $('cadastroInativo'),
    "cgsMunicipio"         : $('cgsMunicipio')
  };

  /**
   * Bloqueia o campo nome da mãe ou do pai de acorco com a ação do checkbox referente a cada um
   * @param oElemento
   */
  function bloqueiaNome( oElemento ) {

    var oInputElement          = $$('input[type=text].' + oElemento.className)[0];
        oInputElement.value    = "";
        oInputElement.readOnly = false;
        oInputElement.removeClassName( 'readonly' );

    if( oElemento.checked === true ) {

      oInputElement.value    = "SEM INFORMAÇÃO";
      oInputElement.readOnly = true;
      oInputElement.addClassName( 'readonly' );
    }
  }

  /**
   * Busca o município de nascimento
   */
  function buscaMunicipioNascimento() {

    if( $F('nacionalidade') != 0 ) {

      alert( _M( MENSAGENS_MANUTENCAO_CGS + 'nacionalidade_invalida' ) );
      return;
    }

    var sUrl  = "func_cadendermunicipiosistema.php";
        sUrl += "?iTipoSistema=4";
        sUrl += "&funcao_js=parent.retornoBuscaMunicipioNascimento|db72_descricao|db71_sigla|db125_codigosistema";

    js_OpenJanelaIframe( '', 'db_iframe_cadendermunicipiosistema', sUrl, 'Pesquisa Município', true );
  }

  /**
   * Preenche os dados do município de nascimento
   */
  function retornoBuscaMunicipioNascimento() {

    db_iframe_cadendermunicipiosistema.hide();

    $('municipioNascimento').value = arguments[0];
    $('ufNascimento').value        = arguments[1];
    $('codigoIbge').value          = arguments[2];
  }

  /**
   * Busca as etnias quando selecionada raça INDÍGENA
   */
  function buscaEtnias() {

    var sUrl  = 'func_etnia.php?';
        sUrl += 'funcao_js=parent.retornoBuscaEtnias|s200_codigo|s200_descricao';

    js_OpenJanelaIframe( '', 'db_iframe_etnia', sUrl, 'Pesquisa Etnia', true );
  }

  /**
   * Retorna a etnia selecionada e preenche o código e descrição
   */
  function retornoBuscaEtnias() {

    $('codigoEtnia').value    = arguments[0];
    $('descricaoEtnia').value = arguments[1];

    db_iframe_etnia.hide();
  }

  /**
   * Quando nacionalidade não for Brasileiro, limpa os campos de município, UF e IBGE
   */
  function validaNacionalidade() {

    $('paisOrigem').setAttribute( 'disabled', 'disabled' );

    if( $F('nacionalidade') == 0 ) {
      $('paisOrigem').value = 10;
    }

    if( $F('nacionalidade') != 0 ) {

      $('paisOrigem').removeAttribute( 'disabled' );

      $('municipioNascimento').value = '';
      $('ufNascimento').value        = '';
      $('codigoIbge').value          = '';
    }
  }

  /**
   * Controla se a linha da etnia deve ser apresentada, caso tenha sido selecionada raça INDÍGENA
   */
  function validaRaca() {

    if( $F('racaCor') == 'INDÍGENA' ) {

      $('linhaEtnia').setStyle({ 'display': '' });
      return;
    }

    $('linhaEtnia').setStyle({ 'display': 'none' });
    $('codigoEtnia').value    = '';
    $('descricaoEtnia').value = '';
  }

  /**
   * Valida os dados pessoais obrigatórios
   * @returns {boolean}
   */
  function validaDadosPessoais() {

    if( $F('cnsObrigatorio') == 1 && empty( $F('cns') ) ) {

      alert( _M( MENSAGENS_MANUTENCAO_CGS + 'cns_nao_informado' ) );
      oDBAba.mostraFilho(oAbaDadosPessoais);
      $('cns').focus();

      return false;
    }

    if( $F('cns').trim() != '' && !$F('cns').validaCNS() ) {

      alert( _M( MENSAGENS_MANUTENCAO_CGS + 'cns_invalido' ) );
      oDBAba.mostraFilho(oAbaDadosPessoais);
      $('cns').focus();

      return false;
    }

    if( empty( $F('nome') ) ) {

      alert( _M( MENSAGENS_MANUTENCAO_CGS + 'nome_nao_informado' ) );
      oDBAba.mostraFilho(oAbaDadosPessoais);
      $('nome').focus();

      return false;
    }

    if( $('desconheceMae').checked === false && empty( $F('nomeMae') ) ) {

      alert( _M( MENSAGENS_MANUTENCAO_CGS + 'nome_mae_nao_informado' ) );
      oDBAba.mostraFilho(oAbaDadosPessoais);
      $('nomeMae').focus();

      return false;
    }

    if( oInputDataNascimento.getValue() == null ) {

      alert( _M( MENSAGENS_MANUTENCAO_CGS + 'data_nascimento_nao_informada' ) );
      oDBAba.mostraFilho(oAbaDadosPessoais);
      $('dataNascimento').focus();

      return false;
    }

    if(    $F('nacionalidade') == 0
        && ( empty( $F('municipioNascimento') ) || empty( $F('ufNascimento') ) || empty( $F('codigoIbge') ) )
      ) {

      alert( _M( MENSAGENS_MANUTENCAO_CGS + 'local_nascimento_nao_informado' ) );
      oDBAba.mostraFilho(oAbaDadosPessoais);
      return false;
    }

    return true;
  }

  /**
   * Seta os atributos dos dados pessoais a serem salvos
   * @param oParametros
   */
  function setValoresDadosPessoais( oParametros ) {


    oParametros.dados_pessoais = {
      "cns":                 dadosPessoais.cns.value,
      "codigo_cartao_sus":   iCodigoCartaoSus,
      "nome":                dadosPessoais.nome.value,
      "nomeMae":             dadosPessoais.nomeMae.value,
      "nomePai":             dadosPessoais.nomePai.value,
      "sexo":                dadosPessoais.sexo.value,
      "racaCor":             dadosPessoais.racaCor.value,
      "codigo_etnia":        dadosPessoais.codigo_etnia.value,
      "label_etnia":         dadosPessoais.label_etnia.value,
      "fatorRH":             dadosPessoais.fatorRH.value,
      "tipoSangue":          dadosPessoais.tipoSangue.value,
      "dataNascimento":      dadosPessoais.dataNascimento.__toLocaleDateString(),
      "nacionalidade":       dadosPessoais.nacionalidade.value,
      "paisOrigem":          dadosPessoais.paisOrigem.value,
      "municipioNascimento": dadosPessoais.municipioNascimento.value,
      "ufNascimento":        dadosPessoais.ufNascimento.value,
      "codigoIbge":          dadosPessoais.codigoIbge.value,
      "dataObito":           dadosPessoais.dataObito.getValue() != null ? dadosPessoais.dataObito.__toLocaleDateString() : '',
      "cadastroInativo":     dadosPessoais.cadastroInativo.checked === true,
      "cgsMunicipio":        dadosPessoais.cgsMunicipio.value == 't'
    }
  }

  validacoes.push(validaDadosPessoais);

  /****************************************************
   * ************** CONTROLES DE EVENTOS **************
   * **************************************************
   */
  $('desconheceMae').observe('click', function() {
    bloqueiaNome( this );
  });

  $('desconhecePai').observe('click', function() {
    bloqueiaNome( this );
  });

  $('nacionalidade').observe('change', function() {
    validaNacionalidade();
  });

  $('racaCor').observe('change', function() {
    validaRaca();
  });

  $('cns').oninput = function() {
    js_ValidaCampos( this, 1, 'CNS', true, 't' );
  };

  $('nome').oninput = function() {
    js_ValidaCampos( this, 2, 'Nome', true, 't' );
  };

  $('nomeMae').oninput = function() {
    js_ValidaCampos( this, 2, 'Nome da Mãe', true, 't' );
  };

  $('nomePai').oninput = function() {
    js_ValidaCampos( this, 2, 'Nome do Pai', true, 't' );
  };

  /**
   * Quando a tela for carregada preencherá os dados na tela
   */
  function carregaDadosPessoais() {

    callbackCarregamento.dadosPessoais = function(dados, informacoesPadrao) {

      /**
       * Preenche "País de Origem"
       */
      $('paisOrigem').length = 0;

      informacoesPadrao.paisOrigem.each(function( oPais ) {
        $('paisOrigem').add( new Option( oPais.label_pais, oPais.codigo_pais ) );
      });

      $('paisOrigem').value = 10;

      if( !dados ) {
        return false;
      }

      dadosPessoais.cadastroInativo.checked = dados.cadastroInativo == 't';
      dadosPessoais.cgsMunicipio.setValue(dados.cgsMunicipio);

      /**
       * Dados do Usuário
       */
      dadosPessoais.cns.setValue(dados.cns);
      dadosPessoais.nome.setValue(dados.nome);
      dadosPessoais.sexo.setValue(dados.sexo);
      dadosPessoais.racaCor.setValue(dados.raca);
      dadosPessoais.codigo_etnia.setValue(dados.codigo_etnia);
      dadosPessoais.label_etnia.setValue(dados.label_etnia);
      dadosPessoais.fatorRH.setValue(dados.fator_rh);
      dadosPessoais.tipoSangue.setValue(dados.tipo_sanguineo);

      /**
       * Dados de Nascimento
       */
      dadosPessoais.dataNascimento.setValue(dados.data_nascimento);
      dadosPessoais.nacionalidade.setValue(dados.nacionalidade);
      dadosPessoais.paisOrigem.setValue(dados.paisOrigem);
      dadosPessoais.municipioNascimento.setValue(dados.municipio_nascimento);
      dadosPessoais.ufNascimento.setValue(dados.uf_nascimento);
      dadosPessoais.codigoIbge.setValue(dados.codigo_ibge_nascimento);

      dadosPessoais.nomeMae.setValue(dados.nome_mae);
      dadosPessoais.nomePai.setValue(dados.nome_pai);

      if($("desconheceMae").checked = dados.nome_mae == "SEM INFORMAÇÃO") {
        bloqueiaNome($("desconheceMae"));
      }

      if($("desconhecePai").checked = dados.nome_pai == "SEM INFORMAÇÃO") {
        bloqueiaNome($("desconhecePai"));
      }
      /**
       * Dados do Óbito
       */
      dadosPessoais.dataObito.setValue(dados.data_obito);

      iCodigoCartaoSus = dados.codigo_cartao_sus;
      $('codigoCgs').innerHTML = ' - CGS: ' + iCgs;

      validaNacionalidade();
      validaRaca();
    };
  }

  document.addEventListener("DOMContentLoaded", function(event) {
    carregaDadosPessoais();
  });

</script>
