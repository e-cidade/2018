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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
$oDaoEventoAutomatico = new cl_eventofinanceiroautomatico();
$oDaoEventoAutomatico->rotulo->label();
?>
<html>
  <head>
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("scripts.js");
    db_app::load("prototype.js");
    db_app::load("windowAux.widget.js");
    db_app::load("strings.js");
    db_app::load("AjaxRequest.js");
    db_app::load("dbtextField.widget.js");
    db_app::load("dbViewAvaliacoes.classe.js");
    db_app::load("dbmessageBoard.widget.js");
    db_app::load("dbautocomplete.widget.js");
    db_app::load("dbcomboBox.widget.js");
    db_app::load("datagrid.widget.js");
    db_app::load("widgets/DBHint.widget.js");
    db_app::load("widgets/datagrid/plugins/DBHint.plugin.js");
    db_app::load("widgets/DBLookUp.widget.js");
    db_app::load("widgets/Collection.widget.js");
    db_app::load("widgets/DatagridCollection.widget.js");
    db_app::load("widgets/FormCollection.widget.js");
    db_app::load("estilos.css,grid.style.css");
    ?>
    <style>
      input#rh181_descricao {width: 100%}
    </style>
  </head>
  <body>
    <div class="container">
      <form id="formConfiguracoesEventosFinanceiros" method="POST">
        <fieldset class="container">
          <Legend>Evento Financeiro Automático</Legend>
          <table >
            <tr>
              <td>
                <label for="rh181_descricao">
                  <b><?=$Lrh181_descricao;?></b>
                </label>
              </td>
              <td>
                <?php
                db_input('rh181_sequencial', 40, 1, true, 'hidden', 1);
                db_input('rh181_descricao', 40, $Irh181_descricao, true, 'text', 1);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label for="rh181_rubrica"><a id='lblRubrica' href=""><?=$Lrh181_rubrica;?></a></label>
              </td>
              <td>
                <?php
                db_input('rh181_rubrica', 10, $Irh181_rubrica, true, 'text', 1, 'data="rh27_rubric"');
                db_input('rh27_descr', 30, 1, true, 'text', 3);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label for="rh181_selecao"><a href="" id="lblSelecao"><?=$Lrh181_selecao?></a></label>
              </td>
              <td>
                <?php
                db_input('rh181_selecao', 10, $Irh181_selecao, true, 'text', 1, 'data="r44_selec"');
                db_input('r44_descr', 30, 1, true, 'text', 3);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label for="rh181_mes">
                  <b><?=$Lrh181_mes;?></b>
                </label>
              </td>
              <td>
                <select id="rh181_mes" name="rh181_mes">
                  <option value='0'>Selecione</option>
                  <option value='1'>Janeiro</option>
                  <option value='2'>Fevereiro</option>
                  <option value='3'>Março</option>
                  <option value='4'>Abril</option>
                  <option value='5'>Maio</option>
                  <option value='6'>Junho</option>
                  <option value='7'>Julho</option>
                  <option value='8'>Agosto</option>
                  <option value='9'>Setembro</option>
                  <option value='10'>Outubro</option>
                  <option value='11'>Novembro</option>
                  <option value='12'>Dezembro</option>
                </select>
              </td>
            </tr>
          </table>
        </fieldset>
        <input type="button" value="Salvar" id="btnSalvar">
        <input type="button" value="Novo" id="btnNovo">
        <input type="button" value="Excluir" id="btnExcluir" disabled="DISABLED">
      </form>
    </div>
    <div class="container">
      <fieldset>
        <legend>
          Configurações
        </legend>
        <div id="gridConfiguracoes" style="width:900px"></div>
      </fieldset>
    </div>
  </body>

</html>
<?php
db_menu();
?>
<script>

  const URL_RPC      = 'pes4_eventofinanceiroautomatico.RPC.php';
  var oLookUpRubrica = new DBLookUp ($('lblRubrica'), $('rh181_rubrica'), $('rh27_descr'),
                                      {'sArquivo' : 'func_rhrubricas.php',
                                       'aParametrosAdicionais' : ['somentcomformula=1']});

  var oLookUpSelecao = new DBLookUp ($('lblSelecao'), $('rh181_selecao'), $('r44_descr'), {'sArquivo' : 'func_selecao.php'});

  var oConfiguracoesEventos  = new Collection().setId('rh181_sequencial');

  oConfiguracoesEventos.setEvent('onAfterCreate', function(item) {

    if (empty(item.rh181_sequencial)) {
      salvarEventoAutomatico(item, this);
    }
  });

  oConfiguracoesEventos.setEvent('onBeforeUpdate', function(item) {
    salvarEventoAutomatico(item);
  });

  oConfiguracoesEventos.setEvent('onBeforeDelete', function(item) {

    AjaxRequest.create(URL_RPC,
      {
        'exec'     : "removerEvento",
        'iCodigo' : item.rh181_sequencial
      },
      function(response, erro) {

        alert(response.sMensagem);

        if(erro) {

          return true;
        }
      }
    ).setMessage('Removendo...Configuração').execute();
  });

  /**
   * Cria a grid, vinculada com a coleção;
   * @type {any}
   */
  var oConfiguracoesEventosGrid = new DatagridCollection(oConfiguracoesEventos, 'gridConfiguracoesEventosAutomaticos');
  oConfiguracoesEventosGrid.configure({"height":"350", "width":"800", "update":true, "delete":true});
  oConfiguracoesEventosGrid.addColumn('rh181_descricao',
                                      {
                                        'label': 'Descrição',
                                        'align': 'left',
                                        'width': '200px'
                                      }
                                     );
  oConfiguracoesEventosGrid.addColumn('rh181_rubrica',
                                      {
                                        'label': 'Rubrica',
                                        'align': 'left',
                                        'width': '280px',

                                      }
                                    ).transform(function(sRubrica, oItemCollecion) {
                                       return  sRubrica+" - "+ oItemCollecion.rh27_descr;
                                    });
  oConfiguracoesEventosGrid.addColumn('rh181_mes',
                                      {
                                        'label': 'Mês',
                                        'align': 'left',
                                        'width': '80px'
                                      }
                                    ).transform(function(iMes) {
                                         return  db_mes(iMes);
                                    });

  oConfiguracoesEventosGrid.addColumn('rh181_selecao',
                                      {
                                        'label': 'Seleção',
                                        'align': 'left',
                                        'width': '200px'
                                      }
                                    ).transform(function(iSelecao, oItemCollecion) {
                                      return oItemCollecion.r44_descr;
                                    });

  oConfiguracoesEventosGrid.show($('gridConfiguracoes'));

  /**
   * Cria o vinculo da grid com o formulario
   */
  var oFormCollectionConfiguracoes = FormCollection.create(oConfiguracoesEventosGrid, $("formConfiguracoesEventosFinanceiros"));
  oFormCollectionConfiguracoes.makeBehavior($('btnSalvar'),
    'save',
    function(item) {

      if (empty(item.rh181_descricao)) {

        alert('Campo descrição deve ser informado.');

        return false;
      }

      if (empty(item.rh181_rubrica)) {
        alert('Campo Rubrica deve ser informado.');
        return false;
      }

      if (empty(item.rh181_mes)) {

        alert('Campo Mês deve ser informado.');
        return false;
      }

      if (empty(item.rh181_selecao)) {

        alert('Campo Seleção deve ser informado.');
        return false;
      }

      if (!validarInclusaoEventoDuplicado(item)) {

        alert('Já existe um Evento Automático com esses dados.');
        return false;
      }

      return true
    }
  );
  oFormCollectionConfiguracoes.makeBehavior($('btnExcluir'), 'delete', function(item) {

    if (!confirm('Confirma a exclusão do Evento Automático?')) {
      return false;
    }
  });

  oFormCollectionConfiguracoes.makeBehavior($('btnNovo'), 'cancel');


  function salvarEventoAutomatico(item, datagridCollection) {

      AjaxRequest.create(URL_RPC,
      {
        'exec'     : "salvarEvento",
        'iCodigo'  :  item.rh181_sequencial,
        'sRubrica' :  item.rh181_rubrica,
        'iMes'     :  item.rh181_mes,
        'iSelecao' :  item.rh181_selecao,
        'sDescricao': item.rh181_descricao
      },

      function(response, erro) {

        alert(response.sMensagem);

        if (erro) {

          datagridCollection.getCollection().itens.pop();
          datagridCollection.reload();
          return false;
        }

        if (datagridCollection) {

          if(response.iCodigoConfiguracao) {

            item.ID               = String(response.iCodigoConfiguracao);
            item.rh181_sequencial = response.iCodigoConfiguracao;
          }

          console.log(item);

          oConfiguracoesEventosGrid.reload();
        }
        $('rh181_sequencial').value = '';
      }
    ).setMessage('Salvando dados do Evento').execute();
  }

  function getDadosEventosFinanceiros() {

    AjaxRequest.create(URL_RPC,
      {
        'exec' : "getEventos"
      },
      function(response, erro) {

        if(erro) {
          alert(response.sMessage.urlDecode());
          return;
        }

        if(response.eventos.length > 0) {
          
          for(var configuracao of response.eventos) {

            oConfiguracoesEventosGrid.collection.add({
              'rh181_sequencial' : configuracao.iCodigo,
              'rh181_rubrica'    : configuracao.sCodigoRubrica,
              'rh27_descr'       : configuracao.sNomeRubrica,
              'rh181_selecao'    : configuracao.iCodigoSelecao,
              'r44_descr'        : configuracao.sNomeSelecao,
              'rh181_mes'        : configuracao.iMes,
              'rh181_descricao'  : configuracao.sDescricao
            });
          }
        }

        oConfiguracoesEventosGrid.reload();
      }
    ).setMessage('Aguarde, pesquisando eventos automáticos cadastrados.').execute();
  }

  getDadosEventosFinanceiros();

  function validarInclusaoEventoDuplicado(oEvento) {

    for (oEventoLancado of oConfiguracoesEventosGrid.getCollection().get()) {

      if (oEvento.rh181_sequencial != "" && oEvento.rh181_sequencial == oEventoLancado.rh181_sequencial) {
         continue;
      }
      if (oEvento.rh181_rubrica == oEventoLancado.rh181_rubrica && oEvento.rh181_mes == oEventoLancado.rh181_mes && oEvento.rh181_selecao == oEventoLancado.rh181_selecao) {
        return false;
      }
    }
    return true;
  }
</script>