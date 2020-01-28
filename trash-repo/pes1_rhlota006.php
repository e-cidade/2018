<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("classes/db_rhlota_classe.php");
require_once("classes/db_orcorgao_classe.php");
require_once("classes/db_orcunidade_classe.php");
require_once("classes/db_rhlotaexe_classe.php");
require_once("classes/db_rhlotacalend_classe.php");
require_once("classes/db_cfpess_classe.php");
require_once("classes/db_rhlotavinc_classe.php");

require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);

$clrhlota       = new cl_rhlota;
$clrhlotaexe    = new cl_rhlotaexe;
$clcfpess       = new cl_cfpess;
$clrhlotacalend = new cl_rhlotacalend;
$cldb_estrut    = new cl_db_estrut;
$clorcorgao     = new cl_orcorgao;
$clorcunidade   = new cl_orcunidade;
$oDaoRhlotavinc = new cl_rhlotavinc;

if(isset($r70_codigo)){

  $db_opcao = 3;
  $db_botao = true;

} else {

  $db_opcao = 33;
  $db_botao = false;
}

if ( isset($excluir) ) {

  $sqlerro=false;
  $anofolha = db_anofolha();
  $mesfolha = db_mesfolha();
  db_inicio_transacao();

  $result = $clcfpess->sql_record($clcfpess->sql_query_file($anofolha,$mesfolha,db_getsession("DB_instit"),"r11_codestrut"));
  if($clcfpess->numrows>0){
    db_fieldsmemory($result,0);
  }

  if($sqlerro==false){
    $clrhlotaexe->excluir(db_getsession("DB_anousu"),$r70_codigo);
    if($clrhlotaexe->erro_status==0){
      $erro_msg = $clrhlotaexe->erro_msg;
      $sqlerro=true;
    }
  }

  if($sqlerro==false){
    $clrhlotacalend->excluir($r70_codigo);
    $erro_msg = $clrhlotacalend->erro_msg;
    if($clrhlotacalend->erro_status==0){
      $sqlerro=true;
    }
  } 

	/**
	 * Exclui da tabela rhlotavinc
	 */	 
	if ( !$sqlerro ) {

		$oDaoRhlotavinc->excluir(null, "rh25_codigo = {$r70_codigo}");

    if ( $oDaoRhlotavinc->erro_status == 0 ) {

      $sqlerro  = true;
      $erro_msg = $oDaoRhlotavinc->erro_msg;
    }
	}

	/**
	 * Excluir da tabela rhlota
	 */	 
	if ( $sqlerro == false ) {

    $clrhlota->r70_codigo = $r70_codigo;
    $clrhlota->excluir($r70_codigo);

		$erro_msg = $clrhlota->erro_msg;

		if ( $clrhlota->erro_status == 0 ) {

      $sqlerro  = true;
      $erro_msg = $clrhlota->erro_msg;
    }
  }

  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clrhlota->sql_record($clrhlota->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);

   if($r70_analitica=="t"){
     $result_rhlotaexe = $clrhlotaexe->sql_record($clrhlotaexe->sql_query(null,$chavepesquisa));
     if($clrhlotaexe->numrows>0){
       db_fieldsmemory($result_rhlotaexe,0);
     }
   }
   $result = $clrhlotacalend->sql_record($clrhlotacalend->sql_query($r70_codigo,"rh64_calend, rh53_descr")); 
   if($clrhlotacalend->numrows>0){
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmrhlota.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?php
if ( isset($excluir) || isset($sem_parametro_configurado) ) {

	if ( $sqlerro == true ) {

    db_msgbox($erro_msg);
		if($clrhlota->erro_campo!=""){

      echo "<script> document.form1.".$clrhlota->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhlota->erro_campo.".focus();</script>";
		};

	} else {
    db_msgbox($erro_msg);
    db_redireciona("pes1_rhlota006.php");
  };

  if(isset($sem_parametro_configurado)){

  	$db_opcao = 3;
  	echo "<script> document.form1.excluir.disabled = true;</script>";
  }

};

if ( isset($chavepesquisa) ) {
  echo "
        <script>
          parent.document.formaba.rhlotavinc.disabled = false;
	      top.corpo.iframe_rhlotavinc.location.href = 'pes1_rhlotavinc001.php?chavepesquisa=$r70_codigo&db_opcaoal=false';
	    </script>
       ";
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>