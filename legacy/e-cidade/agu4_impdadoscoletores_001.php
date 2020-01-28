<?
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

/*
 * importação dados coletores
 */
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
require(modification("libs/db_app.utils.php"));
require(modification("classes/db_aguacoletorexporta_classe.php"));

db_postmemory($_POST);

$clAguaColetorExporta = new cl_aguacoletorexporta();
$clAguaColetorExporta->rotulo->label();

$clrotulo = new rotulocampo();
$clrotulo->label("x46_descricao");

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js, AjaxRequest.js');
db_app::load('estilos.css, grid.style.css');
?>

<style type="text/css">
.arquivo_importacao {
  position: relative;
  float: right;
  top: -21px;
  opacity: 0;
  -moz-opacity: 0;
  filter: alpha(opacity :       0);
}

.arquivo_importacao_falso {
  width: 670px;
  background: url("imagens/tree/folderopen.gif") no-repeat 99% 51%;
  cursor: default;
  background-color: #FFFFFF;
}

.arquivo_importacao,.arquivo_importacao_falso {
  font-size: 11px;
  height: 20px;
  vertical-align: middle;
}

#totalregistros {
  font-weight: bold;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<form name="form1" method="POST" action="" onsubmit="return js_importar_dados()" enctype="multipart/form-data">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table align="center" width="700">
  <tr>
    <td nowrap title="<?=@$Tx49_anousu?>" align="right" width="33%"><b><?=@$RLx49_anousu?>:</b></td>
    <td colspan="2">
  <?
      db_input("x49_anousu", 10, $Ix49_anousu, true, "text", 3);
    ?>
  </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tx49_mesusu?>" align="right"><b><?=@$RLx49_mesusu?>:</b></td>
    <td colspan="2">
  <?
      db_input("x49_mesusu", 10, $Ix49_mesusu, true, "text", 3);
    ?>
  </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tx49_aguacoletor?>" align="right"><b><?=@$RLx49_aguacoletor?></b></td>
    <td>
  <?
      db_input('x49_aguacoletor', 10, $Ix49_aguacoletor, true, 'text', 3);
    ?>
  <?
      db_input('x46_descricao', 30, $Ix46_descricao, true, 'text', 3);
    ?>
  </td>
  </tr>

  <tr>
    <td colspan="3" align="center"><input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();"></td>
  </tr>

  <tr>
    <td valign="top" align="center" colspan="3">
    <fieldset><legend><b>Ruas que serão importadas</b></legend>
    <div id="grid" style="margin-top: 5px; margin-bottom: 5px;"></div>
    Total de matrículas do arquivo:&nbsp;<span id="totalregistros"></span></fieldset>
    </td>
  </tr>

  <tr>
    <td colspan="3" title="Arquivo de Importa&ccedil;&atilde;o" align="center">
    <fieldset><legend><strong>Arquivo de Importa&ccedil;&atilde;o de Dados dos Coletores</strong></legend>
      <input type="text" name="arquivo_importacao_falso" id="arquivo_importacao_falso" class="arquivo_importacao_falso" readonly="readonly" />
      <input type="file" name="arquivo_importacao" id="arquivo_importacao" class="arquivo_importacao" onchange="this.form.arquivo_importacao_falso.value = this.value;" />
    </fieldset>
    </td>
  </tr>

  <tr>
    <td colspan="3" align="center"><input name="importar" type="button" id="importar" value="Importar Registros" onclick="return js_importar_dados()"></td>
  </tr>
</table>
<?
db_input('x49_sequencial', 10, $Ix49_sequencial, true, 'hidden', 3);
db_input('x49_instit', 10, $Ix49_instit, true, 'hidden', 3);
db_input('x49_situacao', 10, $Ix49_situacao, true, 'hidden', 3);
db_input('layout_tarifa', 10, '', true, 'hidden', 3);

db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</form>

<script>
js_init_table();
document.form1.importar.disabled = true;
document.form1.pesquisar.click();

function js_importar_dados() {

  if (document.form1.arquivo_importacao.value == "") {

    alert("Arquivo de importação não informado.");
    return false;
  }

  if (confirm('Deseja importar o arquivo selecionado?')) {

    if ($('layout_tarifa').value == 1) {

      var sArquivo    = 'agua_exportacao.RPC.php';
      var oParameters = {
        'exec'               : 'importar',
        'iCodigoExportacao'  : $('x49_sequencial').value,
        'iCodigoInstituicao' : $('x49_instit').value,
        'iCodigoSituacao'    : $('x49_situacao').value,
        'iCodigoColetor'     : $('x49_aguacoletor').value,
        'iAno'               : $('x49_anousu').value,
        'iMes'               : $('x49_mesusu').value
      };

      new AjaxRequest(sArquivo, oParameters, function (oRetorno, lErro) {

        alert(oRetorno.message.urlDecode());
        if (lErro) {
          return false;
        }

        window.location.reload();
      })
        .addFileInput($('arquivo_importacao'))
        .execute();

      return false;
    }

    with(document.form1) {
      method = 'POST';
      action = 'agu4_impdadoscoletores_002.php';
      submit();
    }
  }else {
    return false;
  }
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

  var oAjax = new Ajax.Request('agua_exportacao.RPC.php',
                          {
                     method: 'POST',
                     parameters: 'json='+Object.toJSON(oParam),
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

  var totalRegistros = 0;

  if (oRetorno.status == 1) {
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
      for (var i = 0; i < oRetorno.aRotasRuas.length; i++) {
        with(oRetorno.aRotasRuas[i]) {
          var aLinha = new Array();
          aLinha[0] = x50_rota;
          aLinha[1] = x06_descr;
          aLinha[2] = x50_codlogradouro;
          aLinha[3] = x50_nomelogradouro;
          aLinha[4] = x07_nroini;
          aLinha[5] = x07_nrofim;
          totalRegistros += parseInt(count);
          oDataGrid.addRow(aLinha);
        }
      }
      oDataGrid.renderRows();
      document.getElementById("totalregistros").innerHTML = totalRegistros;
      document.form1.importar.disabled = false;
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
</body>
</html>
