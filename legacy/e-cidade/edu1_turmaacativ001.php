<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_turmaacativ_classe.php");
require_once ("classes/db_turmaacativnova_classe.php");
require_once ("classes/db_turmaacprof_classe.php");
require_once ("classes/db_censoativcompl_classe.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($_POST);
$oGet              = db_utils::postMemory($_GET);
$iCalendario       = $oGet->iCalendario;
$clturmaacativ     = new cl_turmaacativ;
$clturmaacativnova = new cl_turmaacativnova;
$clcensoativcompl  = new cl_censoativcompl;
$clturmaacprof     = new cl_turmaacprof;
$db_opcao          = 1;
$db_botao          = true;
if(isset($incluir)) {

  db_inicio_transacao();
  $clturmaacativ->incluir($ed267_i_codigo);
  if(trim($ed274_c_nome)!="") {

    $clturmaacativnova->ed274_i_turmaacativ = $clturmaacativ->ed267_i_codigo;
    $clturmaacativnova->incluir(null);
  }
  db_fim_transacao();
}
if ( isset($alterar) ) {

  $db_opcao = 2;
  db_inicio_transacao();
  $clturmaacativ->alterar($ed267_i_codigo);
  $sql2    = "SELECT ed274_i_codigo as conferenova FROM turmaacativnova WHERE ed274_i_turmaacativ = $ed267_i_codigo";
  $result2 = db_query($sql2);
  if (pg_num_rows($result2)>0) {

    db_fieldsmemory($result2,0);
    if (trim($ed274_c_nome)=="") {
      $clturmaacativnova->excluir($conferenova);
    } else {

      $clturmaacativnova->ed274_i_turmaacativ = $ed267_i_codigo;
      $clturmaacativnova->ed274_i_codigo = $conferenova;
      $clturmaacativnova->alterar($conferenova);
    }
  } else {

    if (trim($ed274_c_nome)!="") {
      $clturmaacativnova->ed274_i_turmaacativ = $clturmaacativ->ed267_i_codigo;
      $clturmaacativnova->incluir(null);
    }
  }
  db_fim_transacao();
}

if (isset($excluir)) {

  $db_opcao = 3;
  db_inicio_transacao();
  $clturmaacativnova->excluir(""," ed274_i_turmaacativ = $ed267_i_codigo");
  $clturmaacativ->excluir($ed267_i_codigo);
  db_fim_transacao();
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
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
     <br>
     <center>
     <fieldset style="width:95%"><legend><b>Atividades Complementares da Turma <?=@$ed268_c_descr?></b></legend>
        <?include("forms/db_frmturmaacativ.php");?>
     </fieldset>
     </center>
    </td>
   </tr>
  </table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed267_i_censoativcompl",true,1,"ed267_i_censoativcompl",true);
</script>
<?
if(isset($incluir)){
 if($clturmaacativ->erro_status=="0"){
  $clturmaacativ->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clturmaacativ->erro_campo!=""){
   echo "<script> document.form1.".$clturmaacativ->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clturmaacativ->erro_campo.".focus();</script>";
  }
 }else{
  $clturmaacativ->erro(true,false);
  db_redireciona("edu1_turmaacativ001.php?ed267_i_turmaac=$ed267_i_turmaac&ed268_c_descr=$ed268_c_descr&iCalendario={$iCalendario}");
 }
}
if(isset($alterar)){
 if($clturmaacativ->erro_status=="0"){
  $clturmaacativ->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clturmaacativ->erro_campo!=""){
   echo "<script> document.form1.".$clturmaacativ->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clturmaacativ->erro_campo.".focus();</script>";
  }
 }else{
  $clturmaacativ->erro(true,false);
  db_redireciona("edu1_turmaacativ001.php?ed267_i_turmaac=$ed267_i_turmaac&ed268_c_descr=$ed268_c_descr&iCalendario={$iCalendario}");
 }
}
if(isset($excluir)){
 if($clturmaacativ->erro_status=="0"){
  $clturmaacativ->erro(true,false);
 }else{
  $clturmaacativ->erro(true,false);
  db_redireciona("edu1_turmaacativ001.php?ed267_i_turmaac=$ed267_i_turmaac&ed268_c_descr=$ed268_c_descr&iCalendario={$iCalendario}");
 }
}
if(isset($cancelar)){
 db_redireciona("edu1_turmaacativ001.php?ed267_i_turmaac=$ed267_i_turmaac&ed268_c_descr=$ed268_c_descr&iCalendario={$iCalendario}");
}
?>