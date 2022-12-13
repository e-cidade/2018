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
include("libs/db_utils.php");
include("classes/db_parprojetos_classe.php");
include("classes/db_obrashabite_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_obrashabiteprot_classe.php");
include("classes/db_obrashabiteprotoff_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clparprojetos	      = new cl_parprojetos;
$clobrashabite        = new cl_obrashabite;
$clobrashabiteprot    = new cl_obrashabiteprot;
$clobrashabiteprotoff = new cl_obrashabiteprotoff;

$db_opcao = 22;
$db_botao = false;
$sqlerro  = false;

if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Alterar"){
  db_inicio_transacao();
  $db_opcao = 2;
	
  
	$rsProt	   = $clobrashabiteprot->sql_record($clobrashabiteprot->sql_query(null,"obrashabiteprot.*",null,"ob19_codhab = {$ob09_codhab}")); 
	$rsProtoff = $clobrashabiteprotoff->sql_record($clobrashabiteprotoff->sql_query(null,"obrashabiteprotoff.*",null,"ob22_codhab = {$ob09_codhab}")); 
	
	if(isset($ob19_codproc) && $ob19_codproc != "") {
			
		$clobrashabiteprot->ob19_codhab  = $ob09_codhab; 
		$clobrashabiteprot->ob19_codproc = $ob19_codproc; 
		if($clobrashabiteprot->numrows > 0){
			$clobrashabiteprot->alterar("and ob19_codhab = {$ob09_codhab}");
		} else {
			$clobrashabiteprot->incluir();
		}
		
		if($clobrashabiteprot->erro_status == 0){
			$sqlerro = true;
		}
  
	} else if(isset($ob22_codproc) && $ob22_codproc != "") {

		$clobrashabiteprotoff->ob22_codproc = $ob22_codproc;
		$clobrashabiteprotoff->ob22_titular = $ob22_titular;
		$clobrashabiteprotoff->ob22_data    = $ob22_data_ano."-".$ob22_data_mes."-".$ob22_data_dia;
			
		if($clobrashabiteprotoff->numrows > 0){
			$oProtoff = db_utils::fieldsMemory($rsProtoff,0);
			$ob22_sequencial = $oProtoff->ob22_sequencial;
			$clobrashabiteprotoff->alterar($ob22_sequencial);
		}else{
			$clobrashabiteprotoff->ob22_codhab  = $ob22_codhab;
			$clobrashabiteprotoff->incluir();
    }
		if($clobrashabiteprotoff->erro_status == 0){
			$sqlerro = true;
		}
	
	} else {
     
	 $iStatusErro = 1;
     if ($clobrashabiteprot->numrows > 0){
		$clobrashabiteprot->excluir(null,"ob09_codhab = $ob09_codhab");
	    $iStatusErro = $clobrashabiteprot->erro_status; 
	 } else if ($clobrashabiteprotoff->numrows > 0){
		$clobrashabiteprotoff->excluir(null,"ob22_codhab = $ob09_codhab");
	    $iStatusErro = $clobrashabiteprotoff->erro_status; 
	 }
	 
	 if($iStatusErro == 0){
	    $sqlerro = true;
	 }
	
	}
  
	$clobrashabite->alterar($ob09_codhab);
	
	if($clobrashabite->erro_status == 0){
  		$sqlerro = true;
	}

db_fim_transacao($sqlerro);


}else if(isset($chavepesquisa)){

	 $db_opcao = 2;
   $result = $clobrashabite->sql_record($clobrashabite->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $cep      = $z01_cep;
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
<body bgcolor=#CCCCCC>
	<?
	include("forms/db_frmobrashabitealtexc.php");
	?>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Alterar"){
  if($clobrashabite->erro_status=="0"){
    $clobrashabite->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clobrashabite->erro_campo!=""){
      echo "<script> document.form1.".$clobrashabite->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clobrashabite->erro_campo.".focus();</script>";
    };
  }else{
    $clobrashabite->erro(true,true);
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>