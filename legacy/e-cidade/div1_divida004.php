<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$oPost        = db_utils::postMemory($_POST);
$oGet         = db_utils::postMemory($_GET);

$clcgm        = new cl_cgm;
$cliptubase   = new cl_iptubase;
$clissbase    = new cl_issbase;
$cldivida     = new cl_divida;
$cldivmatric  = new cl_divmatric;
$cldivinscr   = new cl_divinscr;
$clnumpref    = new cl_numpref;
$clproced     = new cl_proced;
$clarrematric = new cl_arrematric;
$clarreinscr  = new cl_arreinscr;
$db_opcao     = 1;
$db_botao     = true;

if(empty($incluir) && empty($alterar) && empty($excluir)){
  if(isset($z_numcgm) && $z_numcgm!=""){
    $z01_numcgm=$z_numcgm;
    $result04=$clcgm->sql_record($clcgm->sql_query_file($z01_numcgm,"z01_nome"));
    if($clcgm->numrows>0){
      $v01_numcgm=$z01_numcgm;
      db_fieldsmemory($result04,0);
    }else{
       db_redireciona("div1_divida001.php?dado=numcgm");
       exit;
    }
  }else if(isset($j01_matric) && $j01_matric!=""){
    $result05=$cliptubase->sql_record($cliptubase->sql_query($j01_matric,"j01_numcgm,z01_nome"));
    if($cliptubase->numrows>0){
      db_fieldsmemory($result05,0);
      $v01_numcgm=$j01_numcgm;
      $tipo="matric";
      $valor=$j01_matric;
    }else{
       db_redireciona("div1_divida001.php?dado=matric");
       exit;
    }
  }else if(isset($q02_inscr) && $q02_inscr!=""){
    $result06=$clissbase->sql_record($clissbase->sql_query($q02_inscr,"q02_numcgm,z01_nome"));
    if($clissbase->numrows>0){
      db_fieldsmemory($result06,0);
      $v01_numcgm=$q02_numcgm;
      $tipo="inscr";
      $valor=$q02_inscr;
    }else{
       db_redireciona("div1_divida001.php?dado=inscr");
       exit;
    }
  }
}

if(isset($incluir)){
  db_inicio_transacao();

  $v01_dtinsc = $v01_dtinsc_ano."-".$v01_dtinsc_mes."-".$v01_dtinsc_dia;
  $sqlerro=false;
  $numpre               = $clnumpref->sql_numpre();
  $cldivida->v01_numcgm = $v01_numcgm;
  $cldivida->v01_dtinsc = $v01_dtinsc;
  $cldivida->v01_exerc  = $v01_exerc;
  $cldivida->v01_numpre = $numpre;
  $cldivida->v01_numpar = $v01_numpar;
  $cldivida->v01_numtot = 1;
  $cldivida->v01_numdig = 1;
  $cldivida->v01_vlrhis = $v01_vlrhis;
  $cldivida->v01_proced = $v01_proced;
  $cldivida->v01_obs    = $v01_obs;
  $cldivida->v01_livro  = $v01_livro;
  $cldivida->v01_folha  = $v01_folha;
  $cldivida->v01_valor  = $v01_valor;
  $cldivida->v01_instit = db_getsession('DB_instit');
  $cldivida->v01_dtinclusao = date('Y-m-d',db_getsession('DB_datausu'));


  if (isset($oPost->lProcessoSistema) && (int)$oPost->lProcessoSistema == 0) {

      $cldivida->v01_processo   = $v01_processoExterno;
      $cldivida->v01_dtprocesso = $v01_dtprocesso;
      $cldivida->v01_titular    = $v01_titular;
    }

  $cldivida->incluir($v01_coddiv);

  if($cldivida->erro_status == '0'){
		$cldivida->erro_msg = $cldivida->erro_msg;
    $sqlerro=true;
  }

  if (isset($oPost->lProcessoSistema) && (int)$oPost->lProcessoSistema == 1 && isset($v01_processo) && $v01_processo != null) {      // PROCESSO INTERNO

    /**
     * se o processo for interno inserimos na tabela de ligação dividaprotprocesso
     */

    require_once("classes/db_dividaprotprocesso_classe.php");
    $oDaoDividaprotprocesso = new cl_dividaprotprocesso();
    $oDaoDividaprotprocesso->v88_divida       = $cldivida->v01_coddiv;
    $oDaoDividaprotprocesso->v88_protprocesso = $v01_processo;
    $oDaoDividaprotprocesso->incluir(null);

    if($oDaoDividaprotprocesso->erro_status == '0'){
      $oDaoDividaprotprocesso->erro_msg = $oDaoDividaprotprocesso->erro_msg;
      $sqlerro=true;
    }

  }


  if (isset($j01_matric)) {
    if ($j01_matric != "") {
      $clarrematric->k00_numpre = $numpre;
      $clarrematric->k00_matric = $j01_matric;
      $clarrematric->k00_perc   = 100;
      $clarrematric->incluir($numpre,$j01_matric);
      if($clarrematric->erro_status == '0'){
		    $cldivida->erro_msg = $clarrematric->erro_msg;
      	$sqlerro=true;
      }
      $cldivmatric->v01_coddiv = $cldivida->v01_coddiv;
      $cldivmatric->v01_matric = $j01_matric;
      $cldivmatric->incluir($cldivida->v01_coddiv);
      if($cldivmatric->erro_status == '0'){
		    $cldivida->erro_msg = $cldivmatric->erro_msg;
      	$sqlerro=true;
      }
    }
  }

  if (isset($q02_inscr)) {
    if ($q02_inscr != "") {
      $clarreinscr->k00_numpre=$numpre;
      $clarreinscr->k00_inscr=$q02_inscr;
      $clarreinscr->k00_perc   = 100;
      $clarreinscr->incluir($numpre,$q02_inscr);
      if($clarreinscr->erro_status == '0'){
		    $cldivida->erro_msg = $clarreinscr->erro_msg;
    	  $sqlerro=true;
      }
      $cldivinscr->v01_coddiv = $cldivida->v01_coddiv;
      $cldivinscr->v01_inscr = $q02_inscr;
      $cldivinscr->incluir($cldivida->v01_coddiv);
      if($cldivinscr->erro_status=='0'){
		    $cldivida->erro_msg = $cldivinscr->erro_msg;
      	$sqlerro=true;
      }
    }
  }


  if (!$sqlerro){
    $sqlGeraArrecad = "select fc_geraarrecad($k00_tipo,$numpre,true) as retorno";
    $result09       = db_query($sqlGeraArrecad) or die("Erro ao incluir em arrecad.");
    db_fieldsmemory($result09,0);
	}
  db_fim_transacao($sqlerro);

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
</head>
<body class="body-default">
  <div class="container">
  	<?php
  	  require_once("forms/db_frmdivida.php");
  	?>
  </div>
  <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<?php
if(isset($incluir)){
  if($cldivida->erro_status=="0"){
    $cldivida->erro(true,false);
    if($cldivida->erro_campo!=""){
      echo "<script> document.form1.".$cldivida->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldivida->erro_campo.".focus();</script>";
    }
  }else{
    $cldivida->erro(true,false);
    db_redireciona('div1_divida001.php');
  }
}
?>
