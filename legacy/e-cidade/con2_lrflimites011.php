<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
 *  junto com este programa; se nao, escreva para a Free Software-e
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("libs/db_liborcamento.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("model/relatorioContabil.model.php"));

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');


db_postmemory($HTTP_POST_VARS);
$anousu = db_getsession("DB_anousu");
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
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
<script>

  variavel = 1;

  function js_buscaEdicaoLrf(iAnousu,sFontePadrao){

    var url       = 'con4_lrfbuscaedicaoRPC.php';
    var parametro = 'ianousu='+iAnousu+'&sfontepadrao='+sFontePadrao ;
    var objAjax   = new Ajax.Request (url, { method:'post',
                                             parameters:parametro,
                                             onComplete:js_setNomeArquivo}
                                      );
  }

  function js_setNomeArquivo(oResposta){
    sNomeArquivoEdicao = oResposta.responseText;
  }

  js_buscaEdicaoLrf(<?php echo $anousu; ?>,'con2_lrflimites002');

  function js_emite(sFonte){

    sel_instit  = oViewInstituicao.getInstituicoesSelecionadas(true).join('-');
    if (sel_instit == 0) {

      alert('Você não escolheu nenhuma Instituição. Verifique!');
      return false;

    } else{

      obj        = document.form1;
      executar   = sNomeArquivoEdicao;
      var sParam = '?db_selinstit='+sel_instit+'&periodo='+obj.o116_periodo.value;
      var aRelatorios = $$('input.relatorios');
      aRelatorios.each(function(oCheckbox, id) {
         sParam += "&"+oCheckbox.id+"="+oCheckbox.checked;
      });

      var sUrl     = executar+sParam;

      jan = window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
    }
  }
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <form name="form1" method="post" action="con2_lrflimites002.php">
    <table  align="center">
      <tr>
        <td class='table_header'>Anexo VI - Demonstrativo Simplificado do Relatório de Gestão Fiscal</td>
      </tr>
      <tr>
        <td width="100%">
          <fieldset>
            <table width="100%">
              <tr>
                <td id="ctnInstituicao" align="center" colspan="3">
                  <input type="hidden" name="db_selinstit" id="db_selinstit" value="">
                </td>
              </tr>
              <tr>
                <td colspan=2 nowrap><b>Período:</b>
                  <?php if ($anousu < 2010) { ?>

                  <select name=periodo>
                    <option value="1Q">Primeiro Quadrimestre</option>
                    <option value="2Q">Segundo  Quadrimestre</option>
                    <option value="3Q">Terceiro Quadrimestre</option>
                    <option value="1S">Primeiro Semestre</option>
                    <option value="2S">Segundo  Semestre</option>
                  </select>
                  <?php
                  } else {

                    $oRelatorio = new relatorioContabil(93, false);
                    $aPeriodos = $oRelatorio->getPeriodos();
                    $aListaPeriodos = array();
                    foreach ($aPeriodos as $oPeriodo) {
                      $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
                    }
                    db_select("o116_periodo", $aListaPeriodos, true, 1);
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <fieldset style='border:0px;border-top:2px groove white'><legend><b>Opções de Impressão</b></legend>
                    <table border=0>
                      <?php
                      if (db_getsession("DB_anousu") >= 2015) {
                      ?>
                        <tr>
                          <td nowrap="nowrap">
                            <label for="rcl"><b>Demonstrativo da Receita Corrente Líquida:</b></label>
                          </td>
                          <td>
                            <input class='relatorios' codigo='' type='checkbox' id='rcl' checked>
                          </td>
                        </tr>
                      <?php } ?>
                      <tr>
                        <td nowrap="nowrap">
                          <label for="pessoal"><b>Demonstrativo da Despesa com Pessoal:</b></label>
                        </td>
                	        <td>
                            <input class='relatorios' codigo='89' type='checkbox' id='pessoal' disabled>
                          </td>
                      </tr>
                      <tr>
                        <td nowrap="nowrap">
                          <label for="divida"><b>Demonstrativo da Dívida Consolidada Líquida:</b></label>
                        </td>
                        <td>
                          <input class='relatorios' codigo='90' type='checkbox' id='divida' disabled>
                        </td>
                      </tr>
                      <tr>
                        <td nowrap="nowrap">
                          <label for="garantias"><b>Demonstrativo das Garantias e Contra-Garantias:</b></label>
                        </td>
                        <td>
                           <input class='relatorios' codigo='91' type='checkbox' id='garantias' disabled>
                        </td>
                      </tr>
                      <?php if (db_getsession("DB_anousu") < 2017) { ?>
                        <tr>
                          <td nowrap="nowrap">
                            <b><label for='restos'>Demonstrativo da Disponibilidade de Caixa:</label></b>
                          </td>
                          <td>
                            <input class='relatorios' codigo='94' type='checkbox' id='restosapagar' disabled>
                          </td>
                        </tr>
                      <?php } ?>
                      <tr>
                        <td nowrap="nowrap">
                          <label for='operacoes'><b>Demonstrativo das Operações de Crédito:</b></label>
                        </td>
                        <td>
                          <input class='relatorios' codigo='92' type='checkbox' id='operacoes' disabled>
                        </td>
                      </tr>
                      <tr>
                        <td nowrap="nowrap">
                          <b><label for='restos'>Demonstrativo dos Restos a Pagar:</label></b>
                        </td>
                        <td>
                          <input class='relatorios' codigo='109' type='checkbox' id='restosapagar' disabled>
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
           <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite(<?php echo $anousu ?>);">
        </td>
      </tr>
    </table>
  </form>
</body>
</html>
<script>
  var oViewInstituicao = new DBViewInstituicao('oViewInstituicao', $('ctnInstituicao'));
      oViewInstituicao.show();
  function js_getRelatoriosPorPeriodos(iPeriodo) {

    var aRelatorios = $$('input.relatorios');
    aRelatorios.each(function(oCheckbox, id) {

       if (oCheckbox.getAttribute('codigo') != '') {

         oCheckbox.disabled = true;
         oCheckbox.checked = false;
       }
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
