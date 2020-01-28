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
  db_app::load("AjaxRequest.js");
  db_app::load("datagrid.widget.js");
  db_app::load("widgets/Collection.widget.js");
  db_app::load("widgets/DatagridCollection.widget.js");
  ?>
  <style type="text/css">
    #gridTiposAssentamentos{
      width: 800px;
      margin: 0 auto;
    }
  </style>
</head>
<body>
  <div class="container">
    <form>
      <fieldset>
        <legend>Justificativa</legend>

        <table class="form-container">
          <tr style="display: none;">
            <td>
              <label for="codigo">Código:</label>
            </td>
            <td>
              <input id="codigo" type="text" value="" class="field-size2 readonly" disabled="disabled" />
            </td>
          </tr>

          <tr>
            <td>
              <label for="descricao">Descrição:</label>
            </td>
            <td>
              <input id="descricao" type="text" value="" class="field-size7" maxlength="50" />
            </td>
          </tr>

          <tr>
            <td>
              <label for="abreviacao">Abreviação:</label>
            </td>
            <td>
              <input id="abreviacao" type="text" value="" class="field-size2" maxlength="3" />
            </td>
          </tr>
        </table>
      </fieldset>
    </form>
  </div>
  <div class="container" style="margin-top: 0">
    <fieldset>
      <legend>Tipos de Assentamentos</legend>
      <div id="gridTiposAssentamentos"></div>
    </fieldset>

    <input id="novaJustificativa"      type="button" value="Nova" />
    <input id="salvarJustificativa"    type="button" value="Salvar"    />
    <input id="pesquisarJustificativa" type="button" value="Pesquisar" />
    <input id="excluirJustificativa"   type="button" value="Excluir"   disabled="disabled" />
  </div>
</body>
<script>
$('novaJustificativa').observe('click', novaJustificativa);
$('salvarJustificativa').observe('click', salvarJustificativa);
$('pesquisarJustificativa').observe('click', pesquisarJustificativa);
$('excluirJustificativa').observe('click', excluirJustificativa);

$('descricao').addEventListener('keyup', function(event) {
  js_ValidaCampos(this, 3, 'Descrição', 'f', 't', event);
});

$('abreviacao').addEventListener('keyup', function(event) {
  js_ValidaCampos(this, 3, 'Abreviação', 'f', 't', event);
});

var collectionTipoasse = Collection.create().setId('sequencial');
var gridTipoasse       = DatagridCollection.create(collectionTipoasse);

montarGrid();
buscarRegistros();

function montarGrid () {

  gridTipoasse.addColumn('sequencial', {'width': '100px', 'label': 'Sequencial', 'align': 'center'});
  gridTipoasse.addColumn('codigo',     {'width': '100px', 'label': 'Código',     'align': 'center'});
  gridTipoasse.addColumn('descricao',  {'width': '500px', 'label': 'Descrição',  'align': 'center'});

  gridTipoasse.configure({'height': '200px', 'order' : false});
  gridTipoasse.hideColumns([1]);
  gridTipoasse.grid.setCheckbox(0);
 
  gridTipoasse.show($('gridTiposAssentamentos'));
}

function buscarRegistros () {
  AjaxRequest.create(
    'rec4_pontoeletronicoconfiguracoes.RPC.php',
    {
      exec     : 'buscarTiposAssentamentos'
    },
    function (retorno, erro) {

      if(erro) {
        alert(retorno.mensagem);
      }

      retorno.tiposAssentamentos.each(function (item) {
        collectionTipoasse.add(item);
      });

      gridTipoasse.reload();
    }
  ).setMessage('Buscando registros...').execute();
}

function novaJustificativa() {

  $('codigo').value     = '';
  $('descricao').value  = '';
  $('abreviacao').value = '';

  $('excluirJustificativa').setAttribute('disabled', 'disabled');
  
  gridTipoasse.setSelectedItens([]);
  gridTipoasse.reload();
}

function salvarJustificativa() {

  if(!validaCampos()) {
    return false;
  }

  var aTiposAssentamentos = [];
  
  if(gridTipoasse.grid.getSelection().length == 0) {
    alert('Selecione ao menos um tipo de assentamento.');
    return false;
  }
    
  gridTipoasse.grid.getSelection().each(function (itemGrid) {
    aTiposAssentamentos.push(itemGrid[0]);
  });

  AjaxRequest.create(
    'rec4_pontoeletronicoconfiguracoes.RPC.php',
    {
      'exec'               : 'salvarJustificativa',
      'iCodigo'            : $F('codigo'),
      'sDescricao'         : $F('descricao'),
      'sAbreviacao'        : $F('abreviacao'),
      'tiposAssentamentos' : aTiposAssentamentos
    },
    function(oRetorno, lErro) {

      alert(oRetorno.mensagem);

      if(lErro) {
        return false;
      }

      novaJustificativa();
    }
  ).setMessage('Aguarde... Salvando a justificativa.').execute();
}

function pesquisarJustificativa() {

  var sUrl  = 'func_pontoeletronicojustificativa.php?funcao_js=parent.retornoPesquisarJustificativa';
      sUrl += '|rh194_sequencial|rh194_descricao|rh194_sigla';

  js_OpenJanelaIframe(
    '',
    'db_iframe_pontoeletronicojustificativa',
    sUrl,
    'Pesquisa Justificativa',
    true
  );
}

function retornoPesquisarJustificativa(iCodigo, sDescricao, sAbreviacao) {

  novaJustificativa();
  db_iframe_pontoeletronicojustificativa.hide();

  if(iCodigo !== null && typeof iCodigo !== 'undefined' && iCodigo !== '') {
    consultarTiposAssentamentos (iCodigo);
  }

  $('codigo').value     = iCodigo;
  $('descricao').value  = sDescricao;
  $('abreviacao').value = sAbreviacao;

  $('excluirJustificativa').removeAttribute('disabled');

}

function excluirJustificativa() {

  if(empty($F('codigo'))) {

    alert('Código da justificativa não encontrado.');
    return false;
  }

  if(!confirm('Confirma a exclusão da justificativa ' + $F('codigo') + ' - ' + $F('descricao'))) {
    return false;
  }

  AjaxRequest.create(
    'rec4_pontoeletronicoconfiguracoes.RPC.php',
    {
      'exec'    : 'excluirJustificativa',
      'iCodigo' : $F('codigo')
    },
    function(oRetorno, lErro) {

      alert(oRetorno.mensagem);

      if(lErro) {
        return false;
      }

      novaJustificativa();
    }
  ).setMessage('Aguarde... Excluindo a justificativa.').execute();
}

function validaCampos() {

  if(empty($F('descricao'))) {

    alert('Descrição não informada.');
    return false;
  }

  if(empty($F('abreviacao'))) {

    alert('Abreviação não informada.');
    return false;
  }

  return true;
}

function consultarTiposAssentamentos (codigoJustificativa) {
  AjaxRequest.create(
    'rec4_pontoeletronicoconfiguracoes.RPC.php',
    {
      'exec'                : 'buscarTiposAssentamentosConfigurados',
      'codigoJustificativa' : codigoJustificativa
    },
    function  (retorno, erro) {

      if(erro) {
        alert(retorno.mensagem);
      }

      gridTipoasse.setSelectedItens([]);

      retorno.tiposAssentamentos.each(function (tipo) {
        gridTipoasse.addSelectedItens(tipo);
      });

      gridTipoasse.reload();
    }
  ).asynchronous(false).setMessage('Consultando tipos de assentamentos..').execute();
}
</script>
</html>