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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_autoandam_classe.php");
require_once("classes/db_autoultandam_classe.php");
require_once("classes/db_fandam_classe.php");
require_once("classes/db_fiscalprocrec_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clautotipo      = new cl_autotipo;
$clautoandam     = new cl_autoandam;
$clautoultandam  = new cl_autoultandam;
$clfandam        = new cl_fandam;
$clfiscalprocrec = new cl_fiscalprocrec;
$db_opcao = 22;
$db_botao = false;
global $y39_codandam;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){

  try {

    db_inicio_transacao();
    $db_opcao = 2;
    $clautotipo->excluir(null,"y59_codauto=$y59_codauto and y59_codtipo=$y59_codtipo_old");
    if ( strpos(trim($y59_valor),',') != "" ){

	    $y59_valor=str_replace('.','',$y59_valor);
	    $y59_valor=str_replace(',','.',$y59_valor);
    }
    $clautotipo->y59_valor = "$y59_valor";
    $clautotipo->y59_codauto=$y59_codauto;
    $clautotipo->y59_codtipo=$y59_codtipo;
    $clautotipo->incluir(null);
    db_fim_transacao();

  } catch (Exception $oErro) {

    db_fim_transacao(true);
    $clautotipo->erro_status = 0;
    $clautotipo->erro_msg    = $oErro->getMessage();
  }
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
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
<?php
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  if($clautotipo->erro_status=="0"){
    $clautotipo->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clautotipo->erro_campo!=""){
      echo "<script> document.form1.".$clautotipo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clautotipo->erro_campo.".focus();</script>";
    }else{
      echo "<script>parent.iframe_autotipo.location.href='fis1_autotipo001.php?y59_codauto=".$y59_codauto."&abas=1';</script>\n";
      echo "<script>parent.iframe_fiscais.location.href='fis1_autousu001.php?y59_codauto=".$y59_codauto."&y39_codandam=".$y39_codandam."&abas=1';</script>\n";
    }
  }else{
    $clautotipo->erro(true,false);
    echo "<script>parent.iframe_autotipo.location.href='fis1_autotipo001.php?y59_codauto=".$y59_codauto."&abas=1';</script>\n";
    echo "<script>parent.iframe_receitas.location.href='fis1_autorec001.php?y59_codauto=".$y59_codauto."&abas=1';</script>\n";
    echo "<script>parent.iframe_fiscais.location.href='fis1_autousu001.php?y59_codauto=".$y59_codauto."&y39_codandam=".$y39_codandam."&abas=1';</script>\n";
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>