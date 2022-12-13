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
require_once("model/issqn/alvara/MovimentacaoAlvaraFactory.model.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$oPost   = db_utils::postMemory($_POST);
$oGet    = db_utils::postMemory($_GET);
$oRotulo = new rotulocampo();
$oRotulo->label("q120_issalvara");
$oRotulo->label("q123_inscr");

$clIssMovAlvara = db_utils::getDao('issmovalvara');
$lLiberado = "";
$dbOpcao = 1;

/**
 * BAIXA DE ALVARA
 *
 * update issalvara para inativo
 * instert na movimentação de alvara
 * insert no motivo da baixa
 */
try {

  require_once("libs/exceptions/DBException.php");
  require_once("libs/exceptions/ParameterException.php");
  require_once("libs/exceptions/BusinessException.php");
  require_once("libs/exceptions/ParameterException.php");

  if ( isset($liberar) ) {

    db_inicio_transacao();

    $oAlvara        = new Alvara($oPost->q120_issalvara);

    $oBaixarAlvara  = $oAlvara->incluirMovimentacao( MovimentacaoAlvara::TIPO_BAIXA );
    $oBaixarAlvara->setUsuario( new UsuarioSistema(db_getsession('DB_id_usuario')) );
    $oBaixarAlvara->setDataMovimentacao( date("d-m-Y", db_getsession("DB_datausu")) );
    $oBaixarAlvara->setCodigoProcesso($oPost->p58_codproc);
    $oBaixarAlvara->setObservacao($oPost->q120_obs);
    $oBaixarAlvara->setTipoBaixa($oPost->motivo);

    $oBaixarAlvara->processar();

    db_msgbox("Movimentação realizada com sucesso");
    db_fim_transacao(false);
    db_redireciona("iss4_baixaalvara_001.php");
  }
} catch (Exception $erro) {

  db_msgbox( $erro->getMessage() );
  db_fim_transacao(true);
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
<style>
  .field {
    border : 0px;
    border-top: 2px groove white;
  }
 fieldset.field table tr td:FIRST-CHILD {
   width: 150px;
 	 white-space: nowrap;
}
 .link_botao {
    color: blue;
    cursor: pointer;
    text-decoration: underline;
  }
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div class="container">
<form name="form1" method="post" action="" class="container">
  <fieldset>
    <legend>Baixa de Alvará</legend>
    <table class="form-container">

      <tr>
        <td><strong>
				  <?
				    db_ancora("Inscrição:", 'js_mostranomes(true);', $dbOpcao)
				  ?></b>
        </td>
        <td>
			   <?
			     db_input("q123_inscr", 8,true, true, 'text',$dbOpcao,"onchange='js_mostranomes(false);'" );
			     db_input("z01_nome", 40,"", true, 'text', 3);
			   ?>
        </td>
      </tr>
      <tr>
        <td><strong>Alvará:</strong>
        </td>
        <td>
			   <?
			     db_input("q120_issalvara", 8,"", true, 'text', 3);
			   ?>
        </td>
      </tr>
      <tr>
        <td><strong>Data da Movimentação:</strong>
        </td>
        <td>
			   <?
			    echo date("d/m/Y",db_getsession("DB_datausu"));
			   ?>
        </td>
      </tr>
      <tr>
        <td nowrap>
         <strong><? db_ancora("Processo:","js_pesquisap58_codproc(true);",1); ?></b>
        </td>
        <td>
          <?
            db_input('p58_codproc',8,"",true,'text',1," onchange='js_pesquisap58_codproc(false);'");
            db_input('p58_requer',40,"",true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap>
         <strong>Motivo da Baixa:</strong>
        </td>
        <td>
          <?
            $aMotivos = array( "" => "Selecione",
                               "1" => "Pedido",
                               "2" => "Oficio"

                              );
            db_select("motivo", $aMotivos ,true, 1);
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <fieldset>
          <legend>Observação:</legend>
          <? db_textarea("q120_obs",5, 48,  "", true,null, 1); ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <div id='ctnDocumento'> </div>
        </td>
      </tr>
    </table>
    <input type='hidden' id='documentos' name='documentos' />
      <?php

      if (isset($oGet->q123_inscr)){

        echo"<script> js_OpenJanelaIframe('','db_iframe_nomes','func_issalvarabaixa.php?filtro=1&pesquisa_chave='+\$F('q123_inscr')+'&funcao_js=parent.js_preenche1','Pesquisa',false); </script>";
        $sSqlLiberado = $clIssMovAlvara->sql_query(null, "q120_issalvara", null, "q123_inscr = {$oGet->q123_inscr} and q120_isstipomovalvara = 1 ");
        $rsLiberado   = $clIssMovAlvara->sql_record($sSqlLiberado);

        if ($clIssMovAlvara->numrows > 0) {
          $lLiberado = "disabled='disabled'";
        }
      }

      ?>
  </fieldset>
  <input type="submit" <? echo $lLiberado ?>  name="liberar" id='liberar' value="Baixa de Alvará" onclick="jsMontaDocumentos();return verifica();" />
</form>

<div id='ficha'></div>

</div>
<?
 if (!isset($oGet->aba)){
   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
 }
?>
</body>
</html>
<?php
  /** Extensao : Inicio [BloqueioManutencaoInscricaoSistemaExterno] */
  /** Extensao : Fim [BloqueioManutencaoInscricaoSistemaExterno] */
?>
<script>
// grid documentos
  var oDocumentos = new DBViewAlvaraDocumentos("oDocumentos", "ctnDocumento");

function jsMontaDocumentos(){

   $('documentos').value = oDocumentos.getDocumentosSelecionados().toString();
}

// mostra processos
function js_pesquisap58_codproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_cgm','func_protprocesso.php?pesquisa_chave='+document.form1.p58_codproc.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
  }
}
function js_mostraprotprocesso(chave,chave1,erro){
  document.form1.p58_requer.value = chave1;
  if(erro==true){
    document.form1.p58_codproc.focus();
    document.form1.p58_codproc.value = '';
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.p58_codproc.value = chave1;
  document.form1.p58_requer.value = chave2;
  db_iframe_cgm.hide();
}

// mostra alvara e matricula
function js_mostranomes(mostra){

  if(mostra == true){
    js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_issalvarabaixa.php?filtro=1&funcao_js=parent.js_preenche|0|1|2|3','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_issalvarabaixa.php?filtro=1&pesquisa_chave='+$F('q123_inscr')+'&funcao_js=parent.js_preenche1','Pesquisa',false);
  }
}

function js_preenche(chave,chave1,chave2, chave3){

  if (!chave) {
    return false;
  }

  document.form1.q123_inscr.value     = chave;
  document.form1.z01_nome.value       = chave1;
  document.form1.q120_issalvara.value = chave3;
  db_iframe_nomes.hide();
  oDocumentos.setCodigoAlvara(chave3);
  oDocumentos.carregaDados();
}

function js_preenche1(chave, lErro, chave2, chave3){

  if ( lErro ) {

    document.form1.z01_nome.value       = chave;
    document.form1.q123_inscr.value     = "";
    document.form1.q120_issalvara.value = "";
    oDocumentos.setCodigoAlvara();
    oDocumentos.carregaDados();
    document.form1.q123_inscr.focus();
    return;
  }

  if (!chave2) {
    return false;
  }

  document.form1.z01_nome.value = chave2;
  document.form1.q120_issalvara.value = chave3;
  oDocumentos.setCodigoAlvara(chave3);
  oDocumentos.carregaDados();
}

function verifica(){

  var iAlvara = $F('q123_inscr');
  var iMotivo = $F('motivo');

  if (iAlvara == null || iAlvara == "" || iAlvara == 'undefined'){
    alert("Selecione um Alvara");
    return false;
	}
  if (iMotivo == null || iMotivo == ""){
    alert("Selecione um Motivo");
    return false;
  }

}
</script>