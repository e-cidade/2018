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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_rhlota_classe.php");
include("classes/db_rhlotavinc_classe.php");
include("classes/db_rhlotavincativ_classe.php");
include("classes/db_rhlotavincele_classe.php");
include("classes/db_rhlotavincrec_classe.php");
include("classes/db_rhelementoemp_classe.php");
include("classes/db_orcprojativ_classe.php");
include("classes/db_orctiporec_classe.php");
include("classes/db_orcelemento_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clrhlota = new cl_rhlota;
$clrhlotavinc = new cl_rhlotavinc;
$clrhlotavincativ = new cl_rhlotavincativ;
$clrhlotavincele = new cl_rhlotavincele;
$clrhelementoemp = new cl_rhelementoemp;
$clrhlotavincrec = new cl_rhlotavincrec;
$clorcprojativ = new cl_orcprojativ;
$clorcelemento = new cl_orcelemento;
$clorctiporec  = new cl_orctiporec;
$db_opcao = 1;
$opcoesae = 3;
$db_botao = true;
if(!isset($default)){
  $default = "";
}

if(isset($opcao) && $opcao=="alterar"){
  $db_opcao = 2;
  $db_botao = false;
}else if(isset($opcao) && $opcao=="excluir"){
  $db_opcao = 3;
  $db_botao = false;
}else if(isset($db_opcaoal)){
  if($db_opcaoal=="false"){
    $db_opcao = 3;
    $opcoesae = 3;
    $db_botao = false;
  }
}
$limpachavee1 = false;
$limpachave2e3 = false;
if(isset($incluir)){
  $sqlerro = false;
  db_inicio_transacao();
  $clrhlotavincele->rh28_codelenov   = $rh28_codelenov;
  $clrhlotavincele->incluir($rh25_codlotavinc,$rh28_codeledef);
  $erro_msg = $clrhlotavincele->erro_msg;
  if($clrhlotavincele->erro_status==0){
    $sqlerro=true;
  }
  if($sqlerro==false && trim($rh39_projativ)!=""){
    $clrhlotavincativ->rh39_anousu    = $rh39_anousu;
    $clrhlotavincativ->rh39_projativ  = $rh39_projativ;

		if(empty($rh39_programa)) {
			$clrhlotavincativ->rh39_programa  = 'null';
		}                                     
		if(empty($rh39_subfuncao)) {         
			$clrhlotavincativ->rh39_subfuncao = 'null';
		}                                     
		if(empty($rh39_funcao)) {            
			$clrhlotavincativ->rh39_funcao    = 'null';
		}

    $clrhlotavincativ->incluir($rh25_codlotavinc,$rh28_codelenov);
    if($clrhlotavincativ->erro_status==0){
      $erro_msg = $clrhlotavincativ->erro_msg;
      $sqlerro=true;
    }
  }
  if($sqlerro==false && trim($rh43_recurso)!=""){
    $clrhlotavincrec->rh43_recurso = $rh43_recurso;
    $clrhlotavincrec->incluir($rh25_codlotavinc,$rh28_codelenov);
    if($clrhlotavincrec->erro_status==0){
      $erro_msg = $clrhlotavincrec->erro_msg;
      $sqlerro=true;
    }
  }
  db_fim_transacao($sqlerro);
}else if(isset($excluir)){
  $sqlerro = false;
  db_inicio_transacao();
  if($sqlerro==false){
    $clrhlotavincativ->excluir($rh25_codlotavinc,$rh28_codelenov);
    $erro_msg = $clrhlotavincativ->erro_msg;
    if($clrhlotavincativ->erro_status==0){
      $sqlerro=true;
    }
  }
  
  if($sqlerro==false){
    $clrhlotavincele->excluir($rh25_codlotavinc,$rh28_codeledef);
    $erro_msg1 = $clrhlotavincele->erro_msg;
    if($clrhlotavincele->erro_status==0){
      $sqlerro=true;
    }
  }
  
  if($sqlerro==false){
    $clrhlotavincele->excluir($rh25_codlotavinc,$rh28_codeledef);
    $erro_msg1 = $clrhlotavincele->erro_msg;
    if($clrhlotavincele->erro_status==0){
      $sqlerro=true;
    }
  }

  if($sqlerro==false){
    $clrhlotavincrec->excluir($rh25_codlotavinc,$rh28_codelenov);
    $erro_msg1 = $clrhlotavincrec->erro_msg;
    if($clrhlotavincrec->erro_status==0){
      $sqlerro=true;
    }
  }

  db_fim_transacao($sqlerro);
}else if(isset($opcao) && !isset($npass)){
  $result_descr = $clrhlota->sql_record($clrhlota->sql_query_file($rh25_codigo,"r70_codigo as rh25_codigo,r70_descr as rh25_descr"));
  if($clrhlota->numrows>0){
    db_fieldsmemory($result_descr,0);
  }
  if(isset($lotavinc)){
    $result_lotavinc = $clrhlotavinc->sql_record($clrhlotavinc->sql_query_file($rh25_codlotavinc,"rh25_codlotavinc"));
    if($clrhlotavinc->numrows>0){
      db_fieldsmemory($result_lotavinc,0);
    }
  }
	$sSqlLotavincele = $clrhlotavincele->sql_query_ele($rh28_codlotavinc,
																										 $rh28_codeledef,
																										 "rh39_projativ,
																										  rh39_anousu,
																											rh39_programa,
																											rh39_subfuncao,
																											rh39_funcao,
																											o55_descr,
																											rh28_codeledef,
																											orcelemento.o56_descr	as o56_descr,
																											rh28_codelenov,
																											a.o56_descr as 
																											o56_descrnov,
																											rh43_recurso,
																											o15_descr,
																											o54_descr,
																											o53_descr,
																											o52_descr"); 
  $result = $clrhlotavincele->sql_record($sSqlLotavincele);
  if($clrhlotavinc->numrows>0){
    $db_botao = true;
    db_fieldsmemory($result,0);
    $default = $rh28_codeledef;
  }
}else if(isset($lotacao)){
  if(isset($opcao) && $opcao=="alterar"){
    $db_botao = true;
  }
  $result_descr = $clrhlota->sql_record($clrhlota->sql_query_file($lotacao,"r70_codigo as rh25_codigo,r70_descr as rh25_descr"));
  if($clrhlota->numrows>0){
    db_fieldsmemory($result_descr,0);
  }
  if(isset($lotavinc)){
    $result_lotavinc = $clrhlotavinc->sql_record($clrhlotavinc->sql_query_file($lotavinc,"rh25_codlotavinc"));
    if($clrhlotavinc->numrows>0){
      db_fieldsmemory($result_lotavinc,0);
    }
  }
  if(isset($ch) && trim($ch)!="" && isset($ch1) && trim($ch1)!=""){    
    if($ch1!="true"){
      $result_projativ = $clorcprojativ->sql_record($clorcprojativ->sql_query_file($ch,$ch1,"o55_projativ as rh39_projativ,o55_anousu as rh39_anousu,o55_descr"));
      if($clorcprojativ->numrows>0){
	db_fieldsmemory($result_projativ,0);
      }else{
	$rh39_projativ = "";
	$rh39_anousu   = "";
	$o55_descr = "REGISTRO NÃO ENCONTRADO";
      }
    }else{
      $rh39_projativ = "";
      $rh39_anousu   = "";
      $o55_descr = "REGISTRO NÃO ENCONTRADO";
    }
  }
  if(isset($ch2) && trim($ch2)!="" && isset($ch3) && trim($ch3)!=""){
    if($ch3!="true"){
      $result_elemento = $clorcelemento->sql_record($clorcelemento->sql_query_file($ch2,db_getsession("DB_anousu"),"o56_codele as rh28_codeledef,o56_descr"));
      if($clorcelemento->numrows >0){
	db_fieldsmemory($result_elemento,0);
      }else{
	$rh28_codeledef = "";
	$o56_descr = "REGISTRO NÃO ENCONTRADO";
      }
    }else{
      $rh28_codeledef = "";
      $o56_descr = "REGISTRO NÃO ENCONTRADO";
    }
  }
  if(isset($ch4) && trim($ch4)!="" && isset($ch5) && trim($ch5)!=""){
    if($ch5!="true"){
      $result_elemento = $clorcelemento->sql_record($clorcelemento->sql_query_file($ch4,db_getsession("DB_anousu"),"o56_codele as rh28_codelenov,o56_descr as o56_descrnov"));
      if($clorcelemento->numrows >0){
	db_fieldsmemory($result_elemento,0);
      }else{
        $rh28_codelenov = "";
        $o56_descrnov = "REGISTRO NÃO ENCONTRADO";
      }
    }else{
      $rh28_codelenov = "";
      $o56_descrnov = "REGISTRO NÃO ENCONTRADO";
    }
  }
  if(isset($ch6) && trim($ch6)!="" && isset($ch7) && trim($ch7)!=""){  	
    if($ch6!="true"){
      $result_recurso = $clorctiporec->sql_record($clorctiporec->sql_query_file($ch6,"o15_codigo as rh43_recurso,o15_descr"));
      if($clorctiporec->numrows >0){
	    db_fieldsmemory($result_recurso,0);
      }else{
        $rh43_recurso = "";
        $o15_descr    = "REGISTRO NÃO ENCONTRADO";
      }
    }else{
      $rh43_recurso = "";
      $o15_descr    = "REGISTRO NÃO ENCONTRADO";
    }
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmrhlotavincele.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
  if(isset($alterar)){
    $erro_msg = str_replace("Inclusão","Alteração",$erro_msg);
    $erro_msg = str_replace("Inclusao","Alteração",$erro_msg);
  }
  if($sqlerro==true){
    db_msgbox($erro_msg);
  }
  echo "<script> location.href = 'pes1_rhlotavincele001.php?lotacao=$rh25_codigo&lotavinc=$rh25_codlotavinc';</script>";
}
if(isset($opcao)){
  echo "<script> top.corpo.iframe_rhlotavinc.document.form1.opcaoiframe.value = '$opcao'; </script>";
  if($opcao=="alterar" && trim($default)==""){
    echo "<script> top.corpo.iframe_rhlotavinc.document.form1.defaultifra.value = '$rh28_codeledef'; </script>";
  }
}else{
  echo "<script> top.corpo.iframe_rhlotavinc.document.form1.opcaoiframe.value = ''; </script>";
}
/*
if($limpachavee1==true){
  echo "<script> parent.document.form1.chave.value = '';</script>";
  echo "<script> parent.document.form1.chave1.value = '';</script>";
}
if($limpachave2e3==true){
  echo "<script> parent.document.form1.chave2.value = '';</script>";
  echo "<script> parent.document.form1.chave3.value = '';</script>";
}
*/
?>