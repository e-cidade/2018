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
require_once("classes/db_itbinome_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_itbinomecgm_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);

$clitbinome    = new cl_itbinome;
$clitbinomecgm = new cl_itbinomecgm;
$sqlerro       = false;

if((isset($HTTP_POST_VARS["bt_opcao"]) && $HTTP_POST_VARS["bt_opcao"])=="Excluir"){

  $result = $clitbinomecgm->sql_record($clitbinomecgm->sql_query_file(null,"*",null," it21_itbinome  = $it03_seq "));
  if($clitbinomecgm->numrows > 0){

    $excluinomecgm = 't';
	  db_fieldsmemory($result,0);
  }

  db_inicio_transacao();

  if(isset($excluinomecgm) && $excluinomecgm == 't'){

  	$clitbinomecgm->it21_numcgm   = $it21_numcgm;
  	$clitbinomecgm->it21_itbinome = $it03_seq;
  	$clitbinomecgm->excluir($it21_sequencial);
	  if(isset($clitbinomecgm->erro_status) && $clitbinomecgm->erro_status == 0){

     $erro    = $clitbinomecgm->erro_msg;
     $sqlerro = true;
	   db_msgbox("itbinomecgm - ".$erro);
    }
  }

  $db_opcao = 3;
  $clitbinome->excluir($it03_seq);
  if(isset($clitbinome->erro_status) && $clitbinome->erro_status == 0){

    $erro    = $clitbinome->erro_msg;
    $sqlerro = true;
	  db_msgbox("itbinome - ".$erro);
  }

  db_fim_transacao($sqlerro);

  echo "<script>
          parent.iframe_compnome.location.href = 'itb1_itbinomecomp001.php?it03_guia=".$it03_guia."';
    		</script>";

}else if(isset($chavepesquisa)){

   $db_opcao = 3;
   $result = $clitbinome->sql_record($clitbinome->sql_query_file(null,"*",null," it03_guia = $chavepesquisa and it03_seq =  $chavepesquisa1"));
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
<script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <?php
      require_once("forms/db_frmitbinomecomp.php");
    ?>
  </div>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){

  if($clitbinome->erro_status=="0"){
    $clitbinome->erro(true,false);
  }else{

    $clitbinome->erro(true,false);
    echo "<script>
            parent.iframe_compnome.location.href = 'itb1_itbinomecomp001.php?it03_guia=".$it03_guia."';
          </script>";
  }
}

if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>