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
$iAnoUsu = db_getsession("DB_anousu");

$sUrl = "con2_lrfresumido002_2010.php";

if ($iAnoUsu < 2007){
  $sUrl = "con2_lrfresumido002.php";
} else if ($iAnoUsu == 2008){
  $sUrl = "con2_lrfresumido002_2008.php";
} else if ($iAnoUsu == 2009) {
  $sUrl = "con2_lrfresumido002_2009.php";
} else if($iAnoUsu >= 2010 && $iAnoUsu < 2013) {
   $sUrl = "con2_lrfresumido002_2010.php";
} else if ($iAnoUsu >= 2013 && $iAnoUsu < 2015 ) {
  $sUrl = "con2_lrfresumido002_2013.php";
} else if ($iAnoUsu >= 2015) {
  $sUrl = "con2_lrfresumido002_2015.php";
}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <meta http-equiv="Expires" CONTENT="0"/>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/EmissaoRelatorio.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css"/>
  </head>
  <body class="body-default">
    <form name="form1" method="post" action="">
    <table  align="center" border=0>
      <tr>
        <td class='table_header'>
          Anexo XIV - Demonstrativo Simplificado do Relatório Resumido da Execução Orçamentária
        </td>
      </tr>
      <tr>
        <td>
          <fieldset>
            <table border=0 width="100%">
              <tr>
                <td id="lista-instituicao" colspan="2">
          	    </td>
              </tr>
              <tr>
                <td width="1%">
                  <label class="bold" for="o116_periodo">Periodo:</label>
                </td>
                <td>
                  <?php
                    $oRelatorio = new relatorioContabil($oGet->codrel);
                    $aPeriodos = $oRelatorio->getPeriodos();
                    $aListaPeriodos = array();
                    foreach ($aPeriodos as $oPeriodo) {
                      $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
                    }
                    db_select("o116_periodo", $aListaPeriodos, true, 1);
                  ?>
                </td>
              </tr>
              <tr>
                <td colspan=2>
                  <fieldset class="separator">
                    <legend ><b>Opções de Impressão:</b></legend>
                    <table border=0>
                      <tr>
                        <td colspan>
                          <label for="emite_balorc">
                            <b>Balanço Orçamentário:</b>
                          </label>
                        </td>
                        <td>
                          <input class='relatorios' codigo='79' type='checkbox' id='emite_balorc' disabled>
                        </td>
                      </tr>
          					  <tr>
          					    <td colspan>
          					       <label for='emite_desp_funcsub'>
          					         <b>Despesas por Função/SubFunção:
          					       </label>
          					    </td>
          						  <td>
          						    <input class='relatorios' codigo='96' type='checkbox' id='emite_desp_funcsub' disabled>
          					    </td>
          					  </tr>
          					  <tr>
           			        <td colspan>
           			          <label for="emite_rcl">
          					      <b>Receita Corrente Líquida:</b>
          					      </label>
          					    </td>
          						  <td>
          						    <input class='relatorios' codigo='81' type='checkbox' id='emite_rcl' disabled>
          					    </td>
          				    </tr>
          				    <tr>
          				      <td colspan>
          				        <label for='emite_rec_desp'>
          				          <b>Receita/Despesa do RPPS:</b>
          				        </label>
          				      </td>
          					    <td>
          					      <input class='relatorios' codigo='82' type='checkbox' id='emite_rec_desp' disabled>
          				      </td>
          				    </tr>
          				    <tr>
          				      <td colspan>
          				        <label for='emite_resultado'>
          				          <b>Resultado Nominal/Primário:</b>
          				        </label>
          				      </td>
          					    <td>
          					      <input class='relatorios' codigo='88' type='checkbox' id='emite_resultado' disabled>
          				      </td>
          				    </tr>
          				    <tr>
          				      <td colspan>
          				        <label for='emite_rp'>
          				        <b>Restos a Pagar:</b>
          				        </label>
          				      </td>
          					    <td>
          					      <input class='relatorios' codigo='97' type='checkbox' id='emite_rp' disabled>
          				      </td>
          				    </tr>
          				    <tr>
          				      <td colspan>
          				        <label for='emite_mde'>
          				         <b>Despesas com MDE:</b>
          				        </label>
          				      </td>
          					    <td>
          					      <input class='relatorios' codigo='86' type='checkbox' id='emite_mde' disabled>
          				      </td>
          				    </tr>
          				    <tr>
          				      <td colspan>
          				        <label for='emite_saude'>
          				          <b>Despesas com Saúde:</b>
          				        </label>
          				      </td>
          					    <td>
          					      <input class='relatorios' codigo='85' type='checkbox' id='emite_saude' disabled>
          				      </td>
          				    </tr>
          				    <tr>
          				      <td colspan>
          				        <label for="emite_oper">
             				        <b>Operações de Crédito e Despesas de Capital:</b>
             				      </label>
          				      </td>
          				      <td>
          				        <input class='relatorios' codigo='105' type='checkbox' id='emite_oper' disabled>
          				      </td>
          				    </tr>
          				    <tr>
          			        <td colspan>
          		            <label for='emite_proj'>
          		             <b>Projeção Atuarial dos Regimes de Previdência:</b>
          		             </label>
          		          </td>
           			        <td>
           			          <input class='relatorios' codigo='106' type='checkbox' id='emite_proj' disabled>
          			        </td>
          				    </tr>
          				    <tr>
          			        <td colspan>
          			          <label for='emite_alienacao'>
          			           <b>Receita de Alienação de Ativos / Aplicação dos Recursos:</b>
          			          </label>
          			        </td>
          				      <td>
          				        <input class='relatorios' codigo='107' type='checkbox' id='emite_alienacao' disabled>
          			        </td>
          				    </tr>
                      <tr>
                        <td colspan>
                          <label for='emite_ppp'><b>Despesas de Caráter Continuado Derivadas de PPP:</b></label>
                        </td>
                        <td>
                         <input class='relatorios' codigo='87' type='checkbox' id='emite_ppp' disabled>
                        </td>
                      </tr>
                    </table>
                  </fieldset>
                </td>
              </tr>
            </table>
        </fieldset>
          </td>
        </tr>
        <tr>
          <td align="center" colspan="2">
            <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite();">
          </td>
        </tr>
      </table>
    </form>
    <script type="text/javascript">

      var oViewInstituicao = new DBViewInstituicao('oViewInstituicao', $('lista-instituicao'));
      oViewInstituicao.show();

      function js_emite() {

        var oInstituicoes = oViewInstituicao.getInstituicoesSelecionadas().map(function(oItem) {
          return oItem.codigo;
        });

        if (oInstituicoes.length < 1) {
          return alert('Selecione ao menos uma instituição.');
        }

        var oParametros = {
              db_selinstit : oInstituicoes.join(','),
              bimestre     : $F('o116_periodo')
            },
            iSelecionados = 0;

        $$('input.relatorios').each(function(oCheckbox, id) {

          if (oCheckbox.checked) {
            iSelecionados++;
          }

          oParametros[oCheckbox.id] = (oCheckbox.checked ? 1 : 0)
        });

        if (!iSelecionados) {
          return alert("Selecione pelo menos um relatorio para ser impresso.");
        }

        var oRelatorio = new EmissaoRelatorio("<?= $sUrl; ?>", oParametros);
        oRelatorio.open();
      }

      function js_getRelatoriosPorPeriodos(iPeriodo) {

        var aRelatorios = $$('input.relatorios');
        aRelatorios.each(function(oCheckbox, id) {

           oCheckbox.disabled = true;
           oCheckbox.checked  = false;
        });
        var oParam             = new Object();
        oParam.exec            = "getRelatoriosPorPeriodos";
        oParam.iCodigoPeriodo  = iPeriodo;
        var oAjax    = new Ajax.Request('con4_configuracaorelatorioRPC.php',
                                      {
                                       method: "post",
                                       parameters:'json='+Object.toJSON(oParam),
                                       onComplete: js_retornoRelatorios
                                       });
      }

      function js_retornoRelatorios(oAjax) {

        var oRetorno = eval("("+oAjax.responseText+")");
        var aRelatorios = $$('input.relatorios');
        aRelatorios.each(function(oCheckbox, id) {

          for (var i = 0; i < oRetorno.itens.length; i++) {

            if (oCheckbox.getAttribute('codigo') == oRetorno.itens[i].o113_orcparamrel) {

              oCheckbox.disabled = false;
              oCheckbox.checked  = true;
            }
          }
        });
      }

      js_getRelatoriosPorPeriodos($F('o116_periodo'));
      $('o116_periodo').observe("change", function () {
        js_getRelatoriosPorPeriodos($F('o116_periodo'));
      });
    </script>
  </body>
</html>