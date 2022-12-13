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
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_classesgenericas.php");
require_once("model/issqn/alvara/MovimentacaoAlvaraFactory.model.php");

$oPost          = db_utils::postMemory($_POST);
$oGet           = db_utils::postMemory($_GET);
$clIssMovAlvara = db_utils::getDao('issmovalvara');
$db_opcao       = 1;

$clIssMovAlvara->rotulo->label("q120_sequencial");

if (isset($oPost->transformar)) {


  try {

  require_once("libs/exceptions/DBException.php");
  require_once("libs/exceptions/ParameterException.php");
  require_once("libs/exceptions/BusinessException.php");
  require_once("libs/exceptions/ParameterException.php");
    db_inicio_transacao();

    $oAlvara            = new Alvara($oPost->q120_issalvara);

    $oTransformaAlvara  = $oAlvara->incluirMovimentacao( MovimentacaoAlvara::TIPO_TRANSFORMACAO );
    $oTransformaAlvara->setDataMovimentacao(date("Y-m-d", db_getsession("DB_datausu")));
    $oTransformaAlvara->setTipoTransformacao($oPost->q98_sequencial);
    $oTransformaAlvara->setValidadeAlvara($oPost->q120_validadealvara);
    $oTransformaAlvara->setUsuario( new UsuarioSistema(db_getsession('DB_id_usuario')) );
    $oTransformaAlvara->setObservacao($oPost->q120_obs);

    if ($oPost->documentos != "" ) {

      $aDocumentos = explode(",", $oPost->documentos);
      foreach($aDocumentos as $iIndice => $oValor){
        $oAlvara->addDocumento($oValor);
      }
    }

    $oTransformaAlvara->processar();

    db_msgbox("Movimentação realizada com sucesso");
    db_fim_transacao(false);
    db_redireciona("iss4_transformacaoalvara001.php");
    exit;
  } catch (Exception $erro) {

    db_msgbox($erro->getMessage());
    db_fim_transacao(true);
  }

} else {

  $clGrupoTipoAlvara  = db_utils::getDao('issgrupotipoalvara');

  $sCampos            = "q97_sequencial, q97_descricao";
  $sSql               = $clGrupoTipoAlvara->sql_query(null, $sCampos);

/**
 * Quando o novo tipo de alvara tiver o campo tipo de validade 3 - indeterminado,
 * nao precisa pedir prazo de validade
 */
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("classes/dbViewAvaliacoes.classe.js");
  db_app::load("widgets/windowAux.widget.js");
  db_app::load("dbcomboBox.widget.js");
  db_app::load("DBViewAlvaraDocumentos.js");
?>
</head>
<body class="body-default">
<div class="container">
<form name="form1" method="post" action="" onsubmit="jsMontaDocumentos();" class="container">
    <fieldset >
      <legend>Transformação de Alvará Individual</legend>
      <table class="form-container">
        <tr>
          <td ><strong>
            <?
              db_ancora("Inscrição:", 'js_buscaAlvara(true);',$db_opcao );
            ?></b>
          </td>
          <td>
            <?php
              db_input("q123_inscr",      10,"", true, 'text',   1, "onchange='js_buscaAlvara(false);'");
              db_input("z01_nome",        45,"", true, 'text',   3);
              db_input("q120_sequencial", 10,"", true, 'hidden', 1);
            ?>
          </td>
        </tr>

        <tr >
          <td nowrap="nowrap"><strong>Alvará:</strong>
          </td>
          <td nowrap="nowrap">
           <?
               db_input("q120_issalvara",  10,"", true, 'text', 3);
           ?>
          </td>
        </tr>

        <tr >
          <td nowrap="nowrap"><strong>Grupo do Alvará:</strong>
          </td>
          <td nowrap="nowrap">
           <?
             db_input("q97_sequencialAtual", 10,"", true, '', 3);
             db_input("q97_descricaoAtual", 45,"", true, 'text', 3);
           ?>
          </td>
        </tr>
        <tr >
          <td nowrap="nowrap"><strong>Tipo do Alvará:</strong>
          </td>
          <td nowrap="nowrap">
           <?
             db_input("q98_sequencialAtual", 10,"", true, '', 3);
             db_input("q98_descricaoAtual", 45,"", true, 'text', 3);
           ?>
          </td>
        </tr>
      </table>
   </fieldset>

   <fieldset>
    <legend><strong>Transformar Para</strong></legend>
    <table class="form-container">
      <tr title="Grupo do Alvara">
        <td style="width: 150px;">
          Grupo do Alvará:
        </td nowrap="nowrap">
          <td>
           <?
             $aGrupo = array("0"=>"Selecione",
                             "1"=>"SEM ALVARA",
                             "2"=>"PERMANENTE",
                             "3"=>"PRECARIO",
                             "4"=>"PROVISORIO",
                             "5"=>"TEMPORARIO",
                             "6"=>"ESPECIAL"
                            );
             db_select("grupo",$aGrupo,true,4,"onchange='mostraTipo();'");
           ?>
        </td>
      </tr>
      <tr id='tipo' style="display: none;" title='Tipo do Grupo'>
        <td ><strong>
          <?
            db_ancora("Tipo Grupo:", 'js_buscaTipoAlvara(true);',$db_opcao );
          ?></b>
        </td>
        <td>
          <?
            db_input("q98_sequencial", 10,"", true, '',     3, "func_isstransformaalvara(false);");
            db_input("q98_descricao" , 45,"", true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap" title="Validade em Dias">
          <strong>Validade do Alvará:</strong>
        </td>
        <td nowrap="nowrap">
         <?
          db_input("q120_validadealvara", 10,"", true, 'text', 1);
         ?>
         <input type="hidden" id="tipovalidade" name='tipovalidade' />
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <fieldset>
          <legend>Observação:</legend>
          <? db_textarea("q120_obs",5, 48,  "", true,null, 1); ?>
        </td>
      </tr>
   </table>
   <input type='hidden' id='documentos' name='documentos'>
   <div id='ctnDocumento'> </div>
  </fieldset>
  <input type="submit" name="transformar" id='transformar' value="Transformar Alvará" onclick="return verifica();" />
</form>
</div>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?php
  /** Extensao : Inicio [BloqueioManutencaoInscricaoSistemaExterno] */
  /** Extensao : Fim [BloqueioManutencaoInscricaoSistemaExterno] */
?>
<script type="text/javascript">
// grid documentos
  var oDocumentos = new DBViewAlvaraDocumentos("oDocumentos", "ctnDocumento");
      oDocumentos.show();

function jsMontaDocumentos(){

   $('documentos').value = oDocumentos.getDocumentosSelecionados().toString();
}

/**
 * Busca a Inscrição do ALvará
 */
function js_buscaAlvara(mostra) {


  if (mostra == true) {
    js_OpenJanelaIframe('',
                        'db_iframe_isstrasnf',
                        'func_isstransformaalvara.php?lLibera=1&filtro=1&funcao_js=parent.js_mostraAlvara|q123_inscr|q120_issalvara|q97_sequencial|q97_descricao|q98_sequencial|q98_descricao|q120_sequencial|z01_nome',
                        'Pesquisa',
                        true);
  }else{

    js_OpenJanelaIframe('',
                        'db_iframe_isstrasnf',
                        'func_isstransformaalvara.php?lLibera=1&filtro=1&pesquisa_chave='+$F('q123_inscr')+'&funcao_js=parent.js_mostraAlvara1',
                        'Pesquisa',false);
  }
}

function js_mostraAlvara(iInscr, iAlvara,iSeqGrupo,sGrupo, iSeqTipo, sTipo, iSeqMov, sNome) {

  $("q123_inscr").value           = iInscr;
  $("q120_issalvara").value       = iAlvara;
  $("q97_sequencialAtual").value  = iSeqGrupo;
  $("q97_descricaoAtual").value   = sGrupo;
  $("q98_sequencialAtual").value  = iSeqTipo;
  $("q98_descricaoAtual").value   = sTipo;
  $("q120_sequencial").value      = iSeqMov;
  $("z01_nome").value      = sNome;
  oDocumentos.setCodigoAlvara(iAlvara);
  oDocumentos.carregaDados();
  db_iframe_isstrasnf.hide();

}
function js_mostraAlvara1(iInscr, iAlvara,iSeqGrupo,sGrupo, iSeqTipo, sTipo, iSeqMov, sNome, lChave){

  if (lChave == false || lChave == 'false' ){

    $("q123_inscr").value           = iInscr;
    $("q120_issalvara").value       = iAlvara;
    $("q97_sequencialAtual").value  = iSeqGrupo;
    $("q97_descricaoAtual").value   = sGrupo;
    $("q98_sequencialAtual").value  = iSeqTipo;
    $("q98_descricaoAtual").value   = sTipo;
    $("q120_sequencial").value      = iSeqMov;
    $("z01_nome").value      = sNome;

    oDocumentos.setCodigoAlvara(iAlvara);
    oDocumentos.carregaDados();

  } else {

     $("z01_nome").value = iInscr.urlDecode();
     $("q123_inscr").value           = "";
     $("q120_issalvara").value       = "";
     $("q97_sequencialAtual").value  = "";
     $("q97_descricaoAtual").value   = "";
     $("q98_sequencialAtual").value  = "";
     $("q98_descricaoAtual").value   = "";
     $("q120_sequencial").value      = "";
     $("q123_inscr").focus();
  }
}

/**
 * Busca o Tipo do alvara dependendo do Grupo Selecionado
 */
function js_buscaTipoAlvara(mostra) {

  if ( !$F("q98_sequencialAtual") || !$F("grupo") ) {
    return;
  }

  if (mostra == true) {
    js_OpenJanelaIframe('',
                        'db_iframe_issgrupo',
                        'func_isstipoalvaranovo.php?tipoantigo='+$F("q98_sequencialAtual")+'&grupo='+$F("grupo")+'&funcao_js=parent.js_mostraTipoAlvara|q98_sequencial|q98_descricao|q98_tipovalidade',
                        'Pesquisa',
                        true);
  }else{
    js_OpenJanelaIframe('',
                        'db_iframe_issgrupo',
                        'func_isstipoalvaranovo.php?tipoantigo='+$F("q98_sequencialAtual")+'&grupo='+$F("grupo")+'&pesquisa_chave='+$F('grupo')+'&funcao_js=parent.js_mostraTipoAlvara1',
                        'Pesquisa',false);
  }
}

function js_mostraTipoAlvara(iSequencia, iGrupoTipo, iTipoValidade) {

  $("q98_sequencial").value      = iSequencia;
  $("q98_descricao").value       = iGrupoTipo;
  $("tipovalidade").value        = iTipoValidade;

  if (iTipoValidade == 3) {

    $("q120_validadealvara").value = '0';
    $("q120_validadealvara").readOnly = "true";
  } else {

    $("q120_validadealvara").value = '';
    $("q120_validadealvara").readOnly = "";
  }

  db_iframe_issgrupo.hide();
}

function verifica(){

  if ($F("grupo") == "" ) {

    alert ("Selecione um grupo.");
    return false;
  }

  if ($F("q98_sequencial") == "" ) {

    alert ("Tipo do Alvará não selecionado.");
    return false;
  }

  if($F("q120_validadealvara") == "" && $F("tipovalidade") != 3 ) {
    alert ("Especifique um número de dias para validade do Alvará.");
    return false;
  }

  return true;
}

function mostraTipo() {

  if (!$F('q123_inscr') || $F('q123_inscr') == '' ) {
    return;
  }

  if ($F("grupo") != 0) {
    $("tipo").style.display = "";
    js_buscaTipoAlvara(true);
  } else {

    $("q98_sequencial").value = "";
    $("q98_descricao").value  = "";
    $("tipo").style.display   = "none";
  }
}
</script>