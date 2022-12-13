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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_rhlota_classe.php");
require_once("classes/db_rhlotavinc_classe.php");
require_once("classes/db_rhlotavincele_classe.php");
require_once("classes/db_rhlotavincativ_classe.php");
require_once("classes/db_rhlotavincrec_classe.php");
require_once("classes/db_orcprojativ_classe.php");
require_once("classes/db_orctiporec_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clrhlota          = new cl_rhlota;
$clrhlotavinc      = new cl_rhlotavinc;
$clrhlotavincele   = new cl_rhlotavincele;
$clrhlotavincativ  = new cl_rhlotavincativ;
$clrhlotavincrec   = new cl_rhlotavincrec;
$clorcprojativ     = new cl_orcprojativ;
$clorctiporec      = new cl_orctiporec;

$db_opcao = 1;
$opcoesae = 1;
$erro_msg = "";
$db_botao = false;

if (isset($incluir)) {
	
	$sWhere = "rh25_codigo = {$rh25_codigo} and rh25_anousu = {$rh25_anousu}";
	$sSqlRhLotaVinc  = $clrhlotavinc->sql_query(null,"rhlotavinc.*",null,$sWhere);
	$rsSqlRhLotaVinc = $clrhlotavinc->sql_record($sSqlRhLotaVinc);
	$iNumRows        = $clrhlotavinc->numrows;
	if ($iNumRows > 0) {
		
		$sMsg     = "Usuário: \\n\\n";
		$sMsg    .= " Já foi informado elemento/recurso para essa lotação para {$rh25_anousu}, caso queira \\n\\n";
    $sMsg    .= " fazer alterações no registro do ano, clique em (A), nas opçoes do registro. \\n\\n";
    $sMsg    .= "Administrador: \\n\\n";
		$erro_msg = $sMsg;
		$sqlerro  = true;
	} else {

	  $sqlerro = false;
	  db_inicio_transacao();
	  $clrhlotavinc->rh25_codigo    = $rh25_codigo;
	  $clrhlotavinc->rh25_projativ  = $rh25_projativ;
	  $clrhlotavinc->rh25_vinculo   = $rh25_vinculo;
	  $clrhlotavinc->rh25_anousu    = $rh25_anousu;
	  $clrhlotavinc->rh25_recurso   = $rh25_recurso;
	  $clrhlotavinc->rh25_programa  = $rh25_programa;
	  $clrhlotavinc->rh25_subfuncao = $rh25_subfuncao;
	  $clrhlotavinc->rh25_funcao    = $rh25_funcao;
	  $clrhlotavinc->incluir(null);
	  $rh25_codlotavinc = $clrhlotavinc->rh25_codlotavinc;  
	  $erro_msg = $clrhlotavinc->erro_msg;
	  if($clrhlotavinc->erro_status==0){
	    $sqlerro = true;
	  }else{
	    $opcao = "alterar";
	    $db_botao = true;
	  }
	  db_fim_transacao($sqlerro);
	}
	
} else if(isset($alterar)) {

  $sqlerro = false;
  db_inicio_transacao();
  $clrhlotavinc->rh25_codlotavinc = $rh25_codlotavinc;
  $clrhlotavinc->rh25_codigo      = $rh25_codigo;
  $clrhlotavinc->rh25_projativ    = $rh25_projativ;
  $clrhlotavinc->rh25_vinculo     = $rh25_vinculo;
  $clrhlotavinc->rh25_anousu      = $rh25_anousu;
  $clrhlotavinc->rh25_recurso     = $rh25_recurso;
	$clrhlotavinc->rh25_programa    = $rh25_programa;
	$clrhlotavinc->rh25_subfuncao   = $rh25_subfuncao;
	$clrhlotavinc->rh25_funcao      = $rh25_funcao;
  $clrhlotavinc->alterar($rh25_codlotavinc);
  $erro_msg = $clrhlotavinc->erro_msg;
  if($clrhlotavinc->erro_status==0){
    $sqlerro=true;
  }else{
    $opcao = "alterar";
    $db_botao = true;
  }
  db_fim_transacao($sqlerro);
} else if (isset($excluir)) {

  $sqlerro = false;
  db_inicio_transacao();
  $clrhlotavincativ->excluir($rh25_codlotavinc);
  if($clrhlotavincativ->erro_status == "0") {
  	$sqlerro  = true;
  	$erro_msg = $clrhlotavinc->erro_msg;
  } else {
    $clrhlotavinc->excluir($rh25_codlotavinc);
    $erro_msg = $clrhlotavinc->erro_msg;
    if($clrhlotavinc->erro_status==0){
      $sqlerro=true;
    }
  }  
  db_fim_transacao($sqlerro);
} else if(isset($importar)) {
  db_inicio_transacao();
  $sqlerro = false;
  $clrhlotavincele->excluir($rh25_codlotavinc,null);
  if($clrhlotavincele->erro_status==0){
    $erro_msg = $clrhlotavincele->erro_msg;
    $sqlerro=true;
  }
  if($sqlerro==false){
    $clrhlotavincativ->excluir($rh25_codlotavinc,null);
    if($clrhlotavincativ->erro_status==0){
      $erro_msg = $clrhlotavincativ->erro_msg;
      $sqlerro=true;
    }
  }
  if($sqlerro==false){
    $clrhlotavincrec->excluir($rh25_codlotavinc,null);
    if($clrhlotavincrec->erro_status==0){
      $erro_msg = $clrhlotavincrec->erro_msg;
      $sqlerro=true;
    }
  }
  if($sqlerro==false){
    $result_importaele = $clrhlotavincele->sql_record($clrhlotavincele->sql_query_file($importar,null,"rh28_codeledef,rh28_codelenov"));
    $numrows_importaele = $clrhlotavincele->numrows;
    for($i=0;$i<$numrows_importaele;$i++){
      db_fieldsmemory($result_importaele,$i);
      if($sqlerro==false){
	    $clrhlotavincele->rh28_codelenov = $rh28_codelenov;
	    $clrhlotavincele->incluir($rh25_codlotavinc,$rh28_codeledef);
	    $erro_msg = $clrhlotavincele->erro_msg;
	    if($clrhlotavincele->erro_status==0){
	      $sqlerro=true;
	      break;
	    }
      }
    }
  }
  if($sqlerro==false){
  	$sCampos = "rh39_codelenov,rh39_anousu,rh39_projativ,rh39_programa,rh39_funcao,rh39_subfuncao";
    $result_importaativ = $clrhlotavincativ->sql_record($clrhlotavincativ->sql_query_file($importar,null,$sCampos));
    $numrows_importaativ = $clrhlotavincativ->numrows;
    for($i=0;$i<$numrows_importaativ;$i++){
      db_fieldsmemory($result_importaativ,$i);
      if($sqlerro==false){
	    $clrhlotavincativ->rh39_anousu    = $rh39_anousu;
	    $clrhlotavincativ->rh39_projativ  = $rh39_projativ;
	    $clrhlotavincativ->rh39_programa  = ($rh39_programa==""?"null":$rh39_programa);
	    $clrhlotavincativ->rh39_subfuncao = ($rh39_subfuncao==""?"null":$rh39_subfuncao);
	    $clrhlotavincativ->rh39_funcao    = ($rh39_funcao==""?"null":$rh39_funcao);
	    $clrhlotavincativ->incluir($rh25_codlotavinc,$rh39_codelenov);
        $erro_msg = $clrhlotavincativ->erro_msg;
        if($clrhlotavincativ->erro_status==0){
	      $sqlerro=true;
	      break;
	    }
      }
    }
  }
  if($sqlerro==false){

    $result_importarec = $clrhlotavincrec->sql_record($clrhlotavincrec->sql_query_file($importar,null,"rh43_codelenov,rh43_recurso"));
    $numrows_importarec = $clrhlotavincrec->numrows;
    for($i=0;$i<$numrows_importarec;$i++){
      db_fieldsmemory($result_importarec,$i);
      if($sqlerro==false){
	    $clrhlotavincrec->rh43_recurso = $rh43_recurso;
	    $clrhlotavincrec->incluir($rh25_codlotavinc,$rh43_codelenov);
        $erro_msg = $clrhlotavincrec->erro_msg;
        if($clrhlotavincrec->erro_status==0){
	      $sqlerro=true;
	      break;
	    }
      }
    }
  }

  db_fim_transacao($sqlerro);
} else if(isset($chavepesquisa)) {
  $result_descr = $clrhlota->sql_record($clrhlota->sql_query_file($chavepesquisa,"r70_codigo as rh25_codigo,r70_descr as rh25_descr"));
  if($clrhlota->numrows>0){
    db_fieldsmemory($result_descr,0);
    $db_botao = true;
  }
}

if(isset($opcao) && trim($opcao) != "" && isset($rh25_codlotavinc) && trim($rh25_codlotavinc) != "") {

 $result = $clrhlotavinc->sql_record($clrhlotavinc->sql_query($rh25_codlotavinc));

 if ( $clrhlotavinc->numrows > 0 ) {

   $db_botao = true;
   if ( !isset($incluirnovo) ) {
     db_fieldsmemory($result, 0);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.rh25_projativ.focus();" >
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
		<?
		  include("forms/db_frmrhlotavinc.php");
		?>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)) {

  if ($sqlerro == true) {

    db_msgbox($erro_msg);
    if($clrhlotavinc->erro_campo!=""){
      echo "<script> document.form1.".$clrhlotavinc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhlotavinc->erro_campo.".focus();</script>";
    }
  } else {
    
    db_msgbox($erro_msg);
    echo "<script> js_cancelar(); </script>";
  }
}
?>