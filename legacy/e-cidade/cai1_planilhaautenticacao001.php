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
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_libdicionario.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("classes/db_placaixa_classe.php"));
require_once(modification("classes/db_placaixarec_classe.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

$oRotulo = new rotulo("placaixa");
$oRotulo->label();

$oGet                   = db_utils::postMemory($_GET);
$sLabel                 = "Autenticação";
$sSTyleButtonAutenticar = "Display:InLine;";
$sSTyleButtonExcluir    = "Display:None;";
$iDbOpcao               = 1;

if (isset($oGet->db_opcao) && $oGet->db_opcao == 3) {

  $sLabel                 = "Exclusão";
  $sSTyleButtonAutenticar = "Display:None;";
  $sSTyleButtonExcluir    = "Display:InLine;";
  $iDbOpcao               = $oGet->db_opcao;
}



?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  ?>
</head>
<body>

<center>
  <form name="form1" method="post">
    <fieldset style="margin-top: 50px; width: 900px;">
      <legend><strong><?php echo $sLabel?> de Planilha de Arrecadação</strong></legend>
      <table width="100%" border="0">
        <tr>
          <td width="70px" nowrap title="<?=$Tk80_codpla?>">
            <? db_ancora(@$Lk80_codpla, "js_pesquisaPlanilha(true);",1);?>
          </td>
          <td  width="150px" >
            <?db_input('k80_codpla', 10, $Ik80_codpla, true, 'text', 1,
                       " onchange='js_pesquisaPlanilha(false);'")?>
          </td>
          <td width="100px" nowrap title="<?php echo $Tk80_data;?>">
            <b>Data de Criação:</b>
          </td>
          <td>
            <? db_input('k80_data', 10, $Ik80_data, true, 'text', 3);?>
          </td>
        </tr>

        <tr>
          <td nowrap="nowrap">
            <strong>Processo Administrativo:</strong>
          </td>

          <td colspan="3">
            <? db_input('k144_numeroprocesso', 10, null, true, 'text', $iDbOpcao, null,null,null,null,15);?>
          </td>
        </tr>

      </table>
      <fieldset style="width: 95%">
        <legend><b>Receitas Vinculadas</b></legend>
        <div id="ctnGridReceitasVinculadas">
        </div>
      </fieldset>
    </fieldset>
    <p align="center">

      <input type="button" style="<?php echo $sSTyleButtonAutenticar; ?>" value='Autenticar Planilha' id='autenticar' name='autenticar' onclick="js_autenticar();"/>
      <input type="button" style="<?php echo $sSTyleButtonExcluir; ?>" value='Excluir Planilha' id='excluir' name='excluir' onclick="js_excluirPlanilha();"/>

    </p>
  </form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script type="text/javascript">

  var sRPC          = 'cai4_planilhaarrecadacao.RPC.php';


  function js_excluirPlanilha(){

    var iPlanilha = $F('k80_codpla');

    if (iPlanilha == '') {

      alert("Selecione uma planilha de arrecadação a ser excluida.");
      return false;
    }

    var sMensagemExcluir  = "Deseja excluir a planilha de arrecadação selecionada?\n\n";
    if (!confirm(sMensagemExcluir)) {
      return false;
    }

    js_divCarregando("Aguarde, autenticando planilha de arrecadação...", "msgBox");

    var oParametro       = new Object();
    oParametro.iPlanilha = iPlanilha;
    oParametro.exec      = 'excluirPlanilha';
    oParametro.iPlanilha = $F('k80_codpla');

    var oAjax = new Ajax.Request(sRPC,
      {
        method: 'post',
        parameters: 'json='+Object.toJSON(oParametro),
        onComplete:js_retornoExclusaoPlanilha
      }
    );

  }
  function js_retornoExclusaoPlanilha(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");

    alert(oRetorno.message.urlDecode());

    js_limparDadosTela();
    js_pesquisaPlanilha(true);
  }

  var oGridReceitas = new DBGrid('ctnGridReceitasVinculadas');
  oGridReceitas.nameInstance = 'oGridReceitas';
  oGridReceitas.setCellWidth(new Array( '40%',
    '40%',
    '20%'));

  oGridReceitas.setCellAlign(new Array( 'left',
    'left',
    'right'));


  oGridReceitas.setHeader(new Array( 'Dados da Conta',
    'Conta Tesouraria',
    'Valor'));
  oGridReceitas.hasTotalizador = true;
  oGridReceitas.show($('ctnGridReceitasVinculadas'));


  function js_autenticar() {

    if ($F('k80_codpla') == '') {

      alert('Selecione uma planilha de arrecadação.');
      return false;
    }

    var sMensagemSalvar  = "Deseja autenticar a planilha de arrecadação selecionada?\n\n";
    sMensagemSalvar     += "Este procedimento pode demandar algum tempo.";
    if (!confirm(sMensagemSalvar)) {
      return false;
    }

    js_divCarregando("Aguarde, autenticando planilha de arrecadação...", "msgBox");

    var oParametro                 = new Object();
    oParametro.exec                = 'autenticarPlanilha';
    oParametro.iPlanilha           = $F('k80_codpla');
    oParametro.k144_numeroprocesso = encodeURIComponent(tagString($F("k144_numeroprocesso")));

    var oAjax = new Ajax.Request(sRPC,
      {
        method: 'post',
        parameters: 'json='+Object.toJSON(oParametro),
        onComplete:js_retornoAutenticacao
      }
    );
  }

  /**
   * Função que busca as receitas vinculadas na planilha selecionada
   */
  function js_getReceitasPlanilha() {

    if ($F('k80_codpla') == "") {
      return false;
    }

    js_divCarregando("Aguarde, carregando receitas vinculadas...", "msgBox");

    var oParam       = new Object();
    oParam.exec      = "getDadosPlanilhaArrecadacao";
    oParam.iPlanilha = $F('k80_codpla');

    new Ajax.Request(sRPC,
      {
        method: 'post',
        parameters: 'json='+Object.toJSON(oParam),
        onComplete: js_preencheGridReceitas
      }
    );
  }

  /**
   * Função que preenche a grid com as receitas vinculadas
   */
  function js_preencheGridReceitas(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");

    var oPlanilha = oRetorno.oPlanilha;
    var iTotalReceitasPlanilha = oPlanilha.aReceitas.length;

    if (oPlanilha.k144_numeroprocesso != null) {
      $('k144_numeroprocesso').value = oPlanilha.k144_numeroprocesso.urlDecode();
    }

    var iDbOpcao               = <?php echo $iDbOpcao;?>

    if (iTotalReceitasPlanilha == 0) {

      // verificamos a acao, autenticar ou excluir, se for autenticar
      // limpamos a tela, pois só pode ser autenticada, planilha com receitas
      alert("Nenhuma receita vinculada para a planilha "+$F('k80_codpla')+".");
      if (iDbOpcao == 1 || iDbOpcao == '1') {

        js_limparDadosTela();
        return false;
      }
    }

    oGridReceitas.clearAll(true);
    $('k80_data').value = oPlanilha.dtDataCriacao;
    var nTotalReceitas = 0;
    oPlanilha.aReceitas.each(function (oReceita, iIndice) {

      var aRow = new Array();
      aRow[0]  = oReceita.iReceita+" - "+oReceita.sDescricaoReceita.urlDecode();
      aRow[1]  = oReceita.iContaTesouraria+" - "+oReceita.sDescricaoConta.urlDecode();
      aRow[2]  = js_formatar(oReceita.nValor, "f");
      nTotalReceitas = (new Number(nTotalReceitas) + new Number(oReceita.nValor));
      oGridReceitas.addRow(aRow);
    });
    oGridReceitas.renderRows();
    $('TotalForCol2').innerHTML = "Total: "+js_formatar(nTotalReceitas, 'f');
  }


  function js_limparDadosTela() {

    $('k80_codpla').value = "";
    $('k80_data').value   = "";
    $('TotalForCol2').innerHTML = "Total: 0";
    oGridReceitas.clearAll(true);
  }


  function js_retornoAutenticacao (oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {

      if (confirm(oRetorno.message.urlDecode())) {

        var sUrlOpen = "cai2_emiteplanilha002.php?codpla="+oRetorno.iPlanilha;
        var oJanelaRelatorio = window.open(sUrlOpen,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      }
    } else {
      alert(oRetorno.message.urlDecode());
    }
    js_limparDadosTela();
  }


  function js_pesquisaPlanilha(mostra) {

    var lPlanilhasSemSlip = "";
    var iDbOpcao          = <?php echo $iDbOpcao;?>;
    if (iDbOpcao == 3) {
      lPlanilhasSemSlip = "lPlanilhasSemSlip=1&";
    }

    var sUrl              = 'func_placaixaaut.php?' + lPlanilhasSemSlip;
    if (mostra) {

      sUrl += 'funcao_js=parent.js_mostraPlanilha1|k80_codpla';
      js_OpenJanelaIframe('', 'db_iframe_placaixa',
        sUrl, 'Pesquisa', true);
    } else {

      if($F('k80_codpla') != '') {

        sUrl += 'pesquisa_chave='+$F('k80_codpla')+'&funcao_js=parent.js_mostraPlanilha';
        js_OpenJanelaIframe('','db_iframe_placaixa',
          sUrl, 'Pesquisa', false);
      } else {
        $('k80_codpla').value = '';
      }
    }
  }
  function js_mostraPlanilha(sData, lErro, iPlanilha) {

    if (lErro) {
      $('k80_codpla').value = '';
    }

    js_getReceitasPlanilha();
  }
  function js_mostraPlanilha1(iPlanilha) {

    $('k80_codpla').value = iPlanilha;
    db_iframe_placaixa.hide();
    js_getReceitasPlanilha();
  }

  js_pesquisaPlanilha(true);
</script>
</html>