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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="ext/javascript/prototype.maskedinput.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post" action="">
        <fieldset>
          <legend>Manutenção Estrutural do PCASP</legend>
          <table>
            <tr>
              <td>
                <label for="estrutual" id="lbl_estrutural" class="bold">Estrutural:</label>
              </td>
              <td>
                <?php
                  $Sestrutural = "Estrutural";
                  db_input('estrutural', 15, 1, false, 'text', 1, '', '', '', '', 15);
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar"/>

        <fieldset style="width: 900px;">
          <legend>Contas</legend>
          <table>
            <tr>
              <td class='bold'>
                <label for='nivel'>
                  Nível:
                </label>
              </td>
              <td>
                <select id='nivel' style="text-align: left;" onchange="setTamanhoMaximoNivel()">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                </select>
              </td>
            </tr>
            <tr>
              <td class='bold'>
                <label for='valor_nivel'>
                  Valor do Nível:
                </label>
              </td>
              <td>
                <?php
                $Svalor_nivel = "Valor do Nível";
                db_input('valor_nivel', 5, 1, true, 'text', 1, null, null, null, null, 1);
                ?>
                &nbsp;
                <input type="button" value="Visualizar" id="btnVisualizar" onclick="visualizarAlteracoes()"/>
              </td>
            </tr>
          </table>
          <div id="grid-estruturais"></div>
        </fieldset>
        <input name="salvar" type="button" id="salvar" value="Salvar" disabled/>
      </form>
    </div>
    <?php db_menu( db_getsession("DB_id_usuario"),
                   db_getsession("DB_modulo"),
                   db_getsession("DB_anousu"),
                   db_getsession("DB_instit") ); ?>
  </body>
  <script type="text/javascript">

    var oNivel = $('nivel');
    var oValorNivel = $('valor_nivel');
    oNivel.style.width = '40px';
    oValorNivel.style.width = '40px';

    function setTamanhoMaximoNivel() {

      oValorNivel.value = "";
      oValorNivel.maxLength = 2;
      if (oNivel.value <= 5) {
        oValorNivel.maxLength = 1;
      }
    }

    function visualizarAlteracoes() {

      if (empty($F('estrutural'))) {
        return alert('O campo Estrutural é de preenchimento obrigatório.');
      }

      if (oValorNivel.value == "") {
        return alert('O campo Valor do Nível é de preenchimento obrigatório.');
      }

      var aContas = oGridEstrutural.getSelection();
      if (aContas.length == 0) {
        return alert('Selecione as contas que deseja alterar.');
      }
      var aContasSelecionadas = [];
      aContas.each(
        function (aCelula) {
          aContasSelecionadas.push({codigo_conta : aCelula[1], estrutural : aCelula[2]});
        }
      );

      var oParametro = {
        exec : 'visualizarAlteracao',
        sEstrutural : $F('estrutural'),
        iNivel : oNivel.value,
        iValorNivel : oValorNivel.value,
        aContas : aContasSelecionadas
      };

      new AjaxRequest(
        'con4_manutencaoEstruturalPCASP.RPC.php',
        oParametro,
        function (oRetorno, lErro) {

          if (lErro) {
            return alert(oRetorno.message.urlDecode());
          }

          var aLinhasTabela = oGridEstrutural.aRows;
          oRetorno.contas.each(
            function (oConta) {

              aLinhasTabela.each(
                function (oLinhaTabela) {

                  if (oLinhaTabela.aCells[1].getValue() == oConta.codigo_conta) {
                    oLinhaTabela.aCells[4].setContent(oConta.estrutural_novo);
                  }
                }
              );

            }
          );
        }
      ).setMessage('Aguarde...').execute();
    }

    (function(exports) {

      const MENSAGENS = 'financeiro.contabilidade.con4_manutencaoEstruturalPCASP.';

      var sRpc            = "con4_manutencaoEstruturalPCASP.RPC.php",
          oGridEstrutural = new DBGrid("oGridEstrutural"),
          obotaoSalvar    = $('salvar'),
          sEstrutural     = '';

      oGridEstrutural.nameInstance = "oGridEstrutural";
      oGridEstrutural.setCheckbox(0);
      oGridEstrutural.setHeight(350);
      oGridEstrutural.setCellWidth(["0%", "17%", "33%", "17%", "33%", "0%"]);
      oGridEstrutural.setCellAlign(["center", "left", "left", "left", "left", "center"]);
      oGridEstrutural.setHeader(["&nbsp;", "Estrutural", "Descrição", "Estrutural Novo", "Descrição Nova", "&nbsp;"]);
      oGridEstrutural.show($('grid-estruturais'));

      oGridEstrutural.showColumn(false, 1);
      oGridEstrutural.showColumn(false, 6);

      exports.oGridEstrutural = oGridEstrutural;

      function js_buscaEstruturais() {

        if (sEstrutural == '') {
          alert( _M( MENSAGENS + 'campo_obrigatorio', { sCampo : 'Estrutural' }) );
          return false;
        }

        var oParametros = {
          exec : "getContasPorEstrutural",
          estrutural : sEstrutural
        }

        new AjaxRequest(sRpc, oParametros, function(oRetorno, lErro) {

          oGridEstrutural.clearAll(true);
          obotaoSalvar.disabled = true;

          if (lErro) {
            alert(oRetorno.message.urlDecode());
            return;
          }
          exports.aCamposComMascara = [];
          oRetorno.contas.each(function(oConta, iRow) {

            oGridEstrutural.addRow([
                oConta.codigo,
                oConta.estrutural,
                oConta.descricao.urlDecode(),
                oConta.estrutural,
                '<input name="descricao_nova" onblur="maiuscula(this)" id="descricao_nova' + oConta.codigo + '" type="text" maxlength="50" style="width: 99%;text-transform:uppercase">',
                oConta.reduzido
              ]);

            if (!oConta.reduzido) {
              oGridEstrutural.aRows[iRow].addClassName("bold");
            }
          })

          oGridEstrutural.renderRows();

          obotaoSalvar.disabled = false;

          oRetorno.contas.each(function(oConta, iLinha) {

            var oHintDescricao = eval("oDBHint_"+iLinha+"_1 = new DBHint('oDBHint_"+iLinha+"_1')");

            oHintDescricao.setWidth(400);
            oHintDescricao.setText(oConta.descricao.urlDecode());
            oHintDescricao.setShowEvents(["onmouseover"]);
            oHintDescricao.setHideEvents(["onmouseout"]);
            oHintDescricao.setScrollElement($('body-container-oGridEstrutural'));
            oHintDescricao.setPosition('B', 'L');

            oHintDescricao.make($(oGridEstrutural.aRows[iLinha].aCells[3].sId));
          })

        }).execute()
      }

      $('pesquisar').observe('click', function() {

        sEstrutural = $F('estrutural');

        js_buscaEstruturais();
      })

      $('salvar').observe('click', function() {

        var aLinhas = oGridEstrutural.getSelection(),
            aContas = new Array() ;

        if (!aLinhas.length) {
          alert( _M( MENSAGENS + "nenhum_estrutural") );
          return false;
        }

        var lValidou = true;

        aLinhas.each(function(aLinha) {

          if (!lValidou) {
            return false;
          }

          aContas.push({
            codigo : aLinha[0],
            estrutural : (aLinha[4].trim() == '' ? aLinha[2].trim() : aLinha[4].trim()).replace(/\./g, ''),
            reduzido : aLinha[6],
            descricao : encodeURIComponent(tagString(aLinha[5].trim() == '' ? aLinha[3].trim() : aLinha[5].trim()))
          });
        });

        if (!lValidou) {
          return false;
        }

        var oParametros = {
          exec : "alterarDadosEstrutural",
          contas : aContas,
          nivel : $F('nivel'),
          valor_nivel : $F('valor_nivel')
        };

        new AjaxRequest(sRpc, oParametros, function(oRetorno, lErro) {

          if (lErro) {
            alert(oRetorno.message.urlDecode());
            return false;
          }

          alert( _M(MENSAGENS + "salvo_com_sucesso") );

          js_buscaEstruturais();

        }).execute()
      })
      exports.aCamposComMascara = [];

      exports.mascara = function(oCampo) {

        if (!exports.aCamposComMascara[oCampo.id]) {

            new MaskedInput( "#" + oCampo.id, '0.0.0.0.0.00.00.00.00.00', { placeholder : "0" } );
            exports.aCamposComMascara[oCampo.id] = oCampo;
        }
      };

      exports.maiuscula = function(campo) {
        campo.value = campo.value.toLocaleUpperCase();
      }


    })(this)
  </script>
</html>