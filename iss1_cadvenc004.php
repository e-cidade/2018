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
include("classes/db_cadvenc_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clcadvenc = new cl_cadvenc;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $result01=$clcadvenc->sql_record($clcadvenc->sql_query_file($q82_codigo,'','max(q82_parc)+1 as parc'));
  db_fieldsmemory($result01,0);
  $q82_parc = $parc == ""?"1":$parc;
  
  $clcadvenc->q82_parc=$q82_parc;
  $clcadvenc->incluir($q82_codigo,$q82_parc);
  if($clcadvenc->erro_status==0){
    $sqlerro=true; 
  }
  db_fim_transacao($sqlerro);
  if(!$sqlerro){
    $q82_parc="";
    $q82_venc_dia="";
    $q82_venc_mes="";
    $q82_venc_ano="";
    $q82_desc="";
    $q82_perc="";
    $q82_hist="";
    $k01_descr="";
  }
}
if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
  $clcadvenc->alterar($q82_codigo,$q82_parc);
  if($clcadvenc->erro_status==0){
    $sqlerro=true; 
  }
  db_fim_transacao($sqlerro);
  if(!$sqlerro){
    $q82_parc="";
    $q82_venc_dia="";
    $q82_venc_mes="";
    $q82_venc_ano="";
    $q82_desc="";
    $q82_perc="";
    $q82_hist="";
    $k01_descr="";
  }
}
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $clcadvenc->excluir($q82_codigo,$q82_parc);
  if($clcadvenc->erro_status==0){
    $sqlerro=true; 
  }
  db_fim_transacao($sqlerro);
  if(!$sqlerro){
    $q82_parc="";
    $q82_venc_dia="";
    $q82_venc_mes="";
    $q82_venc_ano="";
    $q82_desc="";
    $q82_perc="";
    $q82_hist="";
    $k01_descr="";
  }
}
if(isset($soma_ano)){	
	$result_data=$clcadvenc->sql_record($clcadvenc->sql_query_file($q82_codigo,"","*","q82_parc"));
	if ($clcadvenc->numrows>0){
		//db_criatabela($result_data);
		$numrows=$clcadvenc->numrows;
		$sqlerro=false;
  		db_inicio_transacao();
		for($w=0;$w<$numrows;$w++){
			db_fieldsmemory($result_data,$w);
			if ($sqlerro==false){					
				$ano=substr($q82_venc,0,4);
				$mes=substr($q82_venc,5,2);
				$dia=substr($q82_venc,8,2);
				$ano=$ano+1;
				$data_new=$ano."-".$mes."-".$dia;								
				$clcadvenc->q82_venc=$data_new;				            							
  				$clcadvenc->alterar_alt($q82_codigo,$q82_parc);
  				$erro_msg=$clcadvenc->erro_msg;  				
  				if($clcadvenc->erro_status==0){
    				$sqlerro=true; 
    				$erro_msg=$clcadvenc->erro_msg;    			
    				break;
  				}else{
  					//db_msgbox($data_new);
  				}		
			}	
		}
		db_msgbox(@$erro_msg);
		/*
		$result_data=$clcadvenc->sql_record($clcadvenc->sql_query_file($q82_codigo,"","q82_codigo,q82_parc,q82_venc"));
		db_criatabela($result_data);
		exit;
		*/		
  		db_fim_transacao($sqlerro);
  		$q82_parc="";
    	$q82_venc_dia="";
    	$q82_venc_mes="";
    	$q82_venc_ano="";
    	$q82_desc="";
    	$q82_perc="";
    	$q82_hist="";
    	$k01_descr="";  		
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
	include("forms/db_frmcadvencalt.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($clcadvenc->erro_status=="0"){
    $clcadvenc->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcadvenc->erro_campo!=""){
      echo "<script> document.form1.".$clcadvenc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcadvenc->erro_campo.".focus();</script>";
    }
  }else{
    $clcadvenc->erro(true,false);
  }
}  
?>