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
include("dbforms/db_funcoes.php");
include("classes/db_procfiscal_classe.php");
include("classes/db_procfiscalinscr_classe.php");
include("classes/db_procfiscalmatric_classe.php");
include("classes/db_procfiscalsani_classe.php");
include("classes/db_procfiscalcgm_classe.php");
include("classes/db_procfiscalprot_classe.php");
include("classes/db_procfiscalfiscais_classe.php");
$clprocfiscal = new cl_procfiscal;
$clprocfiscalinscr  = new cl_procfiscalinscr;
$clprocfiscalmatric = new cl_procfiscalmatric;
$clprocfiscalsani   = new cl_procfiscalsani;
$clprocfiscalcgm    = new cl_procfiscalcgm;
$clprocfiscalprot   = new cl_procfiscalprot;
  /*
$clprocfiscalfiscais = new cl_procfiscalfiscais;
  */
db_postmemory($HTTP_POST_VARS);
   $db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
	$clprocfiscal->y100_coddepto = db_getsession("DB_coddepto");
  $clprocfiscal->alterar($y100_sequencial);
	$erro_msg = $clprocfiscal->erro_msg; 
	if($clprocfiscal->erro_status==0){
    $sqlerro=true;
  } 
	
	// excluir todos par adepois incluir
  $clprocfiscalinscr->excluir(null," y103_procfiscal = $y100_sequencial ");
	if($clprocfiscalinscr ->erro_status==0){
    $sqlerro=true;
	  $erro_msg = $clprocfiscalinscr->erro_msg; 
  } 
	
	$clprocfiscalmatric->excluir(null," y102_procfiscal = $y100_sequencial ");
	if($clprocfiscalmatric->erro_status==0){
    $sqlerro=true;
	  $erro_msg = $clprocfiscalmatric->erro_msg; 
  } 
	
	$clprocfiscalsani->excluir(null," y104_procfiscal = $y100_sequencial ");
	if($clprocfiscalsani->erro_status==0){
    $sqlerro=true;
	  $erro_msg = $clprocfiscalsani->erro_msg; 
  }
	 
	$clprocfiscalcgm->excluir(null," y101_procfiscal = $y100_sequencial ");
	if($clprocfiscalcgm->erro_status==0){
    $sqlerro=true;
	  $erro_msg = $clprocfiscalcgm->erro_msg; 
  }
	
	// se tiver inscricao
	if(isset($q02_inscr) and $q02_inscr!=""){
		$clprocfiscalinscr->y103_inscr = $q02_inscr;
		$clprocfiscalinscr->y103_procfiscal = $y100_sequencial;
		$clprocfiscalinscr->incluir(null);
		if($clprocfiscalinscr ->erro_status==0){
      $sqlerro=true;
		  $erro_msg = $clprocfiscalinscr->erro_msg; 
    } 
	}
	
	// se tiver matricula
	if(isset($j01_matric) and $j01_matric!=""){
		$clprocfiscalmatric->y102_matric     = $j01_matric;
		$clprocfiscalmatric->y102_procfiscal = $y100_sequencial;
		$clprocfiscalmatric->incluir($y100_sequencial);
		if($clprocfiscalmatric ->erro_status==0){
      $sqlerro=true;
		  $erro_msg = $clprocfiscalmatric->erro_msg; 
    } 
	}
	// se tiver sanitario
	if(isset($y80_codsani) and $y80_codsani!=""){
		$clprocfiscalsani->y104_codsani    = $y80_codsani;
		$clprocfiscalsani->y104_procfiscal = $y100_sequencial;
		$clprocfiscalsani->incluir(null);
		if($clprocfiscalsani ->erro_status==0){
      $sqlerro=true;
		  $erro_msg = $clprocfiscalsani->erro_msg; 
    } 
	}
	// cgm
  if($z01_numcgm==""){
  	$sqlerro=true;
		$erro_msg = "Campo cgm não informado!";
  }else{
    $clprocfiscalcgm->y101_numcgm     = $z01_numcgm;
  	$clprocfiscalcgm->y101_procfiscal = $y100_sequencial;
	  $clprocfiscalcgm->incluir(null);
		if($clprocfiscalcgm ->erro_status==0){
      $sqlerro=true;
		  $erro_msg = $clprocfiscalcgm->erro_msg; 
    } 
	}
	
	// processo protocolo
	if($p58_codproc==""){
  	$sqlerro=true;
		$erro_msg = "Campo Processo não informado!";
  }else{
    $sqlprot = "	select y105_sequencial from procfiscalprot where y105_procfiscal = $y100_sequencial";
		$resultprot = pg_query($sqlprot);
		db_fieldsmemory($resultprot,0);
		$clprocfiscalprot->y105_sequencial= $y105_sequencial;
		$clprocfiscalprot->y105_protprocesso = $p58_codproc;
		$clprocfiscalprot->alterar($y105_sequencial);
		if($clprocfiscalprot ->erro_status==0){
	    $sqlerro=true;
		  $erro_msg = $clprocfiscalprot->erro_msg; 
	  } 
	}
	
	
  db_fim_transacao($sqlerro);
   $db_opcao = 2;
   $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $db_botao = true;
   //$result = $clprocfiscal->sql_record($clprocfiscal->sql_query($chavepesquisa)); 
	 $sql ="
				  select procfiscal.*,y103_inscr as q02_inscr,y102_matric as j01_matric,
					       y105_protprocesso as p58_codproc,y104_codsani,
					       y33_descricao,cgm.z01_nome,cgm.z01_numcgm,c.z01_nome as z01_nome1,
					       descrdepto
					from procfiscal
					inner join db_depart         on db_depart.coddepto               = procfiscal.y100_coddepto 
					inner join procfiscalcadtipo on procfiscalcadtipo.y33_sequencial = procfiscal.y100_procfiscalcadtipo 
					inner join procfiscalcgm     on y101_procfiscal                  = procfiscal.y100_sequencial
					inner join cgm               on cgm.z01_numcgm                   = y101_numcgm
					inner join procfiscalprot    on procfiscalprot.y105_procfiscal   = procfiscal.y100_sequencial
					inner join protprocesso      on protprocesso.p58_codproc         = procfiscalprot.y105_protprocesso
          inner join cgm c             on protprocesso.p58_numcgm          = c.z01_numcgm
					left  join procfiscalmatric  on y102_procfiscal                  = procfiscal.y100_sequencial
					left  join procfiscalinscr   on y103_procfiscal                  = procfiscal.y100_sequencial
					left  join procfiscalsani    on y104_procfiscal                  = procfiscal.y100_sequencial
					where procfiscal.y100_sequencial = $chavepesquisa";
	 $result = pg_query($sql);
	 
	 
   db_fieldsmemory($result,0);
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
	include("forms/db_frmprocfiscal.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clprocfiscal->erro_campo!=""){
      echo "<script> document.form1.".$clprocfiscal->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocfiscal->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.procfiscalfiscais.disabled=false;
         top.corpo.iframe_procfiscalfiscais.location.href='fis1_procfiscalfiscais001.php?y106_procfiscal=".@$y100_sequencial."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('procfiscalfiscais');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>