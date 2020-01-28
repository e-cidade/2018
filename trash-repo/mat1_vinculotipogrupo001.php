<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body style="background-color: #cccccc; margin-top:25px;">
<div align="center">
  <form id="form1" name="form1">
    <fieldset style="width: 500px;">
      <legend><b>Vínculo Tipo / Grupo</b></legend>
      <table width="100%" border="0">
        <tr>
          <td title="<?=$Tm04_db_estruturavalor;?>">
            <?php 
              db_ancora($Lm04_db_estruturavalor, "js_pesquisaGrupo(true);", 1);
            ?>
          </td>
          <td>
            <?php 
              db_input("db121_sequencial", 8, $Im04_db_estruturavalor, true, 'text', 1, "onchange='js_pesquisaGrupo(false);'");
              db_input("db121_descricao", 40, false, true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr>
          <td title="<?=$Tm04_materialtipogrupo;?>" width="100px">
            <?php 
              db_ancora($Lm04_materialtipogrupo, "js_pesquisaTipoGrupo(true);", 1);
            ?>
          </td>
          <td>
            <?php 
              db_input("m03_sequencial", 8, $Im03_sequencial, true, 'text', 1, "onchange='js_pesquisaTipoGrupo(false);'");
              db_input("m03_descricao", 40, $Im03_descricao, true, 'text', 3);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <br />
    <input type="button" name="btnVincular" id="btnVincular" value="Vincular" />
  </form>
</div>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>

var sUrlRpc = "mat4_vinculosTipoGrupoSubGrupo.RPC.php";

$('btnVincular').observe('click', function () {

  var iCodigoTipoGrupo = $F('m03_sequencial');
  var iCodigoGrupo     = $F('db121_sequencial');
  js_divCarregando("Aguarde, efetuando vínculo...", "msgBox");

  var oParam              = new Object();
  oParam.exec             = "processarVinculoTipoGrupo";
  oParam.m03_sequencial   = iCodigoTipoGrupo;
  oParam.db121_sequencial = iCodigoGrupo;
  

  var oAjax   = new Ajax.Request (sUrlRpc,{
                                  method     : 'post',
                                  parameters : 'json='+Object.toJSON(oParam),
                                  onComplete : js_retornoVinculoTipoGrupo
                                 });
});


function js_retornoVinculoTipoGrupo(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  alert(oRetorno.message.urlDecode());
  /*if (oRetorno.status == 1) {
    $('form1').reset();
  }*/
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
}

function js_preencheTipoGrupo(sDescricaoTipoGrupo, lErroTipoGrupo) {

  $('m03_descricao').value = sDescricaoTipoGrupo;
  if (lErroTipoGrupo) {
    $('m03_sequencial').value = '';
  }
}

/**
 * Funcoes de Pesquisa do Grupo
 */
function js_pesquisaGrupo(lMostra) {

  var sUrlTipoGrupo = "func_db_estruturavalor.php?iEstruturaValorPai=0&pesquisa_chave="+$F('db121_sequencial')+"&funcao_js=parent.js_preencheGrupo";
  if (lMostra) {
    sUrlTipoGrupo = "func_db_estruturavalor.php?iEstruturaValorPai=0&funcao_js=parent.js_completaGrupo|db121_sequencial|db121_descricao";
  }
  js_OpenJanelaIframe('top.corpo', 'db_iframe_db_estruturavalor', sUrlTipoGrupo, 'Pesquisa Grupo', lMostra); 
}

function js_completaGrupo(iCodigoGrupo, sDescricaoGrupo) {

  $('db121_sequencial').value = iCodigoGrupo;
  $('db121_descricao').value  = sDescricaoGrupo;
  db_iframe_db_estruturavalor.hide();
}

function js_preencheGrupo(sDescricaoGrupo, lErroGrupo) {

  $('db121_descricao').value = sDescricaoGrupo;
  if (lErroGrupo) {
    $('db121_sequencial').value = '';
  }
}
</script>