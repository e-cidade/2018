<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load("scripts.js");
  db_app::load("strings.js");
  db_app::load("prototype.js");
  db_app::load("estilos.css");
  db_app::load("AjaxRequest.js");
  db_app::load("widgets/DBLookUp.widget.js");
  db_app::load("datagrid.widget.js");
  db_app::load("widgets/Collection.widget.js");
  db_app::load("widgets/DatagridCollection.widget.js");
  db_app::load("widgets/DBInputHora.widget.js");
  db_app::load("widgets/Input/DBInputDate.widget.js");
  db_app::load("classes/recursoshumanos/Efetividade/PeriodoEfetividade.js");
  db_app::load("classes/recursoshumanos/PontoEletronico/Justificativas.js");
  db_app::load("classes/recursoshumanos/PontoEletronico/DiaPonto.js");
  db_app::load("classes/recursoshumanos/PontoEletronico/MarcacaoPonto.js");
  db_app::load("widgets/datagrid/plugins/DBHint.plugin.js");
  db_app::load("EmissaoRelatorio.js");
  ?>
  <style type="text/css">
    .alteracao-manual {
      background-color: #D3D3D3;
    }

    .justificativa-com-marcacao {
      background-color: #FCD6D6;
    }

    .seta-mover-marcacao {
      display: inline-block;
      width: 10px;
      height: 10px;
      background-size: 10px;
    }

    .seta-esquerda {
      background-image: url('imagens/seta_direita.gif');
    }

    .seta-direita {
      background-image: url('imagens/seta.gif');
    }

    .sem-seta-esquerda {
      padding-left: 10px;
    }

    .sem-seta-direita {
      padding-right: 10px;
    }

  </style>
</head>
<body>
<div class="container">
  <form>
    <fieldset>
      <legend>Manutenção do Ponto Eletrônico</legend>

      <table class="form-container">

        <tr>
          <td>
            <label for="periodoInicio">Período:</label>
          </td>
          <td id="linhaPeriodoEfetividade" colspan="2" class="field-size-max"></td>
        </tr>

        <tr>
          <td>
            <label for="rh01_regist">
              <a href="#" id="ancoraMatricula">Matrícula:</a>
            </label>
          </td>
          <td>
            <input id="rh01_regist" type="text" value="" class="field-size2" />
            <input id="z01_nome"    type="text" value="" class="field-size7 readonly" disabled="disabled" />
          </td>
        </tr>

      </table>

    </fieldset>

    <input id="pesquisar" type="button" value="Pesquisar" onclick="buscaRegistros(true)" />
  </form>
</div>

<div id="registrosPonto" style="width: 1300px; margin: 0 auto;">
  <form>
    <fieldset>
      <legend>Registros</legend>
      <div id="containerGridRegistros"></div>
    </fieldset>

    <div class="container">
      <input id="salvar" type="button" value="Salvar" onclick="salvarRegistros()" />
      <input id="imprimirEspelhoPonto" type="button" value="Imprimir" onclick="imprimeEspelhoPonto()" />
    </div>
  </form>
</div>
</body>
<?php db_menu(); ?>
<script>

  var aBotoesOcultar                   = [];
  var aColunasJustificativa            = [];
  var aColunasJustificativaComMarcacao = [];
  var aColunasSetasRemover             = [];
  var aSetasOcultar                    = [];

  var oPeriodoEfetividade = new PeriodoEfetividade();
  oPeriodoEfetividade.show($('linhaPeriodoEfetividade'));

  var oLookupMatricula = new DBLookUp(
    $('ancoraMatricula'),
    $('rh01_regist'),
    $('z01_nome'),
    {
      'sArquivo'  : 'func_rhpessoal.php',
      'sLabel'    : 'Pesquisar Servidor'
    }
  );

  var oCollectionRegistros = new Collection();
  oCollectionRegistros.setId('data');

  var oCollectionRegistrosSemMarcacao = Collection.create().setId('data');

  var sColunasMarcacoes = '9%';
  var sColunasHoras     = '4.5%';
  var oGridRegistros    = new DatagridCollection(oCollectionRegistros, 'gridRegistros').configure({'order' : false});
  oGridRegistros.addColumn('data',     {'width': '1%', 'label': 'Data ID'});
  oGridRegistros.addColumn('data_dia', {'width': '8%', 'label': 'Data'});
  oGridRegistros.addColumn('entrada1', {'width': sColunasMarcacoes,  'label': 'Ent. 1'})
    .transform(function (sValue, oItem) {
      return criaElemento(sValue, oItem, 1);
    });
  oGridRegistros.addColumn('saida1',   {'width': sColunasMarcacoes,  'label': 'Saí. 1'})
    .transform(function (sValue, oItem) {
      return criaElemento(sValue, oItem, 2);
    });
  oGridRegistros.addColumn('entrada2', {'width': sColunasMarcacoes,  'label': 'Ent. 2'})
    .transform(function (sValue, oItem) {
      return criaElemento(sValue, oItem, 3);
    });
  oGridRegistros.addColumn('saida2',   {'width': sColunasMarcacoes,  'label': 'Saí. 2'})
    .transform(function (sValue, oItem) {
      return criaElemento(sValue, oItem, 4);
    });
  oGridRegistros.addColumn('entrada3', {'width': sColunasMarcacoes,  'label': 'Ent. 3'})
    .transform(function (sValue, oItem) {
      return criaElemento(sValue, oItem, 5);
    });
  oGridRegistros.addColumn('saida3',   {'width': sColunasMarcacoes,  'label': 'Saí. 3'})
    .transform(function (sValue, oItem) {
      return criaElemento(sValue, oItem, 6);
    });
  oGridRegistros.addColumn('normais',  {'width': sColunasHoras,  'label': 'Normais'})
    .transform(function (sValue, oItem) {
      return validaHoras(oItem.diaPonto.horasTrabalhadas)
    })
    .configure({'align':'center'});
  oGridRegistros.addColumn('faltas',   {'width': sColunasHoras,  'label': 'Faltas'})
    .transform(function (sValue, oItem) {
      return validaHoras(oItem.diaPonto.horasFalta)
    })
    .configure({'align':'center'});
  oGridRegistros.addColumn('ext50',    {'width': sColunasHoras,  'label': 'Ext. 50%'})
    .transform(function (sValue, oItem) {
      return validaHoras(oItem.diaPonto.horasExtras50)
    })
    .configure({'align':'center'});
  oGridRegistros.addColumn('ext75',    {'width': sColunasHoras,  'label': 'Ext. 75%'})
    .transform(function (sValue, oItem) {
      return validaHoras(oItem.diaPonto.horasExtras75)
    })
    .configure({'align':'center'});
  oGridRegistros.addColumn('ext100',   {'width': sColunasHoras,  'label': 'Ext. 100%'})
    .transform(function (sValue, oItem) {
      return validaHoras(oItem.diaPonto.horasExtras100)
    })
    .configure({'align':'center'});
  oGridRegistros.addColumn('adicNot',   {'width': sColunasHoras,  'label': 'Adic. Not.'})
    .transform(function (sValue, oItem) {
      return validaHoras(oItem.diaPonto.horasAdicinalNoturno)
    })
    .configure({'align':'center'});
  oGridRegistros.addAction('Registrar', 'Alterações no ponto', function(oEvento, oItem) {

    $$('div[data='+oItem.data+']').each(function(oElemento) {
      oElemento.setStyle({'display' : 'none'});
    });

    $$('input[data='+oItem.data+']').each(function(oElemento) {

      oElemento.setStyle({'display' : ''});
      oElemento.previousSiblings().each(function(itemIrmaoMaisVelho){

        if(itemIrmaoMaisVelho.tagName.toUpperCase() == 'SPAN') {
          itemIrmaoMaisVelho.setStyle({'display' : 'inline-block'});
        }
      });

      oElemento.nextSiblings().each(function(itemIrmaoMaisNovo){

        if(itemIrmaoMaisNovo.tagName.toUpperCase() == 'SPAN') {
          itemIrmaoMaisNovo.setStyle({'display' : 'inline-block'});
        }
      });
    });
  });

  oGridRegistros.setEvent('onafterrenderrows', function(collection) {
    $$('.seta-mover-marcacao').forEach(function(item){

      if(js_search_in_array(aSetasOcultar, item.getAttribute('identificador'))) {
        return;
      }

      item.observe('click', function (ev) {

        var data      = this.getAttribute('data-date');
        var origem    = this.getAttribute('data-type');
        var destino   = this.getAttribute('data-type');

        if(this.getAttribute('data-direction') == 'e') {
          destino--;
        }

        if(this.getAttribute('data-direction') == 'd') {
          destino++;
        }

        if(oGridRegistros.collection.get(data).diaPonto.moverMarcacao(origem, destino) === false) {

          alert("Não é possível mover marcações onde já existe hora preenchida.\nMova apenas para espaços vazios.");
          return;
        }

        var oDiaPonto = oGridRegistros.collection.get(data).diaPonto;

        var oElementoOrigem  = $('hora_' + oDiaPonto.codigo + '_' + origem);
        var oElementoDestino = $('hora_' + oDiaPonto.codigo + '_' + destino);

        oElementoDestino.value = oElementoOrigem.value;

        if(oDiaPonto.getMarcacao(destino).manual === true) {
          oElementoDestino.addClassName('alteracao-manual');
        }

        oElementoOrigem.value = '';
        oElementoOrigem.removeClassName('alteracao-manual');
      });
    });
  });

  oGridRegistros.configure({'height': '400px'});
  oGridRegistros.hideColumns([0]);
  oGridRegistros.show($('containerGridRegistros'));

  /**
   * Cria um elemento para cada entrada/saída
   * @param sValue
   * @param oItem
   * @param iTipo
   * @returns {string}
   */
  function criaElemento(sValue, oItem, iTipo) {

    var oDiv           = new Element('div');
    var oElementoDiv   = criaElementoDiv(oItem, iTipo);
    var oElementoInput = criaElementoInput(oItem, iTipo);
    var direcaoEsq     = 'esquerda';
    var direcaoDir     = 'direita';

    if(iTipo == 1) {
      direcaoEsq = null;
    }

    if(iTipo == 6) {
      direcaoDir = null;
    }

    var oElementoSpanSetaEsq = criaElementoSpan(oItem, iTipo, direcaoEsq);
    var oElementoSpanSetaDir = criaElementoSpan(oItem, iTipo, direcaoDir);

    oDiv.appendChild(oElementoDiv);

    if(oElementoSpanSetaEsq != null) {
      oDiv.appendChild(oElementoSpanSetaEsq);
    }

    oDiv.appendChild(oElementoInput);

    if(oElementoSpanSetaDir != null) {
      oDiv.appendChild(oElementoSpanSetaDir);
    }

    if(!oItem.temMarcacoes && (oItem.diaPonto.dsrFolga || oItem.diaPonto.feriado)) {

      oElementoDiv.setStyle({'display' : ''});
      oElementoInput.setStyle({'display' : 'none'});

      if(oElementoSpanSetaEsq != null) {
        oElementoSpanSetaEsq.setStyle({'display' : 'none'});
      }

      if(oElementoSpanSetaDir != null) {
        oElementoSpanSetaDir.setStyle({'display' : 'none'});
      }
    }

    return oDiv.outerHTML;
  }

  /**
   * Cria o elemento input para edição das horas
   */
  function criaElementoInput(oItem, iTipo) {

    var iCodigo                  = null;
    var oElemento                = new Element('input');
    var alteracaoManual          = false;
    var sJustificativaAbreviacao = '';

    var diaPonto = oItem.diaPonto;
    var marcacao = diaPonto.getMarcacao(iTipo);

    switch(iTipo) {

      case 1:
        dataTipoMarcacao         = 'entrada1';
        break;

      case 2:
        dataTipoMarcacao         = 'saida1';
        break;

      case 3:
        dataTipoMarcacao         = 'entrada2';
        break;

      case 4:
        dataTipoMarcacao         = 'saida2';
        break;

      case 5:
        dataTipoMarcacao         = 'entrada3';
        break;

      case 6:
        dataTipoMarcacao         = 'saida3';
        break;
    }

    iCodigo                  = marcacao.codigo;
    alteracaoManual          = marcacao.manual;

    if(marcacao.justificativa !== null) {
      sJustificativaAbreviacao = marcacao.justificativa.abreviacao;
    }

    if(diaPonto.afastamento.isAfastado) {

      if(sJustificativaAbreviacao != '') {
        sJustificativaAbreviacao += ' / ';
      }

      sJustificativaAbreviacao += diaPonto.afastamento.abreviacao;
    }

    oElemento.setAttribute('id', 'hora_' + diaPonto.codigo + '_' + iTipo);
    oElemento.setAttribute('hora_id', iCodigo);
    oElemento.setAttribute('data', oItem.data);
    oElemento.setAttribute('identificador', 'hora_' + diaPonto.codigo + '_' + iTipo);
    oElemento.setAttribute('data-tipo-marcacao', dataTipoMarcacao);
    oElemento.setAttribute('value', (marcacao.hora !== null) ? marcacao.hora : '');
    oElemento.setAttribute('justificativa_abreviacao', sJustificativaAbreviacao);
    oElemento.setStyle({'text-align': 'center'});
    oElemento.addClassName('registros_hora field-size2');

    if(alteracaoManual) {
      oElemento.addClassName('alteracao-manual');
    }

    return oElemento;
  }

  /**
   * Cria DIV com o texto da folga/DSR
   */
  function criaElementoDiv(oItem, iTipo) {

    var oElemento           = new Element('div');
    oElemento.setStyle({'text-align': 'center', 'display' : 'none'});
    oElemento.setAttribute('data', oItem.data);
    oElemento.setAttribute('identificador', 'hora_' + oItem.diaPonto.codigo + '_' + iTipo);
    oElemento.innerHTML = oItem.descricaoJornada;

    if(oItem.diaPonto.feriado) {
      oElemento.innerHTML = 'FERIADO';
    }

    return oElemento;
  }

  /**
   * Cria SPAN com a seta
   */
  function criaElementoSpan(oItem, iTipo, direcao) {

    if(direcao == null) {
      return null;
    }

    var oElemento = new Element('span');
    oElemento.addClassName('seta-'+ direcao);
    oElemento.addClassName('seta-mover-marcacao');
    oElemento.setAttribute('data-direction', direcao.substr(0,1));
    oElemento.setAttribute('data-date', oItem.data);
    oElemento.setAttribute('data-type', iTipo);
    oElemento.setAttribute('identificador', 'mover_hora_' + direcao + '_' + oItem.diaPonto.codigo + '_' + iTipo);

    return oElemento;
  }

  /**
   * Quando valor da hora for null, retorna vazio
   * @param sValue
   * @returns {*}
   */
  function validaHoras(sValue, oItem) {

    if(sValue === 'null') {
      sValue = '';
    }

    return sValue;
  }

  /**
   * Verifica se todos os campos foram prrenchidos para pesquisa do ponto
   * @returns {boolean}
   */
  function validaCampos() {

    if(oPeriodoEfetividade.getDataInicio() == null) {

      alert('Data de início não informada.');
      return false;
    }

    if(oPeriodoEfetividade.getDataFim() == null) {

      alert('Data de fim não informada.');
      return false;
    }

    if(empty($F('rh01_regist'))) {

      alert('Selecione uma Matrícula.');
      return false;
    }

    return true;
  }

  /**
   * Busca os registros do ponto do servidor no período selecionado
   * @returns {boolean}
   */
  function buscaRegistros(lSalvarAutomatico) {

    if(!validaCampos()) {
      return false;
    }

    aBotoesOcultar.length = 0;

    AjaxRequest.create(
      'rec4_pontoeletronico.RPC.php',
      {
        'exec'        : 'buscaRegistrosPonto',
        'periodo' : {
          'dataInicio' : oPeriodoEfetividade.getDataFormatada(oPeriodoEfetividade.getDataInicio()),
          'dataFim'    : oPeriodoEfetividade.getDataFormatada(oPeriodoEfetividade.getDataFim())
        },
        'matriculas'  : [$F('rh01_regist')]
      },
      function(oRetorno, lErro) {

        if(lErro) {

          alert(oRetorno.mensagem);
          return false;
        }

        montaGrid(oRetorno, lSalvarAutomatico);
      }
    ).asynchronous(false).setMessage('Aguarde... Buscando os registros de ponto do servidor...').execute();
  }

  /**
   * Monta a grid
   */
  function montaGrid(oRetorno, lSalvarAutomatico) {

    aColunasJustificativa.length = 0;
    aColunasSetasRemover.length  = 0;
    aSetasOcultar.length         = 0;
    oGridRegistros.clear();

    var aDados = oRetorno.aDados[0];

    aDados.datas.each(function(oData) {

      var oDados                    = {};
      var diaPonto                  = new DiaPonto(oData.data.split('/').reverse().join('-'), aDados.dados.matricula);
      var aJornadas                 = aDados.aHorasJornada[oData.oJornada.codigo];

      oDados.exercicio        = oData.oPeriodoEfetividade.iExercicio;
      oDados.competencia      = oData.oPeriodoEfetividade.iCompetencia;
      oDados.data             = oData.data.replace(/\//g, '_');
      oDados.data_dia         = oData.possuiEvento ? '<b>' + oData.data_dia + '</b>' : oData.data_dia;
      oDados.descricaoEvento  = oData.dadosEvento.descricao;
      oDados.temMarcacoes     = oData.lTemMarcacoes;
      diaPonto.temJustificativa = false;

      diaPonto.codigo           = oData.codigo_data;
      diaPonto.feriado          = oData.lFeriado;
      diaPonto.jornada          = {
        codigo    : oData.oJornada.codigo,
        descricao : oData.oJornada.descricao,
        dsrFolga  : oData.oJornada.dsr_folga,
        horas     : Collection.create().setId('iTipoRegistro')
      };
      diaPonto.afastamento      = oData.afastamento;

      aJornadas.horas.each(function (item, ind) {
        diaPonto.jornada.horas.add(Object.assign({}, item));
      });

      for (var indMarcacoes = 0; indMarcacoes < oData.aMarcacoes.length; indMarcacoes++) {

        var tipoMarcacaoEntrada, tipoMarcacaoSaida;
        var dadosMarcacao                  = oData.aMarcacoes[indMarcacoes];

        switch (indMarcacoes) {

          case 0:
            tipoMarcacaoEntrada = MarcacaoPonto.prototype.ENTRADA1;
            tipoMarcacaoSaida   = MarcacaoPonto.prototype.SAIDA1;
            break;

          case 1:
            tipoMarcacaoEntrada = MarcacaoPonto.prototype.ENTRADA2;
            tipoMarcacaoSaida   = MarcacaoPonto.prototype.SAIDA2;
            break;

          case 2:
            tipoMarcacaoEntrada = MarcacaoPonto.prototype.ENTRADA3;
            tipoMarcacaoSaida   = MarcacaoPonto.prototype.SAIDA3;
            break;
        }

        var marcacaoEntradaPonto        = new MarcacaoPonto(tipoMarcacaoEntrada, dadosMarcacao.oEntrada.hora, diaPonto, dadosMarcacao.oEntrada.data);
        marcacaoEntradaPonto.codigo = dadosMarcacao.oEntrada.codigo;
        marcacaoEntradaPonto.manual = dadosMarcacao.oEntrada.manual;

        if(diaPonto.afastamento.isAfastado || dadosMarcacao.oEntrada.oJustificativa != null) {

          if(dadosMarcacao.oEntrada.oJustificativa != null) {
            marcacaoEntradaPonto.justificativa = dadosMarcacao.oEntrada.oJustificativa;
          }

          aColunasJustificativa.push('hora_'+ oData.codigo_data +'_'+ tipoMarcacaoEntrada);

          aColunasSetasRemover.push('mover_hora_esquerda_' + oData.codigo_data +'_'+ tipoMarcacaoEntrada);
          aColunasSetasRemover.push('mover_hora_direita_' + oData.codigo_data +'_'+ tipoMarcacaoEntrada);

          var iMarcacaoAnterior = tipoMarcacaoEntrada - 1;
          aSetasOcultar.push('mover_hora_direita_' + oData.codigo_data +'_'+ iMarcacaoAnterior);

          if(dadosMarcacao.oEntrada.hora != '' && dadosMarcacao.oEntrada.hora != null) {
            aColunasJustificativaComMarcacao.push('hora_'+ oData.codigo_data +'_'+ tipoMarcacaoEntrada);
          }
        }

        var marcacaoSaidaPonto        = new MarcacaoPonto(tipoMarcacaoSaida, dadosMarcacao.oSaida.hora, diaPonto, dadosMarcacao.oSaida.data);
        marcacaoSaidaPonto.codigo = dadosMarcacao.oSaida.codigo;
        marcacaoSaidaPonto.manual = dadosMarcacao.oSaida.manual;

        if(diaPonto.afastamento.isAfastado || dadosMarcacao.oSaida.oJustificativa != null) {

          if(dadosMarcacao.oSaida.oJustificativa != null) {
            diaPonto.temJustificativa = true;
            marcacaoSaidaPonto.justificativa = dadosMarcacao.oSaida.oJustificativa;
          }

          aColunasJustificativa.push('hora_'+ oData.codigo_data +'_'+ tipoMarcacaoSaida);

          aColunasSetasRemover.push('mover_hora_esquerda_' + oData.codigo_data +'_'+ tipoMarcacaoSaida);
          aColunasSetasRemover.push('mover_hora_direita_' + oData.codigo_data +'_'+ tipoMarcacaoSaida);

          var iMarcacaoPosterior = tipoMarcacaoSaida + 1;
          aSetasOcultar.push('mover_hora_esquerda_' + oData.codigo_data +'_'+ iMarcacaoPosterior);

          if(dadosMarcacao.oSaida.hora != '' && dadosMarcacao.oSaida.hora != null) {
            aColunasJustificativaComMarcacao.push('hora_'+ oData.codigo_data +'_'+ tipoMarcacaoSaida);
          }
        }

        diaPonto.addMarcacao(marcacaoEntradaPonto);
        diaPonto.addMarcacao(marcacaoSaidaPonto);
      }

      diaPonto.horasTrabalhadas     = oData.normais;
      diaPonto.horasFalta           = oData.faltas;
      diaPonto.horasExtras50        = oData.ext50;
      diaPonto.horasExtras75        = oData.ext75;
      diaPonto.horasExtras100       = oData.ext100;
      oDados.descricaoJornada       = oData.oJornada.tipo_descricao;
      diaPonto.dsrFolga             = oData.oJornada.dsr_folga;
      diaPonto.horasAdicinalNoturno = oData.adicional;

      if(!diaPonto.dsrFolga && !diaPonto.feriado && ((oDados.temMarcacoes && (diaPonto.afastamento.isAfastado || diaPonto.temJustificativa) ) != true) ) {
        aBotoesOcultar.push(oDados.data.replace('-', '_'));
      }

      oDados.diaPonto = diaPonto;

      oCollectionRegistros.add(oDados);
    });

    oGridRegistros.reload();

    if(aDados.datasSemMarcacao.length > 0) {

      oCollectionRegistrosSemMarcacao.clear();

      aDados.datasSemMarcacao.each(function (data) {
        oCollectionRegistrosSemMarcacao.add({
          'data' : data
        });
      });

      if(!criarMarcacoesNasDatas(oCollectionRegistrosSemMarcacao)) {
        return false;
      }
    }

    if(lSalvarAutomatico === true) {
      salvarRegistros(true);
    }

    criaEvento();
    validaColunasComJustificativas();

    ocultarBotoesRegistrar();
    hintLegenda();
    hintEvento();
  }

  function criaEvento() {

    var aInputs  = $$('input.registros_hora');

    aInputs.each(function(oElemento) {

      var sId = oElemento.getAttribute('id');
      new DBInputHora($(sId));
    });

    var aItensHoras = document.getElementsByClassName('registros_hora');

    for(oItem of aItensHoras) {

      oItem.addEventListener('blur', function() {

        var oItemCollection   = oCollectionRegistros.get(this.getAttribute('data'));
        var dataTipoMarcacao  = this.getAttribute('data-tipo-marcacao');
        var inputHora         = this;
        var regex             = new RegExp(/_(\d*)$/);

        if(regex.test(inputHora.getAttribute('identificador'))) {

          var marcacao = oItemCollection.diaPonto.getMarcacao(regex.exec(inputHora.getAttribute('identificador'))[1]);

          if(marcacao.codigo == inputHora.getAttribute('hora_id') || dataTipoMarcacao == marcacao.getTipo().toLowerCase()) {

            if(marcacao.hora === '' || marcacao.hora === null) {

              if(inputHora.value === '' || inputHora.value === null) {

                marcacao.diaPonto.verificarDatasMarcacoes(marcacao.tipo);
                return;
              }
            }

            if(marcacao.hora != inputHora.value) {

              marcacao.horaAnterior = marcacao.hora;
              marcacao.hora         = inputHora.value;
              marcacao.alterado     = true;
              inputHora.addClassName('alteracao-manual');

              marcacao.diaPonto.verificarDatasMarcacoes(marcacao.tipo);
            }
          }
        }

        hintLegenda();
        hintEvento();
        oCollectionRegistros.add(oItemCollection);
      });
    }
  }

  function validaColunasComJustificativas() {

    aColunasJustificativa.each(function(sElemento) {

      var sJustificativa = '';

      $$('input[identificador='+sElemento+']').each(function(oElemento) {

        sJustificativa = oElemento.getAttribute('justificativa_abreviacao');
        oElemento.setStyle({'display' : 'none'});
      });

      $$('div[identificador='+sElemento+']').each(function(oElemento) {

        oElemento.innerHTML = sJustificativa;
        oElemento.setStyle({'display' : ''});
      });
    });

    aColunasJustificativaComMarcacao.each(function(sElemento) {

      $$('div[identificador='+sElemento+']').each(function(oElemento) {
        oElemento.addClassName('justificativa-com-marcacao');
      });
    });

    aColunasSetasRemover.each(function(sElemento) {

      $$('span[identificador='+sElemento+']').each(function(oElemento) {
        oElemento.setStyle({'display' : 'none'});
      });
    });

    aSetasOcultar.each(function(sElemento) {

      $$('span[identificador='+sElemento+']').each(function(oElemento) {

        oElemento.removeClassName('seta-mover-marcacao');

        if(oElemento.getAttribute('data-direction') == 'd') {

          oElemento.addClassName('sem-seta-direita');
          oElemento.removeClassName('seta-direita');
        }

        if(oElemento.getAttribute('data-direction') == 'e') {

          oElemento.addClassName('sem-seta-esquerda');
          oElemento.removeClassName('seta-esquerda');
        }
      });
    });
  }

  /**
   * Salva os registros do ponto
   */
  function salvarRegistros(lSalvarInicial) {

    var aItensCollection = oGridRegistros.collection.itens;
    var aDados           = [];

    aItensCollection.each(function(oItem) {

      var oReg               = new RegExp('_', 'g');
      var oDados             = {
        matricula   : $F('rh01_regist'),
        codigo_data : oItem.diaPonto.codigo,
        data        : oItem.data.replace(oReg, '/'),
        aMarcacoes  : []
      };

      oItem.diaPonto.getMarcacoes().each(function (marcacao, ind) {

        var oDadosEntrada          = {};
        oDadosEntrada.codigo   = marcacao.codigo;
        oDadosEntrada.data     = marcacao.data;
        oDadosEntrada.hora     = marcacao.hora;
        oDadosEntrada.alterado = marcacao.alterado || marcacao.manual;

        oDados.aMarcacoes.push(oDadosEntrada);
      });

      aDados.push(oDados);
    });

    AjaxRequest.create(
      'rec4_pontoeletronico.RPC.php',
      {
        'exec'       : 'salvarRegistrosPonto',
        'periodo' : {
          'dataInicio' : oPeriodoEfetividade.getDataFormatada(oPeriodoEfetividade.getDataInicio()),
          'dataFim'    : oPeriodoEfetividade.getDataFormatada(oPeriodoEfetividade.getDataFim()),
        },
        'aDados'     : aDados
      },
      function(oRetorno, lErro) {

        if(!lSalvarInicial || lErro) {
          alert(oRetorno.mensagem);
        }

        if(lErro) {
          return;
        }

        buscaRegistros(false);
      }
    ).asynchronous(false).setMessage('Aguarde... Salvando os registros...').execute();
  }

  function hintLegenda () {

    oGridRegistros.collection.get().forEach(function(oItem, iLinha) {

      var sDescricao = 'Alterado manualmente';

      oItem.diaPonto.getMarcacoes().each(function (marcacao, ind) {

        if(marcacao.alterado || marcacao.manual === true) {
          oGridRegistros.grid.setHint(iLinha, (marcacao.tipo+1), sDescricao);
        }

        if(marcacao.justificativa != null || oItem.diaPonto.afastamento.isAfastado) {
          if(marcacao.hora != '' && marcacao.hora != null) {
            oGridRegistros.grid.setHint(iLinha, (marcacao.tipo+1), 'Hora marcação: ' + marcacao.hora);
          }
        }
      });
    });
  }

  function hintEvento() {

    oGridRegistros.collection.get().forEach(function(oItem, iLinha) {

      if (oItem.descricaoEvento.trim() !== '') {
        oGridRegistros.grid.setHint(iLinha, 1, '<b>Evento:</b> ' + oItem.descricaoEvento);
      }
    });

  }

  function imprimeEspelhoPonto() {

    var oRelatorio = new EmissaoRelatorio(
      'rec2_espelhoponto002.php',
      {
        'sDataInicio'        : oPeriodoEfetividade.getDataFormatada(oPeriodoEfetividade.getDataInicio()),
        'sDataFim'           : oPeriodoEfetividade.getDataFormatada(oPeriodoEfetividade.getDataFim()),
        'aMatriculas'        : [$F('rh01_regist')],
        'lMostraObservacoes' : true
      }
    );
    oRelatorio.open();
  }

  function criarMarcacoesNasDatas(oRegistrosSemMarcacao) {

    var r;

    AjaxRequest.create(
      'rec4_pontoeletronico.RPC.php',
      {
        'exec'      : 'criarMarcacoesNasDatas',
        'matricula' : $F('rh01_regist'),
        'datas'     : oRegistrosSemMarcacao.build()
      },
      function (retorno, erro) {
        r = !erro;
        if(erro) {
          alert(retorno.mensagem);
        }

        oCollectionRegistrosSemMarcacao.clear();
        buscaRegistros(true);
      }
    ).asynchronous(false).setMessage('Ajustando registros de marcações...').execute();

    return r;
  }

  function ocultarBotoesRegistrar () {

    aBotoesOcultar.each(function(sId) {
      $('action_registrar_' + sId).setStyle({'display' : 'none'});
    });
  }

  /**
   * Ao retirar o foco do campo rh01_regist, limpa a grid, evitando que os dados sejam pesquisados para o servidor errado
   */
  $('rh01_regist').observe('blur', function() {
    oGridRegistros.clear();
  });
</script>
</html>
