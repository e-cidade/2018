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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);
$licitacao = !empty($oGet->licitacao) ? $oGet->licitacao : '';

if (empty($licitacao)) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Licitação não informada.");
}

$grupo = '';

$oDaoLicitacaoAtributos = new cl_liclicitacadattdinamicovalorgrupo();
$sSqlAtributos = $oDaoLicitacaoAtributos->sql_query_file(null, "*", null, " l16_liclicita = {$licitacao} ");
$rsAtributos   = $oDaoLicitacaoAtributos->sql_record($sSqlAtributos);

if ($rsAtributos && $oDaoLicitacaoAtributos->numrows > 0) {
  $grupo = db_utils::fieldsMemory($rsAtributos, 0)->l16_cadattdinamicovalorgrupo;
}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBAtributoDinamico.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post">
        <?php
          db_input("licitacao", 1, 1, true, "hidden");
          db_input("grupo", 1, 1, true, "hidden");
        ?>
        <fieldset>
          <legend>Informações Complementares</legend>

          <table id="content"></table>

          <table id="content-epp"></table>

          <fieldset class="separator">
            <legend>Regime Diferenciado de Contratação</legend>
            <table id="content-rdc"></table>
          </fieldset>

          <fieldset class="separator">
            <legend>Credenciamento</legend>
            <table id="content-precredenciamento"></table>
            <table id="content-credenciamento"></table>
          </fieldset>

          <fieldset class="separator">
            <legend>Dados da Adesão à Ata de Registro de Preço</legend>
            <table id="content-oorgao"></table>
          </fieldset>

        </fieldset>
        <input type="button" value="Salvar" id="salvar" />
      </form>
    </div>
    <script type="text/javascript">

      (function(exports) {

        var oAtributos = new DBAtributoDinamico(),
            iGrupo = $("grupo").value;

        function getTr(aCells) {

          var tr = document.createElement("tr");

          for (var oContent of aCells) {

            var td = document.createElement("td");

            td.appendChild(oContent);
            tr.appendChild(td);
          }

          return tr;
        }

        oAtributos.carregarAtributos(1260, function() {

          var aCredenciamento = [
                "datainicioinscricaocredenciamento",
                "datafiminscricaocredenciamento",
                "datainiciovigenciacredenciamento",
                "datafimvigenciacredenciamento",
                "recebeinscricaoperiodovigencia"
              ];
          var aOutroOrgao = [
                "cnpjorgaogerenciador",
                "nomeorgaogerenciador",
                "numerolicitacao",
                "anolicitacao",
                "numeroataregistropreco",
                "dataata",
                "dataautorizacao",
                "tipoatuacao"
              ];
          var aRegimeDiferenciado = [
                "tipodisputa",
                "prequalificacao"
              ];
          var aIgnore = [
                "tipobeneficiomicroepp"
              ];
          var aCampos = this.campos();

          for (var sCampo in aCampos) {

            var aCampo = aCampos[sCampo];

            if (aCredenciamento.indexOf(sCampo) != -1 || aRegimeDiferenciado.indexOf(sCampo) != -1 || aIgnore.indexOf(sCampo) != -1 || aOutroOrgao.indexOf(sCampo) != -1) {
              continue;
            }

            if (sCampo == "inversaofases") {
              aCampos[sCampo][1].remove(0);
            }

            $('content').appendChild(getTr([aCampo[0], aCampo[1]]))
          }

          for (var sCampo of aRegimeDiferenciado) {
            $("content-rdc").appendChild( getTr([ aCampos[sCampo][0], aCampos[sCampo][1] ]) )
          }

          $("content-precredenciamento").appendChild(
              getTr([
                  aCampos.recebeinscricaoperiodovigencia[0],
                  aCampos.recebeinscricaoperiodovigencia[1]
                ])
            );

          $("content-credenciamento").appendChild(
            getTr([
              aCampos.datainicioinscricaocredenciamento[0],
              aCampos.datainicioinscricaocredenciamento[1],
              aCampos.datafiminscricaocredenciamento[0],
              aCampos.datafiminscricaocredenciamento[1]
            ])
          );

          $("content-credenciamento").appendChild(
            getTr([
              aCampos.datainiciovigenciacredenciamento[0],
              aCampos.datainiciovigenciacredenciamento[1],
              aCampos.datafimvigenciacredenciamento[0],
              aCampos.datafimvigenciacredenciamento[1]
            ])
          );

          $("content-epp").appendChild(
              getTr([
                  aCampos.tipobeneficiomicroepp[0],
                  aCampos.tipobeneficiomicroepp[1]
                ])
            );

          for (var sCampo of aOutroOrgao) {
            $("content-oorgao").appendChild( getTr([ aCampos[sCampo][0], aCampos[sCampo][1] ]) )
          }

          $("numeroataregistropreco").maxLength = "20";
          $("numerolicitacao").maxLength        = "20";
          $("anolicitacao").maxLength           = "4";
          $("nomeorgaogerenciador").maxLength   = "60";
          $("cnpjorgaogerenciador").maxLength   = "14";

          /**
           * Controle dos campos
           */
          $("codigofundamentacao").addEventListener("change", function() {

            if (this.value == "OUTD" || this.value == "OUTI" || this.value == "OUTC") {

              $("numeroartigo").parentElement.parentElement.show();
              $("inciso").parentElement.parentElement.show();
              $("lei").parentElement.parentElement.show();
            } else {

              $("numeroartigo").parentElement.parentElement.hide();
              $("inciso").parentElement.parentElement.hide();
              $("lei").parentElement.parentElement.hide();

              $("numeroartigo").value = '';
              $("inciso").value = '';
              $("lei").value = '';
            }
          });

          var oEvento = new Event("change");
          $("codigofundamentacao").dispatchEvent(oEvento);

          if (iGrupo) {

            oAtributos.carregarValores(iGrupo, function() {
              $("codigofundamentacao").dispatchEvent(oEvento)
            });
          }

          parent.iframe_infocomplementar.atualizarFundamentacao();
        });

        $("salvar").addEventListener("click", function() {

          if (!validarInformacoes()) {
            return false;
          }

          oAtributos.salvar(function() {

            if (iGrupo == '') {

              iGrupo = this.getCodigoGrupo();

              var oParametros = {
                exec : "salvarVinculoAtributosDinamicos",
                iCodigoLicitacao : $("licitacao").value,
                iCodigoGrupoValores : iGrupo
              };

              new AjaxRequest("lic4_licitacao.RPC.php", oParametros, function(oRetorno, lErro) {

                  if (lErro) {
                    return alert(oRetorno.message.urlDecode())
                  }

                  alert("Informações salvas com sucesso.")

                }).setMessage("Salvando informações...")
                  .execute();
            } else {
              alert("Informações salvas com sucesso.")
            }

          })
        });

        function validarInformacoes() {

          var aPropriedades = [
            'caracteristicaobjeto',
            'tipolicitacao',
            'tipoobjeto',
            'regimeexecucao',
            'permitesubcontratacao',
            'tipofornecimento',
            'pctaxarisco',
            'inversaofases',
            'codigofundamentacao',
            'permiteconsorcio',
            'tipoorcamento',
            'tipobeneficiomicroepp',
            'tipodisputa',
            'prequalificacao',
            'recebeinscricaoperiodovigencia',
            'datainicioinscricaocredenciamento',
            'datafiminscricaocredenciamento',
            'datainiciovigenciacredenciamento',
            'datafimvigenciacredenciamento',
            'cnpjorgaogerenciador',
            'nomeorgaogerenciador',
            'numerolicitacao',
            'anolicitacao',
            'numeroataregistropreco',
            'dataata',
            'dataautorizacao',
            'tipoatuacao'
          ];

          var aAtributosValidar = [];
          aPropriedades.each(
            function (sNomeCampo) {

              aAtributosValidar.push(
                {
                  nomeatributo: sNomeCampo,
                  valoratributo: $(sNomeCampo).value
                }
              );
            }
          );

          var lErroRetorno = false;
          new AjaxRequest(
            'lic4_licitacao.RPC.php',
            {
              'exec'              : 'validaAtributosDinamicos',
              'codigo_licitacao'  : $("licitacao").value,
              'aAtributosValidar' : aAtributosValidar
            },
            function (oRetorno, lErro) {

              if (lErro) {
                alert(oRetorno.message.urlDecode());
              }
              lErroRetorno = lErro;
            }
          ).asynchronous(false).setMessage('Verificando informações, aguarde...').execute();

          return !lErroRetorno;
        }

      })(this)

      /**
       * Função que busca as fundamentações permitidas para a modalidade escolhida.
       */
      function atualizarFundamentacao(){

        var aFundamentacao = [];

        for( i = 0; i < $('codigofundamentacao').length; i++ ){

          if (empty($('codigofundamentacao').options[i].value)) {
            continue;
          }

          $('codigofundamentacao').options[i].disabled = false;
          $('codigofundamentacao').options[i].style = "display: true";

          aFundamentacao.push($('codigofundamentacao').options[i].value);
        }

        var oParametros = {
          'sExecucao'   : 'buscarFundamentacaoPorModalidade',
          'iModalidade' : parent.iframe_liclicita.$F('l20_codtipocom')
        }

        var oRequest = new AjaxRequest('lic4_liclicitainfocomplementar.RPC.php', oParametros);

        oRequest.setCallBack(function(oRetorno, lErro) {

          if (lErro) {
            return false;
          }

          removerFundamentacao(oRetorno.aFundamentacoes)
        }.bind(this));

        oRequest.asynchronous(false);
        oRequest.execute();

        $('codigofundamentacao').options[0].selected = true;
      }

      /**
       * Função que remove todas as fundamentações do select,
       * exceto as que são passadas por parâmetro
       */
      function removerFundamentacao(aFundamentacoes){

        for( i = 0; i < $('codigofundamentacao').length; i++ ){

          if ($('codigofundamentacao').options[i].value == '') {
            continue;
          }

          if ( aFundamentacoes.indexOf($('codigofundamentacao').options[i].value) < 0 ) {

            $('codigofundamentacao').options[i].disabled = true;
            $('codigofundamentacao').options[i].style = "display: none";
            // $('codigofundamentacao').remove($('codigofundamentacao').options[i].index);
            // removerFundamentacao(aFundamentacoes);
            // break;
          }
        }
      }

    </script>
    <style type="text/css">

      #orgaogerenciador,
      #numerolicitacao,
      #numeroataregistropreco{
        width:100px;
      }
      #cnpjorgaogerenciador{
        width: 100px;
      }
      #nomeorgaogerenciador{
        width: 250px;
      }

      #anolicitacao{
        width:40px;
      }
      fieldset.separator{
        margin-top: 10px;
      }
    </style>
  </body>
</html>
