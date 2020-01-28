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
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clpropricemit = new cl_propricemit;
$clproprijazigo = new cl_proprijazigo;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){

  db_inicio_transacao();
  $db_opcao = 2;
  $clpropricemit->alterar($cm28_i_codigo);
  db_fim_transacao();
}else if(isset($chavepesquisa)){

   $db_opcao = 2;
   $result = $clpropricemit->sql_record($clpropricemit->sql_query($chavepesquisa));
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
<body class="abas">
  <div class="container">
     <?php
       include("forms/db_frmpropricemit.php");
     ?>
  </div>
</body>
</html>
<?
if(isset($alterar)){
  if($clpropricemit->erro_status=="0"){
    $clpropricemit->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clpropricemit->erro_campo!=""){
      echo "<script> document.form1.".$clpropricemit->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpropricemit->erro_campo.".focus();</script>";
    }
  }else{

    if( substr($cm25_c_tipo,0,1) == "J" ){
         $clproprijazigo->sql_record($clproprijazigo->sql_query("","*","","cm29_i_propricemit=".$clpropricemit->cm28_i_codigo));
         $x_opcao = "cem1_proprijazigo001.php?cm28_i_codigo=$clpropricemit->cm28_i_codigo&cm28_i_ossoariojazigo=$cm28_i_ossoariojazigo&cm28_i_proprietario=$cm28_i_proprietario&z01_nome=$z01_nome&chavepesquisa=$cm28_i_codigo";
         if( $clproprijazigo->numrows != 0 ){
             $x_opcao = "cem1_proprijazigo002.php?cm28_i_codigo=$clpropricemit->cm28_i_codigo&cm28_i_ossoariojazigo=$cm28_i_ossoariojazigo&cm28_i_proprietario=$cm28_i_proprietario&z01_nome=$z01_nome&chavepesquisa=$cm28_i_codigo";
         }
         ?>
         <script>
         parent.document.formaba.a2.disabled=false;
         top.corpo.iframe_a2.location.href='<?=$x_opcao?>';
         parent.mo_camada('a2');
         </script>
         <?
    }
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","cm28_i_processo",true,1,"cm28_i_processo",true);
</script>