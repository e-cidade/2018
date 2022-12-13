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
require_once("classes/db_averbacgm_classe.php");
require_once("classes/db_averbacgmold_classe.php");
require_once("classes/db_averbacao_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_propri_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$claverbacgm    = new cl_averbacgm;
$claverbacgmold = new cl_averbacgmold;
$claverbacao    = new cl_averbacao;
$cliptubase     = new cl_iptubase;
$clpropri       = new cl_propri;

$db_opcao = 22;
$db_botao = false;
$sqlerro  = false;

if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();

	$sql_tipo =  "select j93_regra ,j75_tipo,j75_matric from averbacao inner join averbatipo on j75_tipo = j93_codigo where averbacao.j75_codigo = $j76_averbacao";
	$result_tipo =  db_query($sql_tipo);
	db_fieldsmemory($result_tipo,0);

	  $claverbacgm->j76_tipo = $j93_regra;
    $claverbacgm->incluir($j76_codigo);
    $erro_msg = $claverbacgm->erro_msg;

    if($claverbacgm->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $claverbacgm->alterar($j76_codigo);
    $erro_msg = $claverbacgm->erro_msg;
    if($claverbacgm->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $claverbacgm->excluir($j76_codigo);
    $erro_msg = $claverbacgm->erro_msg;
    if($claverbacgm->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){

   $result = $claverbacgm->sql_record($claverbacgm->sql_query($j76_codigo));
   if($result!=false && $claverbacgm->numrows>0){
     db_fieldsmemory($result,0);
   }

}

	 $sqlVerItbi = "select j75_regra,j75_codigo,j103_itbi from averbacao
									inner join averbatipo     on j93_codigo      = j75_tipo
									inner join averbaguia     on j75_codigo      = j104_averbacao
									inner join averbaguiaitbi on j103_averbaguia = j104_sequencial
									left join averbacgm       on j75_codigo      = j76_averbacao
									where j75_codigo = {$j76_averbacao}
									  and j76_codigo is null ";

	 $rsVerItbi     = db_query($sqlVerItbi);
   $linhasVerItbi = pg_num_rows($rsVerItbi);
	 if($linhasVerItbi > 0){
	 	 // é uma averbação do tipo 6 de itbi com guia do sistema.
		 // então grava automatico os cgms dos compradores desta guia itbi. j104_guia

		 db_fieldsmemory($rsVerItbi,0);

		 $sqlNome = "select it03_princ,it03_nome,it21_numcgm
		             from itbinome
			 					 inner join itbinomecgm on it21_itbinome=it03_seq
				 				 where upper(it03_tipo) = 'C'
								   and it03_guia = {$j103_itbi}";

		 $rsNome     =	db_query($sqlNome);
		 $linhasNome = pg_num_rows($rsNome);
		 if($linhasNome > 0){
		 	$sqlerro = false;
		 	db_inicio_transacao();
		 	 for($i=0;$i<$linhasNome;$i++){
				 db_fieldsmemory($rsNome,$i);
					// incluir na averbacgm.

          if($it03_princ == "t"){
          	$it03_princ = "true";
          }else{
          	$it03_princ = "false";
          }
					$claverbacgm->j76_averbacao  = $j75_codigo;
					$claverbacgm->j76_numcgm     = $it21_numcgm;
					$claverbacgm->j76_tipo       = $j75_regra;
					$claverbacgm->j76_principal  = $it03_princ;
					$claverbacgm->incluir(null);

          if($claverbacgm->erro_status==0){
          	$erro_msg = $claverbacgm->erro_msg;
            $sqlerro=true;
						db_msgbox($erro_msg);
          }

		   }

			 db_fim_transacao($sqlerro);
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include("forms/db_frmaverbacgm.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($claverbacgm->erro_campo!=""){
        echo "<script> document.form1.".$claverbacgm->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$claverbacgm->erro_campo.".focus();</script>";
    }
}
?>