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
include("classes/db_varfix_classe.php");
include("classes/db_issbase_classe.php");
include("classes/db_varfixproc_classe.php");
include("classes/db_varfixnotifica_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_procfiscalvarfix_classe.php");
db_postmemory($HTTP_POST_VARS);
$clissbase         = new cl_issbase;
$clvarfix          = new cl_varfix;
$clvarfixproc      = new cl_varfixproc;
$clvarfixnotifica =new cl_varfixnotifica;
$clprocfiscalvarfix = new cl_procfiscalvarfix;
$db_opcao='1';
$db_botao=true;
if(isset($incluir)){
  $rsBaixada = $clissbase->sql_record($clissbase->sql_query_file(null,'*',null," q02_inscr = $q33_inscr and q02_dtbaix is not null "));
	if($clissbase->numrows > 0){
		db_msgbox('Inscrição baixada ! ');		
    db_redireciona('iss1_varfix001.php');
		exit;
	}
  $sqlerro=false;
  db_inicio_transacao();
  $clvarfix->incluir($q33_codigo);
  $q33_codigo=$clvarfix->q33_codigo;
  $erro_msg  =$clvarfix->erro_msg;
  if($clvarfix->erro_status==0){
    $sqlerro=true;
  }
	if($sqlerro==false){
		if($procfiscal!=""){
			$clprocfiscalvarfix->y113_varfix     = $q33_codigo;
			$clprocfiscalvarfix->y113_procfiscal = $procfiscal;
			$clprocfiscalvarfix->incluir(null);
			if($clprocfiscalvarfix->erro_status==0){
	      $sqlerro=true;
				 $erro_msg = $clprocfiscalvarfix->erro_msg;
	    }
		}
  }


if(isset($q36_processo) && $q36_processo != ""){
  $clvarfixproc->q36_processo = $q36_processo;
  $clvarfixproc->q36_varfix   = $q33_codigo;
  $clvarfixproc->incluir(null);
  $erro_msg = $clvarfixproc->erro_msg;
  if($clvarfixproc->erro_status==0){
    $sqlerro=true;
  }
   
}

if(isset($q37_notifica) && $q37_notifica != ""){
  $clvarfixnotifica->q37_notifica = $q37_notifica;
  $clvarfixnotifica->q37_varfix   = $q33_codigo;
  $clvarfixnotifica->incluir(null);
  $erro_msg = $clvarfixnotifica->erro_msg;
  if($clvarfixproc->erro_status==0){
    $sqlerro=true;  
  }
}

db_fim_transacao($sqlerro);
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmvarfix.php");
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
if(isset($incluir) || isset($inc)|| isset($alt)|| isset($exc) ){
  if($sqlerro==true){
    $clvarfix->erro(true,false);
    $db_botao=true;
    if($clvarfix->erro_campo!=""){
      echo "<script> document.form1.".$clvarfix->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clvarfix->erro_campo.".focus();</script>";
    }
  }else{
    db_msgbox($erro_msg);
    db_redireciona("iss1_varfix002.php?chavepesquisa=$q33_codigo");
  }
}
?>