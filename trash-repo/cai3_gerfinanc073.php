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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_sql.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_notiusu_classe.php");
require_once("classes/db_notinumcgm_classe.php");
require_once("classes/db_notiinscr_classe.php");
require_once("classes/db_notimatric_classe.php");
require_once("classes/db_notidebitos_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);


$sDataVenc     = date("Y-m-d",$oPost->H_DATAUSU);

$clnotiusu     = new cl_notiusu();
$clnotinumcgm  = new cl_notinumcgm();
$clnotiinscr   = new cl_notiinscr();
$clnotimatric  = new cl_notimatric();
$clnotidebitos = new cl_notidebitos();

if ($oPost->ver_matric != 0) {
  
  $iValorAcesso     = $oPost->ver_matric;
  $sDescrAcesso     = "matric";
  $rsConsultaNotif  = $clnotimatric->sql_record($clnotimatric->sql_query_arrecad(null, null, "distinct k55_notifica as codnotif", null, " k55_matric = {$iValorAcesso} and k43_notifica is not null "));
  $iNroNotificacoes = $clnotimatric->numrows;
  
} else if ($oPost->ver_inscr != 0) {
  
  $iValorAcesso     = $oPost->ver_inscr;
  $sDescrAcesso     = "inscr";
  $rsConsultaNotif  = $clnotiinscr->sql_record($clnotiinscr->sql_query_arrecad(null, null, " distinct k56_notifica  as codnotif", null, " k56_inscr = {$iValorAcesso} and k43_notifica is not null "));
  $iNroNotificacoes = $clnotiinscr->numrows;
  
} else {
  
  $iValorAcesso     = $oPost->ver_numcgm;
  $sDescrAcesso     = "cgm";
  $rsConsultaNotif  = $clnotinumcgm->sql_record($clnotinumcgm->sql_query_arrecad(null, null, "distinct k57_notifica as codnotif", null, " k57_numcgm = {$iValorAcesso} and k43_notifica is not null "));
  $iNroNotificacoes = $clnotinumcgm->numrows;
  
}


if ($oPost->ver_numcgm != 0) {
  $iNumcgm = $oPost->ver_numcgm;
}

$sNumpres = "";
$iNroVars = count($_POST);

for ($i = 0; $i < $iNroVars; $i++) {
	
  if (db_indexOf(key($_POST), "CHECK")) {
  	
  	if ( $_POST["tipo_debito"] == 3 || $_POST["tipo_debito"] == 5) {
  	  $sNumpres .= 'N'.$_POST[key($_POST)];
  	} else { 
      $sNumpres .= $_POST[key($_POST)];
	  }
	  
  }
  
  next($_POST);
}

if (isset($oGet->marcarvencidas) && isset($oGet->marcartodas)) {
	
	if ($oGet->marcarvencidas == 'true' && $oGet->marcartodas == 'false') {
		
		$aNumpres   = split("N",$sNumpres);
	  $sNumpres   = "";
	  $sNumPreAnt = "";
	  $sAuxiliar  = "";
	  for ($iInd = 0; $iInd < count($aNumpres); $iInd++) {
	    
	    if ($aNumpres[$iInd] == "") {
	      continue;   
	    }
	    
	    $iNumpre = split("P",$aNumpres[$iInd]);  
	    $iNumpar = split("P", strstr($aNumpres[$iInd],"P"));
	    $iNumpar = split("R",$iNumpar[1]);
	    $iReceit = $iNumpar[1];
	    $iNumpar = $iNumpar[0];
	    $iNumpre = $iNumpre[0];
	    
	    $sSqlArrecad  = "  select *                               ";
	    $sSqlArrecad .= "    from arrecad                         "; 
	    $sSqlArrecad .= "   where k00_numpre   = {$iNumpre}       "; 
	    $sSqlArrecad .= "     and k00_numpar   = {$iNumpar}       ";
	    $sSqlArrecad .= "     and k00_dtvenc   > '{$sDataVenc}'   ";
	    $rsSqlArrecad = db_query($sSqlArrecad);
	    $iNumRows     = pg_num_rows($rsSqlArrecad);
	    if ($iNumRows == 0) {
	      
	      if ($_POST["tipo_debito"] == 3 || $_POST["tipo_debito"] == 5) {
	        
	        if (empty($sNumPreAnt) || $sNumPreAnt != $iNumpre) {
	          
	          $sNumPreAnt = $iNumpre;
	          $sAuxiliar  = "N";
	        }
	        
	        $sNumpres .= "{$sAuxiliar}N".$iNumpre."P".$iNumpar."R".$iReceit;
	        $sAuxiliar = ""; 
	      } else { 
	        $sNumpres .= 'N'.$iNumpre."P".$iNumpar."R".$iReceit;
	      }
	    }
	
	  }
	}

}
//die();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" content="0">
<script language="JavaScript" type="text/javascript"
	src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
  <form name="form1" method="post" action="cai3_gerfinanc072.php">
    <center>
	  <table>
		<tr>
		  <td>
		    <fieldset>
			  <legend align="center">
			  	<b> Notificações Existentes </b>
			  </legend>
			  <table>
				<tr>
				  <td>
  			  		<?
					  	  db_input("iNumcgm"     , 10, "", true, "hidden", 3);
					      db_input("sNumpres"    , 50, "", true, "hidden", 3);
					      db_input("sDescrAcesso", 20, "", true, "hidden", 3);
					      db_input("iValorAcesso", 10, "", true, "hidden", 3);
						    db_input("iCodNotif"	 , 10, "", true, "hidden", 3);
				      
  			  		  $sVirgula 	 = "";
			          $sNotificacoes = ""; 
			          
			          if ( $iNroNotificacoes > 0 ) {
			          	 
			            for ($i = 0; $i < $iNroNotificacoes; $i ++) {
			              $oNotificacoes  = db_utils::fieldsMemory($rsConsultaNotif, $i);
			              $sNotificacoes .= $sVirgula.$oNotificacoes->codnotif;
			              $sVirgula       = ",";
						      }
			            
			            $sCampos     = "k50_notifica,k50_procede,k50_dtemite,k50_obs,k50_instit,login";
			            $sSqlNotiusu = $clnotiusu->sql_query(null, "distinct {$sCampos}", "k50_notifica", 
			                                                 "k50_notifica in ({$sNotificacoes})");
			        
			            db_lovrot($sSqlNotiusu,10,"()","16","js_enviaNotif|k50_notifica",null,"NoMe",array(),false);
			          }
        			?>
  				  </td>
				</tr>
			  </table>
			</fieldset>
		  </td>
		</tr>
	  	<tr align="center" >
	  	  <td>
	  	    <input name="nova" type="submit" value="Nova Notificação"/>
	  	  </td>
	  	</tr>
	  </table>
	</center>
  </form>
</body>
<script>
	
	function js_enviaNotif(iNotif){
	   document.form1.iCodNotif.value = iNotif;
	   document.form1.submit();						
	}
	
	<?
	  if ($iNroNotificacoes == 0) {
	    echo "document.form1.submit();";
	  }
	?>
</script>
</html>