<?
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
include("classes/db_lab_exameatributo_classe.php");
include("classes/db_lab_examedisp_classe.php");
include("classes/db_lab_atributo_componente_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cllab_exameatributo = new cl_lab_exameatributo;
$cllab_tributo_componente = new cl_lab_atributo_componente;
$cllab_examedisp = new cl_lab_examedisp;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){

  db_inicio_transacao();
  $cllab_exameatributo->incluir($la42_i_codigo);
  db_fim_transacao();
} else if(isset($alterar)){

 db_inicio_transacao();
 $cllab_exameatributo->alterar($la42_i_codigo);
 $aAtributos=explode("|",$sAtributos);
 $aValores=explode("|",$sValores);

 db_query("delete from lab_examedisp where la50_i_exameatributo=$la42_i_codigo");
 $cllab_examedisp->la50_i_exameatributo=$la42_i_codigo;
 for ($x = 0; $x < count($aAtributos); $x++) {

   if($aValores[$x]==1){

    $cllab_examedisp->la50_i_atributo=$aAtributos[$x];
    $cllab_examedisp->incluir(null);
  }
}
db_fim_transacao();
} if(isset($la42_i_exame)) {

	$sSql=$cllab_exameatributo->sql_query("","*",""," la42_i_exame=$la42_i_exame ");
	$rResult=$cllab_exameatributo->sql_record($sSql);
	if($cllab_exameatributo->numrows>0){

		db_fieldsmemory($rResult,0);
		$db_opcao = 2;

	}
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
<body class='abas' >
  <div class='container'>
    <?php
      include("forms/db_frmlab_exameatributo.php");
    ?>
  </div>
</body>

</html>
<?
if ( (isset($incluir)) || (isset($alterar)) ){
  if($cllab_exameatributo->erro_status=="0"){
    $cllab_exameatributo->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cllab_exameatributo->erro_campo!=""){
      echo "<script> document.form1.".$cllab_exameatributo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cllab_exameatributo->erro_campo.".focus();</script>";
    }
  }else{
    $cllab_exameatributo->erro(true,false);
    db_redireciona("lab1_lab_exameatributo001.php?la42_i_exame=$la42_i_exame&la08_c_descr=$la08_c_descr");
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
  js_tabulacaoforms("form1","la08_c_sigla",true,1,"la08_c_sigla",true);
</script>