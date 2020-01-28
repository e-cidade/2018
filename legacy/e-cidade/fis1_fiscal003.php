<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
include("classes/db_fiscal_classe.php");
include("classes/db_fiscalusuario_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_fiscalocal_classe.php");
include("classes/db_fiscexec_classe.php");
include("classes/db_fiscalinscr_classe.php");
include("classes/db_fiscalmatric_classe.php");
include("classes/db_fiscalsanitario_classe.php");
include("classes/db_fiscalcgm_classe.php");
include("classes/db_fiscalrec_classe.php");
include("classes/db_fiscalandam_classe.php");
include("classes/db_fiscalultandam_classe.php");
include("classes/db_fandam_classe.php");
include("classes/db_fiscaltipo_classe.php");
include("classes/db_fandamusu_classe.php");
include("classes/db_fisctestem_classe.php");
include("classes/db_fiscarquivos_classe.php");
include("classes/db_fiscalvistorias_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if(!isset($abas)){
  echo "<script>location.href='fis1_fiscal005.php?db_opcao=3'</script>";
  exit;
}
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clfiscal          = new cl_fiscal;
$clfiscalvistorias = new cl_fiscalvistorias;
$clfisctestem      = new cl_fisctestem;
$clfiscalusuario   = new cl_fiscalusuario;
$clfiscalocal      = new cl_fiscalocal;
$clfiscexec        = new cl_fiscexec;
$clfiscarquivos    = new cl_fiscarquivos;
$clfiscalinscr     = new cl_fiscalinscr;
$clfiscalmatric    = new cl_fiscalmatric;
$clfiscalcgm       = new cl_fiscalcgm;
$clfiscalrec       = new cl_fiscalrec;
$clfiscalandam     = new cl_fiscalandam;
$clfiscalultandam  = new cl_fiscalultandam;
$clfandam          = new cl_fandam;
$clfiscalsanitario = new cl_fiscalsanitario;
$clfiscaltipo      = new cl_fiscaltipo;
$clfandamusu       = new cl_fandamusu;
$db_botao = false;
$db_opcao = 33;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  db_inicio_transacao();
  $db_opcao = 3;
  $clfiscalocal->excluir($y30_codnoti);
  $clfiscexec->excluir($y30_codnoti);
  $clfiscalcgm->excluir($y30_codnoti); 
  $clfiscalmatric->excluir($y30_codnoti); 
  $clfiscalinscr->excluir($y30_codnoti); 
  $clfiscalsanitario->excluir($y30_codnoti); 
  $clfiscalusuario->excluir($y30_codnoti); 
  $clfiscarquivos->excluir($y30_codnoti); 
  $result1 = $clfiscalrec->sql_record($clfiscalrec->sql_query_file($y30_codnoti));
  if($clfiscalrec->numrows > 0){
    $numrows =  $clfiscalrec->numrows;
    for($x=0;$x<$numrows;$x++){
      db_fieldsmemory($result1,$x);
      $clfiscalrec->y42_codnoti = $y42_codnoti;
      $clfiscalrec->y42_receit = $y42_receit;
      $clfiscalrec->excluir($y42_codnoti,$y42_receit);
    }
  }
  $result = $clfiscalandam->sql_record($clfiscalandam->sql_query_file("","","y49_codandam"," y49_codandam desc"," y49_codnoti = $y30_codnoti"));
  if($clfiscalandam->numrows > 0){
    db_fieldsmemory($result,0);
    $result1 = $clfandamusu->sql_record($clfandamusu->sql_query_file($y49_codandam));
    if($clfandamusu->numrows > 0){
      $numrows = $clfandamusu->numrows;
      for($x=0;$x<$numrows;$x++){
	db_fieldsmemory($result1,$x);
	$clfandamusu->excluir($y40_codandam,$y40_id_usuario);
      }
    }
    $clfiscalultandam->excluir($y30_codnoti);
    $numrows = $clfiscalandam->numrows;
    for($x=0;$x<$numrows;$x++){
      db_fieldsmemory($result,$x);
      $clfiscalandam->excluir($y30_codnoti,$y49_codandam);
      $clfandam->excluir($y49_codandam);
    }
  }
  $result1 = $clfiscaltipo->sql_record($clfiscaltipo->sql_query($y30_codnoti)); 
  if($clfiscaltipo->numrows > 0){
    $numrows = $clfiscaltipo->numrows;
    for($x=0;$x<$numrows;$x++){
      db_fieldsmemory($result1,$x);
      $clfiscaltipo->y31_codnoti = $y31_codnoti;
      $clfiscaltipo->y31_codtipo = $y31_codtipo;
      $clfiscaltipo->excluir($y31_codnoti,$y31_codtipo);
    }
  }
  $result1 = $clfisctestem->sql_record($clfisctestem->sql_query($y30_codnoti)); 
  if($clfisctestem->numrows > 0){
    $numrows = $clfisctestem->numrows;
    for($x=0;$x<$numrows;$x++){
      db_fieldsmemory($result1,$x);
      $clfisctestem->y23_codnoti = $y23_codnoti;
      $clfisctestem->y23_numcgm = $y23_numcgm;
      $clfisctestem->excluir($y23_codnoti,$y23_numcgm);
    }
  }
  $result1 = $clfiscalvistorias->sql_record($clfiscalvistorias->sql_query($y30_codnoti)); 
  if($clfiscalvistorias->numrows > 0){
    $numrows = $clfiscalvistorias->numrows;
    for($x=0;$x<$numrows;$x++){
      db_fieldsmemory($result1,$x);
      $clfiscalvistorias->y21_codnoti = $y21_codnoti;
      $clfiscalvistorias->y21_codvist = $y21_codvist;
      $clfiscalvistorias->excluir($y21_codnoti,$y21_codvist);
    }
  }
  $clfiscal->excluir($y30_codnoti);
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clfiscal->sql_record($clfiscal->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $result = $clfiscalocal->sql_record($clfiscalocal->sql_query($chavepesquisa,"*")); 
   if($clfiscalocal->numrows > 0){
     db_fieldsmemory($result,0);
   }
   $result = $clfiscexec->sql_record($clfiscexec->sql_query($chavepesquisa,"*")); 
   if($clfiscexec->numrows > 0){
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmfiscal.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  if($clfiscal->erro_status=="0"){
    $clfiscal->erro(true,false);
  }else{
    $clfiscal->erro(true,false);
    echo "
         <script>
         function js_src(){
         parent.iframe_fiscal.location.href='fis1_fiscal003.php?abas=1';\n
         }
         js_src();
         </script>
       ";
  };
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>