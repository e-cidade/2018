<?php
  $oRotulo  = new rotulocampo;
  $oRotulo->label('db149_sequencial');
  $oRotulo->label('db149_descricao');
  $oRotulo->label('db150_inicio');
  $oRotulo->label('db150_final');
  $oRotulo->label('rh175_percentual');
  $oRotulo->label('rh175_deducao');
?>
<html>
  <head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("estilos.css");
    db_app::load("scripts.js");
    db_app::load("strings.js");
    db_app::load("object.js");
    db_app::load("prototype.js");
    db_app::load("datagrid.widget.js");
    db_app::load("Collection.widget.js");
    db_app::load("DatagridCollection.widget.js");
    db_app::load("AjaxRequest.js");
    db_app::load("DBLookUp.widget.js");
    ?>
  </head>
  <body class="body-default">

    <form class="container" action="" method="post" id="form_tabela">
      <fieldset>
        <legend>
          Tabela de Valores do IRRF
        </legend>
        <table class="form-container">

          <tr>
            <td>
              <label for="db149_descricao">
                Descrição da Tabela:
              </label>
            </td>
            <td>
              <?php
              db_input("db149_sequencial", 4, $Idb149_sequencial, 1, 'text', 3);
              db_input("db149_descricao",  40, $Idb149_descricao,  1, 'text', $db_opcao);
              ?>
            </td>
          </tr>

        </table>
      </fieldset>

      <input id="novo" value="Novo" type="<?php echo ($db_opcao == 1) ? "button" : "hidden"; ?>" />
      <input type="button" id="processar" value="Processar" onclick="this.form.submit();"/>
      <input id="pesquisar" value="Pesquisar" type="button" />

    </form>

    <?php if ($db_opcao <> 1): ?>
    <form class="container" id="faixas">
      <?php

        $sDisplay         = "block";
        $sType            = "button";

        if($db_opcao == 3 ) {

          $sDisplay = "none";
          $sType    = "hidden";
        }

        db_input("db150_sequencial", 10, 0, 1, 'hidden', $db_opcao);
        db_input("rh175_sequencial", 10, 0, 1, 'hidden', $db_opcao);
        db_input("db149_sequencial", 10, 0, 1, 'hidden', $db_opcao);
      ?>

      <fieldset style="width:700px; display: <?php echo $sDisplay; ?>" >
        <legend>
          Faixas de Valores
        </legend>
        <table class="form-container">

          <tr>
            <td>
              <label for="db150_inicio">
                Inicio da Faixa:
              </label>
            </td>
            <td>
              <?php
              db_input("db150_inicio", 10, $Idb150_inicio, 1, 'text', $db_opcao);
              ?>
            </td>
            <td>
              <label for="db150_final">
                Fim da Faixa:
              </label>
            </td>
            <td>
              <?php
              db_input("db150_final", 10, $Idb150_final, 1, 'text', $db_opcao);
              ?>
            </td>
            <td>
              <label for="rh175_percentual">
                Percentual:
              </label>
            </td>
            <td>
              <?php
              db_input("rh175_percentual", 10, $Irh175_percentual, 1, 'text', $db_opcao);
              ?>
            </td>
            <td>
              <label for="rh175_deducao">
                Dedução:
              </label>
            </td>
            <td>
              <?php
              db_input("rh175_deducao", 10, $Irh175_deducao, 1, 'text', $db_opcao);
              ?>
            </td>
          </tr>
        </table>
      </fieldset>

        <input type="<?php echo $sType;?>" id="salvar_faixa"   value="Salvar">
        <input type="<?php echo $sType;?>" id="excluir_faixa"  value="Excluir">
        <input type="<?php echo $sType;?>" id="cancelar_faixa" value="Cancelar" disabled>

      <fieldset style="width:700px;">
        <legend>
          Registros
        </legend>
        <table class="form-container">
          <tr>
            <td>
              <div id="grid_valores_container"></div>
            </td>
          </tr>
        </table>
      </fieldset>
    </form>

    <script>

    var oAjaxRequest = new AjaxRequest('pes1_faixavaloresirrf.RPC.php', {"exec":"carregar", "tabela":$F('db149_sequencial')}, function(resposta) {

      if(!resposta.erro) {
        carregarDados(resposta.dados);
      }
    });

    oAjaxRequest.setMessage('Salvando dados das Faixas');
    oAjaxRequest.execute();


    function carregarDados(aDados) {

      try {

        var oColecaoFaixas = new Collection();
        oColecaoFaixas.setId("db150_sequencial");


        for( var oRegistro of aDados) {

          oRegistro.db150_inicio     = js_formatar(oRegistro.db150_inicio,     "f");
          oRegistro.db150_final      = js_formatar(oRegistro.db150_final,      "f");
          oRegistro.rh175_percentual = js_formatar(oRegistro.rh175_percentual, "f");
          oRegistro.rh175_deducao    = js_formatar(oRegistro.rh175_deducao,    "f");
          oColecaoFaixas.add(oRegistro);
        }

        var oGridColecaoFaixas = DatagridCollection.create(oColecaoFaixas)
        <?php if($db_opcao <> 3): ?>
        .configure("update", true)
        .configure("delete", true)
        <?php endif; ?>
        ;

        var selectionarRegistro   = function(oItemCollection) {

          $('db150_sequencial').setValue(oItemCollection.db150_sequencial);
          $('rh175_sequencial').setValue(oItemCollection.rh175_sequencial);

          $('db150_inicio').setValue(oItemCollection.db150_inicio.getNumber());
          $('db150_final').setValue(oItemCollection.db150_final.getNumber());
          $('rh175_percentual').setValue(oItemCollection.rh175_percentual.getNumber());
          $('rh175_deducao').setValue(oItemCollection.rh175_deducao.getNumber());
          $('cancelar_faixa').disabled = false;
          oItemCollection.datagridRow.selectLine();
          oGridColecaoFaixas.setSelectedItem(oItemCollection);
        }

        oGridColecaoFaixas.setEvent("onClickUpdate", function(event, item) {

          selectionarRegistro(item);

          $('salvar_faixa').disabled = false;
          $('excluir_faixa').disabled = true;

          $('db150_inicio').readOnly     = false;
          $('db150_final').readOnly      = false;
          $('rh175_percentual').readOnly = false;
          $('rh175_deducao').readOnly    = false;

          $('db150_inicio').removeClassName("readOnly");
          $('db150_final').removeClassName("readOnly");
          $('rh175_percentual').removeClassName("readOnly");
          $('rh175_deducao').removeClassName("readOnly");

        });

        oGridColecaoFaixas.setEvent("onClickDelete", function(event, item) {

          selectionarRegistro(item);

          $('salvar_faixa').disabled = true;
          $('excluir_faixa').disabled = false;

          $('db150_inicio').readOnly     = true;
          $('db150_final').readOnly      = true;
          $('rh175_percentual').readOnly = true;
          $('rh175_deducao').readOnly    = true;

          $('db150_inicio').addClassName("readOnly");
          $('db150_final').addClassName("readOnly");
          $('rh175_percentual').addClassName("readOnly");
          $('rh175_deducao').addClassName("readOnly");

          // $('cancelar_faixa').disa   = true;

        });

        $('cancelar_faixa').observe('click', function(){
          location.href = "";
        });

        var requisicao = function(acao, itens, redireciona) {


          var oParametros  = {"exec":acao, "oItem":itens};

          var oAjaxRequest = new AjaxRequest('pes1_faixavaloresirrf.RPC.php', oParametros, function(resposta) {

            alert(resposta.message.urlDecode());

            if(!resposta.erro && !!redireciona) {
              return window.location.href = '';
            }
          });

          oAjaxRequest.setMessage('Salvando dados das Faixas');
          oAjaxRequest.execute();
        };

        $('salvar_faixa').observe("click", function(){
          requisicao("salvar_faixa", this.form.serialize(true), true);
        });

        $('excluir_faixa').observe("click", function(){
          requisicao("excluir_faixa", this.form.serialize(true), true);
        });

        oGridColecaoFaixas.addColumn("db150_inicio")
          .options({"width":"100px"})
          .options("align","right")
          .options("label","Inicio");

        oGridColecaoFaixas.addColumn(
          "db150_final",
          {"width":"100px", "align":"right", "label":"Fim"}
        );
        oGridColecaoFaixas.addColumn(
          "rh175_percentual",
          {"width":"100px", "align":"right", "label":"Percentual"}
        );
        oGridColecaoFaixas.addColumn(
          "rh175_deducao",
          {"width":"100px", "align":"right", "label":"Dedução"}
        );

        oGridColecaoFaixas.show($('grid_valores_container'));

      } catch(e) {
        console.error(e);
      }
    };
    </script>
  <?php endif; ?>

  <script>
    (function(){
      try{

      var oGet = js_urlToObject();


      var oOptions = {
        "sArquivo"              : "func_db_tabelavalores.php",
        "sLabel"                : "Pesquisar Tabela de Faixa de Valores",
        "sObjetoLookUp"         : "func_db_tabelavalores",
        "sQueryString"          : "&db149_db_tabelavalorestipo=2"
      };
      var lookupFaixas = new DBLookUp(
        $("form_tabela").pesquisar,
        $("form_tabela").db149_sequencial,
        $("form_tabela").db149_descricao,
        oOptions
      );
      lookupFaixas.oInputDescricao.removeClassName("readOnly");
      lookupFaixas.oInputDescricao.readOnly = false;
      lookupFaixas.setCallBack("onClick", function() {
        window.location.href = '<?php echo $sFonteRedireciona; ?>?db149_sequencial=' + lookupFaixas.oInputID.getValue()+'&db149_descricao='+$F("db149_descricao").urlEncode();
      });
      lookupFaixas.oAncora.className = "";

      <?php if($db_opcao == 1): ?>
        $('novo').on('click', function() {
          $('db149_descricao').value    = '';
          $('db149_descricao').readOnly = false;
          $('db149_descricao').removeClassName('readonly');
        });
      <?php endif; ?>

    } catch(e){
      console.error(e);
    }

    })();
  </script>
  <?php db_menu(); ?>
  </body>
</html>
