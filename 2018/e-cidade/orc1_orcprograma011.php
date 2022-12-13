<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_orcprograma_classe.php");
require_once("classes/db_orcprogramahorizontetemp_classe.php");
require_once("classes/db_db_config_classe.php");

$oPost = db_utils::postMemory($_POST);

$clorcprograma 				= new cl_orcprograma();
$clorcprogramahorizontetemp = new cl_orcprogramahorizontetemp();
$cldb_config 			    = new cl_db_config;

$db_opcao = 1;
$db_botao = true;
$lSqlErro = false;
$sMsgErro = '';

//$resultado = $cldb_config->sql_record($cldb_config->sql_query_file(null,"*",null,"codigo = ".db_getsession("DB_instit")." and prefeitura is true"));
//if ($cldb_config->numrows == 0){
//     $db_botao = false;
//}
if( isset($oPost->incluir) ){

  db_inicio_transacao();
  $iAnoUsu       = db_getsession("DB_anousu");
  $sSqlUltimoAno = "select coalesce(max(o54_anousu), {$iAnoUsu}) as anomaximo from orcprograma where o54_anousu > $iAnoUsu";
  $rsUltimoAno   = $clorcprograma->sql_record($sSqlUltimoAno);
  $iUltimoAno    = db_utils::fieldsMemory($rsUltimoAno, 0)->anomaximo;
  for ($iAno = $oPost->o54_anousu;$iAno <= $iUltimoAno; $iAno++) {

    /**
     * Verificamos se o programa já existe no ano
     */
    $sSqlProgramaAno = $clorcprograma->sql_query_file($iAno, $oPost->o54_programa);
    $rsProgramaAno   = $clorcprograma->sql_record($sSqlProgramaAno);
    if ($clorcprograma->numrows > 0) {

      $sMsgErro  = "Já existe o Programa ".str_pad($oPost->o54_programa, 4, "0", STR_PAD_LEFT)." ";
      $sMsgErro .= "cadastrado para o ano {$iAno}.\\nInclusão cancelada.";
      $lSqlErro  = true;
      break;
    }

    if (!$lSqlErro) {

      $clorcprograma->o54_anousu   		      = $iAno;
      $clorcprograma->o54_programa 	        = $oPost->o54_programa;
      $clorcprograma->o54_descr 		        = $oPost->o54_descr;
      $clorcprograma->o54_codtri			      = $oPost->o54_codtri;
      $clorcprograma->o54_finali			      = $oPost->o54_finali;
      $clorcprograma->o54_programa 		      = $oPost->o54_programa;
      $clorcprograma->o54_publicoalvo	      = $oPost->o54_publicoalvo;
      $clorcprograma->o54_justificativa 	  = $oPost->o54_justificativa;
      $clorcprograma->o54_objsetorassociado = $oPost->o54_objsetorassociado;
      $clorcprograma->o54_tipoprograma 	  	= $oPost->o54_tipoprograma;
      $clorcprograma->o54_estrategiaimp   	= $oPost->o54_estrategiaimp;

      $clorcprograma->incluir($iAno, $oPost->o54_programa);
      if ( $clorcprograma->erro_status == 0 ) {
      	$lSqlErro = true;
        $sMsgErro = $clorcprograma->erro_msg;
        break;
      }
      $sMsgErro = $clorcprograma->erro_msg;
    }
    if ( !$lSqlErro && ( trim($oPost->o17_dataini) != "" || trim($oPost->o17_datafin) != "" || trim($oPost->o17_valor) != "" )) {

    	$clorcprogramahorizontetemp->o17_programa = $oPost->o54_programa;
    	$clorcprogramahorizontetemp->o17_anousu   = $iAno;
    	$clorcprogramahorizontetemp->o17_dataini  = "{$oPost->o17_dataini_ano}-{$oPost->o17_dataini_mes}-{$oPost->o17_dataini_dia}";
    	$clorcprogramahorizontetemp->o17_datafin  = "{$oPost->o17_datafin_ano}-{$oPost->o17_datafin_mes}-{$oPost->o17_datafin_dia}";
    	$clorcprogramahorizontetemp->o17_valor    = $oPost->o17_valor;
    	$clorcprogramahorizontetemp->incluir(null);
  	  if ( $clorcprogramahorizontetemp->erro_status == 0 ) {

  	    $lSqlErro = true;
  	    $sMsgErro = $clorcprogramahorizontetemp->erro_msg;
        break;
  	  }
    }
  }
  db_fim_transacao($lSqlErro);

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
		    <?php include("forms/db_frmorcprograma.php"); ?>
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

if( isset($oPost->incluir)) {

  if( $lSqlErro ){

  	db_msgbox($sMsgErro);

    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clorcprograma->erro_campo!=""){
      echo "<script> document.form1.".$clorcprograma->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clorcprograma->erro_campo.".focus();</script>";
    }

  } else {

  	db_msgbox($sMsgErro);

  	$sQuery = "codprograma={$clorcprograma->o54_programa}&anousu={$clorcprograma->o54_anousu}";

    echo " <script> 													 	 	        ";
	echo "   parent.iframe_g2.location.href='orc1_orcprogramaorgao001.php?{$sQuery}';   ";
	echo "   parent.iframe_g3.location.href='orc1_orcprogramaunidade001.php?{$sQuery}'; ";
	echo "   parent.iframe_g4.location.href='orc1_orcindicaprograma001.php?{$sQuery}';  ";
	echo "   parent.iframe_g5.location.href='orc1_orcobjetivovinculo001.php?{$sQuery}';  ";
	echo "   parent.document.formaba.g2.disabled = false; 							    ";
	echo "   parent.document.formaba.g3.disabled = false; 							    ";
	echo "   parent.document.formaba.g4.disabled = false; 							    ";
	echo "   parent.document.formaba.g5.disabled = false; 							    ";
	echo "   parent.mo_camada('g5');																	   		   	  	  										";
    echo "   parent.iframe_g1.location.href='orc1_orcprograma012.php?chavepesquisa={$clorcprograma->o54_anousu}&chavepesquisa1={$clorcprograma->o54_programa}'; ";
	echo " </script>												  										  	          										";


  }


  if ( isset($o54_programa) ){
    $sQuery = "codprograma={$o54_programa}&anousu={$o54_anousu}";

    echo " <script> 														 	    ";
	echo "   parent.iframe_g2.location.href='orc1_orcprogramaorgao001.php?{$sQuery}';   ";
	echo "   parent.iframe_g3.location.href='orc1_orcprogramaunidade001.php?{$sQuery}'; ";
	echo "   parent.iframe_g4.location.href='orc1_orcindicaprograma001.php?{$sQuery}';  ";
    echo "   parent.document.formaba.g2.disabled = false; 							";
    echo " </script>																";
  } else {
    echo " <script>parent.document.formaba.g2.disabled = true;</script> 			";
  }

}
?>
