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
include("classes/db_histbemdiv_classe.php");
include("classes/db_bensdiv_classe.php");
include("classes/db_benslote_classe.php");
include("classes/db_benstransfcodigo_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_histbensocorrencia_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clhistbem = new cl_histbem;
$clhistbemdiv = new cl_histbemdiv;
$cldepartdiv = new cl_departdiv;
$clbensdiv = new cl_bensdiv;
$clbens = new cl_bens;
$clbenslote = new cl_benslote;
$clbenstransfcodigo = new cl_benstransfcodigo;
$clhistbemocorrencia = new cl_histbensocorrencia;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  	db_inicio_transacao();
  	$sqlerro=false;
  	$db_opcao = 2;
  	$result_lote = $clbenslote->sql_record($clbenslote->sql_query_file(null,"t43_bem",null,"t43_codlote=$t42_codigo"));  	  	
    for($w=0;$w<$clbenslote->numrows;$w++){
	    db_fieldsmemory($result_lote,$w);
	  	if($sqlerro == false){
	  		$clhistbem->t56_codbem =$t43_bem;
			$clhistbem->t56_situac = $t56_situac;		
			$clhistbem->incluir(null);
			$erro_msg = $clhistbem->erro_msg; 
			if($clhistbem->erro_status==0){
				$sqlerro=true;
				break;
			}      
	    }  	
		if($sqlerro == false){
		    if (isset($t33_divisao)&&$t33_divisao!=""){	
				if ($sqlerro == false) {				
					$clhistbemdiv->t32_hist1bem=$clhistbem->t56_histbem;
					$clhistbemdiv->t32_divisao=$t33_divisao;
					$clhistbemdiv->incluir(null);
					if ($clhistbemdiv->erro_status == 0) {
						$sqlerro = true;
						$erro_msg = $clhistbemdiv->erro_msg;
						break;
					}						
				}
		    }
		}
		if ($sqlerro == false) {
			$result_bensdiv=$clbensdiv->sql_record($clbensdiv->sql_query_file($t43_bem));
			if ($clbensdiv->numrows>0){
				$clbensdiv->excluir($t43_bem);
				if($clbensdiv->erro_status==0){
					$sqlerro=true;
					$erro_msg=$clbensdiv->erro_msg;
				} 
			}
			if ($sqlerro == false) {
				if (isset($t33_divisao)&&$t33_divisao!=""){
					$clbensdiv->t33_divisao=$t33_divisao;
					$clbensdiv->incluir($t43_bem);
					if($clbensdiv->erro_status==0){
						$sqlerro=true;
						$erro_msg=$clbensdiv->erro_msg;
					} 
				}
			}
		}
		//Inseri na tabela histbensocorrencia
    if ($sqlerro == false) {
				//$t56_codbem	
				//$this->t69_sequencial 			= null; 
		    $clhistbemocorrencia->t69_codbem 					=	$t43_bem; 
		    $clhistbemocorrencia->t69_ocorrenciasbens	=	1;					// valor vem direto da tabela
		    $clhistbemocorrencia->t69_obs	 						=	"Movimento de Transferência do Bem";
		    $clhistbemocorrencia->t69_dthist 					= date('Y-m-d',db_getsession('DB_datausu'));
		    $clhistbemocorrencia->t69_hora						= db_hora();
				$clhistbemocorrencia->incluir(null);
				if($clhistbemocorrencia->erro_status==0){
							$sqlerro=true;
							$erro_msg=$clhistbemocorrencia->erro_msg;
						}
			}
		
		
    }	
  	db_fim_transacao($sqlerro);  	
}else if(isset($chavepesquisa)){
   $vir1="";
   $vir="";	
   $bem_trans="";
   $bem_lote="";
   $db_opcao = 2;   
   $result = $clbenslote->sql_record($clbenslote->sql_query(null,"distinct t42_codigo,t42_descr,t52_codcla,t64_class,t64_descr,t52_numcgm,z01_nome,t52_valaqu,t52_dtaqu,t52_descr,t52_obs,t52_depart,descrdepto",null,"t43_codlote=$chavepesquisa")); 
   if($clbenslote->numrows>1){

  		db_msgbox(_M('patrimonial.patrimonio.db_frmhistbemaltlote.bem_alterado_individualmente'));
  		echo "<script>location.href='pat4_histbemaltlote002.php';</script>";
  		exit;
   }else if($clbenslote->numrows>0){
  		$result_lote = $clbenslote->sql_record($clbenslote->sql_query_file(null,"t43_bem",null,"t43_codlote=$chavepesquisa"));  	
    	for($w=0;$w<$clbenslote->numrows;$w++){
    		db_fieldsmemory($result_lote,$w);
    		$bem_lote .= $vir1." ".$t43_bem;
    		$vir1=",";
    		$result_transf=$clbenstransfcodigo->sql_record($clbenstransfcodigo->sql_query_file(null,null,"*",null," t95_codbem = $t43_bem "));
    		if ($clbenstransfcodigo->numrows>0){
    			$bem_trans .= $vir." ".$t43_bem;
    			$vir=","; 
    		}    	
	    }
    	if ($bem_trans!=""){
    	  
    	  $oParms =  new stdClass();
    	  $oParms->bens = $bem_trans;
    		db_msgbox(_M('patrimonial.patrimonio.db_frmhistbemaltlote.bens_transferidos', $oParms));
    		echo "<script>location.href='pat4_histbemaltlote002.php';</script>";
  			exit;
    	}
    	db_fieldsmemory($result,0);    	    
   }   
   $result_hist = $clhistbem->sql_record($clhistbem->sql_query(null,"distinct t56_situac,t70_descr","","t56_codbem in ($bem_lote)"));
   if($clhistbem->numrows>1){
  		db_msgbox(_M('patrimonial.patrimonio.db_frmhistbemaltlote.situacao_ja_alterada'));   
   		echo "<script>location.href='pat4_histbemaltlote002.php';</script>";
  		exit;
   }else if($clhistbem->numrows==1){
   	db_fieldsmemory($result_hist,0);
   }
   $t56_depart=$t52_depart;
   $db_botao = true;
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
<body bgcolor=#CCCCCC >
	<?
	include("forms/db_frmhistbemaltlote.php");
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