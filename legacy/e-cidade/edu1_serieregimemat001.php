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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_serieregimemat_classe.php");
require_once ("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clserieregimemat = new cl_serieregimemat;
$db_opcao         = 1;
$db_botao         = true;
$escola           = db_getsession("DB_coddepto");
$clserieregimemat->pagina_retorno = "edu1_serieregimemat001.php?ed223_i_serie=$ed223_i_serie&ed11_c_descr=$ed11_c_descr";
if (isset($incluir)) {

 db_inicio_transacao();
 $clserieregimemat->ed223_i_ordenacao = 1;
 $clserieregimemat->incluir($ed223_i_codigo);
 db_fim_transacao();
}
if (isset($alterar)) {

 $db_opcao = 2;
 db_inicio_transacao();
 $clserieregimemat->alterar($ed223_i_codigo);
 db_fim_transacao();
}
if (isset($excluir)) {
 $db_opcao = 3;
 db_inicio_transacao();
 $sql = "select * from basemps
         inner join base on ed34_i_base = ed31_i_codigo
         inner join escolabase on ed77_i_base = ed31_i_codigo
         where ed34_i_serie = $ed223_i_serie
         and ed31_i_regimemat = $ed223_i_regimemat
         and ed77_i_escola = $escola";
 $result= db_query($sql);
 $linhas = pg_num_rows($result);
 if($linhas>0){
 	db_msgbox("Registro não pode ser excluído pois já esta vinculado a uma base curricular");
 	db_redireciona("edu1_serieregimemat001.php?ed223_i_serie=$ed223_i_serie&ed11_c_descr=$ed11_c_descr");
 }else{
   $clserieregimemat->excluir($ed223_i_codigo);
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Vincular Etapa ao Regime de Matrícula</b></legend>
    <?include("forms/db_frmserieregimemat.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed223_i_regimemat",true,1,"ed223_i_regimemat",true);


function redirecionaVinculoEtapaCenso(iEtapa, sDescricao) {

  var sParametros = '?iEtapa=' + iEtapa + '&sEtapa='+sDescricao;
  top.corpo.iframe_a3.location.href = 'edu1_vinculoserieetapacenso001.php' + sParametros;
  parent.mo_camada("a3");
}

</script>
<?
if (isset($incluir)) {

  if ($clserieregimemat->erro_status=="0") {

    $clserieregimemat->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clserieregimemat->erro_campo != "") {

     echo "<script> document.form1.".$clserieregimemat->erro_campo.".style.backgroundColor='#99A9AE';</script>";
     echo "<script> document.form1.".$clserieregimemat->erro_campo.".focus();</script>";
    }
  } else {

    db_msgbox( str_replace("\\n", "\n",$clserieregimemat->erro_msg) );
    echo "<script> redirecionaVinculoEtapaCenso($ed223_i_serie, '$ed11_c_descr')</script>";
  }
}
if (isset($alterar)) {

  if ( $clserieregimemat->erro_status == "0") {

    $clserieregimemat->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clserieregimemat->erro_campo!=""){
     echo "<script> document.form1.".$clserieregimemat->erro_campo.".style.backgroundColor='#99A9AE';</script>";
     echo "<script> document.form1.".$clserieregimemat->erro_campo.".focus();</script>";
    }
  } else {

    db_msgbox( str_replace("\\n", "\n",$clserieregimemat->erro_msg) );
    echo "<script> redirecionaVinculoEtapaCenso($ed223_i_serie, '$ed11_c_descr')</script>";
    $clserieregimemat->erro(true,true);
  }
}
if(isset($excluir)){
 if($clserieregimemat->erro_status=="0"){
  $clserieregimemat->erro(true,false);
 }else{
  $clserieregimemat->erro(true,true);
 }
}
if(isset($cancelar)){
 echo "<script>location.href='".$clserieregimemat->pagina_retorno."'</script>";
}

?>

