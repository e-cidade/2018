<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_utils.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_cidadao_classe.php");
require_once("classes/db_cidadaoemail_classe.php");
require_once("classes/db_cidadaotelefone_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");

db_postmemory($HTTP_POST_VARS);
$db_opcao = 2;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  //$clcidadao->incluir($ov02_sequencial,$ov02_seq);
  db_fim_transacao();
}

$oGet                    = db_utils::postMemory($_GET);
$lHabilitaMenu           = true;
$sStyleProcessado        = "";
$sStylelTelaSocial       = "display:block;";
$oGet->lTelaSocial       = 'true';
$iLocalatendimentosocial = '0';

if (isset($oGet->lOrigemLeitor) && $oGet->lOrigemLeitor) {

  $lHabilitaMenu    = false;
  $sStyleProcessado = "display:none;";
}

$oDaoLocalatendimentosocial = db_utils::getDao('localatendimentosocial');
$sSqlLocalatendimentosocial = $oDaoLocalatendimentosocial->sql_query_file (null, "*", null, "as16_db_depart = ".db_getsession('DB_coddepto'));
$rsLocalatendimentosocial   = $oDaoLocalatendimentosocial->sql_record($sSqlLocalatendimentosocial);

$lDepartamentoCrasCreas = 'false';

if ($oDaoLocalatendimentosocial->numrows > 0) {

  $lDepartamentoCrasCreas = 'true';
  $iLocalatendimentosocial = db_utils::fieldsMemory($rsLocalatendimentosocial, 0)->as16_sequencial;
} else {
  $iLocalatendimentosocial = 0;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
	db_app::load('prototype.js, strings.js, scripts.js, DBAbas.widget.js, DBAbasItem.widget.js, datagrid.widget.js,
                widgets/windowAux.widget.js, widgets/dbtextField.widget.js, dbViewAvaliacoes.classe.js,
                dbmessageBoard.widget.js,dbautocomplete.widget.js,dbcomboBox.widget.js');
	db_app::load('estilos.css, grid.style.css, DBtab.style.css');
?>
<style>
body {
  padding: 0;
  margin: 16px 0 0 0;
}
</style>
<script>
var lDepartamentoCrasCreas = <?php echo $lDepartamentoCrasCreas; ?>;

if (!lDepartamentoCrasCreas) {
  alert('Departamento Selecionado Não é CRAS ou CREAS');
}
</script>
</head>
<body>

<center>
<div id="container">
	<?php
	  require_once ("forms/db_frmcidadao.php");
	?>
	</div>
</center>
<?php
  if ($lHabilitaMenu) {
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  }
?>
</body>
</html>
<script>
//Cria as abas.
  var oAbas                = new DBAbas($("container"));
  var oAbaCidadao          = oAbas.adicionarAba('Cidadão', $("cadastroCidadao"));
  var oAbaAvaliacaoCidadao = oAbas.adicionarAba('Avaliação Sócio Econômica - Cidadão', $("cadastroAvaliacaoSocioEconomicaCidadao"));
  var oAbaAvaliacaoFamilia = oAbas.adicionarAba('Avaliação Sócio Econômica - Família', $("cadastroAvaliacaoSocioEconomicaFamilia"));
</script>
<?php
if (isset($incluir)) {

  if ($clcidadao->erro_status == "0") {

    $clcidadao->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clcidadao->erro_campo != "") {

      echo "<script> document.form1.".$clcidadao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcidadao->erro_campo.".focus();</script>";
    }
  } else {
    $clcidadao->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
if($db_opcao==2){

  if (!isset($chavepesquisa)) {
    echo "<script>js_pesquisa();</script>";
  } else {
    echo "<script>js_pesquisaCidadao($chavepesquisa);</script>";
  }
}
?>