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
require_once("classes/db_autotipo_classe.php");
require_once("classes/db_autoandam_classe.php");
require_once("classes/db_autorec_classe.php");
require_once("classes/db_autoultandam_classe.php");
require_once("classes/db_autousu_classe.php");
require_once("classes/db_fandam_classe.php");
require_once("classes/db_fandamusu_classe.php");
require_once("classes/db_fiscalprocrec_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clautotipo      = new cl_autotipo;
$clautoandam     = new cl_autoandam;
$clautorec       = new cl_autorec;
$clautoultandam  = new cl_autoultandam;
$clautousu       = new cl_autousu;
$clfandam        = new cl_fandam;
$clfandamusu     = new cl_fandamusu;
$clfiscalprocrec = new cl_fiscalprocrec;
$db_botao = false;
$db_opcao = 33;
global $y59_codauto;
$y59_codauto = @$y50_codauto;

if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){

  db_inicio_transacao();
  $db_opcao = 3;
  $result = $clfiscalprocrec->sql_record($clfiscalprocrec->sql_query_autotipo("",""," distinct y45_receit,y45_codtipo,y45_descr,y45_valor",""," y59_codauto = $y59_codauto"));
  if($clfiscalprocrec->numrows > 0){

    $numrows = $clfiscalprocrec->numrows;
    for($y=0;$y<$numrows;$y++){
      db_fieldsmemory($result,$y);
      $result1 = $clautorec->sql_record($clautorec->sql_query_file($y59_codauto));
      $num = $clautorec->numrows;
      if($clautorec->numrows > 0){
        for($x=0;$x<$num;$x++){
          db_fieldsmemory($result1,$x);
	        if($y57_receit == $y45_receit){
            $clautorec->y57_codauto = $y57_codauto;
            $clautorec->y57_receit = $y57_receit;
            $clautorec->excluir($y57_codauto,$y57_receit);
	        }
        }
      }
    }
  }
  $result = $clautoandam->sql_record($clautoandam->sql_query_file("","","y58_codandam",""," y58_codauto = $y59_codauto"));
  if($clautoandam->numrows == 1){
    db_fieldsmemory($result,0);
    $clfandamusu->excluir($y58_codandam);
    $clautousu->excluir($y59_codauto);
    $clautoultandam->excluir($y59_codauto,$y58_codandam);
    $clautoandam->excluir($y59_codauto,$y58_codandam);
    $clfandam->excluir($y58_codandam);
  }
  $clautotipo->excluir(null,"y59_codauto=$y59_codauto and y59_codtipo=$y59_codtipo");
  db_fim_transacao();
  echo "<script>parent.iframe_receitas.location.href='fis1_autorec001.php?y59_codauto=".$y59_codauto."&abas=1';</script>\n";
  echo "<script>parent.iframe_fiscais.location.href='fis1_autousu001.php?y59_codauto=".$y59_codauto."&y39_codandam=".$y39_codandam."&abas=1';</script>\n";
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clautotipo->sql_record($clautotipo->sql_query(null,"*",null,"y59_codauto=$chavepesquisa and y59_codtipo=$chavepesquisa1"));
   db_fieldsmemory($result,0);
   $db_botao = true;
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div class="container">
  	<?php
  	 include("forms/db_frmautotipo.php");
  	?>
  </div>
</body>
</html>
<script type="text/javascript">
js_tabulacaoforms("form1","db_opcao",true,1,"db_opcao",true);
</script>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  if($clautotipo->erro_status=="0"){
    $clautotipo->erro(true,false);
  }else{
    $clautotipo->erro(true,false);
    echo "<script>parent.iframe_autotipo.location.href='fis1_autotipo001.php?y59_codauto=".$y59_codauto."&abas=1';</script>\n";
    echo "<script>parent.iframe_receitas.location.href='fis1_autorec001.php?y59_codauto=".$y59_codauto."&abas=1';</script>\n";
    echo "<script>parent.iframe_fiscais.location.href='fis1_autousu001.php?y59_codauto=".$y59_codauto."&y39_codandam=".$y39_codandam."&abas=1';</script>\n";
  };
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>