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

$clitbinomecgm = new cl_itbinomecgm;
$clitbinome    = new cl_itbinome;

// SELECT PRA BUSCAR OS DADOS
$sqlerro = false;

if((isset($HTTP_POST_VARS["bt_opcao"]) && $HTTP_POST_VARS["bt_opcao"])=="Alterar"){

  $result = $clitbinome->sql_record($clitbinome->sql_query_file(null,"*",null," it03_guia = $chavepesquisa and it03_seq !=  $chavepesquisa1 and it03_princ = 't' and upper(it03_tipo) = 'T'"));
  if($clitbinome->numrows > 0 && $it03_princ == 't'){

  	db_msgbox("Já existe um transmitente principal!");
    $sqlerro = true;
  }

  db_inicio_transacao();
  $db_opcao = 2;
  $clitbinome->alterar($it03_seq);
  if(isset($clitbinome->erro_status) && $clitbinome->erro_status == 0){

    $erro = $clitbinome->erro_msg;
    $sqlerro = true;
	  db_msgbox("itbinome".$erro);
  }
  if(isset($it21_numcgm) && $it21_numcgm != ""){

  	$clitbinomecgm->it21_numcgm   = $it21_numcgm;
  	$clitbinomecgm->it21_itbinome = $it03_seq;
  	$clitbinomecgm->alterar($it21_sequencial);
  	if(isset($clitbinomecgm->erro_status) && $clitbinomecgm->erro_status == 0){

      $erro = $clitbinomecgm->erro_msg;
      $sqlerro = true;
  	  db_msgbox("itbinomecgm".$erro);
    }
  }
  db_fim_transacao($sqlerro);
  echo "<script>
          parent.iframe_transm.location.href = 'itb1_itbinome001.php?it03_guia=".$it03_guia."';
   	    </script>";
}else if(isset($chavepesquisa)){

   $db_opcao = 2;
   $result   = $clitbinome->sql_record($clitbinome->sql_query_file(null,"*",null," it03_guia = $chavepesquisa and it03_seq =  $chavepesquisa1"));
   db_fieldsmemory($result,0);
   if( strtoupper($it03_sexo) == 'M' ){
    $aOptionsSexo = array('m'=>'Masculino');
   }else{
    $aOptionsSexo = array('f'=>'Feminino');
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
<body class="body-default">
  <div class="container">
    <?php
      require_once("forms/db_frmitbinome.php");
    ?>
  </div>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){

  if($clitbinome->erro_status=="0"){

    $clitbinome->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clitbinome->erro_campo!=""){

      echo "<script> document.form1.".$clitbinome->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clitbinome->erro_campo.".focus();</script>";
    }
  }else{

    $clitbinome->erro(true,false);
    echo "<script>
            parent.iframe_compnome.location.href = 'itb1_itbinome001.php?it03_guia=".$it03_guia."';
          </script>";
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>