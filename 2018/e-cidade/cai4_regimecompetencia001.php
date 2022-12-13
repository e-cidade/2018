<?php
/**
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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="ext/javascript/prototype.maskedinput.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div class="container">
      <form>
        <fieldset style="width: 400px; margin: 0 auto">
          <legend>Processamento de Regime de Competência</legend>
          <table class="form-container">
            <tr>
              <td>
                <a href="" id="labelContrato">Acordos:</a>
              </td>
              <td>
                <?=db_input('ac16_sequencial', 10, 1, 1, 'text', 1); ?>
                <?=db_input('ac16_resumoobjeto', 10, 1, 3); ?>
              </td>
            </tr>
            <tr>
              <td>
                 <label>Competência:</label>
              </td>
              <td>
                <input type="text" id="competencia" class="field-size2" name="competencia" />
              </td>
            </tr>
          </table>
        </fieldset>

        <input type="button" value="Pesquisar" onclick="pesquisarAcordos()" id="btnPesquisar">

        <fieldset style="width: 800px">
          <legend>Contratos</legend>
          <div id="gridContratos"></div>
        </fieldset>
        <input type="button" onclick="processarAcordos()" id="btnProcessar" value="Processar">
      </form>
    </div>
  <?php db_menu();?>
  <script>

    var oContratosCollection;
    var oGridContratos;

    (function() {

      var oInputData = new MaskedInput($('competencia'), '99/9999', {placeholder:' '}); 

      new DBLookUp($('labelContrato'), $('ac16_sequencial'), $('ac16_resumoobjeto'), { 
        'sArquivo': 'func_acordo.php',
        'sQueryString': '&iTipoFiltro=4&'
      });

      montaGrid();
    })();

    function montaGrid() {

      oContratosCollection = new Collection().setId('codigo');
      oGridContratos = DatagridCollection.create(oContratosCollection);
      oGridContratos.addColumn("codigo", {label : "codigo",   align: "center", "width" : "60px"});
      oGridContratos.addColumn("numacordo",   {label : "numacordo",   align: "center", "width" : "60px"});
      oGridContratos.addColumn("acordo",      {label : "Acordo",      align: "left", "width" : "300px"});
      oGridContratos.addColumn("competencia", {label : "Competência", align: "center", "width" : "90px"});
      oGridContratos.addColumn("valortotal",  {label : "Valor Total", align: "left", "width" : "90px"});
      oGridContratos.configure({'order': false});      
      oGridContratos.grid.setCheckbox(0);
      oGridContratos.hideColumns([1,2]);
      
      oGridContratos.show($("gridContratos"));
    }

    function pesquisarAcordos() {

      var sCompetencia = $F('competencia');

      if (sCompetencia.replace(' ', '').length != 7) {
        sCompetencia = null;
      } 

      var oAjaxRequest = new AjaxRequest('con4_reconhecimentoregimecompetencia.RPC.php', { 
            exec: 'getAcordosParaReconhecimento', 
            acordo: $F('ac16_sequencial'),
            competencia: sCompetencia
          }, function (retorno) {

            if (retorno.erro) {
              alert(retorno.message);
              oContratosCollection.clear();
              oGridContratos.reload();
            } 

            oContratosCollection.clear();
            retorno.reconhecimentos.forEach(function(oReconhecimento) {

              var oContrato = {};
                  oContrato.codigo      = oReconhecimento.codigo;
                  oContrato.numacordo   = oReconhecimento.acordo;
                  oContrato.acordo      = oReconhecimento.numero_acordo + ' - ' + oReconhecimento.resumo;
                  oContrato.competencia = oReconhecimento.competencia;
                  oContrato.valortotal  = 'R$' + oReconhecimento.valor; 

              oContratosCollection.add(oContrato);
            });

            oGridContratos.reload();
          });

      oAjaxRequest.setMessage('Buscando acordos...');
      oAjaxRequest.execute();
      return false;
    }

    function processarAcordos() {

      var aReconhecimentos = new Array();

      oGridContratos.grid.getSelection().each(function(aAcordo) {
        
        var oReconhecimento         = {};
        oReconhecimento.codigo      = aAcordo[1];
        oReconhecimento.acordo      = aAcordo[2];
        oReconhecimento.competencia = aAcordo[4];

        aReconhecimentos.push(oReconhecimento);
      });

      if (aReconhecimentos.length === 0) {
        alert('Selecione pelo menos um acordo.');
        return false;
      }

      var oAjaxRequest = new AjaxRequest('con4_reconhecimentoregimecompetencia.RPC.php', { 
            exec: 'processar', 
            reconhecimentos: aReconhecimentos
          }, function (retorno) {

            if (retorno.erro) {
              alert(retorno.message);
              return false;
            } 

            aReconhecimentos.each(function(oReconhecimento) {

              console.log(oReconhecimento.codigo);
              oContratosCollection.remove(oReconhecimento.codigo);
            });

            oGridContratos.reload();

            alert(retorno.message);
          }
      );

      oAjaxRequest.setMessage('Processando acordos...');
      oAjaxRequest.execute();

      return false;
    }

  </script>
  </body>
</html>