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
include("classes/db_taxa_classe.php");
include("dbforms/db_funcoes.php");
require_once("classes/db_favorecidotaxa_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);
$cltaxa           = new cl_taxa;
$clFavorecidoTaxa = new cl_favorecidotaxa;

$db_botao = false;
$db_opcao = 33;
if (isset($excluir)) {

  db_inicio_transacao();
  $db_opcao = 3;
  $clFavorecidoTaxa->excluir(null, "v87_taxa = {$ar36_sequencial}");
  $cltaxa->excluir($ar36_sequencial);
  db_fim_transacao();
} else if(isset($chavepesquisa)) {
   $db_opcao = 3;
   $result   = $cltaxa->sql_record($cltaxa->sql_query($chavepesquisa));
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
  <script language="JavaScript" type="text/javascript"src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript"src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript"src="scripts/numbers.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class='body-default' >
<div class ='container'>
  <?php
    include("forms/db_frmtaxa.php");
  ?>
</div>
</body>
</html>
<?
if(isset($excluir)){
  if($cltaxa->erro_status=="0"){
    $cltaxa->erro(true,false);
  }else{

    $cltaxa->erro(true,true);

  }

}

if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}

if (isset($chavepesquisa)) {

 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.favorecido.disabled = false;
         top.corpo.iframe_favorecido.location.href='arr1_taxaFavorecido001.php?ar36_sequencial=".@$chavepesquisa."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('favorecido');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";

}

?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>