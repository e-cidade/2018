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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$oRotuloTipoGrupoVinculo = new rotulo("materialtipogrupovinculo");
$oRotuloTipoGrupoVinculo->label();
$oRotuloTipoGrupo = new rotulo("materialtipogrupo");
$oRotuloTipoGrupo->label();
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body style="background-color: #cccccc; margin-top:25px;">
<div align="center">
  <form id="form1" name="form1">
    <fieldset style="width: 550px;">
      <legend><b>Vínculo Tipo / Grupo</b></legend>
      <table width="100%" border="0">
        <tr>
          <td title="<?=$Tm04_materialtipogrupo;?>" width="100px">
            <?php
              db_ancora($Lm04_materialtipogrupo, "js_pesquisaTipoGrupo(true);", 1);
            ?>
          </td>
          <td>
            <?php
              db_input("m04_materialtipogrupo", 8, $Im04_materialtipogrupo, true, 'text', 1, "onchange='js_pesquisaTipoGrupo(false);'", "m03_sequencial");
              db_input("m03_descricao", 45, $Im03_descricao, true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <div id="divLancadorGrupo">
            </div>
          </td>
        </tr>
      </table>
    </fieldset>
    <br />
    <input type="button" name="btnVincular" id="btnVincular" value="Salvar"  />
  </form>
</div>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>

var oButtonSalvar = $('btnVincular');
oButtonSalvar.disabled = true;
var sUrlRpc = "mat4_vinculosTipoGrupoSubGrupo.RPC.php";

var oLancador = new DBLancador('Lancador');
    oLancador.setTextoFieldset("Grupos Vinculados");
    oLancador.setNomeInstancia('oLancador');
    oLancador.setTituloJanela('Pesquisar Dados');
    oLancador.setLabelAncora('Grupo:');
    oLancador.setLabelValidacao('Grupo');
    oLancador.setParametrosPesquisa('func_materialestoquegrupo.php', ['m65_sequencial','dl_descrição_do_grupo'], 'lGruposAtivos=true&iTipoConta=2');
    oLancador.show($('divLancadorGrupo'));
    
oButtonSalvar.observe('click', function () {

  var oParam            = {};
  oParam.exec           = "processarVinculoTipoGrupo";
  oParam.m03_sequencial = $F('m03_sequencial');
  oParam.aGrupos        = oLancador.getRegistros(true);

  var oAjaxRequest = new AjaxRequest(sUrlRpc, oParam, callbackSalvarVinculo);
  oAjaxRequest.setMessage('Aguarde, salvando vínculos...');
  oAjaxRequest.execute();
});

function callbackSalvarVinculo(oRetorno, lErro) {
  alert(oRetorno.message.urlDecode());
}


function js_retornoVinculoTipoGrupo(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.message.urlDecode());
}


/**
 * Funcoes de Pesquisa do Tipo de Grupo
 */
function js_pesquisaTipoGrupo(lMostra) {

  var sUrlTipoGrupo = "func_materialtipogrupo.php?pesquisa_chave="+$F('m03_sequencial')+"&funcao_js=parent.js_preencheTipoGrupo";
  if (lMostra) {
    sUrlTipoGrupo = "func_materialtipogrupo.php?funcao_js=parent.js_completaTipoGrupo|m03_sequencial|m03_descricao";
  }
  js_OpenJanelaIframe('top.corpo', 'db_iframe_materialtipogrupo', sUrlTipoGrupo, 'Pesquisa Tipo Grupo', lMostra);
}

function js_completaTipoGrupo(iCodigoTipoGrupo, sDescricaoTipoGrupo) {

  $('m03_sequencial').value = iCodigoTipoGrupo;
  $('m03_descricao').value  = sDescricaoTipoGrupo;
  db_iframe_materialtipogrupo.hide();
  oButtonSalvar.disabled = false;
  pesquisarGruposVinculados();
}

function js_preencheTipoGrupo(sDescricaoTipoGrupo, lErroTipoGrupo) {

  $('m03_descricao').value = sDescricaoTipoGrupo;
  if (lErroTipoGrupo) {
    $('m03_sequencial').value = '';
  }
  pesquisarGruposVinculados();
}

  function pesquisarGruposVinculados() {

    oLancador.clearAll();
    var iTipoGrupo = $F('m03_sequencial');
    if (iTipoGrupo == "") {

      oButtonSalvar.disabled = true;
      return false;
    }
    oButtonSalvar.disabled = false;
    var oParametro = {exec:"buscarGruposPorTipo", tipo : iTipoGrupo};

    new AjaxRequest(sUrlRpc, oParametro, preencherGridGrupos).execute();
  }

  function preencherGridGrupos(oRetorno, lErro) {

    oRetorno.aGrupos.each(
      function(oGrupo) {
        oLancador.adicionarRegistro(oGrupo.codigo, oGrupo.descricao.urlDecode());
      }
    );
  }

</script>