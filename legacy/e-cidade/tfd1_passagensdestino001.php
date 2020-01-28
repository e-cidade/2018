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
    <?
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
      db_app::load(implode(",", $aLibs));
    ?>
  </head>
  <body>
  
  <form class="container" id="formularioPassagens">
    <input id="tf37_sequencial" name="tf37_sequencial" type="hidden" />
    <fieldset>
      <legend>
        Dados do destino
      </legend>
      <table class="form-container">
        <tr>
          <td>
            <a id="ancoraDestino">
              Destino:
            </a>
          </td>
          <td>
            <input id="tf37_destino" name="tf37_destino" data="tf03_i_codigo" />
            <input id="tf03_c_descr" name="tf03_c_descr" />
          <td>
        </tr>
        <tr>
          <td>
            <label>
              Valor da Passagem:
            </label>
          </td>
          <td>
            <input id="tf37_valor" name="tf37_valor" class="field-size2"/>
          <td>
        </tr>
      </table>  
    </fieldset>
    <input type="button" value="Salvar"   id="salvar" />    
    <input type="button" value="Excluir"  id="excluir" disabled/>
    <input type="button" value="Cancelar" id="cancelar"/>
  </form>
  <div class="container" style="width: 700px">
    <fieldset>
      <Legend>
        Valores Lançados
      </Legend>
      <div id="containerGridPassagens"></div>
    </fieldset>
  </div>
    <?php
     db_menu();
    ?>
  </body>
</html>
<script>

(function() {


  var oRequest      = new AjaxRequest('tfd1_passagensdestino.RPC.php', {"exec": 'getValoresDestinos'});


  var oLookup       = new DBLookUp($('ancoraDestino'), $('tf37_destino'), $('tf03_c_descr'), {
    arquivo : "func_tfd_destino.php",
    label   : "Pesquisar destino",
  });


  var passagens     = Collection.create().setId("tf37_sequencial");

  /**
   * Trata o valor que entrará na coleção
   */
  passagens.setEvent("onBeforeCreate", function(itemCollection) {
    itemCollection.tf37_sequencial = itemCollection.tf37_sequencial || '---';
    itemCollection.tf37_valor      = (itemCollection.tf37_valor instanceof String) ? itemCollection.tf37_valor.getNumber() : itemCollection.tf37_valor;
    return true;
  });

  var gridPassagens = createDatagrid(passagens);
  formPassagens = createForm(gridPassagens);
  
  makeInputs();
  gridPassagens.show($('containerGridPassagens'));
  carregarGrid(gridPassagens);  


  /**
   * 
   */
  function createForm(grid) {

    var formPassagens = new FormCollection(gridPassagens, $("formularioPassagens"));
    formPassagens.makeBehavior($('salvar'),   'save',   save);
    formPassagens.makeBehavior($('excluir'),  'delete', remove);
    formPassagens.makeBehavior($('cancelar'), 'cancel');
    return formPassagens;
  }

  function createDatagrid(collection) {

    var gridPassagens = new DatagridCollection(collection);
    gridPassagens.addColumn("tf37_sequencial")
                 .configure('label', 'Código')
                 .configure('align', 'center')
                 .configure('width', '60px')
                 ;
    gridPassagens.addColumn("tf03_c_descr")
                 .configure('label','Destino')
                 .configure('width', '450px')
                 .transform(function(valor, itemCollection) {

                   return itemCollection.tf37_destino + ' - ' + itemCollection.tf03_c_descr;
                 });

                 ;

    gridPassagens.addColumn("tf37_valor")
                 .configure('label', 'Valor')
                 .configure('align', 'right')
                 .configure('width', '60px')
                 .transform('number');
    return gridPassagens;
  }

  function carregarGrid(gridPassagens) {
  
    oRequest.setCallBack(function(oResposta, lErro) {

      if(lErro) {
        alert(oResposta.mensagem);
      }

      passagens.add(oResposta.valores);
      gridPassagens.reload();
    });  
    oRequest.setMessage('Buscando destinos cadastrados...');
    oRequest.execute();
  }

  function save(dadosPreenchidos) {
    
    var dadosAnteriores;

    if (!dadosPreenchidos.tf37_destino) {

      alert("O Campo destino é obrigatório.");
      $('tf37_destino').focus();
      return false;
    }

    if(!dadosPreenchidos.tf37_valor) {
  
      alert("O Campo valor é obrigatório.");
      $('tf37_valor').focus();
      return false;
    }

    if (dadosPreenchidos.tf37_sequencial) {
      dadosAnteriores = passagens.get(dadosPreenchidos.tf37_sequencial);
    }

    dadosPreenchidos.tf37_valor = dadosPreenchidos.tf37_valor.getNumber();
    oRequest.setParameters({'item' : dadosPreenchidos,'exec' : 'salvar'});
    oRequest.setMessage('Salvando dados da passagem.')
    oRequest.setCallBack(function(resposta, erro){

      passagens.remove('---');

      if (erro) {

        alert(resposta.mensagem);

        if(!dadosPreenchidos.tf37_sequencial) {
          
          formPassagens.clearForm();
          return;
        }
        
        formPassagens.selectItem('A', dadosPreenchidos);
        return;
      }
      passagens.add(resposta.item);
      gridPassagens.reload();
    });
    oRequest.execute();
    return true;
  };

  function remove(itemCollection) {

    if (itemCollection.tf37_sequencial == '---') {

      passagens.remove(itemCollection.tf37_sequencial);
      return true;
    }

    oRequest.setParameters({'item' : itemCollection, 'exec' : 'excluir'});
    oRequest.setMessage('Excluindo dados da passagem.')
    oRequest.setCallBack(function(resposta, erro){
    formPassagens.clearForm();

      if (erro) {

        alert(resposta.mensagem);
        formPassagens.selectItem('E', itemCollection);
        return;
      }
    });
    oRequest.execute();
    return 
  }

  function makeInputs() {

    new DBInputValor($('tf37_valor'));
    new DBInputInteger($('tf37_destino'));
  }
})();
</script>
