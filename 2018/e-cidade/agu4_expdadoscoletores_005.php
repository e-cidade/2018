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
/*
 * cancelamento de exportação
 */
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
require(modification("libs/db_app.utils.php"));
require(modification("classes/db_aguacoletorexporta_classe.php"));
require_once(modification("model/agua/ArquivoExportaColetor.model.php"));

$oPost = db_utils::postMemory($_POST);

$claguacoletorexporta = new cl_aguacoletorexporta ();
$claguacoletorexporta->rotulo->label ();

$rotulo = new rotulocampo();
$rotulo->label("x46_descricao");

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load ( 'scripts.js, prototype.js, strings.js, datagrid.widget.js, AjaxRequest.js, DBDownload.widget.js' );
db_app::load ( 'estilos.css, grid.style.css' );
?>

<script>

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<form name="form1" method="POST" action="" id="formReprocessar" onsubmit="return js_reprocessar_exportacao()">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table align="center" width="790">
  <tr>
    <td nowrap title="<?=@$Tx49_anousu?>" align="right" width="33%"><b><?=@$RLx49_anousu?>:</b></td>
    <td colspan="2">
  <?
  db_input ( "x49_anousu", 10, $Ix49_anousu, true, "text", 3 );
  ?>
  </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tx49_mesusu?>" align="right"><b><?=@$RLx49_mesusu?>:</b></td>
    <td colspan="2">
  <?
  db_input ( "x49_mesusu", 10, $Ix49_mesusu, true, "text", 3 );
  ?>
  </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tx49_aguacoletor?>" align="right"><b><?=@$RLx49_aguacoletor?></b></td>
    <td>
    <?
    db_input ( 'x49_aguacoletor', 10, $Ix49_aguacoletor, true, 'text', 3 );
    ?>
    <?
    db_input ( 'x46_descricao', 30, $Ix46_descricao, true, 'text', 3 );
    ?>
    </td>
  </tr>

  <tr>
    <td colspan="3" align="center"><input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();"></td>
  </tr>

  <tr>
    <td colspan="3" align="center"><input type="checkbox" name="geraDadosArquivos" value="t" checked="checked" /> Arquivo 01 - Rotas e Leituras. <input type="checkbox"
      name="geraSituacaoLeitura" value="t" checked="checked" /> Arquivo 02 - Situa&ccedil;&otilde;es de Leitura. <input type="checkbox" name="geraLeiturista" value="t"
      checked="checked" /> Arquivo 03 - Leituristas. <input type="checkbox" name="geraConfiguracoes" value="t" checked="checked" /> Arquivo 04 - Configurações.</td>
  </tr>

  <tr>

    <td valign="top" align="center" colspan="3">
    <fieldset><legend><b>Ruas Exportadas</b></legend>

    <div id="grid" style="margin-top: 5px;"></div>

    </fieldset>
    </td>

  </tr>


  <tr>
    <td colspan="3" align="center"><input name="reprocessar" type="submit" id="reprocessar" value="Reprocessar Arquivo Exporta&ccedil;&atilde;o"></td>
  </tr>
</table>
<br/><br/><br/>
<?php
db_input('x49_sequencial', 10, $Ix49_sequencial, true, 'hidden', 3);
db_input('x49_instit', 10, $Ix49_instit, true, 'hidden', 3);
db_input('x49_situacao', 10, $Ix49_situacao, true, 'hidden', 3);
db_input('layout_tarifa', 10, '', true, 'hidden', 3);

db_menu();
?>

<script>
js_init_table();
document.form1.reprocessar.disabled = true;

function limparDados() {

  $('x49_sequencial').value = '';
  $('x49_instit').value     = '';
  $('x49_situacao').value   = '';
  $('layout_tarifa').value  = '';

  $('formReprocessar').reset();
  $('reprocessar').disabled = true;
  oDataGrid.clearAll(true);
}

function js_reprocessar_exportacao() {

  if(confirm('Deseja reprocessar a exportação selecionada?')) {

    /**
     * Caso seja o antigo layout, continua fazendo POST.
     */
    if ($('layout_tarifa').value == 0) {
      return true;
    }

    var oParametros = {
      'exec' : 'reprocessarExportacao',
      'iCodigoExportacao' : $('x49_sequencial').value
    };

    new AjaxRequest('agua_exportacao.RPC.php', oParametros, function(oRetorno, lErro) {

      alert(oRetorno.message.urlDecode());
      if (lErro) {
        return false;
      }

      var oDownload = new DBDownload;
      for (oArquivo of oRetorno.aArquivos) {
        oDownload.addFile(oArquivo.sCaminho.urlDecode(), oArquivo.sNome.urlDecode());
      }
      oDownload.show();
      limparDados();
    })
    .execute();
  }

  return false;
}

function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_aguacoletorexporta','func_aguacoletorexporta.php?funcao_js=parent.js_preenchepesquisa|x49_sequencial','Pesquisa',true);
}

function js_preenchepesquisa(codigoExportacao) {
  db_iframe_aguacoletorexporta.hide();

  var oParam           = new Object();
  oParam.exec          = 'getDadosExportacao';
  oParam.codExportacao = codigoExportacao;

  js_divCarregando('Aguarde, pesquisando registros.', 'msgbox');

  var oAjax = new Ajax.Request('agua_exportacao.RPC.php', {
    method: 'POST',
    parameters: 'json=' + Object.toJSON(oParam),
    onComplete: js_retorno_pesquisa
  });
}

function js_retorno_pesquisa(oAjax) {

  js_removeObj('msgbox');
  var oRetorno    = eval("("+oAjax.responseText+")");

  var sequencial   = document.form1.x49_sequencial;
  var coletor      = document.form1.x49_aguacoletor;
  var descricao    = document.form1.x46_descricao;
  var instituicao  = document.form1.x49_instit;
  var ano          = document.form1.x49_anousu;
  var mes          = document.form1.x49_mesusu;
  var situacao     = document.form1.x49_situacao;
  var layoutTarifa = $('layout_tarifa');

  if(oRetorno.status == 1) {

    sequencial.value   = oRetorno.x49_sequencial;
    coletor.value      = oRetorno.x49_aguacoletor;
    descricao.value    = oRetorno.x46_descricao;
    instituicao.value  = oRetorno.x49_instit;
    ano.value          = oRetorno.x49_anousu;
    mes.value          = oRetorno.x49_mesusu;
    situacao.value     = oRetorno.x49_situacao;
    layoutTarifa.value = oRetorno.lLayoutTarifa ? 1 : 0;

    if(oRetorno.aRotasRuas.length > 0) {

      oDataGrid.clearAll(true);
      for(var i = 0; i < oRetorno.aRotasRuas.length; i++) {

        with(oRetorno.aRotasRuas[i]) {

          var aLinha = new Array();
          aLinha[0] = x50_rota;
          aLinha[1] = x06_descr;
          aLinha[2] = x50_codlogradouro;
          aLinha[3] = x50_nomelogradouro;
          aLinha[4] = x07_nroini;
          aLinha[5] = x07_nrofim;
          oDataGrid.addRow(aLinha);
        }
      }
      oDataGrid.renderRows();
      document.form1.reprocessar.disabled = false;
    }
  }

}

function js_init_table() {

    oDataGrid = new DBGrid('grid');
    oDataGrid.nameInstance = 'oDataGrid';
    oDataGrid.setCellAlign(new Array('center', 'left', 'center', 'left', 'center', 'center'));
    oDataGrid.setCellWidth(new Array("10%","15%", "15%", "40%", "10%", "10%"));
    oDataGrid.setHeader(new Array('Cod Rota', 'Rota', 'Cod Logradouro', 'Logradouro', 'Nro Inicial', 'Nro Final'));
    oDataGrid.setHeight(150);
    oDataGrid.show($('grid'));

}

</script>
<?
if (isset ( $reprocessar )) {

  $clArquivosExportacaoColetor = new clArqExpColetor();

  if (isset($oPost->geraDadosArquivos) && $oPost->geraDadosArquivos == "t") {

    $nomearqdados = $clArquivosExportacaoColetor->arquivoDadosMatricula ( $oPost->x49_sequencial, 1 );
    $nomearqlayout = $clArquivosExportacaoColetor->gerarArquivoLayout ( 261, "01" );

  }

  if (isset($oPost->geraSituacaoLeitura) && $oPost->geraSituacaoLeitura == "t") {

    $arqsitleitura = $clArquivosExportacaoColetor->arquivoDadosSitLeitura ();
    $arqlayoutsitleitura = $clArquivosExportacaoColetor->gerarArquivoLayout ( 263, "02" );

  }

  if (isset($oPost->geraLeiturista) && $oPost->geraLeiturista == "t") {

    $arqleiturista = $clArquivosExportacaoColetor->arquivoDadosLeituristas ();
    $arqlayoutleiturista = $clArquivosExportacaoColetor->gerarArquivoLayout ( 262, "03" );

  }

  if (isset($oPost->geraConfiguracoes) && $oPost->geraConfiguracoes == "t") {

    $arqconfiguracoes = $clArquivosExportacaoColetor->arquivoDadosConfiguracoes ();
    $arqlayoutconfiguracoes = $clArquivosExportacaoColetor->gerarArquivoLayout ( 284, "04" );

  }

  echo "<script> var listagem;";

  if (isset($oPost->geraDadosArquivos) && $oPost->geraDadosArquivos == "t") {
    echo "  listagem = '$nomearqdados#Download arquivo TXT (dados dos coletores)|';";
    echo "  listagem+= '$nomearqlayout#Download arquivo TXT (layout dos coletores)|';";
  }

  if (isset($oPost->geraSituacaoLeitura) && $oPost->geraSituacaoLeitura == "t") {
    echo " if(listagem == '') listagem = '$arqsitleitura#Download arquivo TXT (dados das situacoes de leitura)|'; else ";
    echo "  listagem+= '$arqsitleitura#Download arquivo TXT (dados das situacoes de leitura)|';";
    echo "  listagem+= '$arqlayoutsitleitura#Download arquivo TXT (layout das situacoes de leitura)|';";
  }

  if (isset($oPost->geraLeiturista) && $oPost->geraLeiturista == "t") {
    echo "if(listagem == '') listagem = '$arqleiturista#Download arquivo TXT (dados dos leituristas)|'; else ";
    echo "  listagem+= '$arqleiturista#Download arquivo TXT (dados dos leituristas)|';";
    echo "  listagem+= '$arqlayoutleiturista#Download arquivo TXT (layout dos leituristas)|';";
  }

  if (isset($oPost->geraConfiguracoes) && $oPost->geraConfiguracoes == "t") {
    echo "if(listagem == '') listagem = '$arqconfiguracoes#Download arquivo TXT (dados das configuracoes c&oacute;digo de barras)|'; else ";
    echo "  listagem+= '$arqconfiguracoes#Download arquivo TXT (dados das configuracoes c&oacute;digo de barras)|';";
    echo "  listagem+= '$arqlayoutconfiguracoes#Download arquivo TXT (layout das configura&ccedil;&otilde;es c&oacute;digo de barras.)|';";
  }

  echo "  js_montarlista(listagem,'form1');";

  echo "document.form1.x49_sequencial.value  = '';";
  echo "document.form1.x49_aguacoletor.value = '';";
  echo "document.form1.x46_descricao.value   = '';";
  echo "document.form1.x49_instit.value      = '';";
  echo "document.form1.x49_anousu.value      = '';";
  echo "document.form1.x49_mesusu.value      = '';";
  echo "document.form1.x49_situacao.value    = '';";
  echo "</script>";

  //db_redireciona('agu4_expdadoscoletores_005.php');

}
?>
</form>
</body>
</html>
