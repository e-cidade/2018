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
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcprograma_classe.php");
include("classes/db_orcprogramaorgao_classe.php");
include("classes/db_orcprogramaunidade_classe.php");
include("classes/db_orcprogramahorizontetemp_classe.php");
include("classes/db_orcindicaprograma_classe.php");
include("classes/db_db_config_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clorcprograma 							= new cl_orcprograma();
$clorcprogramaorgao 				= new cl_orcprogramaorgao();
$clorcprogramaunidade   		= new cl_orcprogramaunidade();
$clorcindicaprograma 	    	= new cl_orcindicaprograma();
$clorcprogramahorizontetemp = new cl_orcprogramahorizontetemp();
$cldb_config 			    			= new cl_db_config;

$db_botao = false;
$db_opcao = 33;
$lSqlErro = false;

//$resultado = $cldb_config->sql_record($cldb_config->sql_query_file(null,"*",null,"codigo = ".db_getsession("DB_instit")." and prefeitura is true"));
//if ($cldb_config->numrows == 0){
//     $db_botao = false;
//     $db_opcao = 3;
//}


if( isset($oPost->excluir) ){

  db_inicio_transacao();

  $sCampos = " max(o54_anousu) as maxo54anousu ";
  $sWhere = " o54_programa = $oPost->o54_programa ";// and o41_orgao = $o41_orgao";
  $rsProgramaMaxAno = $clorcprograma->sql_record($clorcprograma->sql_query_file(null,null,$sCampos,null,$sWhere));
	if ($clorcprograma->numrows > 0){
  	db_fieldsmemory($rsProgramaMaxAno,0);
  	$aAnousu = "(";
  	$anousu_atual = db_getsession('DB_anousu');
  	$virgula = "";
  	for($iInd = $anousu_atual; $iInd <= $maxo54anousu; $iInd++){
  		$aAnousu .= $virgula.$iInd;
  		$virgula = ",";
  	}
  	$aAnousu .= ")";

  	$sSqlProgramaPpa = "select * from  ppadotacao
  													where o08_programa = $oPost->o54_programa and o08_ano in $aAnousu";
  	$rsSqlProgramaPpa = db_query($sSqlProgramaPpa);
  	if(pg_num_rows($rsSqlProgramaPpa) > 0){
  		$lSqlErro = true;
  		$sMsgErro = "Usuário:\\n\\nPrograma encontra-se em estimativas do ppa\\n\\n";
  	}

  }
  //die($sSqlProgramaPpa);
  //$lSqlErro = true;
	if(!$lSqlErro){
		for($iInd = $anousu_atual; $iInd <= $maxo54anousu; $iInd++){
			$anoUsu = $iInd;
		  //$clorcprogramaorgao->excluir(null," o12_orcprograma = {$oPost->o54_programa} and o12_anousu = {$oPost->o54_anousu}  ");
		  $clorcprogramaorgao->excluir(null," o12_orcprograma = {$oPost->o54_programa} and o12_anousu = $anoUsu  ");

		  if ( $clorcprogramaorgao->erro_status == "0" ) {
		  	$lSqlErro = true;
		  }

		  $sMsgErro = $clorcprogramaorgao->erro_msg;


		  if ( !$lSqlErro ) {

		    //$clorcprogramaunidade->excluir(null," o14_orcprograma = {$oPost->o54_programa} and o14_anousu = {$oPost->o54_anousu}  ");
		    $clorcprogramaunidade->excluir(null," o14_orcprograma = {$oPost->o54_programa} and o14_anousu = $anoUsu  ");

		    if ( $clorcprogramaunidade->erro_status == "0" ) {
		  	  $lSqlErro = true;
		    }

		    $sMsgErro = $clorcprogramaunidade->erro_msg;

		  }


		  if ( !$lSqlErro ) {

		    //$clorcindicaprograma->excluir(null," o18_orcprograma = {$oPost->o54_programa} and o18_anousu = {$oPost->o54_anousu}  ");
		    $clorcindicaprograma->excluir(null," o18_orcprograma = {$oPost->o54_programa} and o18_anousu = $anoUsu  ");

		    if ( $clorcindicaprograma->erro_status == "0" ) {
		  	  $lSqlErro = true;
		    }

		    $sMsgErro = $clorcindicaprograma->erro_msg;

		  }

		  if ( !$lSqlErro ) {

				$clorcprogramahorizontetemp->excluir(null," o17_programa = {$oPost->o54_programa} and o17_anousu = $anoUsu  ");

				if ( $clorcprogramahorizontetemp->erro_status == "0" ) {
			  	$lSqlErro = true;
				}

				$sMsgErro = $clorcprogramahorizontetemp->erro_msg;

		  }

		  if ( !$lSqlErro ) {

		    //$clorcprograma->excluir($oPost->o54_anousu,$oPost->o54_programa);
		    $clorcprograma->excluir($anoUsu,$oPost->o54_programa);

		    if ( $clorcprograma->erro_status == 0 ) {
		  	  $lSqlErro = true;
		    }

		    $sMsgErro = $clorcprograma->erro_msg;

		  }

		}


	}

  db_fim_transacao($lSqlErro);

} else if(isset($oGet->chavepesquisa)) {

   $db_opcao = 3;
   $result   = $clorcprograma->sql_record($clorcprograma->sql_query($oGet->chavepesquisa,$oGet->chavepesquisa1));
   db_fieldsmemory($result,0);
   $db_botao = true;
//   if ($cldb_config->numrows == 0){
//        $db_botao = false;
//   }
//
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table style="padding-top:15px;" align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <center>
	<?
	include("forms/db_frmorcprograma.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
//if ($cldb_config->numrows == 0){
//     db_msgbox("Somente instituicao prefeitura esta autorizada para este procedimento.Verifique.");
//}

if( isset($oPost->excluir) ){
  if( $lSqlErro ){
  	db_msgbox($sMsgErro);
    echo "<script>parent.iframe_g1.location.href='orc1_orcprograma013.php?chavepesquisa={$oPost->o54_anousu}&chavepesquisa1={$oPost->o54_programa}';</script> ";
  }else{
  	db_msgbox($sMsgErro);
    echo "<script>parent.iframe_g1.location.href='orc1_orcprograma013.php';</script> ";
  }
}

if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>