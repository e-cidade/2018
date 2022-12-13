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
require_once("classes/db_fiscalproc_classe.php");
require_once("classes/db_fiscalprocpa_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if(!isset($abas)){

  echo "<script>location.href='fis1_fiscalproc005.php'</script>";
  exit;
}

db_postmemory($HTTP_POST_VARS);
$clfiscalproc   = new cl_fiscalproc;
$clfiscalprocpa = new cl_fiscalprocpa;

$db_opcao = 1;
$db_botao = true;
$sqlerro = false;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){

  db_inicio_transacao();
  $clfiscalproc->incluir($y29_codtipo);
  if (isset($y61_codtipo) && $y61_codtipo!=""){

    $codigo_gerado = $clfiscalproc->y29_codtipo;
    $clfiscalprocpa->y61_codtipo = $y61_codtipo;
    $clfiscalprocpa->incluir($codigo_gerado);
  }
  db_fim_transacao();
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
<body>
  <div class="container">
	<?php
	  include("forms/db_frmfiscalproc.php");
	?>
  </div>
</body>
</html>
<?php
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){

  if($clfiscalproc->erro_status=="0"){

    $clfiscalproc->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clfiscalproc->erro_campo!=""){

      echo "<script> document.form1.".$clfiscalproc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clfiscalproc->erro_campo.".focus();</script>";
    }
  }else{

    if($sqlerro == true){
      db_msgbox($erro);
    }else{

      $clfiscalproc->erro(true,false);
      echo "<script>parent.iframe_fiscalprocrec.location.href='fis1_fiscalprocrec001.php?y45_codtipo=".$clfiscalproc->y29_codtipo."';</script>";
      echo "<script>parent.mo_camada('fiscalprocrec');</script>";
      echo "<script>parent.document.formaba.fiscalprocrec.disabled=false;</script>";
      echo "<script>parent.iframe_fiscalproc.location.href='fis1_fiscalproc002.php?abas=1&chavepesquisa=".$clfiscalproc->y29_codtipo."';</script>";
    }
  }
}
?>