<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("classes/db_lotecemit_classe.php");
require_once("classes/db_propricemit_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clpropricemit  = new cl_propricemit;
$cllotecemit    = new cl_lotecemit;
$clproprijazigo = new cl_proprijazigo;

$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){

  db_inicio_transacao();
  $db_opcao = 3;

  $cllotecemit->cm23_i_codigo = $cm25_i_lotecemit;
  $cllotecemit->cm23_c_situacao = 'D';
  $cllotecemit->alterar( $cm25_i_lotecemit );

  $clproprijazigo->excluir( null, ' cm29_i_propricemit = ' . $cm28_i_codigo );

  $clpropricemit->excluir($cm28_i_codigo);
  db_fim_transacao();

}else if(isset($chavepesquisa)){

   $db_opcao = 3;
   if(file_exists("funcoes/db_func_propricemit.php")==true){
      include("funcoes/db_func_propricemit.php");
   }else{
      $campos = "propricemit.*";
   }
   $result = $clpropricemit->sql_record($clpropricemit->sql_query($chavepesquisa,$campos));
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
<body class="body-default" >
  <div class="container">
     <?php
     include("forms/db_frmpropricemit.php");
     ?>
  </div>
</body>
</html>
<?
if(isset($excluir)){
  if($clpropricemit->erro_status=="0"){
    $clpropricemit->erro(true,false);
  }else{
    $clpropricemit->erro(true,true);
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>