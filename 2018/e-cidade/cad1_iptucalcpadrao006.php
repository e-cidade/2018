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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

$cliptucalcpadrao = new cl_iptucalcpadrao;

db_postmemory($HTTP_POST_VARS);

$db_opcao = 33;
$db_botao = false;
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $cliptucalcpadraoconstr->j11_sequencial=$j10_sequencial;
  $cliptucalcpadraoconstr->excluir($j10_sequencial);

  if($cliptucalcpadraoconstr->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $cliptucalcpadraoconstr->erro_msg;
  $cliptutaxamatric->j09_iptutaxamatric=$j10_sequencial;
  $cliptutaxamatric->excluir($j10_sequencial);

  if($cliptutaxamatric->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $cliptutaxamatric->erro_msg;
  $cliptucalcpadrao->excluir($j10_sequencial);
  if($cliptucalcpadrao->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $cliptucalcpadrao->erro_msg;
  db_fim_transacao($sqlerro);
   $db_opcao = 3;
   $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $cliptucalcpadrao->sql_record($cliptucalcpadrao->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
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
<body class="abas">
  <div class="container">
    <?php
      include("forms/db_frmiptucalcpadrao.php");
    ?>
  </div>
</body>
</html>
<?
if(isset($excluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($cliptucalcpadrao->erro_campo!=""){
      echo "<script> document.form1.".$cliptucalcpadrao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cliptucalcpadrao->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='cad1_iptucalcpadrao003.php';
    }\n
    js_db_tranca();
  </script>\n
 ";
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.iptucalcpadraoconstr.disabled=false;
         top.corpo.iframe_iptucalcpadraoconstr.location.href='cad1_iptucalcpadraoconstr001.php?db_opcaoal=33&j11_sequencial=".@$j10_sequencial."';
         parent.document.formaba.iptutaxamatric.disabled=false;
         top.corpo.iframe_iptutaxamatric.location.href='cad1_iptutaxamatric001.php?db_opcaoal=33&j09_iptutaxamatric=".@$j10_sequencial."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('iptucalcpadraoconstr');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>