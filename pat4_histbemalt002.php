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
include("classes/db_histbem_classe.php");
include("classes/db_bens_classe.php");
include("classes/db_departdiv_classe.php");
include ("classes/db_histbemdiv_classe.php");
include ("classes/db_bensdiv_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_histbensocorrencia_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clhistbem = new cl_histbem;
$clhistbemdiv = new cl_histbemdiv;
$cldepartdiv = new cl_departdiv;
$clbensdiv = new cl_bensdiv;
$clbens    = new cl_bens;
$clhistbemocorrencia = new cl_histbensocorrencia;
$db_opcao = 22;
$db_botao = false;
if (isset($alterar)) {
	
  	db_inicio_transacao();
  	$sqlerro=false;
  	$db_opcao = 2;
  	if ($sqlerro == false) {
		  $clhistbem->t56_situac = $t56_situac;		
		  $clhistbem->incluir(null);
		  $erro_msg = $clhistbem->erro_msg; 
		  if ($clhistbem->erro_status==0) {
			  $sqlerro=true;
			  //break;
		  }
		      
    }
      	
	  if ($sqlerro == false) {
	    if ($t33_divisao!=""){	
			  if ($sqlerro == false) {				
				  $clhistbemdiv->t32_histbem=$clhistbem->t56_histbem;
				  $clhistbemdiv->t32_divisao=$t33_divisao;
				  $clhistbemdiv->incluir(null);
				  if ($clhistbemdiv->erro_status == 0) {
					  $sqlerro = true;
					  $erro_msg = $clhistbemdiv->erro_msg;
  				}						
			  }
	    }
	  }
	  
	  if ($sqlerro == false) {
		  $result_bensdiv=$clbensdiv->sql_record($clbensdiv->sql_query_file($t56_codbem));
		  if ($clbensdiv->numrows>0){
			  $clbensdiv->excluir($t56_codbem);
			  if ($clbensdiv->erro_status==0) {
				  $sqlerro=true;
				  $erro_msg=$clbensdiv->erro_msg;
			  } 
		  }
		  
		  if ($sqlerro == false) {
			  if ($t33_divisao!=""){
				  $clbensdiv->t33_divisao=$t33_divisao;
				  $clbensdiv->incluir($t56_codbem);
				  if ($clbensdiv->erro_status==0) {
					  $sqlerro=true;
					  $erro_msg=$clbensdiv->erro_msg;
				  } 
			  }
		  }
		  
	  }
	  
	  if ($sqlerro == false) {
      $clhistbemocorrencia->t69_codbem 					=	$t56_codbem; 
      $clhistbemocorrencia->t69_ocorrenciasbens	=	4;					// valor vem direto da tabela
      $clhistbemocorrencia->t69_obs	 						=	"Alterada a situação do bem";
      $clhistbemocorrencia->t69_dthist 					= date('Y-m-d',db_getsession('DB_datausu'));
      $clhistbemocorrencia->t69_hora						= db_hora();
		  $clhistbemocorrencia->incluir(null);
		  if ($clhistbemocorrencia->erro_status==0) {
				$sqlerro=true;
				$erro_msg=$clhistbemocorrencia->erro_msg;
			}
	  }
  	
	  db_fim_transacao($sqlerro);
  	
} else if(isset($chavepesquisa)) {
   
	 $db_opcao = 2;
   $result = $clbens->sql_record($clbens->sql_query($chavepesquisa));    
   if ($clbens->numrows>0){
  	db_fieldsmemory($result,0);
   }
   
   $result_div = $clbensdiv->sql_record($clbensdiv->sql_query($chavepesquisa));
   if ($clbensdiv->numrows>0){
      db_fieldsmemory($result_div,0);
   }   
   
   $result_hist = $clhistbem->sql_record($clhistbem->sql_query(null,"t56_situac,t70_descr","t56_histbem desc limit 1","t56_codbem=$chavepesquisa"));
   if ($clhistbem->numrows>0){
      db_fieldsmemory($result_hist,0);
   }
   
   $t56_codbem = $t52_bem;
   $t56_depart = $t52_depart;
   $db_botao   = true;
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
<body bgcolor=#CCCCCC>

	<?
	include("forms/db_frmhistbemalt.php");
	?>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($clhistbem->erro_status=="0"){
    $clhistbem->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clhistbem->erro_campo!=""){
      echo "<script> document.form1.".$clhistbem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clhistbem->erro_campo.".focus();</script>";
    }
  }else{
    $clhistbem->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","t56_situac",true,1,"t56_situac",true);
</script>