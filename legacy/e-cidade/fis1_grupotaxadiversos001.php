<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016 DBselller Servicos de Informatica
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
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");

?>
<html>
  <head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      /**
       * Default
       */
      $aLibs   = array("scripts.js");
      $aLibs[] = "prototype.js";
      $aLibs[] = "AjaxRequest.js";
      $aLibs[] = "strings.js";
      $aLibs[] = "estilos.css";

      /**
       * Datagrid
       */
      $aLibs[] = "datagrid.widget.js";
      $aLibs[] = "grid.style.css";

      /**
       * Collections
       */
      $aLibs[] = "Collection.widget.js";
      $aLibs[] = "DatagridCollection.widget.js";
      $aLibs[] = "FormCollection.widget.js";

      /**
       * DBLookUp
       */
      $aLibs[] = "DBLookUp.widget.js";

      /**
       * DBInput
       */
      $aLibs[] = "Input/DBInput.widget.js";
      $aLibs[] = "Input/DBInputValor.widget.js";
      $aLibs[] = "Input/DBInputInteger.widget.js";

      /**
       * DBHint
       */
      $aLibs[] = "widgets/DBHint.widget.js";
      $aLibs[] = "widgets/datagrid/plugins/DBHint.plugin.js";
      
      db_app::load(implode(",", $aLibs));
    ?>
  </head>
  <body>

  <form class="container">
    <fieldset>
      <legend>
        Dados do Grupo
      </legend>

      <table class="form-container">
        <tr style="display:none;">
          <td>
            <label for="y118_sequencial">
              Código:
            </label>
          </td>
          <td>
            <input id="y118_sequencial" name="y118_sequencial" class="field-size2 readOnly" disabled readonly tabindex="-1"/>
          <td>
        </tr>

        <tr>
          <td>
            <label for="y118_descricao">
              Descrição:
            </label>
          </td>
          <td>
            <input id="y118_descricao" name="y118_descricao" class="field-size10"/>
          <td>
        </tr>

        <tr>
          <td>
            <label for="i01_codigo">
              <a id="ancora_inflator" tabindex="-1">
                Inflator:
              </a>
            </label>
          </td>
          <td>
            <input id="i01_codigo" name="i01_codigo" class="field-size2" />
            <input id="i01_descr"  name="i01_descr"  class="field-size8 readOnly" disabled tabindex="-1"/>
          <td>
        </tr>

        <tr>
          <td>
            <label for="dv09_procdiver">
              <a id="ancora_procedencia" tabindex="-1">
                Procedência:
              </a>
            </label>

          </td>
          <td>
            <input id="dv09_procdiver" name="dv09_procdiver" class="field-size2"/>
            <input id="dv09_descr"     name="dv09_descr"     class="field-size8 readOnly" disabled tabindex="-1"/>
          <td>
        </tr>
      </table>
    </fieldset>
    <input type="button" value="Salvar"   id="salvar" />
    <input type="button" value="Excluir"  id="excluir" disabled/>
    <input type="button" value="Novo" id="cancelar"/>
  </form>
  <div class="container" style="width: 800px">
    <fieldset>
      <Legend>
        Valores Lançados
      </Legend>
      <div id="grid_formulario"></div>
    </fieldset>
  </div>
    <?php db_menu(); ?>
  </body>
</html>
<script>


  var oRequest      = new AjaxRequest('fis1_taxagrupo.RPC.php', {"exec": 'getGrupos'});
  oLookupInflator = new DBLookUp($('ancora_inflator'), $('i01_codigo'), $('i01_descr'), {
    arquivo : "func_inflan.php",
    label   : "Pesquisa de Inflator",
  });

  oLookupProcedencia = new DBLookUp($('ancora_procedencia'), $('dv09_procdiver'), $('dv09_descr'), {
    arquivo : "func_procdiver.php",
    label   : "Pesquisa de Procedência de Diversos",
  });


  grupoTaxa     = Collection.create().setId("y118_sequencial");

  /**
   * Trata o valor que entrará na coleção
   */
  grupoTaxa.setEvent("onBeforeCreate", function(itemCollection) {
    itemCollection.y118_sequencial = itemCollection.y118_sequencial || '---';
    return true;
  });

  var gridGrupoTaxa = createDatagrid(grupoTaxa);
  formGrupoTaxa = createForm(gridGrupoTaxa);
  formGrupoTaxa.onAfterSelectRow(function(acao){

    oLookupInflator.habilitar();
    oLookupProcedencia.habilitar();

    if(acao == 'E') {

      oLookupInflator.desabilitar();
      oLookupProcedencia.desabilitar();
    }
    setarHint(gridGrupoTaxa);
  });

  makeInputs();
  gridGrupoTaxa.show($('grid_formulario'));
  carregarGrid(gridGrupoTaxa);


  /**
   *
   */
  function createForm(grid) {

    var formGrupoTaxa = new FormCollection(gridGrupoTaxa, document.forms[0]);
        formGrupoTaxa.makeBehavior($('salvar'),   'save',   save);
        formGrupoTaxa.makeBehavior($('excluir'),  'delete', remove);

        formGrupoTaxa.events.onClickCancel = function (e) {
          this.clearForm();
          setarHint(gridGrupoTaxa);
        }.bind(formGrupoTaxa);

        formGrupoTaxa.makeBehavior($('cancelar'), 'cancel');

    return formGrupoTaxa;
  }

  function createDatagrid(collection) {

    var gridGrupoTaxa = new DatagridCollection(collection);
    gridGrupoTaxa.addColumn("y118_sequencial")
                 .configure('label', 'Código')
                 .configure('align', 'center')
                 .configure('width', '60px')
                 ;
    gridGrupoTaxa.addColumn("y118_descricao")
                 .configure('label','Descrição')
                 .configure('width', '200px')
                 .transform(function (descricao, itemCollection) {

                    if(descricao.length > 25) {
                      return descricao.substr(0, 25) +'...';
                    }

                    return descricao;
                 })
                 ;

    gridGrupoTaxa.addColumn("i01_descr")
                 .configure('label', 'Inflator')
                 .configure('align', 'left')
                 .configure('width', '200px')
                 .transform(function(valor, itemCollection) {
                   
                    var sTexto = itemCollection.i01_codigo + ' - ' + itemCollection.i01_descr;

                    if(sTexto.length > 25) {
                      sTexto = sTexto.substr(0, 25) +'...';
                    }

                    return sTexto;
                 })
                 ;                 ;
    gridGrupoTaxa.addColumn("dv09_descr")
                 .configure('label', 'Procedência')
                 .configure('align', 'left')
                 .configure('width', '200px')
                 .transform(function(valor, itemCollection) {
                   
                    var sTexto = itemCollection.dv09_procdiver + ' - ' + itemCollection.dv09_descr;

                    if(sTexto.length > 25) {
                      sTexto = sTexto.substr(0, 25) +'...';
                    }

                    return sTexto;
                 });
    return gridGrupoTaxa;
  }

  function carregarGrid(gridGrupoTaxa) {

    oRequest.setCallBack(function(oResposta, lErro) {

      if(lErro) {
        alert(oResposta.mensagem);
      }

      grupoTaxa.add(oResposta.valores);
      gridGrupoTaxa.reload();

      setarHint(gridGrupoTaxa);
    });
    oRequest.setMessage('Buscando Grupos cadastrados...');
    oRequest.execute();
  }

  function save(dadosPreenchidos) {

    var dadosAnteriores;

    if (!dadosPreenchidos.y118_descricao) {

      alert("O campo Descrição é obrigatório.");
      $('y118_descricao').focus();
      return false;
    }

    if (!dadosPreenchidos.i01_codigo) {

      alert("O campo Inflator é obrigatório.");
      $('i01_codigo').focus();
      return false;
    }

    if (!dadosPreenchidos.dv09_procdiver) {

      alert("O campo Procedência é obrigatório.");
      $('dv09_procdiver').focus();
      return false;
    }

    if (dadosPreenchidos.y118_sequencial) {
      dadosAnteriores = grupoTaxa.get(dadosPreenchidos.y118_sequencial);
    }

    oRequest.setParameters({'item' : dadosPreenchidos,'exec' : 'salvar'});
    oRequest.setMessage('Salvando Grupo de Taxa.')

    oRequest.setCallBack(function(resposta, erro){

      grupoTaxa.remove('---');
      
      if(resposta.mensagem) {
        alert(resposta.mensagem);
      }

      if (erro) {


        if(!dadosPreenchidos.y118_sequencial) {

          formGrupoTaxa.clearForm();
          return;
        }

        formGrupoTaxa.selectItem('A', dadosPreenchidos);
        return;
      }

      location.reload();
    });
    oRequest.execute();
    return true;
  };

  function remove(itemCollection) {

    if (itemCollection.y118_sequencial == '---') {

      grupoTaxa.remove(itemCollection.y118_sequencial);
      return true;
    }

    oRequest.setParameters({'item' : itemCollection, 'exec' : 'excluir'});
    oRequest.setMessage('Excluindo Grupo de Taxa.')
    oRequest.setCallBack(function(resposta, erro){
      formGrupoTaxa.clearForm();

      if(resposta.mensagem) {
        alert(resposta.mensagem);
      }

      if (erro) {

        formGrupoTaxa.selectItem('E', itemCollection);
        return;
      }     
      location.reload();
    });
    oRequest.execute();
    return
  }

  function makeInputs() {
    new DBInputInteger($('dv09_procdiver'));
    $('y118_descricao').focus();
    $('cancelar').observe('click', function() {
      oLookupInflator.habilitar();
      oLookupProcedencia.habilitar();
    });
  }

  function setarHint(gridGrupoTaxa) {

    gridGrupoTaxa.collection.get().forEach(function(oGrupo, iLinha) {
      
      if(oGrupo.y118_descricao.length > 25) {
        gridGrupoTaxa.grid.setHint(iLinha, 1, oGrupo.y118_descricao);
      }
      
      if((oGrupo.i01_codigo +' - '+ oGrupo.i01_descr).length > 25) {
        gridGrupoTaxa.grid.setHint(iLinha, 2, (oGrupo.i01_codigo +' - '+ oGrupo.i01_descr));
      }
      
      if((oGrupo.dv09_procdiver +' - '+ oGrupo.dv09_descr).length > 25) {
        gridGrupoTaxa.grid.setHint(iLinha, 3, (oGrupo.dv09_procdiver +' - '+ oGrupo.dv09_descr));
      }

    });
  }

</script>
