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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_constrescr_classe.php");
require_once("classes/db_constrcar_classe.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$db_botao     = 1;
$db_opcaoid   = 1;
$db_opcao     = 1;
$testasel     = false;
$clconstrescr = new cl_constrescr;
$cliptubase   = new cl_iptubase;
$clconstrcar  = new cl_constrcar;
$clconstrescr->rotulo->label();
$clconstrescr->rotulo->tlabel();

$clrotulo = new rotulocampo;
$clrotulo->label("j14_nome");
$clrotulo->label("z01_nome");

$j52_dtlan_dia = date("d");
$j52_dtlan_mes = date("m");
$j52_dtlan_ano = date("Y");

if(isset($alterando)){
  $j52_matric = $j01_matric;
  $result = $cliptubase->sql_record($cliptubase->sql_query($j52_matric,"z01_nome",""));
  @db_fieldsmemory($result,0);
}

if(isset($j52_idcons)&&$j52_idcons=="nova"){
   $result = $cliptubase->sql_record($cliptubase->sql_query($j52_matric,"z01_nome",""));
   @db_fieldsmemory($result,0);
   $j52_idcons="";
}else if(isset($incluir)){
   db_inicio_transacao();
   if($j52_idcons==0){
     $result = $clconstrescr->sql_record($clconstrescr->sql_query_file($j52_matric,"",'max(j52_idcons) as j52_idcons'));
     if($clconstrescr->numrows>0){
       db_fieldsmemory($result,0);
     }else{
       $j52_idcons = 0;
     }
     $j52_idcons = $j52_idcons + 1;
   }
   $clconstrescr->incluir($j52_matric,$j52_idcons);
   $matriz= split("X",$caracteristica);
   for($i=0;$i<sizeof($matriz);$i++){
     $j53_caract = $matriz[$i];
     if($j53_caract!=""){
       $clconstrcar->incluir($j52_matric,$j52_idcons,$j53_caract);
     }
   }
  db_fim_transacao();
  $db_botao=1;
}else if(isset($alterar)){
  db_inicio_transacao();

  $result = $clconstrcar->sql_record($clconstrcar->sql_query_file($j52_matric,$j52_idcons));
  $xx=$clconstrcar->numrows;
  for($i=0; $i<$xx; $i++){
    db_fieldsmemory($result,$i);
    $clconstrcar->j53_matric = $j53_matric;
    $clconstrcar->j53_idcons = $j53_idcons;
    $clconstrcar->j53_caract = $j53_caract;
    $clconstrcar->excluir($j53_matric,$j53_idcons,$j53_caract);

  }


  $clconstrescr->alterar($j52_matric,$j52_idcons);

  $matriz= split("X",$caracteristica);
  for($i=0;$i<sizeof($matriz);$i++){
    $j53_caract = $matriz[$i];
    if($j53_caract!=""){
      $clconstrcar->incluir($j52_matric,$j52_idcons,$j53_caract);
    }
  }
  db_fim_transacao();
 $db_botao=2;
}else if(isset($j52_matric)&&isset($j52_idcons)){
  $result = $clconstrescr->sql_record($clconstrescr->sql_query($j52_matric,$j52_idcons,"*","",""));
  if($clconstrescr->numrows!=0){
    $db_opcaoid=3;
    $db_botao=2;
    db_fieldsmemory($result,0);
    $result = $clconstrcar->sql_record($clconstrcar->sql_query($j52_matric,$j52_idcons,"","*"));
    $caracteristica = null;
    $car="X";
    for($i=0; $i<$clconstrcar->numrows; $i++){
      db_fieldsmemory($result,$i);
      $caracteristica .= $car.$j53_caract ;
      $car="X";
    }
    $caracteristica .= $car;
  }else{
     $result = $cliptubase->sql_record($cliptubase->sql_query($j52_matric,"z01_nome",""));
     @db_fieldsmemory($result,0);
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
td {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
input {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
  height: 17px;
  border: 1px solid #999999;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()">

<br /><br />

<table height="430" align="center" width="790" border="0" cellspacing="0" cellpadding="0">
<form name="form1" method="post" onSubmit="return js_verifica_campos_digitados();" action="">
  <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
      <center>
      <?
        include("forms/db_frmconstrescralt.php");
        flush();
      ?>
      </center>
    </td>
  </tr>
</form>
</table>
</body>
</html>
<script>
function js_colocaid2(){
  document.form1.id_setor.value=parent.document.form1.idsetor.value;
  document.form1.id_quadra.value=parent.document.form1.idquadra.value;
}
js_colocaid2();
</script>


<?
if(isset($incluir)||(isset($alterar))){
  if($clconstrescr->erro_status=="0"){
    $clconstrescr->erro(true,false);
    if($clconstrescr->erro_campo!=""){
      echo "<script> document.form1.".$clconstrescr->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clconstrescr->erro_campo.".focus();</script>";
    }
  }else{
     $clconstrescr->erro(true,false);
      db_redireciona("cad1_constrescralt.php?id_setor=$id_setor&id_quadra=$id_quadra&j52_matric=$j52_matric&j52_idcons=nova");

  }
}
?>