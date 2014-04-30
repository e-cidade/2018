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
include("libs/db_utils.php");
include("classes/db_procjurtipo_classe.php");
include("classes/db_procjurtiporegra_classe.php");
include("classes/db_procjur_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clprocjurtipo      = new cl_procjurtipo;
$clprocjur	        = new cl_procjur();
$clprocjurtiporegra = new cl_procjurtiporegra();

$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){
  db_inicio_transacao();
  
  $rsVerificaProcjur = $clprocjur->sql_record($clprocjur->sql_query(null,"distinct v62_sequencial, v62_descricao",null," v62_procjurtipo = {$v66_sequencial} ")); 
  $iNroLinhasProcjur = $clprocjur->numrows;
  
  if ( $iNroLinhasProcjur > 0 ) {
  	
  	$aProcjur = array();
  	
  	for ($iInd=0; $iInd < $iNroLinhasProcjur; $iInd++) {
  	  $oProcjur   = db_utils::fieldsMemory($rsVerificaProcjur,$iInd);
  	  $aProcjur[] = "{$oProcjur->v62_sequencial}-{$oProcjur->v62_descricao}";	
  	}
  	
  	db_msgbox("Tipo de Processo envolvido com ".(count($aProcjur)>1?"os processos":"o processo")." : ".implode(",",$aProcjur));
  	db_redireciona($_SERVER['REQUEST_URI']);	
  	
  }
  
  $db_opcao = 3;
  $clprocjurtipo->excluir($v66_sequencial);
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clprocjurtipo->sql_record($clprocjurtipo->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table style="padding-top:15px;" align="center">
  <tr> 
    <td> 
    <center>
	<?
	include("forms/db_frmprocjurtipo.php");
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
if(isset($excluir)){
  if($clprocjurtipo->erro_status=="0"){
    $clprocjurtipo->erro(true,false);
  }else{
    $clprocjurtipo->erro(true,true);
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>