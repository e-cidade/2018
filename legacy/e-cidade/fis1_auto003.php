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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if(!isset($abas)){

  echo "<script>location.href='fis1_auto005.php?db_opcao=3'</script>";
  exit;
}

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$auto = 1;
$clauto          = db_utils::getDao("auto");
$clautousu       = db_utils::getDao("autousu");
$clautolocal     = db_utils::getDao("autolocal");
$clautofiscal    = db_utils::getDao("autofiscal");
$clautoexec      = db_utils::getDao("autoexec");
$clautoinscr     = db_utils::getDao("autoinscr");
$clautomatric    = db_utils::getDao("automatric");
$clautocgm       = db_utils::getDao("autocgm");
$clautorec       = db_utils::getDao("autorec");
$clautoandam     = db_utils::getDao("autoandam");
$clautoultandam  = db_utils::getDao("autoultandam");
$clfandam        = db_utils::getDao("fandam");
$clautosanitario = db_utils::getDao("autosanitario");
$clautotipo      = db_utils::getDao("autotipo");
$clautotestem    = db_utils::getDao("autotestem");
$clfandamusu     = db_utils::getDao("fandamusu");
$clautonumpre    = db_utils::getDao("autonumpre");
$clautonumpre    = db_utils::getDao("autonumpre");

$db_botao = false;
$db_opcao = 33;

if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){

  db_inicio_transacao();
  $db_opcao = 3;

  $clautolocal->excluir($y50_codauto);
  $clautolocal->erro(false,false);

  $clautoexec->excluir($y50_codauto);
  $clautoexec->erro(false,false);

  $clautocgm->excluir($y50_codauto);
  $clautocgm->erro(false,false);

  $clautomatric->excluir($y50_codauto);
  $clautomatric->erro(false,false);

  $clautoinscr->excluir($y50_codauto);
  $clautoinscr->erro(false,false);

  $clautosanitario->excluir($y50_codauto);
  $clautosanitario->erro(false,false);

  $clautofiscal->excluir($y50_codauto);
  $clautofiscal->erro(false,false);

  $clautousu->excluir($y50_codauto);
  $clautousu->erro(false,false);

  $result1 = $clautorec->sql_record($clautorec->sql_query_file($y50_codauto));
  if($clautorec->numrows > 0){

    $numrows = $clautorec->numrows;
    for($x=0;$x<$numrows;$x++){
      db_fieldsmemory($result1,$x);
      $clautorec->y57_codauto = $y57_codauto;
      $clautorec->y57_receit = $y57_receit;
      $clautorec->excluir($y57_codauto,$y57_receit);
      $clautorec->erro(false,false);
    }
  }

  $result = $clautoandam->sql_record($clautoandam->sql_query_file("","","y58_codandam"," y58_codandam desc"," y58_codauto = $y50_codauto"));
  if($clautoandam->numrows > 0){
    db_fieldsmemory($result,0);

	$result1 = $clfandamusu->sql_record($clfandamusu->sql_query_file($y58_codandam));
	if ($clfandamusu->numrows > 0) {
      $numrows = $clfandamusu->numrows;
      for ($x=0;$x<$numrows;$x++) {
	    db_fieldsmemory($result1,$x);
	    $clfandamusu->excluir($y40_codandam,$y40_id_usuario);
	    $clfandamusu->erro(false,false);
      }
    }

    $clautoultandam->excluir($y50_codauto);
    $clautoultandam->erro(false,false);
    $numrows = $clautoandam->numrows;
    for($x=0;$x<$numrows;$x++){
      db_fieldsmemory($result,$x);
      $clautoandam->excluir($y50_codauto,$y58_codandam);
      $clautoandam->erro(false,false);
      $clfandam->excluir($y58_codandam);
      $clfandam->erro(false,false);
    }
  }

  $result1 = $clautotipo->sql_record($clautotipo->sql_query(null, "*", "", "y59_codauto = ".$y50_codauto));
  if($clautotipo->numrows > 0){
    $numrows = $clautotipo->numrows;
    for($x=0;$x<$numrows;$x++){
      db_fieldsmemory($result1,$x);
      $clautotipo->excluir($y59_codigo);
      $clautotipo->erro(false,false);
    }
  }

  $clautotestem->excluir($y50_codauto);
  $clauto->excluir($y50_codauto);
  $msg = $clauto->erro_msg;

  db_fim_transacao();
} else if (isset($chavepesquisa)) {
   $db_opcao = 3;
   $result = $clauto->sql_record($clauto->sql_query($chavepesquisa,"*",null," y50_instit = ".db_getsession('DB_instit') ));
   db_fieldsmemory($result,0);
   $result = $clautolocal->sql_record($clautolocal->sql_query($chavepesquisa,"*"));
   if($clautolocal->numrows > 0){
     db_fieldsmemory($result,0);
   }
   $result = $clautoexec->sql_record($clautoexec->sql_query($chavepesquisa,"*"));
   if($clautoexec->numrows > 0){
     db_fieldsmemory($result,0);
   }

   //Verifica se o auto foi calculado
   $result_numpre = $clautonumpre->sql_record($clautonumpre->sql_query(null,"*",null," auto.y50_codauto = $chavepesquisa "));
   if ($clautonumpre->numrows > 0) {
     db_fieldsmemory($result_numpre,0);
     db_msgbox('Alteração não Permitida!\n\nAuto já calculado, numpre do calculo: '.$y17_numpre.'\n\nDeve ser acessada a rotina de Baixa de Auto de Infração em Procedimentos > Auto de Infração > Baixa > Inclusão');
	 db_redireciona('fis1_auto003.php?abas=1');
   }

   $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div class="container">
  	<?php
  	  include("forms/db_frmauto.php");
  	?>
  </div>
</body>
</html>
<?php
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
   db_msgbox($msg);
   echo " <script>
            parent.iframe_auto.location.href='fis1_auto003.php?abas=1';
         </script> ";
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>