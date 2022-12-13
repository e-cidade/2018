<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("classes/db_fiscal_classe.php");
require_once("classes/db_fiscalocal_classe.php");
require_once("classes/db_fiscexec_classe.php");
require_once("classes/db_fiscalinscr_classe.php");
require_once("classes/db_fiscalmatric_classe.php");
require_once("classes/db_fiscalvistorias_classe.php");
require_once("classes/db_fiscalsanitario_classe.php");
require_once("classes/db_fiscalcgm_classe.php");
require_once("classes/db_procfiscalnotificacao_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if (!isset($abas)) {
	
  echo "<script>location.href='fis1_fiscal005.php?como=$como'</script>";
  exit;
}

db_postmemory($HTTP_POST_VARS);

$clfiscal                = new cl_fiscal;
$clfiscalocal            = new cl_fiscalocal;
$clfiscexec              = new cl_fiscexec;
$clfiscalinscr           = new cl_fiscalinscr;
$clfiscalmatric          = new cl_fiscalmatric;
$clfiscalcgm             = new cl_fiscalcgm;
$clfiscalsanitario       = new cl_fiscalsanitario;
$clfiscalvistorias       = new cl_fiscalvistorias;
$clprocfiscalnotificacao = new cl_procfiscalnotificacao;

$db_opcao = 1;
$db_botao = true;
if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir") {
	
	$sqlerro  = false;
	
  db_inicio_transacao();
  
  if (!$sqlerro) {
  	
		$clfiscal->y30_dtlanc = date('Y-m-d', db_getsession('DB_datausu'));
		$clfiscal->y30_instit = db_getsession('DB_instit');
	  $clfiscal->incluir($y30_codnoti);
	  $erro = $clfiscal->erro_msg;
	  if ($clfiscal->erro_status == 0) {
	  	$sqlerro = true;
	  }
  }

  if (!$sqlerro) {
    
	  $y30_codnoti = $clfiscal->y30_codnoti;
	  $clfiscalocal->y12_codnoti=$y30_codnoti;
	  $clfiscalocal->y12_codigo=$y12_codigo;
	  $clfiscalocal->y12_codi=$y12_codi;
	  $clfiscalocal->y12_numero=$y12_numero;
	  $clfiscalocal->y12_compl=$y12_compl;
	  $clfiscalocal->incluir($clfiscal->y30_codnoti);
	  if ($clfiscalocal->erro_status == 0) {
	  	
	    $erro    = $clfiscalocal->erro_msg;
	    $sqlerro = true;
	  }
  }

  if (!$sqlerro) {
    
	  $clfiscexec->y13_codnoti=$y30_codnoti;
	  $clfiscexec->y13_codigo=$y13_codigo;
	  $clfiscexec->y13_codi=$y13_codi;
	  $clfiscexec->y13_numero=$y13_numero;
	  $clfiscexec->y13_compl=$y13_compl;
	  $clfiscexec->incluir($clfiscal->y30_codnoti);
	  if ($clfiscexec->erro_status == 0) {
	  	
	    $erro    = $clfiscexec->erro_msg;
	    $sqlerro = true;
	  }
  }
  
  if (!$sqlerro) {
    
	  if(isset($z01_numcgm) && $z01_numcgm != ""){
	  	
	  	if (!$sqlerro) {
	  		
		    $clfiscalcgm->y36_numcgm=$z01_numcgm;
		    $clfiscalcgm->incluir($y30_codnoti); 
		    if ($clfiscalcgm->erro_status==0){
		    	
		      $erro    = $clfiscalcgm->erro_msg;
		      $sqlerro = true;
		    }
	  	}
	  } else if (isset($j01_matric) && $j01_matric != ""){
	  	
	  	if (!$sqlerro) {
	  		
		    $clfiscalmatric->y35_matric=$j01_matric; 
		    $clfiscalmatric->incluir($y30_codnoti); 
		    if ($clfiscalmatric->erro_status==0) {
		    	
		      $erro    = $clfiscalmatric->erro_msg;
		      $sqlerro = true;
		    }
	  	}
	  } else if (isset($q02_inscr)  && $q02_inscr  != "") {
	  	
	  	if (!$sqlerro) {
	  		
		    $clfiscalinscr->y34_inscr=$q02_inscr; 
		    $clfiscalinscr->incluir($y30_codnoti); 
		    if($clfiscalinscr->erro_status==0){
		    	
		      $erro    = $clfiscalinscr->erro_msg;
		      $sqlerro = true;
		    }
	  	}
	  } else if (isset($y80_codsani)  && $y80_codsani  != "") {
	  	
	  	if (!$sqlerro) {
	  		
		    $clfiscalsanitario->y37_codsani=$y80_codsani; 
		    $clfiscalsanitario->incluir($y30_codnoti); 
		    if($clfiscalsanitario->erro_status==0){
		    	
		      $erro    = $clfiscalsanitario->erro_msg;
		      $sqlerro = true;
		    }
	  	}
	  } else if (isset($y70_codvist)  && $y70_codvist  != "") {
	  	
	  	if (!$sqlerro) {
	  		
		    $clfiscalvistorias->y20_codvist=$y70_codvist; 
		    $clfiscalvistorias->incluir($y30_codnoti,$y70_codvist); 
		    if($clfiscalvistorias->erro_status==0){
		    	
		      $erro    = $clfiscalvistorias->erro_msg;
		      $sqlerro = true;
		    }
	  	}
	  }
  }
	
	if (!$sqlerro) {
		
		if($procfiscal!="") {
			
		  $clprocfiscalnotificacao->y110_notificacaofiscal = $y30_codnoti;
		  $clprocfiscalnotificacao->y110_procfiscal        = $procfiscal ;
		  $clprocfiscalnotificacao->incluir(null);
			if($clprocfiscalnotificacao->erro_status==0){
				
			  $erro    = $clprocfiscalnotificacao->erro_msg;
	      $sqlerro = true;
	    }
		}
	}
	
  db_fim_transacao($sqlerro);
}

if (!isset($pri)) {
	
  include("fis1_fiscal004.php");
  exit;
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
<script>
js_setatabulacao();
</script>
<?
if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir") {
	
	db_msgbox($erro);
	
  if ($sqlerro) {
  	
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clfiscal->erro_campo!=""){
      echo "<script> document.form1.".$clfiscal->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clfiscal->erro_campo.".focus();</script>";
    }
  } else {

    echo "
         <script>
         function js_src(){
           parent.iframe_fiscal.location.href='fis1_fiscal002.php?chavepesquisa=".$clfiscal->y30_codnoti."&abas=1';\n
           parent.iframe_fiscaltipo.location.href='fis1_fiscaltipo001.php?y31_codnoti=".$clfiscal->y30_codnoti."&abas=1';\n
           parent.iframe_receitas.location.href='fis1_fiscalrec001.php?y42_codnoti=".$clfiscal->y30_codnoti."&abas=1';\n
           parent.iframe_fiscais.location.href='fis1_fiscalusuario001.php?y38_codnoti=".$clfiscal->y30_codnoti."&abas=1';\n
           parent.iframe_test.location.href='fis1_fisctestem001.php?y23_codnoti=".$clfiscal->y30_codnoti."&abas=1';\n
           parent.iframe_artigos.location.href='fis1_fiscarquivos001.php?y26_codnoti=".$clfiscal->y30_codnoti."&abas=1';\n
           parent.mo_camada('fiscaltipo');
				   parent.document.formaba.fiscaltipo.disabled=false; 
				   parent.document.formaba.receitas.disabled=true; 
				   parent.document.formaba.fiscais.disabled=false; 
				   parent.document.formaba.test.disabled=false; 
				   parent.document.formaba.artigos.disabled=false; 
         }
         js_src();
         </script>
       ";
  };
};
?>