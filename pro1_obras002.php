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
require_once("classes/db_obras_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("classes/db_obrasresp_classe.php");
require_once("classes/db_obrastiporesp_classe.php");
require_once("classes/db_obraspropri_classe.php");
require_once("classes/db_obraslote_classe.php");
require_once("classes/db_obraslotei_classe.php");
require_once("classes/db_obrasender_classe.php");
require_once("classes/db_obrastec_classe.php");
require_once("classes/db_obrastecnicos_classe.php");
require_once("classes/db_obrasprotprocesso_classe.php");
require_once("classes/db_obrasiptubase_classe.php");

db_postmemory($_POST);
db_postmemory($_GET);

if(!isset($abas)){
    echo "<script>location.href='pro1_obras005.php?db_opcao=2'</script>";
      exit;
};

$clobras				 		 = new cl_obras;
$clobrasresp		 		 = new cl_obrasresp;
$clobrastec			 		 = new cl_obrastec;
$clobrastecnicos 		 = new cl_obrastecnicos;
$clobraspropri   		 = new cl_obraspropri;
$clobrastiporesp 		 = new cl_obrastiporesp;
$clobraslote	   		 = new cl_obraslote;
$clobraslotei    		 = new cl_obraslotei;
$clobrasender    		 = new cl_obrasender;
$clobrasprotprocesso = new cl_obrasprotprocesso;
$clobrasiptubase     = new cl_obrasiptubase;

$db_opcao = 22;
$db_botao = false;

if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Alterar"){
	
  db_inicio_transacao();
  
  $db_opcao = 2;
  
  if(!isset($ob10_numcgm)){
    $clobrasresp->ob10_numcgm = $ob03_numcgm;
    $clobrasresp->ob10_codobra = $ob01_codobra;
    $clobrasresp->alterar($ob01_codobra);
  }else{
    $clobrasresp->ob10_codobra = $ob01_codobra;
    $clobrasresp->alterar($ob01_codobra);
  }
  
  $clobraspropri->ob10_codobra = $ob01_codobra;
  $clobraspropri->excluir($ob01_codobra);
  $clobraspropri->incluir($ob01_codobra);
  
  if(isset($ob05_idbql) and $ob05_idbql != ""){
  	
    $clobraslote->excluir($ob01_codobra);
    $clobraslote->incluir($ob01_codobra);
    
  }else{
  	
    $clobraslotei->ob06_codobra = $ob01_codobra;
    $clobraslotei->excluir($ob01_codobra);
    $clobraslotei->incluir($ob01_codobra);
    
  }
  
	$rsTecnicos = $clobrastecnicos->sql_record($clobrastecnicos->sql_query_file(null,"ob20_sequencial","","ob20_codobra = $ob01_codobra"));
	
  if($clobrastecnicos->numrows > 0){	
		db_fieldsmemory($rsTecnicos,0);
		$clobrastecnicos->ob20_sequencial = $ob20_sequencial;
		$clobrastecnicos->ob20_codobra    = $ob01_codobra;
		$clobrastecnicos->ob20_obrastec   = $ob15_sequencial; 
		$clobrastecnicos->alterar($ob20_sequencial);
  }else if(isset($ob15_sequencial) && trim($ob15_sequencial) != "") {
		$clobrastecnicos->ob20_codobra    = $ob01_codobra;
		$clobrastecnicos->ob20_obrastec   = $ob15_sequencial; 
		$clobrastecnicos->incluir(null);
	}

	if ($ob01_regular) {
		$rsObrasiptubase = $clobrasiptubase->sql_record($clobrasiptubase->sql_query_file(null, 
																																										 "*", 
																																										 null, 
																																										 "ob24_obras = {$ob01_codobra}"));

		if ($clobrasiptubase->numrows > 0) {
			$oObrasIptubase = db_utils::fieldsMemory($rsObrasiptubase, 0);
			$clobrasiptubase->ob24_sequencial = $oObrasIptubase->ob24_sequencial;
			$clobrasiptubase->ob24_obras      = $oObrasIptubase->ob24_obras;
			$clobrasiptubase->ob24_iptubase   = $j01_matric;
			$clobrasiptubase->alterar($clobrasiptubase->ob24_sequencial);
			
		} else {
			$clobrasiptubase->ob24_obras      = $ob01_codobra;
			$clobrasiptubase->ob24_iptubase   = $j01_matric;
			$clobrasiptubase->incluir(null);
			
		}
	}
	
	//verifica se e um processo do sistema
	$rsObrasProtProcesso = $clobrasprotprocesso->sql_record($clobrasprotprocesso->sql_query(null, 
																																												  "*",
																																												  null,
																																												  "ob25_obras = {$ob01_codobra}"));
	
	if ($clobrasprotprocesso->numrows > 0) {
		
		db_fieldsmemory($rsObrasProtProcesso, 0);
		
		if($ob01_processosistema == 'S' && !empty($ob01_processo)) {
			
			$clobrasprotprocesso->ob25_sequencial   = $ob25_sequencial;
			$clobrasprotprocesso->ob25_obras        = $ob25_obras;
			$clobrasprotprocesso->ob25_protprocesso = $ob01_processo;
			$clobrasprotprocesso->alterar($ob25_sequencial);
			
		} else {
			
			$clobrasprotprocesso->excluir($ob25_sequencial);
		
		}
		
	} else if ($ob01_processosistema == 'S' && !empty($ob01_processo)) {
			$clobrasprotprocesso->ob25_obras        = $ob01_codobra;
			$clobrasprotprocesso->ob25_protprocesso = $ob01_processo;
			$clobrasprotprocesso->incluir(null);
	}
	
	$clobras->alterar($ob01_codobra);
	
  db_fim_transacao();
  
  
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clobras->sql_record($clobras->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $result = $clobraspropri->sql_record($clobraspropri->sql_query($chavepesquisa)); 
   if ($clobraspropri->numrows > 0) {
     db_fieldsmemory($result,0);
   }
   $result = $clobraslote->sql_record($clobraslote->sql_query($chavepesquisa)); 
   if($clobraslote->numrows > 0){
     db_fieldsmemory($result,0);
   }
   $result = $clobraslotei->sql_record($clobraslotei->sql_query($chavepesquisa)); 
   if($clobraslotei->numrows > 0){
     db_fieldsmemory($result,0);
   }
   $result = $clobrasresp->sql_record($clobrasresp->sql_query($chavepesquisa, "ob10_numcgm, z01_nome as z01_nomeresp")); 
   if($clobrasresp->numrows > 0) {
   	db_fieldsmemory($result,0);
   }
   $db_botao = true;
    
	 $result = $clobrastecnicos->sql_record($clobrastecnicos->sql_query("","z01_nome as z01_nometec, ob15_crea",""," ob20_codobra = $chavepesquisa "));
	 if($clobrastecnicos->numrows > 0){
		 db_fieldsmemory($result,0);
   }
   
   if($ob01_regular) {
     $rsObrasiptubase = $clobrasiptubase->sql_record($clobrasiptubase->sql_query(null,
   																																						  "j01_matric, z01_nome as z01_nome_matricula",
   																																						  null,
   																																						  "ob24_obras = {$chavepesquisa}"));
   	  if ($clobrasiptubase->numrows > 0) {
   		  db_fieldsmemory($rsObrasiptubase, 0);
   	  }
   	
   }
   
   $rsObrasProtProcesso  = $clobrasprotprocesso->sql_record($clobrasprotprocesso->sql_query("","*",""," ob25_obras = $chavepesquisa "));
   $ob01_processosistema = 'N';    	
   
   if($clobrasprotprocesso->numrows > 0){
     $oObraProcesso        = db_utils::fieldsMemory($rsObrasProtProcesso, 0);
     $ob01_processosistema = 'S';
   }   

	 echo "<script>
					 function js_src(){
						 parent.iframe_constr.location.href='pro1_obrasconstr001.php?ob08_codobra=".$chavepesquisa."&abas=1';\n
						 parent.document.formaba.constr.disabled = false;
					 }
					 js_src();
         </script>";
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
				include("forms/db_frmobras.php");
			?>
</body>
</html>
<?
if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Alterar"){
  if($clobras->erro_status=="0"){
    $clobras->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clobras->erro_campo!=""){
      echo "<script> document.form1.".$clobras->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clobras->erro_campo.".focus();</script>";
    };
  }else{
    $clobras->erro(true,false);
    echo "
         <script>
         function js_src(){
           parent.iframe_obras.location.href='pro1_obras002.php?chavepesquisa=".$ob01_codobra."&abas=1';\n
           parent.iframe_constr.location.href='pro1_obrasconstr001.php?ob08_codobra=".$ob01_codobra."&abas=1';\n
         }
         js_src();
         </script>
       ";
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>