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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);
$clpropricemit = new cl_propricemit;
$cllotecemit = new cl_lotecemit;
if( isset( $db_opcao ) ){
    echo "<script>";
    echo " location.href='cem1_propricemit001.php';";
    echo "</script>";
}
$db_opcao = 1;

$db_botao = true;
if(isset($incluir)){
       db_inicio_transacao();
       $clpropricemit->incluir(null);
       db_fim_transacao();
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
      include(modification("forms/db_frmpropricemit.php"));
    ?>
  </div>
</body>
</html>
<script>
js_tabulacaoforms("form1","cm28_i_processo",true,1,"cm28_i_processo",true);
</script>
<?
if(isset($incluir)){
  if($clpropricemit->erro_status=="0"){
    $clpropricemit->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clpropricemit->erro_campo!=""){
      echo "<script> document.form1.".$clpropricemit->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpropricemit->erro_campo.".focus();</script>";
    }
  }else{
    db_msgbox($clpropricemit->erro_sql);
    if( substr($cm25_c_tipo,0,1) == "O" ){
         echo "<script>";
         echo " parent.document.formaba.a3.disabled=false; ";
         echo " (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.location.href='cem1_itenserv111.php?cm28_i_ossoariojazigo=$cm28_i_ossoariojazigo&cm28_i_proprietario=$cm28_i_proprietario&z01_nome=$z01_nome';";
         echo " parent.mo_camada('a3'); ";
         echo "</script>";
    }else{
         echo "<script>";
         echo " parent.document.formaba.a2.disabled=false; ";
         echo " (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href='cem1_proprijazigo00".$db_opcao.".php?cm28_i_codigo=".$clpropricemit->cm28_i_codigo."&cm28_i_ossoariojazigo=$cm28_i_ossoariojazigo&cm28_i_proprietario=$cm28_i_proprietario&z01_nome=$z01_nome';";
         echo " parent.mo_camada('a2'); ";
         echo "</script>";

    }
  }
}
?>