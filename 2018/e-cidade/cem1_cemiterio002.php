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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_cemiterio_classe.php");
require_once("classes/db_cemiteriocgm_classe.php");
require_once("classes/db_cemiteriorural_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clcemiterio      = new cl_cemiterio;
$clcemiteriocgm   = new cl_cemiteriocgm;
$clcemiteriorural = new cl_cemiteriorural;
$db_opcao         = 2;
$db_botao         = true;

if(isset($alterar)){

  db_inicio_transacao();

  if($tp == 1){

	  $clcemiteriocgm->cm15_i_cgm = $cm15_i_cgm;
    $clcemiteriocgm->cm15_i_cemiterio = $cm15_i_codigo;
	  $clcemiteriocgm->alterar($cm15_i_cemiterio);

    $erro = $clcemiteriocgm->erro_status;

  }else if($tp == 2){

    $clcemiteriorural->cm16_c_nome     = $cm16_c_nome;
    $clcemiteriorural->cm16_c_endereco = $cm16_c_endereco;
    $clcemiteriorural->cm16_c_cidade   = $cm16_c_cidade;
    $clcemiteriorural->cm16_c_bairro   = $cm16_c_bairro;
	  $clcemiteriorural->cm16_c_cep      = $cm16_c_cep;
	  $clcemiteriorural->cm16_c_telefone = $cm16_c_telefone;
  	$clcemiteriorural->alterar($cm16_i_cemiterio);

    $erro = $clcemiteriorural->erro_status;
  }

  if($erro == 1){
   $erro = 0;
  }else{
   $erro = 1;
  }
  db_fim_transacao($erro);

}else if(isset($chavepesquisa)){

   $db_opcao = 2;
   if($tp == 2){

    $result = $clcemiteriorural->sql_record($clcemiteriorural->sql_query($chavepesquisa));
    db_fieldsmemory($result,0);
   }else{

    $result = $clcemiteriocgm->sql_record($clcemiteriocgm->sql_query($chavepesquisa));
    db_fieldsmemory($result,0);
   }
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
<body class="body-default">
<table width="790" align="center"border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
     <form>
      <table>
       <tr>
         <td><strong>Tipo de Cemitério:</strong></td>
       </tr>
       <tr>
        <td>
		  <?
			$x = array('0'=>'Selecione','1'=>'Urbano','2'=>'Rural');
			db_select('tp',$x,true,$db_opcao,"onchange='submit()'");
		  ?>
        </td>
       </tr>
      </table>
     </form>
     <?
      if(@$tp == 1){
       include("forms/db_frmcemiteriocgm.php");
      }else if(@$tp == 2){
       include("forms/db_frmcemiteriorural.php");
      }
     ?>
    </center>
     </td>
  </tr>
</table>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
 if($tp == 1){
  if($clcemiteriocgm->erro_status=="0"){
    $clcemiteriocgm->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcemiteriocgm->erro_campo!=""){
      echo "<script> document.form1.".$clcemiteriocgm->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcemiteriocgm->erro_campo.".focus();</script>";
    }
  }else{
    $clcemiteriocgm->erro(true,true);
  }
 }else if($tp == 2){
  if($clcemiteriorural->erro_status=="0"){
   $clcemiteriorural->erro(true,false);
   $db_botao=true;
   echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
   if($clcemiteriorural->erro_campo!=""){
     echo "<script> document.form1.".$clcemiteriorural->erro_campo.".style.backgroundColor='#99A9AE';</script>";
     echo "<script> document.form1.".$clcemiteriorural->erro_campo.".focus();</script>";
   }
  }else{
   $clcemiteriorural->erro(true,true);
  }
 }
}
?>