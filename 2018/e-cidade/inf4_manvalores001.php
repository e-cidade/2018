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
require_once("classes/db_infla_classe.php");
require_once("classes/db_inflan_classe.php");
require_once("dbforms/db_funcoes.php");

$tod = 'f';
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clinfla  = new cl_infla;
$clinflan = new cl_inflan;

$db_opcao   = 22;
$db_botao   = false;
$sqlerro    = false;
$wheretodos = " 1 = 1 ";

if($tod == 't'){

    if (isset($i01_dm) && $i01_dm == 1){

			if(isset($mesini) && $mesini != "" ){

						 $wheretodos .= " and infla.i02_codigo = '".$i02_codigo."'
							    						and extract(month from i02_data) = $mesini
		          		  					and extract(year from i02_data)  = $exercicio ";
			}
		}else{

				 if(isset($exercicio) && $exercicio != ""){

							 $wheretodos .= " and infla.i02_codigo = '".$i02_codigo."'
							                  and extract(year from i02_data) = $exercicio
																and extract(month from i02_data) >= $mesini";
				 }
		}

    $rsTodos = $clinfla->sql_record($clinfla->sql_query_file($i02_codigo,"","*","",$wheretodos));
    $numrows = $clinfla->numrows;

    if($numrows > 0){
		db_inicio_transacao();
		for($i=0;$i < $numrows;$i++){
      db_fieldsmemory($rsTodos,$i);
			if($sqlerro==false){
         $clinfla->i02_data   = $i02_data;
         $clinfla->i02_codigo = $i02_codigo;
         $clinfla->excluir($i02_codigo,$i02_data);
         $erro_msg = $clinfla->erro_msg;
			   if($clinfla->erro_status==0){
				    $sqlerro=true;
            $msgerro = $clinfla->erro_msg;
            db_msgbox($msgerro);
			   }
			}
			if($sqlerro==false){
    		  $clinfla->i02_valor  = $valortodos;
          $clinfla->i02_data   = $i02_data;
          $clinfla->i02_codigo = $i02_codigo;
		   	  $clinfla->incluir($i02_codigo,$i02_data);
			    $erro_msg = $clinfla->erro_msg;
			    if($clinfla->erro_status==0){
			      	$sqlerro=true;
              $msgerro = $clinfla->erro_msg;
              db_msgbox($msgerro);
  			  }
			}
		}
		db_fim_transacao($sqlerro);
    }

}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clinfla->incluir($i02_codigo,$i02_data);
    $erro_msg = $clinfla->erro_msg;
    if($clinfla->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar) && $tod != 't' ){
  if($sqlerro==false){
    db_inicio_transacao();

    $clinfla->alterar($i02_codigo,$i02_data);
    $erro_msg = $clinfla->erro_msg;
    if($clinfla->erro_status==0){

      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clinfla->excluir($i02_codigo,$i02_data);
    $erro_msg = $clinfla->erro_msg;
    if($clinfla->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($i02_codigo)){
   $result = $clinflan->sql_record($clinflan->sql_query($i02_codigo, "*", null, ""));
   if($result!=false && $clinflan->numrows>0){
     db_fieldsmemory($result, 0);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width=100% border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include("forms/db_frminfla.php");
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
<script>
    <?
	    if(!isset($alterar) && !isset($excluir) && !isset($incluir) && !isset($opcao) && !isset($i02_codigo) && !isset($mes)){
        	echo "js_pesquisai02_codigo(true);";
		}
	?>
</script>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clinfla->erro_campo!=""){
        echo "<script> document.form1.".$clinfla->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clinfla->erro_campo.".focus();</script>";
    }
}
?>