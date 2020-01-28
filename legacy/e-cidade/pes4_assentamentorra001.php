<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2015  DBSeller Servicos de Informatica             
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

$sCodigoAssentamento = isset($oGet->iTipoAssentamento) ? $oGet->iTipoAssentamento : '';
$oDaoAssentamentoRRA = new cl_assentamentorra();
$oDaoAssentamentoRRA->rotulo->label();
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js,
        strings.js,
        prototype.js,
        estilos.css,
        AjaxRequest.js,
        classes/DBViewFormularioFolha/CompetenciaFolha.js,
        datagrid.widget.js,
        widgets/windowAux.widget.js,
        widgets/messageboard.widget.js,
        widgets/DBHint.widget.js
      ");
    ?>
  </head>
  <body>

  <div class="container">

    <fieldset class="container">
      <legend>Assentamentos de RRA</legend>
      <?php db_input('sCodigoAssentamento', 10, '', true, "hidden", 3); ?>
      <div id="ctn_gridAssentamentos"></div>
    </fieldset>
  </div>

  <?php db_menu(); ?>

  <script>

    require_once('scripts/datagrid.widget.js');
    require_once('scripts/numbers.js');
    var aLancamentosServidor = [];
    var aPensionistas        = [];
    (function() {
      montaAssentamentos();
    })();

    function montaAssentamentos() {

      require_once('scripts/widgets/datagrid/plugins/DBHint.plugin.js');

      var aTamanhosCabecalho      = ['60px',      '250px', '65px',   '50px', '65px',      '60px',   '70px',          '60px'];
      var aAlinhamentosCabecalho  = ['center',    'left',  'center', 'left', 'right',     'center', 'right',         'center'];
      var aCabecalhoAssentamentos = ['Matrícula', 'Nome',  'Data',   'Obs.', 'Total(R$)', 'Meses',  'Encargos (R$)', 'Ações'];

      oGridAssentamentos = new DBGrid('assentamentos');
      oGridAssentamentos.nameInstance = 'oGridAssentamentos';
      oGridAssentamentos.allowSelectColumns(false);
      oGridAssentamentos.setCellWidth(aTamanhosCabecalho);
      oGridAssentamentos.setCellAlign(aAlinhamentosCabecalho);
      oGridAssentamentos.setHeader(aCabecalhoAssentamentos);
      oGridAssentamentos.setHeight(400);
      oGridAssentamentos.show($('ctn_gridAssentamentos'));

      buscarAssentamentos(oGridAssentamentos);
    }

    function buscarAssentamentos(oGridAssentamentos) {

      var oParametros  = { 
        'exec'                  :  'getAssentamentosRRA',
        'iTipoAssentamento'     :  $F('sCodigoAssentamento')
      };

      if($F('sCodigoAssentamento') == null) {
        alert("Não foi possível detectar o tipo de assentamento.\nEntre novamente na rotina");
        return;
      }

      var oAjaxRequest = new AjaxRequest(
        'pes4_assentamentorra.RPC.php', 
        oParametros,
        function (oAjax, lResposta) {

          if(lResposta) {
            alert(oAjax.sMessage.URLDecode());
            return;
          }
          montarLinhaAssentamento(oGridAssentamentos, oAjax.aAssentamentos);
          oGridAssentamentos.renderRows();

          for (var i = 0; i < oAjax.aAssentamentos.length; i++) {

            var sTextHint = '';

            if(document.getElementById('assentamentosrow0cell2').children[0].tagName.toLowerCase() == 'span') {
              sTextHint = document.getElementById('assentamentosrow0cell2').children[0].textContent;
            }
            if (!empty(sTextHint)) {
              oGridAssentamentos.setHint(i,2,sTextHint);
            }
          };
        }
      );
      oAjaxRequest.setMessage('Buscando Lançamentos...');
      oAjaxRequest.execute();
    }

    function montarLinhaAssentamento(oGridAssentamentos, aListaAssentamentos) {

      oGridAssentamentos.clearAll(true);
      var aAssentamentoHint = [];

      for (var i = 0; i < aListaAssentamentos.length; i++) {

        oAssentamento         = aListaAssentamentos[i];
        var sButtonLancar     = '<input type="button" value="Lançar" onClick="abrirLancamentos(\''+oAssentamento.iCodigoAssentamento+'\')" />';
        var sObservacoes      = oAssentamento.sObservacoes.substr(0, 7)+'...<span style="display:none">'+oAssentamento.sObservacoes+'</span>';

        oGridAssentamentos.addRow([
          oAssentamento.iMatricula,
          oAssentamento.sNomeServidor,
          oAssentamento.sDataAssentamento,
          sObservacoes,
          js_formatar(oAssentamento.nValorDevido, "f"),
          oAssentamento.nNumeroMeses,
          js_formatar(oAssentamento.nValorEncargos, "f"),
          sButtonLancar
        ]);
      };
    }

    function abrirLancamentos(iCodigoAssentamento) {

      aLancamentosServidor = [];
      aPensionistas        = [];
      wLancamentos = new windowAux('wLancamentos', 'Parcelas do RRA', 850, 470);
      wLancamentos.setContent('');
      wLancamentos.getContentContainer().load('pes4_assentamentorra002.php?iCodigoAssentamento='+iCodigoAssentamento);
      wLancamentos.setShutDownFunction(function () {
        wLancamentos.destroy();
      });
      wLancamentos.allowDrag(false);
      wLancamentos.show(10,null);

      $('iTipoFolha').on('change', function() {
        buscarLancamentosAnteriores()
      });

      var sTituloMBoardLancamentos  = "Lançamento do RRA";
      var sMessageMBoardLancamentos = 'Informe os valores da parcela do RRA';

      oMessageBoardLancamentos = new messageBoard('msgboardParcelaRRA', sTituloMBoardLancamentos, sMessageMBoardLancamentos, wLancamentos.getContentContainer());
      oMessageBoardLancamentos.show();

      if (!empty($F('h12_assentdescr'))) {

        oHintHistoricoAssentamento = new DBHint('oHintHistoricoAssentamento');
        oHintHistoricoAssentamento.setWidth(330);
        oHintHistoricoAssentamento.setZIndexHint(99999);
        oHintHistoricoAssentamento.setText($F('h12_assentdescr'));
        oHintHistoricoAssentamento.make($('h12_assentdescr'));
      }

      montarLancamentos();
    }

    function montarLancamentos() {

      var aTamanhosLancamentos     = ['14%', '12%', '12%', '12%', '14%', '12%', '20%'];
      var aAlinhamentosLancamentos = ['center', 'center', 'center', 'center', 'center', 'center', 'left'];
      var aCabecalhoLancamentos    = ['Competência', 'Valor da Parcela', 'Desp. Judiciais', 'Pensão', 'Base Previdência', 'Base IRRF', 'Ações'];

      oGridLancamentos = new DBGrid('lancamentos');
      oGridLancamentos.nameInstance = 'oGridLancamentos';
      oGridLancamentos.allowSelectColumns(false);
      oGridLancamentos.setCellWidth(aTamanhosLancamentos);
      oGridLancamentos.setCellAlign(aAlinhamentosLancamentos);
      oGridLancamentos.setHeader(aCabecalhoLancamentos);
      oGridLancamentos.setHeight(100);
      oGridLancamentos.show($('ctn_gridLancamentos'));

      buscarLancamentosAnteriores();
    }

    function buscarLancamentosAnteriores() {
      aPensionistas = [];
      oGridLancamentos.clearAll(true);
      var oParametros  = {
        'exec'                :  'getLancamentos',
        'iCodigoAssentamento' :  $F('h83_assenta'),
        'iTipoFolha'          :  $F('iTipoFolha')
      };


      var oAjaxRequest = new AjaxRequest(
        'pes4_assentamentorra.RPC.php', 
        oParametros,
        function (oAjax, lResposta) {

          if (lResposta) {
            alert(oAjax.sMessage.urlDecode().replace(/\\n/g, "\n"));
            return;
          }
          aLancamentos  = oAjax.aLancamentos;
          aPensionistas = oAjax.aPensionistas;

          preencherGridLancamentos(aLancamentos);
        });
      oAjaxRequest.setMessage('Buscando Lançamentos...');
      oAjaxRequest.execute();
      
    }

    function montarLinhaLancamento(oGridLancamentos, oValores, iIndice) {

      var sValorlancado    = montaCampoValor('valorlancado', oValores.nValorlancado);
      var sEncargos        = montaCampoValor('encargos', oValores.nEncargos);
      var sPensao          = montaCampoValor('pensao', oValores.nPensao, true);
      var sBaseprevidencia = montaCampoValor('baseprevidencia', oValores.nBaseprevidencia);
      var sBaseirrf        = montaCampoValor('baseirrf', oValores.nBaseirrf);
      var sInputPensao     = '<input type="button" value="Pensão" onClick="return lancarPensionistas('+iIndice+')" />';
      var sInputSalvar     = '<input type="button" value="Salvar" onClick="return processarLancamento('+oValores.iCodigo+', '+iIndice+')" />';

      oGridLancamentos.addRow([oValores.sCompetencia, sValorlancado, sEncargos, sPensao, sBaseprevidencia, sBaseirrf, sInputPensao+sInputSalvar]);
    }

    function montaCampoValor(sName, nValor, readOnly) {
      if (empty(readOnly)) {
        readOnly = false;
      }
      sReadonly = readOnly ? ' readonly ' : '';

      return '<input type="text" '+sReadonly+' name="'+sName+'" size="8" id="'+sName+'" value="'+nValor+'" onkeypress="return mascaraValor(event, this);" />';
    }

    function processarLancamento(iCodigoLancamento, iLinha) {

      var aLinhaLancamento = aLancamentosServidor[iLinha];
      var aPensionistas    = aLinhaLancamento.aPensionistas;
      var oParametros  = {
        'exec'                    :  'processarLancamentos',
        'iCodigoLancamento'       :  iCodigoLancamento,
        'iCodigoAssentamento'     :  $F('h83_assenta'),
        'iTipoFolha'              :  $F('iTipoFolha'),
        'nValorParcela'           :  $F('valorlancado'),
        'nValorEncargos'          :  $F('encargos'),
        'nValorPensao'            :  $F('pensao'),
        'nValorBasePrevidencia'   :  $F('baseprevidencia'),
        'nValorBaseIRRF'          :  $F('baseirrf'),
        'aPensionistas'           : aPensionistas
      };

      var oAjaxRequest = new AjaxRequest(
        'pes4_assentamentorra.RPC.php', 
        oParametros,
        function (oAjax, lResposta) {
          alert(oAjax.sMessage.urlDecode().replace(/\\n/g, "\n"));
        }
      );
      oAjaxRequest.setMessage('Processando Lançamentos...');
      oAjaxRequest.execute();
    }

    function lancarPensionistas(iLinhaLancamento) {

      wLancamentosPensionistas = new windowAux('wLancamentosPensionistas', 'Pensionistas da Parcelas do RRA', 650, 350);

      var sContent = '<div class="container" style="width: 100%">';
      sContent    += '  <fieldset>';
      sContent    += '    <legend>';
      sContent    += '    Valores dos Pensionistas';
      sContent    += '    </legend>';
      sContent    += '    <div id="gridContainer" style="width: 100%">';
      sContent    += '    </div>';
      sContent    += '  </fieldset>';
      sContent    += '<input type="button" id="btnSalvarValorPensionistas" value="Salvar">';
      sContent    += '</div>';
      wLancamentosPensionistas.setContent(sContent);
      wLancamentosPensionistas.setShutDownFunction(function () {
        wLancamentosPensionistas.destroy();
      });
      wLancamentosPensionistas.show(10, -20, true);
      oGridPensionistas = new DBGrid('oGridPensionistas');
      oGridPensionistas.nameInstance = 'oGridPensionistas';
      oGridPensionistas.allowSelectColumns(false);
      oGridPensionistas.setHeader(["Cgm", "Pensionista", "Valor"]);
      oGridPensionistas.setHeight(100);
      oGridPensionistas.show($('gridContainer'));

      oGridPensionistas.clearAll(true);
      var aLinhaLancamento = aLancamentosServidor[iLinhaLancamento];
      aLinhaLancamento.aPensionistas.each(function(oPensionista, iSeq) {

        var aRow = [
            oPensionista.iNumcgm,
            oPensionista.sNome,
            "<input type='text' id='txtValorPensao"+iSeq+"' value='"+oPensionista.nValor+"' onkeypress='return mascaraValor(event, this);'>"
        ];
        oGridPensionistas.addRow(aRow);
      });
      oGridPensionistas.renderRows();
      wLancamentosPensionistas.toFront();
      $('btnSalvarValorPensionistas').onclick = function() {

        nValorPensionistas             = 0;
        var aLinhasPensionistas        = oGridPensionistas.aRows;
        aLinhaLancamento.aPensionistas = [];
        aLinhasPensionistas.each(function (linha, iSeq) {

          var oPensionista = {
            iNumcgm : linha.aCells[0].getValue(),
            sNome   : linha.aCells[1].getValue(),
            nValor  : linha.aCells[2].getValue()
          };
          aLinhaLancamento.aPensionistas.push(oPensionista);
          nValorPensionistas += new Number(linha.aCells[2].getValue()).valueOf();
        });
        aLinhaLancamento.nPensao = nValorPensionistas;
        preencherGridLancamentos(aLancamentosServidor);
        wLancamentosPensionistas.destroy();
      }
    }
    function preencherGridLancamentos(aLancamentos) {

      oGridLancamentos.clearAll(true);
      aLancamentosServidor  = aLancamentos;
      var lExisteLancamento = false;
      var lExisteNovo       = false;
      for (var iIndLancamentos = 0; iIndLancamentos < aLancamentos.length; iIndLancamentos++) {

        oLancamento = aLancamentos[iIndLancamentos];

        if (oLancamento.lAtual || oLancamento.iCodigo == null) {

          montarLinhaLancamento(oGridLancamentos, {
            iCodigo          : oLancamento.iCodigo,
            sCompetencia     : oLancamento.sCompetencia,
            nValorlancado    : oLancamento.nValorlancado,
            nEncargos        : oLancamento.nEncargos,
            nPensao          : oLancamento.nPensao,
            nBaseprevidencia : oLancamento.nBaseprevidencia,
            nBaseirrf        : oLancamento.nBaseirrf
          }, iIndLancamentos);
          lExisteLancamento = true;

        } else {

          oGridLancamentos.addRow([
            oLancamento.sCompetencia,
            oLancamento.nValorlancado,
            oLancamento.nEncargos,
            oLancamento.nPensao,
            oLancamento.nBaseprevidencia,
            oLancamento.nBaseirrf,
            ''
          ]);
        }
      };

      if (!lExisteLancamento && $F('iTipoFolha') != 0 ) {

          var oLancamento = {

            iCodigo          : null,
            sCompetencia     : $F('sCompetenciaFolha'),
            nValorlancado    : 0 ,
            nEncargos        : 0,
            nPensao          : 0,
            nBaseprevidencia : 0,
            nBaseirrf        : 0,
            aPensionistas    : aPensionistas
         };
         aLancamentosServidor.push(oLancamento);
         montarLinhaLancamento(oGridLancamentos, {
          iCodigo          : null,
          sCompetencia     : $F('sCompetenciaFolha'),
          nValorlancado    : '',
          nEncargos        : '',
          nPensao          : '',
          nBaseprevidencia : '',
          nBaseirrf        : ''
        }, iIndLancamentos);
      }
      oGridLancamentos.renderRows();
    }
  </script>
  </body>
</html>
