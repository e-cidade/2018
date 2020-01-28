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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_aidofcanc_classe.php");
include("classes/db_aidof_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$claidofcanc = new cl_aidofcanc;
$claidof = new cl_aidof;
$db_opcao = 3;
$pesq=true;
$db_botao = false;
if(isset($incluir)){
	$pesq=false;
  $sqlerro=false;
  $Result_Notfim=$claidof->sql_record($claidof->sql_query(null,"y08_notain as notaini,y08_inscr as inscr,y08_nota",null,"y08_codigo = $y03_aidof and y08_cancel = 't'"));
  if($claidof->numrows>0){
  	db_fieldsmemory($Result_Notfim,0);
  		$Result_Notini=$claidof->sql_record($claidof->sql_query(null,"*",null,"y08_notain >= $notaini and y08_inscr = $inscr and y08_cancel = 'f' and y08_nota=$y08_nota"));
  		if($claidof->numrows>0){
  			db_msgbox("Já existe aidof com sequencia de nota maior!!\\nOperação Cancelada!!");
  			$sqlerro=true;
  			echo "<script>location.href='fis1_aidofcancalt001.php';</script>";
  			exit;
  		}
  } 
  db_inicio_transacao();  
  $claidofcanc->y03_tipocanc = '0'; 
  $claidofcanc->y03_usuario = db_getsession("DB_id_usuario");
  $claidofcanc->y03_data = date("Y-m-d",db_getsession("DB_datausu"));
  $claidofcanc->incluir(null);
  if($claidofcanc->erro_status==0){
  	$erro_msg=$claidofcanc->erro_msg;
  	$sqlerro=true;
  } 
  if ($sqlerro==false){
  	$claidof->y08_cancel= '0';
  	$claidof->y08_codigo = $y03_aidof;
  	$claidof->alterar($y03_aidof);
  	if($claidof->erro_status==0){
  		$erro_msg=$claidof->erro_msg;
  		$sqlerro=true;
  	} 
  }  
  db_fim_transacao($sqlerro);
}
if (isset($chavepesquisa)&&$chavepesquisa!=""){
	$db_opcao = 2;
	$db_botao = true;
	$pesq=false;
	$result_info=$claidofcanc->sql_record($claidofcanc->sql_query_nome(null,"*","y03_codigo desc limit 1","y03_aidof=".$chavepesquisa));
	
	db_fieldsmemory($result_info,0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC onLoad="a=1" >

	<?
	include("forms/db_frmaidofdescancalt.php");
	?>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","y03_aidof",true,1,"y03_aidof",true);
</script>
<?
if(isset($incluir)){
  if($sqlerro==true){  	
  	if($claidofcanc->erro_status=="0"){
  		db_msgbox($erro_msg);
    	//$claidofcanc->erro(true,false);
    	$db_botao=true;
    	echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    	if($claidofcanc->erro_campo!=""){
      		echo "<script> document.form1.".$claidofcanc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      		echo "<script> document.form1.".$claidofcanc->erro_campo.".focus();</script>";
    	}
  	}
  }else{
  	db_msgbox("Aidof $y03_aidof descancelado com sucesso!!");
  	echo "<script>location.href='fis1_aidofdescancalt001.php';</script>";
  	exit;
  }
}
if ($pesq==true){
	echo "<script>js_pesquisa();</script>";
}
?>