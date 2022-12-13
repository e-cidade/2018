<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_arrecad_classe.php");
require_once("classes/db_arresusp_classe.php");
require_once("classes/db_db_usuarios_classe.php");
require_once("classes/db_procjur_classe.php");
require_once("classes/db_suspensao_classe.php");
require_once("model/suspensaoDebitos.model.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

if (isset($oPost->H_DATAUSU)) {
  $sDataVenc = date("Y-m-d",$oPost->H_DATAUSU);	
}

$clarrecad	   	  = new cl_arrecad();
$clarresusp	   	  = new cl_arresusp();
$cldb_usuarios 	  = new cl_db_usuarios();
$clprocjur	   	  = new cl_procjur(); 
$clsuspensao   	  = new cl_suspensao();
$oSuspensaoDebito = new suspensaoDebitos();


if ( isset($oPost->incluir)  ) {

  $lSqlErro = false;

  db_inicio_transacao();
  
  try {
  	$oSuspensaoDebito->incluirSuspensao( $oPost->ar18_procjur,
  										 1,
  										 $oPost->ar18_obs,
  										 date("Y-m-d",db_getsession('DB_datausu')),
  										 db_hora(),
  										 db_getsession("DB_instit"),
  										 $oPost->ar18_usuario 
  									   );
  } catch (Exception $eException) {
	  $lSqlErro = true;
    $sMsgErro = $eException->getMessage();
  }
  
  
  if (!$lSqlErro) {

  	$aNumpres 	  = split("N",$oPost->sNumpres);
  	$aDadosDebito = array();

	  for ($i = 0; $i < count($aNumpres); $i++  ) {
		
		  if ($aNumpres[$i] == "") {
			  continue;		
		  }
		  
		  $iNumpre = split("P",$aNumpres[$i]);
	    $iNumpar = split("P", strstr($aNumpres[$i],"P"));
	    $iNumpar = split("R",$iNumpar[1]);
	    $iReceit = $iNumpar[1];
	    $iNumpar = $iNumpar[0];
	    $iNumpre = $iNumpre[0];
	
	    $oDebitos = new stdClass();
	    $oDebitos->iNumpre = $iNumpre;
	    $oDebitos->iNumpar = $iNumpar;
	    $oDebitos->iReceit = $iReceit;
	      
	    $aDadosDebito[] = $oDebitos;
	    
		}
			
	  try {
		  $oSuspensaoDebito->suspendeDebito($aDadosDebito);
		} catch (Exception $eException){
		  $lSqlErro = true;
	    $sMsgErro = $eException->getMessage();	  	  	
		}      
	
  }

  db_fim_transacao($lSqlErro);

} else {
	
  $sNumpres = "";
  $iNroVars = count($_POST);

  for ($i = 0; $i < $iNroVars; $i ++) {
  	
    if (db_indexOf(key($_POST), "CHECK")) {
    	
      if ($_POST[key($_POST)]{0} == "N") {
      	$sPrefix = "";
      } else {
      	$sPrefix = "N";
      }
      
      $sNumpres .= $sPrefix.$_POST[key($_POST)];

    }
    
    next($_POST);
  }
  
  
	if (isset($oGet->marcarvencidas) && isset($oGet->marcartodas)) {
	  
	  if ($oGet->marcarvencidas == 'true' && $oGet->marcartodas == 'false') {
	    
	    $aNumpres   = split("N",$sNumpres);
	    $sNumpres   = "";

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
		      $sNumpres .= "N".$iNumpre."P".$iNumpar."R".$iReceit;
	      }
	  
	    }
	  }
	
	}

}

$clprocjur->rotulo->label();
$clsuspensao->rotulo->label();

$db_opcao = 1;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" 		content="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" 			rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>

<body>
  <form name="form1" method="post" action="">
    <center>
  		<table>
  		  <tr>
  			<td valign="top">
  			  <fieldset>
  			  <legend><b>Suspensão de Débitos</b></legend>
  			  <table  height="140px;">
				<tr>
				  <td>
				  	<?
					  db_ancora("<b>Processo de Suspensão:</b>","js_pesquisaar18_procjur(true);",$db_opcao,"");
				  	?>
				  </td>
				  <td>
				  	<?
					  db_input("ar18_procjur",10,$Iar18_procjur,true,"text",$db_opcao,"onChange='js_pesquisaar18_procjur(false);'");
					  db_input("v62_descricao",40,"",true,"text",3,"");
					  
					  db_input("ar18_sequencial",10,"",true,"hidden",3,"");
					  db_input("sNumpres"		,50,"",true,"hidden",3,"");
				  	?>
				  </td>
				</tr>
				<tr>
				  <td>
				  	<? echo $Lar18_obs ?>
				  </td>
				  <td>
				  	<? 
				  	  db_textarea("ar18_obs"  ,3,51,$Iar18_obs,true,"text",1);
				  	?>
				  </td>
				</tr>
			    <tr>
				  <td>
				  	<b>Usuário :</b>
				  </td>
				  <td>
				  	<?
				  	   $rsNomeUsu 	 = $cldb_usuarios->sql_record($cldb_usuarios->sql_query(db_getsession('DB_id_usuario'),"id_usuario,nome",null,""));
				  	   $oNomeUsu  	 = db_utils::fieldsMemory($rsNomeUsu,0);
				  	   $nomeUsu 	 = $oNomeUsu->nome;
				  	   $ar18_usuario = $oNomeUsu->id_usuario;
				  	    
				  	   db_input("ar18_usuario",10,"",true,"hidden",3,"");
				  	   db_input("nomeUsu",54,"",true,"text",3,"");
				  	   
				  	?>
				  </td>
				</tr>
			  </table> 
			  <table align="center"> 
			    <tr>
			      <td>
			      	<input name="incluir" type="submit" value="Suspender" onClick="return js_validaCampos();">
			      </td>
			    </tr>
			  </table>
			  </fieldset>
			</td>
  			  <?				  	
				echo "<td valign='top'>";
				echo "	<fieldset>";
				echo "	<legend><b>Lista Débitos : </b></legend>";
				echo "  <table cellspacing='0' style='border:2px inset white'> ";
				echo "    <tr>";
				echo "      <th class='table_header' width='70px'><b>Numpre</b>	  </th>";	 
				echo "      <th class='table_header' width='20px'><b>Parc.</b>	  </th>";
				echo "      <th class='table_header' width='180x>'<b>Receita</b>  </th>";
				echo "      <th class='table_header' width='70px'><b>Dt.Venc.</b> </th>";
				echo "      <th class='table_header' width='70px'><b>Valor</b>	  </th>";
				echo "      <th class='table_header' width='70px'><b>Val Cor.</b> </th>";
				echo "      <th class='table_header' width='70px'><b>Jur</b>	  </th>";
				echo "      <th class='table_header' width='70px'><b>Mul.</b>	  </th>";
				echo "      <th class='table_header' width='70px'><b>Desc</b>	  </th>";
				echo "      <th class='table_header' width='70px'><b>Tot.</b>	  </th>";
				echo "      <th class='table_header' width='30px'><b>&nbsp;</b>	  </th>";
				echo "    </tr>";
				echo "    <tbody style='height:120px; overflow:scroll; overflow-x:hidden; background-color:white'>";
				  	
				$aNumpres 		  = split("N",$sNumpres);
				$sMsgExisteDebito = "";
				
			  	$nTotHis = 0;
			  	$nTotCor = 0;
			  	$nTotJur = 0;
			  	$nTotMul = 0;
			  	$nTotDes = 0;
			  	$nTotal  = 0;				
				
				for ($i = 0; $i < count($aNumpres); $i++  ) {
					
				  if ($aNumpres[$i] == "") {
				    continue;		
				  }

			  	  $iNumpre = split("P",$aNumpres[$i]);
		          $iNumpar = split("P", strstr($aNumpres[$i],"P"));
		          $iNumpar = split("R",$iNumpar[1]);

		          $iReceit = $iNumpar[1];
		          $iNumpar = $iNumpar[0];
		          $iNumpre = $iNumpre[0];
		          
				  if ($iReceit != 0) {
		            $sWhere	= " and arrecad.k00_receit = $iReceit ";
				  } else {
				  	$sWhere	= "";
				  }
		
		          $rsDebitosNovos = debitos_numpre($iNumpre,0,0,db_getsession('DB_datausu'),db_getsession("DB_anousu"),$iNumpar,"","",$sWhere);
				  if (!$rsDebitosNovos) {
				  	$iLinhasDebitosNovos = 0;
				  } else {
		            $iLinhasDebitosNovos = pg_num_rows($rsDebitosNovos);
				  } 
				  
				  for ($x=0; $x < $iLinhasDebitosNovos; $x++) {
				  	
				    $oDebitosNovos  = db_utils::fieldsMemory($rsDebitosNovos,$x);
				    	
			  	    echo "    <tr bgcolor='#CCCEEE'>";
			  	    echo "      <td class='linhagrid'>{$oDebitosNovos->k00_numpre}</td>";	 
			  	    echo "      <td class='linhagrid'>{$oDebitosNovos->k00_numpar}</td>";
			  	    echo "      <td class='linhagrid'>{$oDebitosNovos->k02_descr }</td>";
			  	    echo "      <td class='linhagrid'>".db_formatar($oDebitosNovos->k00_dtvenc,"d"). "</td>";
			  	    echo "      <td class='linhagrid'>".db_formatar($oDebitosNovos->vlrhis,"f").	 "</td>";
			  	    echo "      <td class='linhagrid'>".db_formatar($oDebitosNovos->vlrcor,"f").	 "</td>";
			  	    echo "      <td class='linhagrid'>".db_formatar($oDebitosNovos->vlrjuros,"f").	 "</td>";
			  	    echo "      <td class='linhagrid'>".db_formatar($oDebitosNovos->vlrmulta,"f").	 "</td>";
			  	    echo "      <td class='linhagrid'>".db_formatar($oDebitosNovos->vlrdesconto,"f")."</td>";
			  	    echo "      <td class='linhagrid'>".db_formatar($oDebitosNovos->total,"f").		 "</td>";
			  	    echo "    </tr>";
			  	    
			  	    $nTotHis += $oDebitosNovos->vlrhis;
			  	    $nTotCor += $oDebitosNovos->vlrcor;
			  	    $nTotJur += $oDebitosNovos->vlrjuros;
			  	    $nTotMul += $oDebitosNovos->vlrmulta;
			  	    $nTotDes += $oDebitosNovos->vlrdesconto;
			  	    $nTotal  += $oDebitosNovos->total;
			  	    
				  }
			    }
				echo "<tr><td style='height:auto;'>&nbsp;</td></tr>";
				echo "    </tbody>";
				
				echo "    <tfoot> ";
                echo "      <tr>  ";
                echo "        <td colspan='4' style='text-align:right;padding-top:0px' class='table_footer'>";
                echo "    		<b>Total:</b></td>";
                echo "    	  <td class='table_footer'>".db_formatar($nTotHis,"f")."</td>";
                echo "    	  <td class='table_footer'>".db_formatar($nTotCor,"f")."</td>";
                echo "    	  <td class='table_footer'>".db_formatar($nTotJur,"f")."</td>";
                echo "    	  <td class='table_footer'>".db_formatar($nTotMul,"f")."</td>";
                echo "    	  <td class='table_footer'>".db_formatar($nTotDes,"f")."</td>";
                echo "    	  <td class='table_footer'>".db_formatar($nTotal ,"f")."</td>";
                echo "    	  <td class='table_footer'>&nbsp;</td>";
                echo "    	</tr>";
				echo "    </tfoot>";
				
				echo "	</table>";
				echo "	</fieldset>";
				echo "</td>";
				  
			  ?>
  		</tr>
 	  </table>
    </center>
  </form>
</body>
</html>
<script>

function js_pesquisaar18_procjur(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_procjur','func_procjur.php?validaativ=true&funcao_js=parent.debitos.js_mostraprocjur1|v62_sequencial|v62_descricao','Pesquisa',true);
  }else{
     if(document.form1.ar18_procjur.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_procjur','func_procjur.php?validaativ=true&pesquisa_chave='+document.form1.ar18_procjur.value+'&funcao_js=parent.debitos.js_mostraprocjur','Pesquisa',false);
     }else{
       document.form1.v62_descricao.value = '';
     }
  }
}

function js_mostraprocjur(sDescr,lErro){
  if(lErro==true){ 
    document.form1.ar18_procjur.focus(); 
  	document.form1.v62_descricao.value = sDescr;    
    document.form1.ar18_procjur.value  = ''; 
  } else {
  	document.form1.v62_descricao.value = sDescr;
  }
  
}

function js_mostraprocjur1(iSeq,sDescr){

  document.form1.ar18_procjur.value	 = iSeq;
  document.form1.v62_descricao.value = sDescr;
  
  top.corpo.db_iframe_procjur.hide();
  
}


function js_validaCampos(){

  if ( document.form1.ar18_procjur.value == '' ) {
     alert('Processo não preenchido! Verifique');
     return false;
  }
  
  if ( document.form1.ar18_obs.value == '' ) {
     alert('Observação não preenchida! Verifique');
     return false;
  }  

} 


</script>
<?
	if ( isset($oPost->incluir) ) {
	  if($lSqlErro){	
		db_msgbox($sMsgErro);
		echo "<script>parent.document.formatu.pesquisar.click();</script>";
	  } else {
		db_msgbox( "Débitos suspensos com sucesso!");
	 	echo "<script>parent.document.formatu.pesquisar.click();</script>";	
	  }
	}
?>