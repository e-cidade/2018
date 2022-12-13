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
$oDaoTipoRecolhimento = new \cl_tiporecolhimento;
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewCadastroAtributoDinamico.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div class="container">
      <form id="frmTiporecolhimento" method="post">
      <fieldset>
        <legend>Tipo de Recolhimento</legend>
        <table>
          <tr>
            <td>
              <label for="codigo"><b>Código:</b></label>
            </td>
            <td>
               <input type="text" id="codigo" readonly class="field-size2 readonly">
               <input type="hidden" id="grupo_atributo_dinamico" name="grupo_atributo_dinamico">
            </td>
          </tr>
          <tr>
            <td>
              <label for="codigo_recolhimento"><b>Código do Recolhimento:</b></label>
            </td>
            <td>
              <input type="text" id="codigo_recolhimento" onkeyup="js_ValidaCampos(this, 0, 'Código do Recolhimento', 0, 't')" class="field-size4">
            </td>
          </tr>
          <tr>
            <td>
              <label for="nome"><b>Título:</b></label>
            </td>
            <td>
              <input type="text" id="nome" class="field-size8" onkeyup="js_ValidaCampos(this, 0, 'Título', 0, 't')" maxlength="100">
            </td>
          </tr>
          <tr>
            <td>
              <label for="titulo_reduzido"><b>Título Reduzido:</b></label>
            </td>
            <td>
              <input type="text" id="titulo_reduzido" class="field-size8" onkeyup="js_ValidaCampos(this, 0, 'Título Reduzido', 0, 't')" maxlength="100">
            </td>
          </tr>
          <tr>
            <td>
              <label for="codigo_workflow" id="lblCodigoWorkflow"><b>Atividade:</b></label>
            </td>
            <td>
              <input type="text" id="codigo_workflow" data='db112_sequencial' onkeyup="js_ValidaCampos(this, 1, 'Atividade', 0, 1)" class="field-size2">
              <input type="text" id="nome_workflow" data='db112_descricao' class="field-size6 readonly" readonly maxlength="100">
            </td>
          </tr>
          <tr>
          <td colspan="4">
            <fieldset class="separator">
              <legend>Opções</legend>
              <table>
                <tr>
                 <td>
                  <label for="especie_ingresso">
                    <b>Espécie de Ingresso:</b>
                  </label>
                </td>
                <td>
                  <select id="especie_ingresso" class="field-size6">
                    <option value="1" selected>Receita</option>
                    <option value="2">DDO</option>
                    <option value="3">Estorno de Despesa</option>
                  </select>
                </td>

              <tr>
                <td>
                  <label for="tipo_recolhedor">
                    <b>Tipo Recolhedor:</b>
                  </label>
                </td>
                <td>
                  <select id="tipo_recolhedor" class="field-size6">
                    <option value="1">Pessoa Física</option>
                    <option value="2">Pessoa Jurídica</option>
                    <option value="3" default>Ambos</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="obriga_referencia">
                    <b>Obriga Informar Número de Referência:</b>
                  </label>
                </td>
                <td>
                  <select id="obriga_referencia" class="field-size6">
                    <option value="1">Sim</option>
                    <option value="2" selected>Não</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="informa_desconto">
                    <b>Informar Desconto/Abatimento:</b>
                  </label>
                </td>
                <td>
                  <select id="informa_desconto" class="field-size6">
                    <option value="1">Sim</option>
                    <option value="2" selected>Não</option>
                  </select>
                </td>
              </tr>
                <tr>
                  <td>
                    <label for="informa_outras_deducoes">
                      <b>Informar Outras Deduções:</b>
                    </label>
                  </td>
                  <td>
                    <select id="informa_outras_deducoes" class="field-size6">
                      <option value="1">Sim</option>
                      <option value="2" selected>Não</option>
                    </select>
                  </td>
                </tr>
              <tr>
                <td>
                  <label for="informa_multa">
                    <b>Informar Mora/Multa:</b>
                  </label>
                </td>
                <td>
                  <select id="informa_multa" class="field-size6">
                    <option value="1">Sim</option>
                    <option value="2" selected>Não</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="informa_juros">
                    <b>Informar Juros/Encargos:</b>
                  </label>
                </td>
                <td>
                  <select id="informa_juros" class="field-size6">
                    <option value="1">Sim</option>
                    <option value="2" selected>Não</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="informa_outros_acrescimos">
                    <b>Informar Outros Acréscimos:</b>
                  </label>
                </td>
                <td>
                  <select id="informa_outros_acrescimos" class="field-size6">
                    <option value="1">Sim</option>
                    <option value="2" selected>Não</option>
                  </select>
                </td>
              </tr>
            </table>
            </fieldset>
          </td>
          </tr>
          <tr>
            <td colspan="4">
               <fieldset class="separator">
                 <legend>
                     <label for="instrucoes">Instruções*</label>
                 </legend>
                 <textarea id="instrucoes" style="width:100%; resize: none" rows="3"></textarea>
                 <div style="text-align: right"><b>* As instruções podem conter até 7 linhas</b></div>
               </fieldset>
            </td>
          </tr>
        </table>
      </fieldset>
        <input type="button" value="Salvar"    id="btnSalvar" />
        <input type="button" value="Excluir"   id="btnExcluir" disabled />
        <input type="button" value="Pesquisar" id="btnPesquisar" />
        <input type="button" value="Novo"      id="btnNovo" />
        <input type="button" value="Adicionar Campos" id="btnAdicionarCampos" onclick="adicionarCampos()" />
      </form>
    </div>
  </body>
</html>
<script>

  (function(window) {

    const URL_RPC  = 'cai4_tiporecolhimento.RPC.php';

    function salvar () {

      if ($('codigo_recolhimento').value == '') {
        alert('Preenchimento obrigatório do campo Código de Recolhimento!');
        return false;
      }

      if ($('nome').value =='') {

        alert('Preenchimento obrigatório do campo Título!');
        return false;

      }

      if ($('titulo_reduzido').value =='') {

        alert('Preenchimento obrigatório do campo Título Reduzido!');
        return false;

      }

      if ($('tipo_recolhedor').value == '') {

        alert('Preenchimento obrigatório do campo Tipo Recolhedor!');
        return false;
      }

      if( $('obriga_referencia').value == '') {

        alert('Informar número de referência!');
        return false;
      }

      var aInstrucoes =  $('instrucoes').value.split('\n');

      if (aInstrucoes.length > 7) {
        alert("Não é possível informar mais que sete linhas no campo Instruções. Informe até sete linhas no mesmo.");
        $('instrucoes').focus();
        return false;
      }

      var lErro = false;
      aInstrucoes.each(
        function (linha, indice) {

          if (linha.length > 100) {
            lErro = true;
            return alert("A linha "+(indice+1)+" possui mais de 100 caracteres.");
          }
        }
      );

      if (lErro) {
        return false;
      }

      var oParametro = {
        exec     : 'salvar',
        codigo : $F('codigo'),
        codigo_recolhimento       : $F('codigo_recolhimento'),
        nome                      : $F('nome'),
        titulo_reduzido           : $F('titulo_reduzido'),
        especie_ingresso          : $F('especie_ingresso'),
        tipo_recolhedor           : $F('tipo_recolhedor'),
        codigo_workflow           : $F('codigo_workflow'),
        instrucoes                : $F('instrucoes'),
        obriga_referencia         : $F('obriga_referencia') == 1,
        informa_desconto          : $F('informa_desconto') == 1,
        informa_multa             : $F('informa_multa') == 1,
        informa_juros             : $F('informa_juros') == 1,
        informa_outros_acrescimos : $F('informa_outros_acrescimos') == 1,
        informa_outras_deducoes   : $F('informa_outras_deducoes') == 1,
        grupo_atributo_dinamico   : $('grupo_atributo_dinamico').value
      }

      new AjaxRequest(URL_RPC, oParametro, function (oResponse, lErro) {

        alert(oResponse.sMessage.urlDecode());
        if (lErro) {
          return;
        }

        limpar();
      }).setMessage('Aguarde, processando dados...').execute();
    }

    function excluir () {

      if (!confirm('Confirma a exclusão do Tipo de Recolhimento?')) {
        return;
      }
      var oParametro = {
        exec     : 'remover',
        codigo : $F('codigo')
      }

      new AjaxRequest(URL_RPC, oParametro, function (oResponse, lErro) {

        alert(oResponse.sMessage.urlDecode());
        if (lErro) {
          return;
        }

        limpar();

        pesquisar();
      }).setMessage('Aguarde, removendo dados...').execute();
    }

    function pesquisar () {
      js_OpenJanelaIframe('CurrentWindow.corpo',
                          'db_iframe_tiporecolhimento',
                          'func_tiporecolhimento.php?funcao_js=parent.js_preenchepesquisa|k172_sequencial|k172_codigorecolhimento',
                          'Pesquisa de Tipos de Recolhimentos',
                          true
                        );
    }

    window.js_preenchepesquisa = function(tiporecolhimento) {

      db_iframe_tiporecolhimento.hide();
      var oParametro = {
        exec     : 'getDadosTipoRecolhimento',
        codigo : tiporecolhimento
      }

      new AjaxRequest(URL_RPC, oParametro, function (oResponse, lErro) {

        if (lErro) {

          alert(oResponse.sMessage.urlDecode());
          return;
        }

        fillFormFromObject($('frmTiporecolhimento'), oResponse);
        $('btnExcluir').disabled = false;
      }).setMessage('Aguarde, processando dados...').execute();
    }

    function limpar() {

      $('frmTiporecolhimento').reset();
      $('btnExcluir').disabled = true;
      $('grupo_atributo_dinamico').value = '';

    }

    $('btnSalvar').observe("click", function() {
      salvar();
    }) ;
    $('btnPesquisar').observe("click", function() {
      pesquisar();
    }) ;

    $('btnNovo').observe("click", function() {
      limpar();
    });
    $('btnExcluir').observe("click", function() {
      excluir();
    }) ;

    window.fillFormFromObject  = function(form, jsonObject) {

      var aElements = form.elements;
      for (oElement of aElements) {
        if (jsonObject[oElement.id] != null) {
           oElement.value = jsonObject[oElement.id];
        }
      }

    }
    var oLookupWorkFlow = new DBLookUp($('lblCodigoWorkflow'), $('codigo_workflow'), $('nome_workflow'), {
      "sArquivo"      : "func_workflow.php",
      "sObjetoLookUp" : "db_iframe_workflow",
      "sLabel"        : "Pesquisar WorkFlow"
    });

  })(window);

  function adicionarCampos() {


  var oDBViewCadastroAtributoDinamico = new DBViewCadastroAtributoDinamico();
  oDBViewCadastroAtributoDinamico.showComboObrigatorio(true);
  oDBViewCadastroAtributoDinamico.showCampoReferencia(false);
  oDBViewCadastroAtributoDinamico.setTiposDesabilitados([oDBViewCadastroAtributoDinamico.TIPO_COMBO]);

    var iCodigoAttDinamico = $('grupo_atributo_dinamico').value;

    if (iCodigoAttDinamico == '') {
      oDBViewCadastroAtributoDinamico.newAttribute();
    } else {
      oDBViewCadastroAtributoDinamico.loadAttribute(iCodigoAttDinamico);
    }

    oDBViewCadastroAtributoDinamico.setSaveCallBackFunction( function(iRetornoCodigoAttDinamico) {
      $('grupo_atributo_dinamico').value = iRetornoCodigoAttDinamico;
    });
  }
</script>

<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
