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
include("classes/db_orcprojativ_classe.php");
include("classes/db_orcprojativprogramfisica_classe.php");
include("classes/db_orcprojativunidaderesp_classe.php");
include("classes/db_orciniciativavinculoprojativ_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

db_postmemory($HTTP_POST_VARS);

$clorcprojativ 			   			= new cl_orcprojativ();
$clorcprojativunidaderesp   = new cl_orcprojativunidaderesp();
$clorcprojativprogramfisica = new cl_orcprojativprogramfisica();

$db_botao = false;
$db_opcao = 33;

if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
	
	
	$lSqlErro = false;
  db_inicio_transacao();
  $db_opcao = 3;
  $oDaoOrciniciativavinculoprojativ = new cl_orciniciativavinculoprojativ();
  $oDaoOrciniciativavinculoprojativ->excluir(null,"o149_projativ = {$o55_projativ} and o149_anousu = {$o55_anousu} ");
  if ($oDaoOrciniciativavinculoprojativ->erro_status == 0) {
  	
  	$lSqlErro = true;
  	$sMsgErro = $oDaoOrciniciativavinculoprojativ->erro_msg;
  }
  
	$sCampos = " max(o55_anousu) as maxo55_anousu ";
  $sWhere  = " o55_projativ = {$o55_projativ} ";
  $rsProjMaxAno = $clorcprojativ->sql_record($clorcprojativ->sql_query_file(null,null,$sCampos,null,$sWhere));
  
  if ($clorcprojativ->numrows > 0){
  	
  	db_fieldsmemory($rsProjMaxAno,0);
  	
  	$aAnousu = "(";
  	$anousu_atual = db_getsession('DB_anousu');
  	$virgula = "";
  	for($iInd = $anousu_atual; $iInd <= $maxo55_anousu; $iInd++){
  		$aAnousu .= $virgula.$iInd;
  		$virgula = ",";
  	}
  	$aAnousu .= ")";
  	
  	$sSqlProjPpa = "select * from  ppadotacao
  													where o08_projativ = $o55_projativ and o08_ano in $aAnousu";
  	$rsSqlProjPpa = db_query($sSqlProjPpa);
  	if(pg_num_rows($rsSqlProjPpa) > 0){
  		$lSqlErro = true;
  		$sMsgErro = "Usuário:\\n\\nProjto/Atividade encontra-se em estimativas do ppa\\n\\n";
  	}
  	$sSqlDotacao = "select * from orcdotacao 
  													where o58_projativ = $o55_projativ and o58_anousu in $aAnousu ";
  	$rsSqlDotacao = db_query($sSqlDotacao);
  	if(pg_num_rows($rsSqlDotacao) > 0){
  		$lSqlErro = true;
  		$sMsgErro = "Usuário:\\n\\nExistem Dotações cadastradas para esse projeto.\\n\\nNão Excluído!\\n\\n";
  	}
  	
  }
 	if (!$lSqlErro) {
		for($iInd = $anousu_atual; $iInd <= $maxo55_anousu; $iInd++){
			$anoUsu = $iInd;	
		  $clorcprojativprogramfisica->excluir(null,"o28_orcprojativ = {$o55_projativ} and o28_anousu = $anoUsu");
		  
		  if ( $clorcprojativprogramfisica->erro_status == "0" ) {
		    $lSqlErro = true;
		    $sMsgErro = $clorcprojativprogramfisica->erro_msg;
		    //die($sMsgErro);	
		  }    
		  
		  if ( !$lSqlErro ) {
		  	
		    $clorcprojativunidaderesp->excluir(null," o13_orcprojativ = {$o55_projativ} and o13_anousu = $anoUsu");
		  
		    if ( $clorcprojativunidaderesp->erro_status == 0 ) {
		      $lSqlErro = true;	
		      $sMsgErro = $clorcprojativunidaderesp->erro_msg;
		      //die($sMsgErro);exit();
		    }  
		  
		    if ( !$lSqlErro ) {
		      $clorcprojativ->excluir($anoUsu,$o55_projativ);
		  
		      if ( $clorcprojativ->erro_status == 0 ){
		    		$lSqlErro = true;
		    		$sMsgErro = $clorcprojativ->erro_msg;
		    		//die($sMsgErro);exit();
		      }
		    }
		  }
		}
	}
  db_fim_transacao($lSqlErro);
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clorcprojativ->sql_record($clorcprojativ->sql_query($chavepesquisa,$chavepesquisa1)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
      
   $digito = ($o55_projativ{0}*1000);
   
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
<table align="center" style="padding-top:25px;" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td> 
    <center>
	<?
	include("forms/db_frmorcprojativ.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir" && $lSqlErro == true){
	db_msgbox($sMsgErro);	
}else if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  if($clorcprojativ->erro_status=="0"){
    $clorcprojativ->erro(true,false);
  }else{
    $clorcprojativ->erro(true,true);
  };
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>