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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cltaxaserv        = new cl_taxaserv();
$clitenserv        = new cl_itenserv();
$cltxsepultamentos = new cl_txsepultamentos;
$clarreold         = new cl_arreold;
$clarrepaga        = new cl_arrepaga;
$clarrecad         = new cl_arrecad;
$clrotulo          = new rotulocampo();

$db_botao          = false;
$db_opcao          = 33;

if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;

  ///exclui do arrecad e inclui no arreold
  $sqlerro=false;
  $result11=$clarrecad->sql_record($clarrecad->sql_query_file("","*","","k00_numpre=$cm10_i_numpre"));
  $numrows11=$clarrecad->numrows;
  if($numrows11>0){
     $clarrecad->excluir_arrecad($cm10_i_numpre);
     if($clarrecad->erro_status=="0"){
       $sqlerro=true;
     }
  }

  //exclui o vinculo com o sepultamento
  $cltxsepultamentos->excluir(null,' cm31_i_itenserv = '.$cm10_i_codigo);
  if($cltxsepultamentos->erro_status=='0'){
    $sqlerro=true;
  }

  //exclui a taxa do cemiterio
  $clitenserv->excluir($cm10_i_codigo);
  if($clitenserv->erro_status=='0'){
     $sqlerro=true;
  }
  db_fim_transacao($sqlerro);

}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $clitenserv->sql_record($clitenserv->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);

   $result10=$clarrepaga->sql_record($clarrepaga->sql_query_file("","*","","k00_numpre=$cm10_i_numpre"));
   if($clarrepaga->numrows>0){
     $foipago="ok";
     $db_botao = false;
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
<body class="body-default">
  <div class="container">
     <?php
      require_once(modification("forms/db_frmitenserv.php"));
     ?>
  </div>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?php
if(isset($foipago) && $foipago=="ok"){
  db_msgbox("Débito com parcela paga. Exclusão não permitida.");
}

if(isset($excluir)){
  if($clitenserv->erro_status=="0"){
    $clitenserv->erro(true,false);
  }else{
    $clitenserv->erro(true,true);
  }
}

if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>