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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_orcprojativ_classe.php");
require_once("classes/db_orcprojativunidaderesp_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clorcprojativ 			  = new cl_orcprojativ();
$clorcprojativunidaderesp = new cl_orcprojativunidaderesp();

$db_opcao = 22;
$db_botao = false;
$lSqlErro = false;

if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){

  db_inicio_transacao();
  $db_opcao = 2;

  $sSqlUltimoAno = "select max(o55_anousu) as anomaximo from orcprojativ";
  $rsUltimoAno   = $clorcprojativ->sql_record($sSqlUltimoAno);
  $iUltimoAno    = db_utils::fieldsMemory($rsUltimoAno, 0)->anomaximo;
  for ($iAno = $o55_anousu;$iAno <= $iUltimoAno; $iAno++) {

    $clorcprojativ->o55_anousu   = $iAno;
    $clorcprojativ->o55_projativ = $o55_projativ;
    $clorcprojativ->alterar($iAno,$o55_projativ);

    if ( $clorcprojativ->erro_status == 0 ){
    	$lSqlErro = true;
    }

  }
  if ( !$lSqlErro && isset($o13_unidaderesp) && trim($o13_unidaderesp) != "" ) {


  	$clorcprojativunidaderesp->o13_anousu 	    = $o55_anousu;
  	$clorcprojativunidaderesp->o13_orcprojativ	= $o55_projativ;
  	$clorcprojativunidaderesp->o13_unidaderesp	= $o13_unidaderesp;

  	if ( trim($o13_sequencial) != "") {
  	  $clorcprojativunidaderesp->alterar($o13_sequencial);
  	} else {
  	  $clorcprojativunidaderesp->incluir(null);
  	}

  	if ( $clorcprojativunidaderesp->erro_status == 0 ) {
  	  $lSqlErro = true;
  	}


  } else {

  	if ( trim($o13_sequencial) != "") {

  	  $clorcprojativunidaderesp->excluir($o13_sequencial);

  	  if ( $clorcprojativunidaderesp->erro_status == 0 ) {
  	    $lSqlErro = true;
  	  }

  	}

  }

  db_fim_transacao($lSqlErro);

}else if(isset($chavepesquisa)){

   $db_opcao = 2;
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
<table style="padding-top:25px;" align="center" border="0" cellspacing="0" cellpadding="0">
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
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  if($clorcprojativ->erro_status=="0"){
    $clorcprojativ->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clorcprojativ->erro_campo!=""){
      echo "<script> document.form1.".$clorcprojativ->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clorcprojativ->erro_campo.".focus();</script>";
    };
  }else{
    $clorcprojativ->erro(true,false);
  }
}

if ( isset($o55_projativ) ){
    $sQuery = "codprojativ={$o55_projativ}&anousu={$o55_anousu}";
    echo " <script> 															 	    ";
	echo "   parent.iframe_g2.location.href='orc1_orcprojativprogramfisica001.php?{$sQuery}';  ";
	echo "   parent.iframe_g3.location.href='orc1_orciniciativavinculoprojativ001.php?{$sQuery}';  ";
    echo "   parent.document.formaba.g2.disabled = false; 								";
    echo "   parent.document.formaba.g3.disabled = false; 								";
    echo " </script>																	";
} else {
    echo " <script>parent.document.formaba.g2.disabled = true;</script> 				";
    echo " <script>parent.document.formaba.g3.disabled = true;</script> 				";
}


if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>