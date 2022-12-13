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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_itbiconstr_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_itbiconstrespecie_classe.php");
require_once("classes/db_itbiconstrtipo_classe.php");
require_once("classes/db_paritbi_classe.php");
require_once("classes/db_itbi_classe.php");
require_once("classes/db_caracter_classe.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_itbiconstrpadraoconstrutivo_classe.php");
	
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST); 

$clitbiconstr 			           = new cl_itbiconstr;
$clitbiconstrespecie 	         = new cl_itbiconstrespecie;
$clitbiconstrtipo		           = new cl_itbiconstrtipo;
$clitbiconstrpadraoconstrutivo = new cl_itbiconstrpadraoconstrutivo();
$clparitbi				             = new cl_paritbi();
$clitbi		 			               = new cl_itbi;
$clcaracter				             = new cl_caracter;
$cliframe_alterar_excluir      = new cl_iframe_alterar_excluir;

$sMensagens = 'tributario.itbi.itb1_itbiconstr001';

$db_opcao = 22;
$db_botao = false;
$lSqlErro = false;
$sErroMsg = "";

if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  db_inicio_transacao();
  $db_opcao = 2;
  $clitbiconstr->alterar($it08_codigo);
  
  if ( $clitbiconstr->erro_status == 0 ) {
  	$lSqlErro = true;
  }  

  if ( !$lSqlErro ) {
  	
    $clitbiconstrespecie->it09_caract = $it09_codigo;
    $clitbiconstrespecie->it09_codigo = $it08_codigo;
    $clitbiconstrespecie->alterar($it08_codigo);
  
    if ( $clitbiconstrespecie->erro_status == 0 ) {
  	  $lSqlErro = true;
      $sErroMsg = _M($sMensagens . '.especie_nao_informada');
    }    
  
    $clitbiconstrtipo->it10_caract = $it10_codigo;
    $clitbiconstrtipo->it10_codigo = $it08_codigo;
    $clitbiconstrtipo->alterar($it08_codigo);
    
    if ( $clitbiconstrtipo->erro_status == 0 ) {
  	  $lSqlErro = true;
      $sErroMsg = _M($sMensagens . '.tipo_nao_informado');
    }

    /**
     * Verifica se existe padrão construtivo
     */
    $sSql = $clitbiconstrpadraoconstrutivo->sql_query_file( $it08_codigo );
    $clitbiconstrpadraoconstrutivo->sql_record( $sSql );

    $clitbiconstrpadraoconstrutivo->it34_codigo = $it08_codigo;
    $clitbiconstrpadraoconstrutivo->it34_caract = $it34_codigo;

    if ( $clitbiconstrpadraoconstrutivo->numrows == 0 ) {
      $clitbiconstrpadraoconstrutivo->incluir($it08_codigo, $it34_codigo);
    } else {
      $clitbiconstrpadraoconstrutivo->alterar($it08_codigo);
    }


    if ( $clitbiconstrpadraoconstrutivo->erro_status == 0 ) {
      $lSqlErro = true;
      $sErroMsg = _M($sMensagens . '.padrao_construtivo_nao_informado');
    }

    if ( !$lSqlErro ) {
       if (!isset($it08_guia) or trim($it08_guia)=='') {
         $it08_guia = 'NULL';
       }
       $sWhere         = " it08_guia = {$it08_guia}";
       $rsConsultaArea = $clitbiconstr->sql_record($clitbiconstr->sql_query(null,"it08_areatrans",null,$sWhere));
       $iLinhasArea	   = $clitbiconstr->numrows;
	     $nTotalArea     = 0;
        
       for ( $iInd=0; $iInd < $iLinhasArea; $iInd++  ) {
       	
       	 $oDadosArea  = db_utils::fieldsMemory($rsConsultaArea,$iInd);
       	 $nTotalArea += $oDadosArea->it08_areatrans;
       	
       }
       
       $clitbi->it01_guia		   = $it08_guia;
       $clitbi->it01_areaedificada = "$nTotalArea";
       $clitbi->alterar($it08_guia);	
       
       if ( $clitbi->erro_status == 0 ) {
       	  $lSqlErro = true;
       }
       
    }    
    
  }
  
  
  db_fim_transacao($lSqlErro);
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clitbiconstr->sql_record($clitbiconstr->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);

   $result = $clitbiconstrespecie->sql_record($clitbiconstrespecie->sql_query($it08_codigo)); 
   db_fieldsmemory($result,0);
   $it09_codigo = $it09_caract;

   $result = $clitbiconstrtipo->sql_record($clitbiconstrtipo->sql_query($it08_codigo)); 
   db_fieldsmemory($result,0);
   $it10_codigo = $it10_caract;

   $result = $clitbiconstrpadraoconstrutivo->sql_record( $clitbiconstrpadraoconstrutivo->sql_query($it08_codigo) );
   $it34_codigo = null;

   if ($clitbiconstrpadraoconstrutivo->numrows > 0) {
     $rsItbiConstrPadraoConstrutivo = db_utils::fieldsMemory($result, 0);
     $it34_codigo = $rsItbiConstrPadraoConstrutivo->it34_caract;
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
<table align="center" style="padding-top:25px;" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td> 
    <center>
  <?
  include("forms/db_frmitbiconstr.php");
  ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  if ($clitbiconstr->erro_status == "0" || $clitbiconstrespecie->erro_status == "0" || $clitbiconstrtipo->erro_status == "0"
      || $clitbiconstrpadraoconstrutivo->erro_status == "0") {

    if (!empty($sErroMsg)) {
      db_msgbox($sErroMsg);
    } else {
      $clitbiconstr->erro(true, false);
    }

    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if($clitbiconstr->erro_campo!=""){
      echo "<script> document.form1.".$clitbiconstr->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clitbiconstr->erro_campo.".focus();</script>";
    };

  } else {

    $clitbiconstr->erro(true, false);
    echo "<script>
            parent.iframe_constr.location.href = 'itb1_itbiconstr001.php?it08_codigo=".$it08_codigo."&it08_guia=$it08_guia&tipo=$tipo'; 
          </script>";
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>