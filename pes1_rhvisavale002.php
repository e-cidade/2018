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
include("classes/db_rhvisavale_classe.php");
include("classes/db_rhvisavalecgm_classe.php");
include("classes/db_db_config_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clrhvisavale = new cl_rhvisavale;
$clrhvisavalecgm = new cl_rhvisavalecgm;
$cldb_config = new cl_db_config;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $sqlerro = false;
  $clrhvisavale->alterar($rh47_instit);
	if($clrhvisavale->erro_status == 0) {
		$erro_msg = $clrhvisavale->erro_msg;
		$sqlerro = true;
	}

  if($sqlerro == false){
    $clrhvisavalecgm->excluir(null,"rh48_instit = $rh47_instit");
		if($clrhvisavalecgm->erro_status == 0) {
			$erro_msg = $clrhvisavalecgm->erro_msg;
			$sqlerro = true;
		}
  }

  if($sqlerro == false){
	  $clrhvisavalecgm->rh48_instit = $rh47_instit;
	  $clrhvisavalecgm->rh48_numcgm = $inter1;
	  $clrhvisavalecgm->rh48_ordem  = "1";
	  $clrhvisavalecgm->incluir(null);
		if($clrhvisavalecgm->erro_status == 0) {
			$erro_msg = $clrhvisavalecgm->erro_msg;
			$sqlerro = true;
		}
  }

  if($sqlerro == false){
	  if(trim($inter2) != ""){
	    $clrhvisavalecgm->rh48_instit = $rh47_instit;
		  $clrhvisavalecgm->rh48_numcgm = $inter2;
	    $clrhvisavalecgm->rh48_ordem  = "2";
		  $clrhvisavalecgm->incluir(null);
			if($clrhvisavalecgm->erro_status == 0) {
				$erro_msg = $clrhvisavalecgm->erro_msg;
				$sqlerro = true;
			}
	  }
  }

  if($sqlerro == false){	
	  if(trim($inter3) != ""){
	    $clrhvisavalecgm->rh48_instit = $rh47_instit;
		  $clrhvisavalecgm->rh48_numcgm = $inter3;
  	  $clrhvisavalecgm->rh48_ordem  = "3";
		  $clrhvisavalecgm->incluir(null);
			if($clrhvisavalecgm->erro_status == 0) {
				$erro_msg = $clrhvisavalecgm->erro_msg;
				$sqlerro = true;
			}
	  }
  }
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clrhvisavale->sql_record($clrhvisavale->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);

   $resultcgm = $clrhvisavalecgm->sql_record($clrhvisavalecgm->sql_query(null,"rh48_numcgm,z01_nome","rh48_ordem","rh48_instit=".$chavepesquisa));
   for($i=0; $i<$clrhvisavalecgm->numrows; $i++){
   	 db_fieldsmemory($resultcgm,$i);
   	 $inter  = "inter".($i+1);
   	 $$inter = $rh48_numcgm;
   	 $dinter  = "deinter".($i+1);
   	 $$dinter = $z01_nome;
   }
   $db_botao = true;
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
	include("forms/db_frmrhvisavale.php");
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
if(isset($alterar)){
  if($sqlerro == true){
  	db_msgbox($erro_msg);
  }else{
    $clrhvisavale->erro(true,true);
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
  js_tabulacaoforms("form1","inter1",true,1,"inter1",true);
</script>