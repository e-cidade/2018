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
require_once("classes/db_autorec_classe.php");
require_once("classes/db_fiscalprocrec_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clautorec       = new cl_autorec;
$clfiscalprocrec = new cl_fiscalprocrec;
$db_opcao = 1;
$db_botao = true;
global $y59_codauto;

if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){

  db_inicio_transacao();
  $clautorec->incluir($y57_codauto,$y57_receit);
  db_fim_transacao();
}
if(isset($y59_codauto) && $y59_codauto != ""){
  $result = $clautorec->sql_record($clautorec->sql_query_file($y59_codauto));
  if($clautorec->numrows == 0){
    $result = $clfiscalprocrec->sql_record($clfiscalprocrec->sql_query_autotipo("",""," *",""," y59_codauto = $y59_codauto"));
    if($clfiscalprocrec->numrows > 0){
      db_inicio_transacao();
      $numrows = $clfiscalprocrec->numrows;
      for($i=0;$i<$numrows;$i++){
	db_fieldsmemory($result,$i);
	$clautorec->y57_valor = $y45_valor;
	$clautorec->y57_descr = $y45_descr;
	$clautorec->incluir($y59_codauto,$y45_receit);
      }
      db_fim_transacao();
    }
  }
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
	include("forms/db_frmautorec.php");
	?>
</div>
</body>
</html>
<?php
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if($clautorec->erro_status=="0"){
    $clautorec->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clautorec->erro_campo!=""){
      echo "<script> document.form1.".$clautorec->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clautorec->erro_campo.".focus();</script>";
    };
  }else{
    $clautorec->erro(true,false);
    echo "<script>parent.iframe_receitas.location.href='fis1_autorec001.php?y59_codauto=$y59_codauto';</script>";
  };
};
?>