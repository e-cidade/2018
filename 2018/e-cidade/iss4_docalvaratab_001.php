<?php
/**
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
require_once("libs/db_app.utils.php");
require_once("dbforms/db_classesgenericas.php");
require_once("model/issqn/AlvaraMovimentacao.model.php");
require_once("model/issqn/AlvaraMovimentacaoLiberacao.model.php");
require_once("classes/db_issmovalvara_classe.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$oPost   = db_utils::postMemory($_POST);
$oGet    = db_utils::postMemory($_GET);
$oRotulo = new rotulocampo();
$oRotulo->label("q120_issalvara");
$oRotulo->label("q123_inscr");

$cliframe_seleciona  = new cl_iframe_seleciona;
$clIssMovAlvara      = new cl_issmovalvara;
$lLiberado = "";
$dbOpcao = 1;

if (isset($oGet->q123_inscr)){

  $q123_inscr = $oGet->q123_inscr;
  $dbOpcao = 3;
}

if (isset($liberar)) {

	$oLiberarAlvara  = new AlvaraMovimentacaoLiberacao($oPost->q120_issalvara);
	$aDocumentos     = Array();
	try {

		db_inicio_transacao();

	  if ($oPost->documentos != "") {

	     $aDocumentos = explode(",", $oPost->documentos);
	     foreach($aDocumentos as $iIndice => $oValor){

	       $oLiberarAlvara->addDocumento($oValor);
	     }
	  }

	  $oLiberarAlvara->gravaDocumentos();

	  db_msgbox("Movimentação realizada com sucesso");
	  db_fim_transacao(false);
	} catch (ErrorException $erro) {

		db_msgbox($erro->getMessage().$erro->getLine().$erro->getFile());
		db_fim_transacao(true);
	}

}

if (isset($oGet->q123_inscr)){

  $sSqlIncricao  = " select q123_inscr,                                                 ";
  $sSqlIncricao .= "        z01_nome,                                                   ";
  $sSqlIncricao .= "        z01_numcgm,                                                 ";
  $sSqlIncricao .= "        q123_sequencial,                                            ";
  $sSqlIncricao .= "        q123_sequencial  as q120_issalvara                          ";
  $sSqlIncricao .= "   from issalvara                                                   ";
  $sSqlIncricao .= "  inner join issbase  on  issbase.q02_inscr = issalvara.q123_inscr  ";
  $sSqlIncricao .= "  inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm           ";
  $sSqlIncricao .= " where q123_inscr = {$oGet->q123_inscr}                             ";
  $rsIncricao = db_query($sSqlIncricao);

  if (pg_num_rows($rsIncricao) > 0) {
    db_fieldsmemory($rsIncricao,0);
  }

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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad=" a=1;" bgcolor="#cccccc">
<center>
<form name="form1" method="post" action="">
  <fieldset style="margin-top:50px; width: 700px;">
    <legend><strong>Documentação do Alvará</strong></legend>
    <table  align="center" width="100%" cellpadding="" border="0">

      <tr>
        <td><b>
				  <?
				    db_ancora("Inscrição : ", 'js_mostranomes(true);', 3)
				  ?></b>
        </td>
        <td>
			   <?
			     db_input("q123_inscr", 8,true, true, 'text',$dbOpcao,"onchange='js_mostranomes(false);'" );
			     db_input("z01_nome", 50,"", true, 'text', 3);
			   ?>
        </td>
      </tr>


      <tr>
        <td><b>Alvará : </b>
        </td>
        <td>
			   <?
			     db_input("q120_issalvara", 8,"", true, 'text', 3);
			   ?>
        </td>
      </tr>

      <tr>
        <td colspan="2">

          &nbsp;

        </td>
      </tr>
      <tr>
        <td colspan="2">

          <div id='ctnDocumento'> </div>

        </td>
      </tr>


    </table>

    <input type='hidden' id='documentos' name='documentos'>

  </fieldset>
    <table  align="center" width="100%" cellpadding="5" border="0">
      <tr>
         <td colspan="2" align="center">
           <input type="submit" <? echo $lLiberado ?>  style="margin-left: 10px; margin-top: 10px;" name="liberar" id='liberar' value="Cadastrar" onclick="jsMontaDocumentos();return verifica();" />
         </td>
      </tr>
    </table>
</form>

<div id='ficha'>
</div>

</center>
<?
 if (!isset($oGet->aba)){
   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
 }
?>
</body>
</html>
<script>
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
    js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_issalvara.php?lLibera=1&filtro=1&funcao_js=parent.js_preenche|0|1|2|3','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_issalvara.php?lLibera=1&filtro=1&pesquisa_chave='+$F('q123_inscr')+'&funcao_js=parent.js_preenche1','Pesquisa',true);
  }
}

function js_preenche(chave,chave1,chave2, chave3){

  document.form1.q123_inscr.value = chave;
  document.form1.z01_nome.value = chave1;
  document.form1.q120_issalvara.value = chave3;
  db_iframe_nomes.hide();
  oDocumentos.setCodigoAlvara(chave3);
  oDocumentos.carregaDados();
}
function js_preenche1(chave,chave1, chave2, chave3){

  if (chave1 == false || chave1 == 'false' ){

     document.form1.z01_nome.value = chave2;
     document.form1.q123_inscr.value = chave;
     document.form1.q120_issalvara.value = chave3;
     oDocumentos.setCodigoAlvara(chave3);
     oDocumentos.carregaDados();

	} else {

     //document.form1.z01_nome.value = chave;
     document.form1.z01_nome.value = "Departamentos nao configurados para o tipo de alvará";
     document.form1.q123_inscr.value = "";
     //alert("Configure Departamentos para o Tipo de Alvarás");
  }

}


//////////////////////

function verifica(){

  var iAlvara = $F('q123_inscr');

  if (iAlvara == null || iAlvara == ""){
    alert("Selecione um Alvara");
    return false;
	}

	if (oDocumentos.getDocumentosSelecionados() <= 0 ){
    alert("Selecione um documento");
    return false;
	}
}

</script>
<?
  if (pg_num_rows($rsIncricao) > 0) {

    echo "<script> oDocumentos.setCodigoAlvara($q123_sequencial); ";
    echo " oDocumentos.carregaDados();         </script> ";

  }

?>