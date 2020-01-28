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
include("classes/db_procandamint_classe.php");
include("classes/db_procandamintusu_classe.php");
include("classes/db_protprocesso_classe.php");
include("classes/db_proctransferintand_classe.php");
include("classes/db_proctransfer_classe.php");
include("classes/db_protparam_classe.php");
include("classes/db_solicita_classe.php");
include("classes/db_solicitemprot_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clprocandamint = new cl_procandamint;
$clprocandamintusu = new cl_procandamintusu;
$clprotprocesso = new cl_protprocesso;
$clproctransferintand = new cl_proctransferintand;
$clproctransfer = new cl_proctransfer;
$clprotparam = new cl_protparam;
$clsolicita = new cl_solicita;
$clsolicitemprot = new cl_solicitemprot;
$db_opcao = 1;
$db_botao = true;
$sqlerro=false;
if(isset($incluir)){
  db_inicio_transacao();  
  $dados=split("#",$chaves);
  for($w=0;$w<count($dados);$w++){
  	  $result_proc=$clsolicitemprot->sql_record($clsolicitemprot->sql_query_file(null,"*",null," pc49_solicitem =".$dados[$w]));
  	  db_fieldsmemory($result_proc,0);
  	  $p58_codproc=$pc49_protprocesso;  	
	  $result=$clprotprocesso->sql_record($clprotprocesso->sql_query_file($p58_codproc,"p58_codandam"));
	  db_fieldsmemory($result,0);
	  $data= date("Y-m-d",db_getsession("DB_datausu"));  
	  if ($sqlerro==false){
	  	$clprocandamint->p78_codandam=$p58_codandam;
	  	$clprocandamint->p78_data=$data;
	  	$clprocandamint->p78_hora=db_hora();
		$clprocandamint->p78_usuario=db_getsession("DB_id_usuario");
	  	$clprocandamint->p78_publico='false';
	  	$clprocandamint->p78_transint='false';
	  	$clprocandamint->incluir(null);
	  	$erro_msg = $clprocandamint->erro_msg;
	  	if ($clprocandamint->erro_status==0){
	    	$sqlerro=true;
	  	} 
	  	$codprocandamint=$clprocandamint->p78_sequencial;
	  	if ($sqlerro==false){
	    	$db_botao = false;
	  	}
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmdespsol.php");
	?>
    </center>
	</td>
  </tr>
</table>
</center>
</body>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<?
if(isset($incluir)){
  db_msgbox($erro_msg);
  if($sqlerro==true){
    $clprocandamint->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clprocandamint->erro_campo!=""){
      echo "<script> document.form1.".$clprocandamint->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocandamint->erro_campo.".focus();</script>";
    }
  }else{
  	echo "<script>location.href='com4_despsol001.php';</script>";
  }
};
?>