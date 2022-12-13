<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBseller Servicos de Informatica
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("y120_taxadiversos");
$clrotulo->label("y119_natureza");
$clrotulo->label("y120_unidade");
$clrotulo->label("y120_periodo");
$clrotulo->label("y120_datainicio");
$clrotulo->label("y120_datafim");
$clrotulo->label("q02_inscr");
$clrotulo->label("dv05_obs");

$oGet = db_utils::postMemory($_GET);
$oDataVencimento = new DBDate(date('Y-m-d', db_getsession('DB_datausu')));
$oDataVencimento = $oDataVencimento->adiantarPeriodo(30, 'd');

$data_inicio_dia = null;
$data_inicio_mes = null;
$data_inicio_ano = null;
$data_fim_dia    = null;
$data_fim_mes    = null;
$data_fim_ano    = null;

if(isset($oGet->codigo) && !empty($oGet->codigo)) {
  
  $oLancamentoAlterar   = LancamentoTaxaDiversosRepository::getInstanciaPorCodigo($oGet->codigo);
  $codigo               = $oLancamentoAlterar->getCodigo();
  
  $inscricao            = $oLancamentoAlterar->getInscricaoMunicipal() != null ? $oLancamentoAlterar->getInscricaoMunicipal() : null;
  $inscricao_nome       = $oLancamentoAlterar->getInscricaoMunicipal() != null ? $oLancamentoAlterar->getCGM()->getNome() : null;

  if(!isset($inscricao) || empty($inscricao)) {
    $cgm                = $oLancamentoAlterar->getCGM()->getCodigo();
    $cgm_nome           = $oLancamentoAlterar->getCGM()->getNome();
  }
  
  $taxa                 = $oLancamentoAlterar->getNaturezaTaxa()->getCodigo();
  $taxa_natureza        = $oLancamentoAlterar->getNaturezaTaxa()->getNatureza();
  $unidade              = $oLancamentoAlterar->getUnidade();
  $periodo              = $oLancamentoAlterar->getPeriodo();

  switch ($oLancamentoAlterar->getNaturezaTaxa()->getTipoPeriodo()) {
    case 'A':
      $tipoPeriodoNatureza = 'Anual';
      break;

    case 'M':
      $tipoPeriodoNatureza = 'Mensal';
      break;
    
    default:
      $tipoPeriodoNatureza = 'Diária';
      break;
  }

  $data_inicio = null;
  if($oLancamentoAlterar->getDataInicio() instanceof DBDate) {

    $data_inicio     = $oLancamentoAlterar->getDataInicio()->getDate(DBDate::DATA_PTBR);
    $data_inicio_dia = $oLancamentoAlterar->getDataInicio()->getDia();
    $data_inicio_mes = $oLancamentoAlterar->getDataInicio()->getMes();
    $data_inicio_ano = $oLancamentoAlterar->getDataInicio()->getAno();
  }
  
  if($oLancamentoAlterar->getDataFim() instanceof DBDate) {
    
    $data_fim_dia = $oLancamentoAlterar->getDataFim()->getDia();
    $data_fim_mes = $oLancamentoAlterar->getDataFim()->getMes();
    $data_fim_ano = $oLancamentoAlterar->getDataFim()->getAno();
  }

  $data_vencimento = $oDataVencimento->getDate(DBDate::DATA_PTBR);
  if($oLancamentoAlterar->getDataVencimento() instanceof DBDate) {
    $data_vencimento = $oLancamentoAlterar->getDataVencimento()->getDate(DBDate::DATA_PTBR);
    $oDataVencimento = $oLancamentoAlterar->getDataVencimento();
  }

  $taxa_tem_calculo  = $oGet->taxa_tem_calculo;
  $bloqueia          = $oGet->bloqueia;
}

if(!isset($unidade_descricao)) {
  $unidade_descricao = '';
}

$data_vencimento_dia = $oDataVencimento->getDia();
$data_vencimento_mes = $oDataVencimento->getMes();
$data_vencimento_ano = $oDataVencimento->getAno();

?>
<html>
<head>
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load(array(
    "strings.js",
    "scripts.js",
    "dates.js",
    "prototype.js",
    "strings.js",
    "AjaxRequest.js",
    "widgets/DBLookUp.widget.js",
    "datagrid.widget.js",
    "widgets/Collection.widget.js",
    "widgets/DatagridCollection.widget.js",
    "widgets/FormCollection.widget.js",
    "estilos.css",
    "grid.style.css"
  ));
  ?>
  <style type="text/css">
    #tipoPeriodoAviso {
      font-style: italic;
      padding: 3px;
    }
    .campos-debito {
      display: none;
    }
  </style>
</head>
<body onload="ajustaCamposEdicao()">
<div class="container">
  <form method="POST" id="formLancamentoTaxa" action="fis4_lancamentotaxadiversos_processamento.php">
    <fieldset class="container">
      <Legend>Lançamento da Taxa</Legend>
      <table class="form-container">
        <tr>
          <td nowrap title="<?php echo $Tz01_numcgm; ?>">
            <label id="lbl_cgm" for="cgm"><a href="#">CGM:</a></label>
          </td>
          <td>
            <?php db_input('cgm', 10, $Iz01_numcgm, true, "text", 1, 'class="field-size2" data="z01_numcgm"'); ?>
            <?php db_input('cgm_nome', 50, $Iz01_nome, true, "text", 3, 'class="field-size8" data="z01_nome"'); ?>
            <?php db_input('codigo', 10, '', true, "hidden", 3); ?>
            <?php db_input('taxa_tem_calculo', 10, '', true, "hidden", 3); ?>
            <?php db_input('bloqueia', 10, '', true, "hidden", 3); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Tq02_inscr; ?>">
            <label id="lbl_inscricao" for="inscricao"><a href="#" class="dbancora">Inscrição Municipal:</a></label>
          </td>
          <td>
            <?php
            db_input('inscricao',      10, $Iq02_inscr, true, "text", 1, 'class="field-size2" data="q02_inscr"');
            db_input('inscricao_nome', 50, $Iz01_nome,  true, "text", 3, 'class="field-size8" data="z01_nome"');
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty120_taxadiversos; ?>">
            <label id="lbl_taxa" for="taxa"><a href="#">Natureza: </a></label>
          </td>
          <td>
            <?php db_input('taxa', 10, $Iy120_taxadiversos, true, "text", 1, 'class="field-size2" data="y119_sequencial"'); ?>
            <?php db_input('taxa_natureza', 50, $Iy119_natureza, true, "text", 3, 'class="field-size8" data="y119_natureza"'); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty120_unidade; ?>">
            <label id="lbl_unidade" for="unidade"><?php echo $Ly120_unidade; ?></label>
          </td>
          <td>
            <?php db_input('unidade', 15, $Iy120_unidade, true, "text", 1, 'class="field-size2 opcional"'); ?>
            <span id="unidade_descricao"><?php echo $unidade_descricao ?></span>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty120_periodo; ?>">
            <label id="lbl_periodo" for="periodo"><?php echo $Ly120_periodo; ?></label>
          </td>
          <td>
            <?php db_input('periodo', 10, $Iy120_periodo, true, 'text', 1, 'class="field-size2"'); ?>
            <?php db_input('tipoPeriodoNatureza', 15, '', true, "text", 3, 'class="field-size2"'); ?>
            <span id="tipoPeriodoAviso"></span>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty120_datainicio; ?>">
            <label id="lbl_data_inicio" for="data_inicio"><?php echo $Ly120_datainicio; ?></label>
          </td>
          <td>
            <?php db_inputdata('data_inicio', $data_inicio_dia, $data_inicio_mes, $data_inicio_ano, true, 'text', 1, 'class="field-size2 opcional" onChange="calcularPeriodo()"', '', '', 'parent.calcularPeriodo()'); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Ty120_datafim; ?>">
            <label id="lbl_data_fim" for="data_fim"><?php echo $Ly120_datafim; ?></label>
          </td>
          <td>
            <?php db_inputdata('data_fim', $data_fim_dia, $data_fim_mes, $data_fim_ano, true, 'text', 1, 'class="field-size2 opcional" onChange="calcularPeriodo()"', "", "", 'parent.calcularPeriodo()'); ?>
          </td>
        </tr>

        <tr class="campos-debito">
          <td nowrap title="Data de Vencimento da taxa">
            <label id="lbl_data_fim" for="data_vencimento">Data de Vencimento:</label>
          </td>
          <td>
            <?php db_inputdata('data_vencimento', $data_vencimento_dia, $data_vencimento_mes, $data_vencimento_ano, true, 'text', 1, 'class="field-size2 opcional"'); ?>
          </td>
        </tr>

        <tr class="campos-debito">
          <td nowrap title="<?php echo $Tdv05_obs; ?>">
            <label id="lbl_dv05_obs" for="dv05_obs"><?php echo $Ldv05_obs; ?></label>
          </td>
          <td>
            <?php db_textarea('dv05_obs', 5, 50, $Idv05_obs, true, 'text', 1); ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" value="Excluir"       id="btnExcluir"   onclick="excluir()" style="display:none" >
    <input type="button" value="Salvar"        id="btnLancar"    onclick="lancar()" >
    <input type="button" value="Pesquisar"     id="btnPesquisar" onclick="pesquisar()">
    <input type="button" value="Novo"          id="btnNovo"      onclick="location.href='fis4_lancamentotaxadiversos.php'" style='display:none'>
    <input type="button" value="Lançar Débito" id="lancarDebito" onclick="gerarDebito(this)" disabled="true">
  </form>
</div>
<script type="text/javascript">

  var oAncoraCGM = new DBLookUp($('lbl_cgm'),
    $('cgm'),
    $('cgm_nome'),
    {
      sArquivo      : 'func_cgm.php',
      sObjetoLookUp : 'func_nome',
      fCallBack : function() {

        if($F('cgm').trim() != '') {
          bloqueiaInscricao();
        }
      }
    });

  var oAncoraInscricaoMunicipal = new DBLookUp(
    $('lbl_inscricao'),
    $('inscricao'),
    $('inscricao_nome'),
    {
      sArquivo      : 'func_issbase.php',
      sObjetoLookUp : 'db_iframe_issbase',
      fCallBack : function() {

        if($F('inscricao').trim() != '') {
          bloqueiaCGM();
        }
      }
    }
  );

  var oAncoraTaxa = new DBLookUp( $('lbl_taxa'),
    $('taxa'),
    $('taxa_natureza'),
    {
      sArquivo  : 'func_taxadiversos.php',
      fCallBack : getConfiguracoesTaxa
    });

  oAncoraTaxa.callBackChange = function(id, erro, descricao) {

    this.oInputDescricao.value = descricao;

    if (erro) {
      this.oInputDescricao.value = id;
    }

    this.oCallback.onChange(erro, arguments);

    if (this.oParametros.fCallBack) {
      this.oParametros.fCallBack();
    }
  }.bind(oAncoraTaxa);

  $('taxa').on('change', function (event) {
    if(this.value == '' || this.value == null) {
      configuraUnidadeDataFimPeriodoTaxa({
        unidadeOpcional : true,
        unidade         : null,
        dataFimOpcional : true
      })
    }
  });

  $('cgm').observe('change', function(){

    oAncoraInscricaoMunicipal.habilitar();
    $('inscricao').disabled      = false;
    $('inscricao_nome').disabled = false;
  });

  $('inscricao').observe('change', function(){

    oAncoraCGM.habilitar();
    $('cgm').disabled      = false;
    $('cgm_nome').disabled = false;
  });

  function ajustaCamposEdicao() {

    if(parseInt($F('codigo')) > 0) {

      $('btnNovo').style.display           = '';
      $('btnExcluir').style.display        = '';

      if($F('bloqueia') == 'cgm') {
        bloqueiaCGM();
      } else {
        bloqueiaInscricao();
      }

      if($F('taxa_tem_calculo') == 1) {

        setFormReadOnly($('formLancamentoTaxa'), true);
        liberaCampos($F('taxa_tem_calculo'));
      }

      if($F('taxa_tem_calculo') == 0 || $F('taxa_tem_calculo') == '0') {
        
        $$('.campos-debito').each(function(campo){
          campo.style = 'display:table-row!important';
        });

        $('lancarDebito').removeAttribute('disabled');
      }
      
      getConfiguracoesTaxa();
    }
  }

  function bloqueiaCGM() {

    oAncoraCGM.desabilitar();

    $('cgm').disabled      = true;
    $('cgm_nome').disabled = true;

    $('cgm').value      = '';
    $('cgm_nome').value = '';
  }

  function bloqueiaInscricao() {

    oAncoraInscricaoMunicipal.desabilitar();

    $('inscricao').disabled      = true;
    $('inscricao_nome').disabled = true;

    $('inscricao').value      = '';
    $('inscricao_nome').value = '';
  }

  function pesquisar () {

    var sQueryStringJanelaPesquisa  = 'func_lancamentotaxadiversos.php';
    sQueryStringJanelaPesquisa += '?funcao_js=parent.js_retornoPesquisar|y120_sequencial|y120_cgm|y120_taxadiversos|y120_unidade|y120_datafim|z01_nome|y119_natureza|y120_periodo|y120_datainicio|y120_issbase|db_observacao|db_taxa_tem_calculo';

    js_OpenJanelaIframe(
      '',
      'db_iframe_lancamentotaxadiversos',
      sQueryStringJanelaPesquisa,
      'Pesquisar lançamentos',
      true
    );
  }

  function js_retornoPesquisar(y120_sequencial, y120_cgm, y120_taxadiversos, y120_unidade, y120_datafim, z01_nome, y119_natureza, y120_periodo, y120_datainicio, y120_issbase, dv05_obs, taxa_tem_calculo) {

    var sBbloqueia    = 'cgm';
    var sUrlRedirect  = 'fis4_lancamentotaxadiversos.php?';
        sUrlRedirect += 'codigo='+ y120_sequencial;
        sUrlRedirect += '&taxa_tem_calculo='+ parseInt(taxa_tem_calculo);

    $('taxa_tem_calculo').value = parseInt(taxa_tem_calculo);

    if(y120_issbase == '') {

      $('cgm').value      = y120_cgm;
      $('cgm_nome').value = z01_nome;

      sBbloqueia = 'inscricao';
      
      bloqueiaInscricao();
    }
    
    sUrlRedirect += '&bloqueia='+ sBbloqueia;

    if(y120_issbase != '') {

      $('inscricao').value      = y120_issbase;
      $('inscricao_nome').value = z01_nome;
      
      sUrlRedirect += '&inscricao='+ y120_issbase;
      sUrlRedirect += '&inscricao_nome='+ z01_nome.urlEncode();

      bloqueiaCGM();
    }

    if(taxa_tem_calculo == 0 || taxa_tem_calculo == '0') {
      db_iframe_lancamentotaxadiversos.hide();
      location.href = sUrlRedirect;
    } else {
      $$('.campos-debito').each(function(campo){
        campo.style = 'display:none';
      });
    }

    $('codigo').value                    = y120_sequencial;
    $('taxa').value                      = y120_taxadiversos;
    $('unidade').value                   = y120_unidade;
    $('data_inicio').value               = js_formatar(y120_datainicio, 'd');
    $('data_fim').value                  = js_formatar(y120_datafim, 'd');
    $('periodo').value                   = y120_periodo;
    $('taxa_natureza').value             = y119_natureza.substr(0, 50);
    $('dv05_obs').innerHTML              = dv05_obs;
    $('btnNovo').style.display           = '';
    $('btnExcluir').style.display        = '';
    $('lancarDebito').disabled           = true;

    db_iframe_lancamentotaxadiversos.hide();

    setFormReadOnly($('formLancamentoTaxa'), true);

    liberaCampos(taxa_tem_calculo);
    getConfiguracoesTaxa();
  }

  function liberaCampos(taxa_tem_calculo) {

    $('dtjs_data_fim').removeAttribute('disabled');
    $('data_vencimento').removeAttribute('disabled');
    $('dtjs_data_vencimento').removeAttribute('disabled');
    
    $('data_fim').removeAttribute('readonly');
    $('dtjs_data_fim').removeAttribute('disabled');
    $('data_vencimento').removeAttribute('disabled');
    $('data_vencimento').removeAttribute('readonly');
    $('dtjs_data_vencimento').removeAttribute('disabled');
    $('dv05_obs').removeAttribute('readonly');

    $('data_fim').style.backgroundColor = '#FFFFFF';
    $('data_vencimento').style.backgroundColor = '#FFFFFF';
    $('dv05_obs').style.backgroundColor = '#FFFFFF';

    $('btnExcluir').removeAttribute('disabled');
    $('btnLancar').removeAttribute('disabled');
    $('btnPesquisar').removeAttribute('disabled');
    $('btnNovo').removeAttribute('disabled');
  }

  function getConfiguracoesTaxa () {

    if($F('taxa')) {

      AjaxRequest.create( 'fis1_taxadiversos.RPC.php',
        {
          exec        : 'getConfiguracoesTaxa',
          iCodigoTaxa : $F('taxa')
        },
        function (oRetorno) {

          if(oRetorno.sMessage) {
            alert(oRetorno.sMessage);
          }

          if(oRetorno.lErro) {
            return;
          }

          configuraUnidadeDataFimPeriodoTaxa(oRetorno.oConfiguracaoTaxa)
          calcularPeriodo();
        }
      )
        .setMessage('Verificando configurações da taxa...')
        .execute();
    }
  }

  function configuraUnidadeDataFimPeriodoTaxa (configuracaoTaxa) {

    $('unidade_descricao').innerHTML = '';

    if(!$('unidade').hasClassName('opcional')) {

      $('unidade').addClassName('opcional');
    }

    if(!$('data_inicio').hasClassName('opcional')) {

      $('data_inicio').addClassName('opcional');
    }

    if(!$('data_fim').hasClassName('opcional')) {

      $('data_fim').addClassName('opcional');
    }

    if(!configuracaoTaxa.unidadeOpcional) {

      if($('unidade').hasClassName('opcional')) {

        $('unidade').removeClassName('opcional');
        $('unidade_descricao').innerHTML = configuracaoTaxa.unidade;
      }
    }

    if(!configuracaoTaxa.periodoOpcional) {
      if($('data_inicio').hasClassName('opcional')) {
        $('data_inicio').removeClassName('opcional');
      }
    }

    if(!configuracaoTaxa.dataFimOpcional) {
      if($('data_fim').hasClassName('opcional')) {
        $('data_fim').removeClassName('opcional');
      }
    }

    $('tipoPeriodoAviso').innerHTML = '';
    $('tipoPeriodoAviso').style.background = 'initial';

    if(configuracaoTaxa.tipoPeriodoNatureza) {

      $('tipoPeriodoNatureza').value  = configuracaoTaxa.tipoPeriodoNatureza;

      if(configuracaoTaxa.tipoPeriodoNatureza.substr(0, 1).toUpperCase() == 'A') {
        $('tipoPeriodoAviso').innerHTML = 'Informe a quantidade do período em meses.';
        $('tipoPeriodoAviso').style.background = '#FFF';
      }
    }
  }

  function lancar () {

    var cgm         = $F('cgm');
    var inscricao   = $F('inscricao');
    var taxa        = $F('taxa');
    var unidade     = $F('unidade');
    var periodo     = $F('periodo');
    var data_inicio = $F('data_inicio');
    var data_fim    = $F('data_fim');

    if(    (cgm == '' || cgm == null)
      && (inscricao == '' || inscricao == null)
    ) {

      alert('Informe o CGM ou a Inscrição Municipal.');
      return;
    }

    if(taxa == '' || taxa == null) {
      alert('Informe a taxa a ser lançada.');
      return;
    }

    if(unidade == '' || unidade == null) {

      if(!$('unidade').hasClassName('opcional')) {

        alert('Informe a(s) unidade(s).');
        return;
      }
    }

    if(periodo == '' || periodo == null) {

      if(!$('periodo').hasClassName('opcional')) {

        alert('Informe o período.');
        return;
      }
    }

    if(data_inicio == '' || data_inicio == null) {

      if(!$('data_inicio').hasClassName('opcional')) {

        alert('Informe a data de início para cálculo da taxa.');
        return;
      }
    }

    if(data_fim == '' || data_fim == null) {

      if(!$('data_fim').hasClassName('opcional')) {

        alert('Informe a data de fim para cálculo da taxa.');
        return;
      }
    }

    $('formLancamentoTaxa').submit();
  }

  function excluir () {

    var codigo = $F('codigo');

    if(codigo) {

      AjaxRequest.create(
        'fis1_taxadiversos.RPC.php',
        {
          exec   : 'excluirLancamento',
          codigo : codigo
        },
        function (retorno) {

          if(retorno.sMessage) {
            alert(retorno.sMessage.replace(/\\n/g, "\n"));
          }

          if(retorno.lErro) {
            return;
          }

          location.href = 'fis4_lancamentotaxadiversos.php';
        }
      ).setMessage('Excluindo lançamento...').execute();
    }
  }

  function calcularPeriodo() {

    return; // Conforme decisão com P.O. retirado cálculo de período

    var aDataInicio, aDataFim, oDataInicio, oDataFim, nPeriodo;
    var nPeriodoDias, nPeriodoMeses, nPeriodoAnos;
    var oDataAtual;

    if($F('data_inicio')) {
      aDataInicio = getDateInDatabaseFormat($F('data_inicio')).split('-');
      oDataInicio = new Date(Date.UTC(aDataInicio[0], (aDataInicio[1]-1), aDataInicio[2], 3));
    }

    oDataAtual = new Date(Date.now());

    if($F('data_fim')) {

      aDataFim = getDateInDatabaseFormat($F('data_fim')).split('-');
      oDataFim = new Date(Date.UTC(aDataFim[0], (aDataFim[1]-1), aDataFim[2], 3));

      if(aDataFim[0] > oDataAtual.getUTCFullYear()) {
        oDataFim = new Date(oDataAtual.getUTCFullYear(), 11, 31, 3);
      }

    } else {
      oDataFim = new Date(oDataAtual.getUTCFullYear(), 11, 31, 3);
    }

    if(!oDataInicio || !oDataFim) {

      alert('Não foi possível calcular o período, verifique as datas de início e fim informadas.');
      $('periodo').value = '';

      return false;
    }

    if(!oDataInicio.compararData(oDataFim, '<')) {

      alert('A data fim deve ser maior que a data de início');
      $('periodo').value  = '';
      $('data_fim').value = '';

      return false;
    }

    nPeriodoDias  = oDataFim.diferenca(oDataInicio, 'D') +1;
    nPeriodoMeses = oDataFim.diferenca(oDataInicio, 'M');
    nPeriodoAnos  = oDataFim.diferenca(oDataInicio, 'A');

    switch($F('tipoPeriodoNatureza').substr(0, 1).toUpperCase()) {

      case 'A':

        var nTotalDiasAnos = 0;
        var anoDataInicio  = oDataInicio.getUTCFullYear();

        for (var i = 0; i <= nPeriodoAnos; i++) {

          nTotalDiasAnos += 365;

          if(anoDataInicio%4 == 0) {
            nTotalDiasAnos += 1;
          }

          anoDataInicio++;
        };

        // console.log(nPeriodoDias, nTotalDiasAnos);
        nPeriodo = nPeriodoDias / nTotalDiasAnos;

        break;

      case 'M':

        var nTotalDiasMeses = 0;
        var mesDataInicio   = oDataInicio.getUTCMonth() + 1;
        var anoDataInicio   = oDataInicio.getUTCFullYear();

        nPeriodoMeses += 1;

        for (var i = 0; i < nPeriodoMeses; i++) {

          nTotalDiasMeses += diasNoMes(anoDataInicio, mesDataInicio);

          mesDataInicio++;

          if(mesDataInicio > 12) {
            mesDataInicio = 1;
            anoDataInicio++;
          }
        };

        // console.log(nPeriodoMeses, nPeriodoDias, nTotalDiasMeses);
        nPeriodo = nPeriodoMeses * nPeriodoDias / nTotalDiasMeses;

        break;

      case 'D':
        nPeriodo = nPeriodoDias;
        break;

    }

    $('periodo').value = nPeriodo.toFixed(2);

    return true;
  }

  function gerarDebito (btnLancarDebito) {

    AjaxRequest.create(
      'fis1_taxadiversos.RPC.php', 
      {
        exec            : 'calcularTaxasUnica',
        codigo          : $F('codigo'),
        data_vencimento : $F('data_vencimento'),
        observacao      : $F('dv05_obs'),
      },
      function (oRetorno, lErro) {

        if(oRetorno.sMessage) {
          alert(oRetorno.sMessage.urlDecode());
        }

        if(lErro) {
          return;
        }

        location.href = 'fis4_lancamentotaxadiversos.php';
      }
    ).setMessage('Lançando débito...').execute();
  }
</script>
<?php
db_menu();

if(isset($oGet->mensagem)) {
  echo '<script>alert("'.str_replace("\n", '\n', urldecode($oGet->mensagem)).'");</script>'."\n";
}
?>
</body>
</html>