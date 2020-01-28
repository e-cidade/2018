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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_protprocesso_classe.php"));
require_once(modification("classes/db_procdoctipo_classe.php"));
require_once(modification("classes/db_protparam_classe.php"));
require_once(modification("classes/db_procvar_classe.php"));
require_once(modification("classes/db_andpadrao_classe.php"));
require_once(modification("classes/db_proctipovar_classe.php"));
require_once(modification("classes/db_procprocessodoc_classe.php"));
require_once(modification("classes/db_db_depusu_classe.php"));
require_once(modification("classes/db_db_syscampo_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST,0);
$oGet  = db_utils::postMemory($_GET,0);

$clprotprocesso    = new cl_protprocesso;
$clprocprocessodoc = new cl_procprocessodoc;
$clproctipovar     = new cl_proctipovar;
$clandpadrao       = new cl_andpadrao;
$cldepusu          = new cl_db_depusu;
$clprotparam       = new cl_protparam;
$cldoc             = new cl_procprocessodoc;

$db_opcao = 22;
$db_botao = false;

if (isset($oGet->chavepesquisa) && $oGet->chavepesquisa != "") {
	$p58_codproc = $oGet->chavepesquisa;
}

if (isset($oGet->p58_codigo) && $oGet->p58_codigo != "") {
	$p58_codigo = $oGet->p58_codigo;
}

if(isset($btnalterar) && $btnalterar == 2){

if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  db_inicio_transacao();
  $db_opcao        = 2;
  $sqlerro         =  false;
  $aPartesNumero   = explode("/", $p58_numero);
  $p58_numero     = 0;
  if (count($aPartesNumero) == 2) {
    $p58_numero = $aPartesNumero[0];
  }
  $clprotprocesso->p58_numero = $p58_numero;
  $clprotprocesso->alterar($p58_codproc);
  $chaves = split("#",$docs);
  $clprocprocessodoc->excluir($p58_codproc);
  //$clprocprocessodoc->erro(true,false);
  for($i=0;$i<sizeof($chaves);$i++){
    $clprocprocessodoc->p81_codproc = $p58_codproc;
    $clprocprocessodoc->p81_coddoc = $chaves[$i];
    $clprocprocessodoc->p81_doc = 't';
    $clprocprocessodoc->incluir($p58_codproc,$chaves[$i]);
    //$clprocprocessodoc->erro(true,false);
  }
  $chaves = split("#",$ndocs);
  for($i=0;$i<sizeof($chaves);$i++){
    $HTTP_POST_VARS['p81_doc'] = 'f';
    $clprocprocessodoc->p81_codproc = $p58_codproc;
    $clprocprocessodoc->p81_coddoc = $chaves[$i];
    $clprocprocessodoc->p81_doc = 'f';
    $clprocprocessodoc->incluir($p58_codproc,$chaves[$i]);
    //$clprocprocessodoc->erro(true,false);
  }
  $sql = " select p54_codigo, p54_codcam from procvar where p54_codigo = {$oPost->p58_codigo} ";
  //die($sql);
  $rs = db_query($sql) or die($sql);

  $clproctipovar->excluir($clprotprocesso->p58_codproc);
  if ($clproctipovar->erro_status == "0"){
      $sqlerro = true;
  }

  if (pg_num_rows($rs) > 0){
    while ($ln = pg_fetch_array($rs)){
      $sql2 = "select nomecam from db_syscampo where codcam = ".$ln["p54_codcam"];
      $rscam = db_query($sql2) or die($sql2);
      $nomecam = trim(pg_result($rscam,0,"nomecam"));

      global $p55_codproc, $p55_codvar, $p55_codcam, $p55_conteudo;
      $GLOBALS["HTTP_POST_VARS"]["p55_codproc"] = $clprotprocesso->p58_codproc;
      $GLOBALS["HTTP_POST_VARS"]["p55_codvar"] = $ln["p54_codigo"];
      $GLOBALS["HTTP_POST_VARS"]["p55_codcam"] = $ln["p54_codcam"];
      $GLOBALS["HTTP_POST_VARS"]["p55_conteudo"] = $$nomecam;

      if($$nomecam == ""){
      	continue;
      }

      $clproctipovar->p55_codproc = $clprotprocesso->p58_codproc;
      $clproctipovar->p55_codvar = $ln["p54_codigo"];
      $clproctipovar->p55_codcam = $ln["p54_codcam"];
      $clproctipovar->p55_conteudo = $$nomecam;
      $clproctipovar->incluir($clprotprocesso->p58_codproc,$ln["p54_codigo"],$ln["p54_codcam"]);
      if ($clproctipovar->erro_status == "0"){
        $sqlerro = true;
      }
      // $clproctipovar->erro(true,false);
      //           echo "<script>alert('processo: ".$p55_codproc."\\ncodvar: ".$p55_codvar."\\ncodcam: ".$p55_codcam."\\n$nomecam: ".$$nomecam."');</script>";
    }
  }
  db_fim_transacao($sqlerro);

}
}elseif(!isset($btnalterar) ){
    if(isset($chavepesquisa) ){
       $db_opcao = 2;
       $result   = $clprotprocesso->sql_record($clprotprocesso->sql_query($chavepesquisa)); 
       db_fieldsmemory($result,0);
       $db_botao = true;
       $result_andam = $clprotprocesso->sql_record($clprotprocesso->sql_query_alt($p58_codproc,"*",null,"p58_codproc = $p58_codproc and p61_codandam is null and p63_codtran is null "));
       if ($clprotprocesso->numrows==0){
           $db_opcao = 3;
          $db_botao  = false;
      }
   }
}else{
//     include(modification("classes/db_procdoctipo_classe.php"));
       $cldoc = new cl_procdoctipo;
       $res = $cldoc->sql_record($cldoc->sql_query($p58_codigo,"","p56_coddoc,p56_descr"));
        $db_opcao = 2;
        $db_botao = true;

}

if (!isset($btnalterar)){
      $btnalterar = 2;
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<?
if (isset($oGet->alt) && $oGet->alt == 1) {
  if(isset($db_opcao)){
  	$sOnLoad = " onload='js_preenchepesquisa(".$oGet->chavepesquisa.");'";
  }
} else {
	 $sOnLoad = " onload='a=1'";
}
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" <?=$sOnLoad?>>
<form name="form1" method="post" action="" onsubmit="return js_validaObservacao();">
<br /><br />
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
      <?
         include(modification("forms/db_frmprotprocessoalt.php"));
      ?>
    </td>
  </tr>
</table>
</center>
</form>
</body>
</html>
<?
if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Alterar") {
  echo "<script> window.open('pro4_capaprocesso.php?codproc=".$clprotprocesso->p58_codproc."','','location=0'); </script>";
  $result_param = $clprotparam->sql_record($clprotparam->sql_query_file());
  if ($clprotparam->numrows>0) {
    db_fieldsmemory($result_param,0);
    if ($p90_emiterecib == "t") {
      echo "<script>
          if (confirm('Deseja Emitir Recibo?')) {
            location.href='cai4_recibo001.php?p58_codproc=$p58_codproc&codtipo=$p58_codigo&incproc=true&mostramenu=true&sIframe=iframe_dadosprocesso';
          } else {
            location.href='pro4_aba1protprocesso002.php';
          }
           </script>";

    } else {
        echo "<script>location.href='pro4_aba1protprocesso002.php';</script>";
    }
  } else {
      echo "<script>location.href='pro4_protprocesso002.php';</script>";
  }
}
?>
