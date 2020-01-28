<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$oRotulo = new rotulocampo();
$oRotulo->label("q120_issalvara");
$oRotulo->label("q123_inscr");
$oRotulo->label("z01_nome");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$oPost               = db_utils::postMemory($_POST);
$oGet                = db_utils::postMemory($_GET);
$clIssMovAlvara      = new cl_issmovalvara;
$clIssAlvara         = new cl_issalvara;
$lLiberado           = "disabled='disabled'";
$dbOpcao             = 1;

if (isset($oGet->q123_inscr) && !isset($liberar)) {

  $iDepart  = db_getsession("DB_coddepto");
  $sCampo   = " q123_sequencial, z01_nome,q123_inscr";
  $sWhere   = " q123_inscr = {$oGet->q123_inscr}";
  $sSql     = $clIssAlvara->sql_queryAlvara("", $sCampo, "", $sWhere,null);
  $rsAlvara = $clIssAlvara->sql_record($sSql);

  if($clIssAlvara->numrows > 0 ) {

    $oAlvara  = db_utils::fieldsMemory($rsAlvara, 0);
    $z01_nome         = $oAlvara->z01_nome;
    $q120_issalvara   = $oAlvara->q123_sequencial;
    $lLiberado        = "";
  } else {
    $z01_nome = "Liberação Bloqueada";
  }

  $dbOpcao = 3;
}

if (isset($liberar)) {

  $oAlvara         = new Alvara($oPost->q120_issalvara);
  $oLiberarAlvara  = $oAlvara->incluirMovimentacao( MovimentacaoAlvara::TIPO_LIBERACAO );
  $aDocumentos     = Array();

  try {

    db_inicio_transacao();

    $oLiberarAlvara->setDataMovimentacao( date("Y-m-d", db_getsession("DB_datausu")));
    $oLiberarAlvara->setValidadeAlvara($oPost->q120_validadealvara);
    $oLiberarAlvara->setCodigoProcesso($oPost->p58_codproc);
    $oLiberarAlvara->setObservacao($oPost->q120_obs);
    $oLiberarAlvara->setUsuario( new UsuarioSistema(db_getsession('DB_id_usuario')) );

    if ($oPost->documentos != "" && !isset($oGet->aba)) {

      $aDocumentos = explode(",", $oPost->documentos);

      foreach($aDocumentos as $iIndice => $oValor){
        $oAlvara->addDocumento($oValor);
      }
    }

    $oAlvara->setTipoAlvara($oPost->q123_isstipoalvara);
    $oLiberarAlvara->processar();

    db_msgbox("Movimentação realizada com sucesso");

    $lLiberado           = "disabled='disabled'";
    $q123_inscr          = "";
    $z01_nome            = "";
    $q120_issalvara      = "";
    $q123_isstipoalvara  = "";
    $q98_descricao       = "";
    $q120_validadealvara = "";
    $p58_codproc         = "";
    $p58_requer          = "";
    $q120_obs            = "";

    db_fim_transacao(false);

  } catch (ErrorException $erro) {

    db_msgbox($erro->getMessage().$erro->getLine().$erro->getFile());
    db_fim_transacao(true);
  }

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js, prototype.js, datagrid.widget.js, strings.js, grid.style.css, estilos.css");
  db_app::load("classes/dbViewAvaliacoes.classe.js, widgets/windowAux.widget.js, dbcomboBox.widget.js");
  db_app::load("DBViewAlvaraDocumentos.js");
?>
<style type="text/css">
  .field {
    border: 0px;
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
</head>
<body onLoad="a=1;" class="body-default">
  <div class="container">
  <form name="form1" method="post" action="" onsubmit="jsMontaDocumentos(); return verifica();">
    <fieldset style="width: 700px;">
      <legend><strong>Liberação de Alvará</strong></legend>
      <table align="center" width="100%" cellpadding="" border="0">

        <tr>
          <td><strong>
  				  <?
  				    db_ancora("Inscrição:", 'js_mostranomes(true);', $dbOpcao)
  				  ?></strong>
          </td>
          <td>
  			   <?
  			     db_input("q123_inscr", 8,true, true, 'text',$dbOpcao,"onchange='js_mostranomes(false);'" );
  			     db_input("z01_nome", 50,"", true, 'text', 3);
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
          <td>
            <strong>
            <?
              db_ancora("Tipo de Alvará:","js_pesquisaTipoAlvara(true);",1);
            ?>
            </strong>
          </td>
          <td>
           <?
             db_input("q123_isstipoalvara", 8,"", true, 'text', 3);
             db_input("q98_descricao",     50,"", true, 'text', 3);
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
          <td title="Validade em Dias"><strong>Validade do Alvará:</strong>
          </td>
          <td>
  			   <?
  			    db_input("q120_validadealvara", 8,"", true, 'text', 1);
  			   ?>
          </td>
        </tr>

          <tr>
            <td nowrap><strong>
               <?
                 db_ancora("Processo:","js_pesquisap58_codproc(true);",1);
               ?></strong>
            </td>
            <td>
              <?
                db_input('p58_codproc',8,"",true,'text',1," onchange='js_pesquisap58_codproc(false);'");
                db_input('p58_requer',50,"",true,'text',3,'');
              ?>
            </td>
          </tr>

        <tr>
          <td ><strong>Observação:</strong>
          </td>
          <td>
  			   <?
  			    db_textarea("q120_obs",5, 58,  "", true,null, 1)
  			   ?>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <div id='ctnDocumento' <?=isset($oGet->aba) ? "style=\"display: none\"":"" ?>></div>
          </td>
        </tr>

      </table>

      <input type='hidden' id='documentos' name='documentos'>

    </fieldset>
    <input type="submit" <? echo $lLiberado ?>  name="liberar"  id='liberar' value="Liberar Alvará" />
  </form>

  <div id='ficha'></div>

</div>
<?php
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
<script type="text/javascript">

// grid documentos
  var oDocumentos = new DBViewAlvaraDocumentos("oDocumentos", "ctnDocumento");
      oDocumentos.show();

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
    js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_issalvara.php?lMov=1&lLibera=1&filtro=1&funcao_js=parent.js_preenche|0|1|2|3','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_issalvara.php?lMov=1&lLibera=1&filtro=1&pesquisa_chave='+$F('q123_inscr')+'&funcao_js=parent.js_preenche1','Pesquisa',false);
  }
}

function js_preenche(chave,chave1,chave2, chave3){

  document.form1.q123_inscr.value = chave;
  document.form1.z01_nome.value = chave1;
  document.form1.q120_issalvara.value = chave3;      document.getElementById('liberar').disabled = false;

  db_iframe_nomes.hide();
  oDocumentos.setCodigoAlvara(chave3);
  oDocumentos.carregaDados();
  document.getElementById('liberar').disabled = false;

}
function js_preenche1(chave,chave1, chave2, chave3){

  if (chave1 == false || chave1 == 'false' ){

     document.getElementById('liberar').disabled = false;
     document.form1.z01_nome.value               = chave2 || '';
     document.form1.q123_inscr.value             = chave  || '';
     document.form1.q120_issalvara.value         = chave3 || '';
     oDocumentos.setCodigoAlvara(chave3 || '' );
     oDocumentos.carregaDados();

	} else {

     document.form1.z01_nome.value   = chave;
     document.form1.q123_inscr.value = "";
  }

}

function verifica(){

  var iAlvara = $F('q123_inscr');

  if (iAlvara == null || iAlvara == "") {

    alert("Selecione um Alvara");
    return false;
	}
  if ($F('q123_isstipoalvara') == "") {

    alert("Escolha um tipo de alvará.");
    return false;
  }
}
function js_pesquisaTipoAlvara(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_isstipoalvara','func_isstipoalvara.php?funcao_js=parent.js_mostratipoalvara1|q98_sequencial|q98_descricao|q98_tipovalidade|q98_quantvalidade','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_isstipoalvara','func_isstipoalvara.php?pesquisa_chave='+document.form1.p58_codproc.value+'&funcao_js=parent.js_mostratipoalvara|q98_sequencial|q98_descricao|q98_tipovalidade|q98_quantvalidade','Pesquisa',false);
  }
}

/**
 * Função de pesqueisa de alvará
 */
function js_mostratipoalvara(chave1,chave2,chave3,chave4,erro){

  document.form1.q98_descricao.value = chave2;
  if(erro==true){
    document.form1.q123_isstipoalvara.focus();
    document.form1.q98_sequencial.value = '';
  }
  if(chave3 == 3){

    document.form1.q120_validadealvara.readOnly = 'readyonly';
    document.form1.q120_validadealvara.value    = 0;
  } else if(chave3 == 1) {

    document.form1.q120_validadealvara.readOnly = 'readyonly';
    document.form1.q120_validadealvara.value    = chave4;
  } else {
    document.form1.q120_validadealvara.readOnly = false;
    document.form1.q120_validadealvara.value    = '';
    document.form1.q120_validadealvara.focus();
  }
}
function js_mostratipoalvara1(chave1,chave2,chave3,chave4){
  document.form1.q123_isstipoalvara.value = chave1;
  document.form1.q98_descricao.value = chave2;
  if(chave3 == 3){

    document.form1.q120_validadealvara.readOnly = 'readyonly';
    document.form1.q120_validadealvara.value    = 0;
  } else if(chave3 == 1) {

    document.form1.q120_validadealvara.readOnly = 'readyonly';
    document.form1.q120_validadealvara.value    = chave4;
  } else {
    document.form1.q120_validadealvara.readOnly = false;
    document.form1.q120_validadealvara.value    = '';
    document.form1.q120_validadealvara.focus();
  }

  db_iframe_isstipoalvara.hide();
}
</script>