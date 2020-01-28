<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */


  require_once("libs/db_stdlib.php");
  require_once("libs/db_utils.php");
  require_once("dbforms/db_funcoes.php");
  require_once("libs/db_app.utils.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");

  $oDaoCampos = db_utils::getDao("db_syscampo");
  $oDaoTabela = db_utils::getDao("db_sysarquivo");

  $oGet = db_utils::postMemory($_GET);

  $iTabela = $oGet->iTabela;

  $sSqlTabela = $oDaoTabela->sql_query_file($iTabela);
  $rsTabela   = $oDaoTabela->sql_record($sSqlTabela);

  if ($oDaoTabela->numrows == 0) {
    die("ERROR");
  }

  $oDadosTabela = db_utils::fieldsMemory($rsTabela, 0);
  $sNomeTabela  = $oDadosTabela->nomearq;

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js, prototype.js, estilos.css, json2.js");
    ?>
    <script type="text/javascript" src="scripts/widgets/dbcomboBox.widget.js"></script>
    <script type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>

  </head>
  <body style="background-color: #ccc;">

    <div class="center container" style="width: 800px;">

      <form method="post" id="form1" name="form1">

        <input type="hidden" id="codarq" value="<?php echo $iTabela; ?>" />
        <input type="hidden" id="codigo_campo" value="" />

        <fieldset>
          <legend>Cadastro de Campos - Tabela: <?php echo $sNomeTabela; ?></legend>

          <table class="form-container" border="0">

            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td rowspan="11" align="center">
                <strong>Valores Default:</strong><br>
                <select multiple name="itensdef[]" onChange="js_mostradef(this)" size="10" id="itensdef" style="width:300;">
                </select>
                <br>

                <input name="textodef" type="text" id="textodef" style="width:245" maxlength="100">
                <input type="button" name="adicionar" id="adicionar" onClick="js_adddef(this.form)" style="width:15" value="+">
                <input type="button" name="retirar" onClick="js_remdef(this.form)" style="width:15" value="-" disabled>
                <input type="button" name="alterardef" onClick="js_alterardef(this.form)" style="width:15" value="A" disabled>

                <br>

                <strong>Descrição Valor Default:</strong><br>
                <textarea name="descitensdef" rows="5" id="descitensdef" style="width:300"></textarea>

              </td>
            </tr>

            <tr>
              <td>
                <label id="lbl_campo_principal" for="campo_principal">Campo Principal:</label>
              </td>
              <td colspan="2">
                <input type="text" id="campo_principal" name="campo_principal" class="field-size5"/>
                <input type="hidden" id="id_campo_principal" name="id_campo_principal" />
              </td>
            </tr>

            <tr>
              <td title="Nome do Campo">
                <label id="lbl_nome_campo" for="nome_campo">Nome do Campo:</label>
              </td>
              <td colspan="2">
                <input type="text" id="nome_campo" name="nome_campo" class="field-size5"/>
              </td>
            </tr>

            <tr>
              <td title="Tipo/Tamanho">
                <label id="lbl_tipo_campo" for="tipo_campo">Tipo/Tamanho:</label>
              </td>
              <td id="conteudo"></td>
              <td>
                <input id="tamanho" name="tamanho" class="field-size1"/>
              </td>
            </tr>

            <tr>
              <td>
                <label id="lbl_label_form" for="label_form">Label Formulário:</label>
              </td>
              <td colspan="2">
                <input id="label_form" name="label_form" class="field-size5"/>
              </td>
            </tr>

            <tr>
              <td>
                <label id="lbl_label_rel" for="label_rel">Label Relatório:</label>
              </td>
              <td colspan="2">
                <input id="label_rel" name="label_rel" class="field-size5"/>
              </td>
            </tr>

            <tr>
              <td>
                <label id="lbl_default" for="default">Valor Default:</label>
              </td>
              <td colspan="2">
                <input id="default" name="default" class="field-size5"/>
              </td>
            </tr>

            <tr>
              <td colspan="3">
                <label for="descricao" id="lbl_descricao">Descrição:</label>
              </td>
            </tr>

            <tr>
              <td colspan="3">
                <textarea id="descricao" name="descricao"></textarea>
              </td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" id="aceita_nulo" name="aceita_nulo"/>
                <label id="lbl_aceita_nulo" for="aceita_nulo">Aceita Nulo</label>
              </td>

              <td>
                <input type="checkbox" id="maiusculo" name="maiusculo"/>
                <label id="lbl_maiusculo" for="maiusculo">Maiúsculo</label>
              </td>

              <td>
                <input type="checkbox" id="auto_completar" name="auto_completar"/>
                <label id="lbl_auto_completar" for="auto_completar">Auto Completar</label>
              </td>

            </tr>

            <tr>
              <td>
                <label id="lbl_validacao" for="validacao">Validação</label>
              </td>
              <td id="validacao" colspan="2"></td>
            </tr>

          </table>

        </fieldset>

        <input type="button" id="incluir" value="Incluir" />
        <input type="button" id="excluir" value="Excluir" disabled="true" />
        <input type="button" id="cancelar" value="Cancelar" disabled="true" />

        <fieldset class="separator"></fieldset>

        <div id="gridCampos"></div>

        <br />

        <input type="button" id="salvar" value="Salvar" />
        <input type="button" id="visualizaForm" value="Visualizar Formulário" />

        <input type="hidden" value="" id="campos" name="campos" />
      </form>

    </div>

  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>


  <script type="text/javascript">

    (function() {

      var aCampos = [];

      var sUrlRpc = "sys1_camposnovo.RPC.php";

      /**
       * Tipos de campo
       */
      var aItens = {
        "0"       : "Tipos"  ,
        "varchar" : "Varchar",
        "text"    : "Text"   ,
        "oid"     : "Oid"    ,
        "int4"    : "Int4"   ,
        "int8"    : "Int8"   ,
        "float4"  : "Float4" ,
        "float8"  : "Float8" ,
        "bool"    : "Lógico" ,
        "char"    : "Char"   ,
        "date"    : "Data"
      };

      /**
       * Instancia do select de tipos de campo
       */
      var oComboboxTipoCampo = new DBComboBox("tipo_campo", "oComboboxTipoCampo", aItens);


      /**
       * Tipo de validação
       */
      var aItensValidacao = {
        "0": "Não Valida Campo",
        "1": "Somente Números",
        "2": "Somente Letras",
        "3": "Números e Letras",
        "4": "Números Casa Dec.",
        "5": "Verdadeiro/Falso"
      };

      var oComboboxValidacao = new DBComboBox("tipo_validacao", "oComboboxValidacao", aItensValidacao);
      /**
       * Event handler do campo Tipo Campo
       */
      var eventTipoCampo;
      oComboboxValidacao.show($("validacao"));


      oComboboxTipoCampo.show($("conteudo"));
      $("tipo_campo").observe("change", eventTipoCampo = function() {

        var oTamanho   = $("tamanho"),
            oMaiusculo = $("maiusculo");

        oTamanho.focus();
        oMaiusculo.disabled = true;
        oMaiusculo.checked  = false;

        var sValor = oComboboxTipoCampo.getValue();

        switch (sValor) {

          case "int4":
          case "int8":
            oComboboxValidacao.setValue(1);
          break;

          case "date":
            oComboboxValidacao.setValue(1);
            oTamanho.value = '10';
          break;

          case "oid" :
            oTamanho.value = '1';
            oComboboxValidacao.setValue(1);
          break;

          case "char":
          case "varchar":
          case "text":

            oMaiusculo.checked=true;
            oMaiusculo.disabled=false;

            oComboboxValidacao.setValue(0);

            if ( sValor == 'text' ) {
              oTamanho.value = '1';
            }

          break;

          case "bool":
            oComboboxValidacao.setValue(5);
            oTamanho.value = '1';
          break;

          case "float4":
          case "float8":
            oComboboxValidacao.setValue(4);

          break;

          default:
            oMaiusculo.disabled = false;
            oComboboxValidacao.setValue(0);

        }

      })


      /**
       * Instancia do autocomplete
       */
      var oCampoPrincipal = new dbAutoComplete($("campo_principal"), sUrlRpc);

      oCampoPrincipal.setTxtFieldId($("id_campo_principal"));
      oCampoPrincipal.show();
      oCampoPrincipal.setQueryStringFunction(function() {

        var oParams = {
          sField    : $F("campo_principal"),
          sExecucao : "findField"
        }

        return "json="+JSON.stringify(oParams);

      });

      /**
       * Ao selecionar o campo principal
       * É preenchido automaticamente os outros campos
       */
      oCampoPrincipal.setCallBackFunction(function(id, label, oDadoCampo) {

        $("id_campo_principal").value = id;
        oComboboxTipoCampo.setValue(oDadoCampo.conteudo.urlDecode().split("(")[0]);
        eventTipoCampo();
        $("tamanho").value          = oDadoCampo.tamanho;
        $("label_form").value       = oDadoCampo.rotulo.urlDecode();
        $("label_rel").value        = oDadoCampo.rotulorel.urlDecode();
        $("default").value          = oDadoCampo.valorinicial.urlDecode();
        $("descricao").value        = oDadoCampo.descricao.urlDecode();
        $("aceita_nulo").checked    = oDadoCampo.nulo      == "t";
        $("maiusculo").checked      = oDadoCampo.maiusculo == "t";
        $("auto_completar").checked = oDadoCampo.autocompl == "t";
        oComboboxValidacao.setValue(oDadoCampo.aceitatipo);

        $("nome_campo").focus();
      });


      /**
       * Instancia a grid
       */
      var oGridCampos = new DBGrid("gridCampos");
      oGridCampos.nameInstance = "oGridCampos";
      oGridCampos.setCellWidth(["25%", "10%", "10%", "10%", "20%","20%", "5%"]);
      oGridCampos.setCellAlign(["left", "center", "center", "center", "center", "center", "center"]);
      oGridCampos.setHeader(["Nome", "Tipo", "Tamanho", "Aceita Nulo", "Validação", "Valores Default", "&nbsp;"]);
      oGridCampos.show($('gridCampos'));
      oGridCampos.clearAll(true);

      /**
       * Faz requisicao para buscar os campos da tabela selecionada
       */
      js_requestFields();


      /**
       * Event listener do botao incluir
       */
      $("incluir").observe("click", function() {

        /**
         * Busca o campo do form
         */
        var oCampo = js_getField(this.value);

        /**
         * Valida o campo do form
         */
        if ( js_validaCampo(oCampo) ) {

          /**
           * Adiciona o campo no array final
           */
          js_addField(oCampo);


          /**
           * Adiciona o campo na grid
           */
          js_loadGrid();


          js_resetForm();
        }

      })


      /**
       * Event listener do botao exlcuir
       */
      $("excluir").observe("click", function() {

        var oCampo = js_getField(this.value);

        if ( confirm("Tem certeza que deseja excluir o campo?")) {
          js_removeField(oCampo.nome_campo);
          js_resetForm();
          js_loadGrid();
        }

      })

      $("salvar").observe("click", function() {

        var oParametros = {
          sExecucao: "salvar",
          aCampos  : aCampos,
          iTabela  : $F("codarq")
        }

        var oDadosRequisicao = {
          method       : 'POST',
          asynchronous : false,
          parameters   : 'json='+encodeURIComponent(Object.toJSON(oParametros)),
          onComplete   : function(oAjax) {

            var oRetorno = JSON.parse(oAjax.responseText);

            alert(oRetorno.sMessage.urlDecode());

            if (oRetorno.iStatus == "2") {
              return false;
            }

            aCampos = [];
            js_requestFields();

          }
        }

        var oAjax  = new Ajax.Request( sUrlRpc, oDadosRequisicao );

      });


      $("cancelar").observe("click", function() {
        js_resetForm();
        js_loadGrid();
      });

      $("label_form").observe("blur", function() {

        if ($F("label_rel") == '') {
          $("label_rel").value = this.value;
        }
      });

      /**
       * Recarrega os dados da grid, de acordo com o array principal
       * @param sNomeCampo string - Nome do campo que éstá no form
       */
      function js_loadGrid(sNomeCampo) {

        oGridCampos.clearAll(true);

        for (i = 0; i < aCampos.length; i++) {

          var oCampo = aCampos[i];

          if (oCampo.nome_campo == sNomeCampo) {
            continue;
          }

          var sButton = '<input type="button" value="S" class="selectRow" data-nome="'+oCampo.nome_campo+'" />';

          oGridCampos.addRow([
            oCampo.nome_campo,
            aItens[oCampo.tipo_campo] || oCampo.tipo_campo,
            oCampo.tamanho,
            oCampo.aceita_nulo ? "Sim" : "Não",
            aItensValidacao[oCampo.tipo_validacao] || oCampo.tipo_validacao,
            oCampo.valores_default,
            sButton
          ]);

        }

        oGridCampos.renderRows();

        /**
         * Para cada botao "Selecionar" da grid, aplica um evento
         */
        $$(".selectRow").each(function(oButton) {
          oButton.observe("click", function() {
            /**
             * Chama a funcao generica para dar load no campo
             */
            js_resetForm();
            js_loadField(this.getAttribute("data-nome"));
            js_loadGrid(this.getAttribute("data-nome"));
            $("salvar").disabled  = true;
            $("cancelar").disabled = false;
          })
        });

      }

      /**
       * Busca os valores da tela e coloca num objeto
       */
      function js_getField(method) {
        var oCampo;

        aItens = $("itensdef").options;
        aItensDef = new Array();
        for (var iCont = 0; iCont < aItens.length; iCont++) {
          aItensDef.push(aItens[iCont].value);
        }

        oCampo = {
          codigo_campo       : $F("codigo_campo")            ,
          nome_campo         : $F("nome_campo")              ,
          tipo_campo         : oComboboxTipoCampo.getValue() ,
          tamanho            : $F("tamanho")                 ,
          label_form         : $F("label_form")              ,
          label_rel          : $F("label_rel")               ,
          "default"          : $F("default")                 ,
          descricao          : $F("descricao")               ,
          aceita_nulo        : $F("aceita_nulo")    == "on"  ,
          maiusculo          : $F("maiusculo")      == "on"  ,
          auto_completar     : $F("auto_completar") == "on"  ,
          tipo_validacao     : oComboboxValidacao.getValue() ,
          id_campo_principal : $F("id_campo_principal")      ,
          campo_principal    : $F("campo_principal")         ,
          valores_default    : aItensDef,
          method             : method
        }

        return oCampo;
      }

      /**
       * Reseta o valores do form
       */
      function js_resetForm() {

        $("campo_principal")   .value   = "";
        $("id_campo_principal").value   = "";
        $("codigo_campo")      .value   = "";
        $("nome_campo")        .value   = "";
        $("nome_campo").removeAttribute("readonly");
        oComboboxTipoCampo.setValue("0");
        eventTipoCampo();
        $("tamanho")           .value   = "";
        $("label_form")        .value   = "";
        $("label_rel")         .value   = "";
        $("default")           .value   = "";
        $("descricao")         .value   = "";
        $("aceita_nulo")       .checked = false;
        $("maiusculo")         .checked = false;
        $("auto_completar")    .checked = false;
        oComboboxValidacao.setValue("0");
        $("itensdef").innerHTML         = '';
        $("descitensdef").value     = '';
        $("textodef").value         = '';
        $("adicionar").disabled     = false;

        $("incluir").value    = "Incluir";
        $("excluir").disabled = true
        $("cancelar").disabled = true
        $("salvar").disabled  = false

      }

      /**
       * Valida os campo passados por parametro
       */
      function js_validaCampo(oCampo) {

        if (!oCampo.nome_campo) {
          alert("Informe o Nome do Campo.");
          return false;
        }

        /**
         * Caso seja inclusão verifica se já existe o nome cadastrado.
         */
        if (oCampo.method == "Incluir" && js_findIndex(oCampo.nome_campo) >= 0 ) {
          alert("Campo já existente.");
          return false;
        }

        if (oCampo.tipo_campo == "0") {
          alert("Informe o Tipo de Campo.")
          return false;
        }

        if (!oCampo.tamanho) {
          alert("Informe o Tamanho do campo.");
          return false;
        }

        if (!oCampo.descricao) {
          alert("Informa a Descrição do Campo");
          return false;
        }

        return true;
      }

      /**
       * Adiciona um campo no array principal
       */
      function js_addField(oCampo, lForce) {

        lForce = lForce || false;

        delete oCampo.method;

        if (!lForce) {

          var iIndex = js_findIndex(oCampo.nome_campo)

          if (iIndex >= 0) {
            aCampos[iIndex] = oCampo;
            return;
          }

        }

        aCampos.push(oCampo)
      }

      /**
       * Procura o indice do array correspondente ao nome do campo passado
       */
      function js_findIndex(sNome) {
        for (var i = 0; i < aCampos.length; i++) {
          if (sNome == aCampos[i].nome_campo) {
            return i;
          }
        };
      }

      /**
       * Remove um campo do array pelo nome
       */
      function js_removeField(sNome) {

        delete aCampos[js_findIndex(sNome)];
        aCampos = aCampos.filter(function(v,k){
          return v;
        })

      }

      function js_loadField(sNome) {

        var oCampo = aCampos[js_findIndex(sNome)];

        $("campo_principal")   .value   = oCampo.campo_principal;
        $("id_campo_principal").value   = oCampo.id_campo_principal;
        $("codigo_campo")      .value   = oCampo.codigo_campo;
        $("nome_campo")        .value   = oCampo.nome_campo;
        $("nome_campo").setAttribute("readonly", true);
        oComboboxTipoCampo.setValue(oCampo.tipo_campo);
        eventTipoCampo();
        $("tamanho")           .value   = oCampo.tamanho;
        $("label_form")        .value   = oCampo.label_form;
        $("label_rel")         .value   = oCampo.label_rel;
        $("default")           .value   = oCampo["default"];
        $("descricao")         .value   = oCampo.descricao;
        $("aceita_nulo")       .checked = oCampo.aceita_nulo;
        $("maiusculo")         .checked = oCampo.maiusculo;
        $("auto_completar")    .checked = oCampo.auto_completar;
        oComboboxValidacao.setValue(oCampo.tipo_validacao);

        oCampo.valores_default.each(function(sDado){

          var oOption = document.createElement("option");
          aDado = sDado.split("#&");
          oOption.text = aDado[0];
          oOption.value = aDado[0]+'#&'+aDado[1];
          $("itensdef").add(oOption, null);
        });

        $("incluir").value = "Alterar";
        $("excluir").disabled = false;
      }


      function js_requestFields() {

        var oParametros = {
          sExecucao: "getFields",
          iTabela  : $F("codarq")
        }

        var oDadosRequisicao = {
          method       : 'POST',
          asynchronous : false,
          parameters   : 'json='+Object.toJSON(oParametros),
          onComplete   : function(oAjax) {

            var oRetorno = JSON.parse(oAjax.responseText);

            if (oRetorno.status != "2") {

              oRetorno.oCampos.each(function(oDado, iIndex) {

                var oCampo = {
                  campo_principal    : oDado.campo_principal || '',
                  id_campo_principal : oDado.id_campo_principal || '',
                  codigo_campo       : oDado.codcam,
                  nome_campo         : oDado.nomecam,
                  tipo_campo         : oDado.conteudo.urlDecode().split("(")[0],
                  tamanho            : oDado.tamanho,
                  label_form         : oDado.rotulo.urlDecode(),
                  label_rel          : oDado.rotulorel.urlDecode(),
                  "default"          : oDado.valorinicial.urlDecode(),
                  descricao          : oDado.descricao.urlDecode(),
                  aceita_nulo        : oDado.nulo      == "t",
                  maiusculo          : oDado.maiusculo == "t",
                  auto_completar     : oDado.autocompl == "t",
                  tipo_validacao     : oDado.aceitatipo,
                  valores_default    : js_requestDefaultValues(oDado.codcam)
                }

                js_addField(oCampo, true);

              });

              js_loadGrid();

            }

          }
        }

        var oAjax  = new Ajax.Request( sUrlRpc, oDadosRequisicao );

      }

      function js_requestDefaultValues(iCodCam) {

        var oParametros = {
          sExecucao: "getDefaultValues",
          iCodCam  : iCodCam
        }

        var aDados = new Array();

        var oDadosRequisicao = {
          method       : 'POST',
          asynchronous : false,
          parameters   : 'json='+Object.toJSON(oParametros),
          onComplete   : function(oAjax) {

            var oRetorno = JSON.parse(oAjax.responseText);

            if (oRetorno.status != "2") {

              oRetorno.aValoresDefault.each(function(oDado, iIndex) {

                aDados.push(oDado);

              });
            }
          }
        }

        var oAjax  = new Ajax.Request( sUrlRpc, oDadosRequisicao );

        return aDados;

      }

      $("visualizaForm").observe("click", function() {

        $('campos').value = Object.toJSON(aCampos);

        js_OpenJanelaIframe('top.corpo','db_iframe_visualizaForm','func_camposnovovisualizaform.php','Pesquisa',true);
      });

    })();


    function js_adddef(obj) {

      if(obj.textodef.value == "") {

        alert("Campo não pode ser vazio!");
        obj.textodef.focus();
        return false;
      }

      obj.elements["itensdef[]"].options[obj.elements["itensdef[]"].length] = new Option(obj.textodef.value,obj.textodef.value + '#&' + obj.descitensdef.value);
      obj.elements["itensdef[]"].options[obj.elements["itensdef[]"].length-1].select = true;
      js_trocacordeselect();
      obj.textodef.value = "";
      obj.descitensdef.value = "";
      obj.textodef.focus();
    }

    function js_mostradef(obj) {

      var mat = new String(obj.options[obj.selectedIndex].value);
      mat = mat.split("#&");
      document.form1.textodef.value = mat[0];
      document.form1.descitensdef.value = mat[1];
      document.form1.adicionar.disabled = true;
      document.form1.retirar.disabled = false;
      document.form1.alterardef.disabled = false;
    }

    function js_alterardef(obj) {

      document.form1.adicionar.disabled = false;
      document.form1.retirar.disabled = true;
      document.form1.alterardef.disabled = true;
      obj.elements["itensdef[]"].options[obj.elements["itensdef[]"].selectedIndex].text = obj.textodef.value;
      obj.elements["itensdef[]"].options[obj.elements["itensdef[]"].selectedIndex].value = obj.textodef.value + '#&' + obj.descitensdef.value;
      obj.textodef.value = "";
      obj.descitensdef.value = "";
      obj.textodef.focus();
    }

    function js_remdef(obj) {

      if(!confirm("Excluir Item Default?"))
        return false;

      obj.elements["itensdef[]"].options[obj.elements["itensdef[]"].selectedIndex] = null;
      js_trocacordeselect();
      document.form1.adicionar.disabled = false;
      document.form1.retirar.disabled = true;
      document.form1.alterardef.disabled = true;
      obj.textodef.value = "";
      obj.descitensdef.value = "";
      obj.textodef.focus();
    }


  </script>

  </body>
</html>