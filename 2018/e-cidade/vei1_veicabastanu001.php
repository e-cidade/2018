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
include("classes/db_veicabastanu_classe.php");
include("classes/db_veicabast_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clveicabast = new cl_veicabast;
$clveicabastanu = new cl_veicabastanu;
$db_opcao = 1;
$db_botao = true;
$pesq=false;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $clveicabastanu->ve74_data=date("Y-m-d",db_getsession("DB_datausu"));
  $clveicabastanu->ve74_hora=db_hora();
  $clveicabastanu->ve74_usuario=db_getsession("DB_id_usuario");  
  $clveicabastanu->incluir($ve74_codigo);
  $erro_msg=$clveicabastanu->erro_msg;
  if ($clveicabastanu->erro_status==0){
  	$sqlerro=true;
  }
  if ($sqlerro==false){
  	$clveicabast->ve70_ativo="0";
  	$clveicabast->ve70_codigo=$ve74_veicabast;
  	$clveicabast->alterar($ve74_veicabast);  	
  	if ($clveicabast->erro_status==0){
  		$sqlerro=true;
  		$erro_msg=$clveicabast->erro_msg;
  	}  	  	
  }
  db_fim_transacao($sqlerro);
}
if (isset($abast)&&$abast!=""){
	$ve74_veicabast=$abast;
	$pesq=true;
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
<body bgcolor=#CCCCCC style='margin-top: 25px'topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
	<?
	include("forms/db_frmveicabastanu.php");
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<script>
js_tabulacaoforms("form1","ve74_veicabast",true,1,"ve74_veicabast",true);
</script>
<?
if(isset($incluir)){
  if($clveicabastanu->erro_status=="0"||$sqlerro==true){
    //$clveicabastanu->erro(true,false);
    db_msgbox($erro_msg);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clveicabastanu->erro_campo!=""){
      echo "<script> document.form1.".$clveicabastanu->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clveicabastanu->erro_campo.".focus();</script>";
    }
  }else{
    $clveicabastanu->erro(true,true);
  }
}
if ($pesq==false){
	echo "<script>js_pesquisa_abast();</script>";
}
?>