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
require_once("classes/db_arrecad_classe.php");
require_once("classes/db_notitipo_classe.php");
require_once("classes/db_notificacao_classe.php");
require_once("classes/db_notiusu_classe.php");
require_once("classes/db_notidebitos_classe.php");
require_once("classes/db_notidebitosreg_classe.php");
require_once("classes/db_notinumcgm_classe.php");
require_once("classes/db_notiinscr_classe.php");
require_once("classes/db_notimatric_classe.php");
require_once("classes/db_db_usuarios_classe.php");
require_once("classes/db_notificadoc_classe.php");
require_once("classes/db_notificaarretipodoc_classe.php");

$oPost = db_utils::postMemory($_POST);

$clarrecad			       = new cl_arrecad();
$clnotitipo       	   = new cl_notitipo();
$clnotificacao    	   = new cl_notificacao();
$clnotiusu	   	  	   = new cl_notiusu();  
$clnotinumcgm	  	     = new cl_notinumcgm();
$clnotiinscr	  	     = new cl_notiinscr();
$clnotimatric     	   = new cl_notimatric();
$clnotidebitos    	   = new cl_notidebitos();
$clnotidebitosreg 	   = new cl_notidebitosreg(); 
$cldb_usuarios         = new cl_db_usuarios();
$clnotificadoc	       = new cl_notificadoc();
$clnotificaarretipodoc = new cl_notificaarretipodoc();

$lSqlErro 	   = false;
$lExisteDebito = false;
$db_opcao      = 1;

$clnotiusu->rotulo->label();
$clnotificacao->rotulo->label();
$clnotificadoc->rotulo->label();
$clnotificaarretipodoc->rotulo->label();


  if ( isset($oPost->incluir) ){

  	db_inicio_transacao();

  	//------------------ Tabela Noticiação
  	  
  	$clnotificacao->k50_procede = $oPost->selTipoNoti;
  	$clnotificacao->k50_dtemite = $oPost->k50_dtemite_ano."-".$oPost->k50_dtemite_mes."-".$oPost->k50_dtemite_dia;
  	$clnotificacao->k50_obs	    = $oPost->k50_obs;
  	$clnotificacao->k50_instit  = db_getsession("DB_instit"); 
	  $clnotificacao->incluir($oPost->k50_notifica);  	  

	
	if ($clnotificacao->erro_status == "0"){
 	   $lSqlErro = true;
  	   $sMsgErro = $clnotificacao->erro_msg;
  	}

  	
  	//------------------ Tabela Notiusu
    
  	$clnotiusu->k52_id_usuario = db_getsession("DB_id_usuario");
  	$clnotiusu->k52_data	   = $oPost->k50_dtemite_ano."-".$oPost->k50_dtemite_mes."-".$oPost->k50_dtemite_dia;
  	$clnotiusu->k52_hora	   = db_hora();
  	$clnotiusu->incluir($clnotificacao->k50_notifica);  	  
  	  
	if ($clnotiusu->erro_status == "0"){
 	   $lSqlErro = true;
  	   $sMsgErro = $clnotiusu->erro_msg;
  	}
  	
  	//------------------ Inclui Notimatric, Notiinscr, Notinumcgm
  	
  	switch ($oPost->sDescrAcesso) {
  		case "matric":
		  $clnotimatric->incluir($clnotificacao->k50_notifica,$oPost->iValorAcesso);
  		  if ($clnotimatric->erro_status == "0"){
 			$lSqlErro = true;
  		    $sMsgErro = $clnotimatric->erro_msg;
  		  }
		break;
  		case "inscr":	
  		  $clnotiinscr->incluir($clnotificacao->k50_notifica,$oPost->iValorAcesso);
  		  if ($clnotiinscr->erro_status == "0"){
 		    $lSqlErro = true;
  		    $sMsgErro = $clnotiinscr->erro_msg;
  		  }
  	    break;
  	}
  	
  	if ( $lSqlErro == false ) {
      
  	  $clnotinumcgm->incluir($clnotificacao->k50_notifica,$oPost->iNumcgm); 	
  	  
  	  if ($clnotinumcgm->erro_status == "0"){
 		    $lSqlErro = true;
  		    $sMsgErro = $clnotinumcgm->erro_msg;
  	  }
  	  
  	  
  	  //---------------- Busca Numpre, Numpar, Receita
  	  
  	  $aNumpres = split("N",$oPost->sNumpres);
  	  
	  for ($i = 0; $i < count($aNumpres); $i++  ) {
		if ($aNumpres[$i] == "") {
		  continue;		
		}
	  	$iNumpre = @split("P",$aNumpres[$i]);
        $iNumpar = @split("P", strstr($aNumpres[$i],"P"));
        $iNumpar = @split("R",$iNumpar[1]);
        $iReceit = @$iNumpar[1];
        $iNumpar = @$iNumpar[0];
        $iNumpre = @$iNumpre[0];
		
        $aDebitos[$iNumpre][$iNumpar]['Receita'] = $iReceit;

	  }
  	  
	  //---------------- Inclui nas Tabelas Notidebitos, Notidebitosreg
	  
	  $sDebitos = "";
      $sVirgula = "";

      foreach ( $aDebitos as $ChaveNumpre => $aNumpar ) {
      	
      	$sDebitos .= $sVirgula.$ChaveNumpre;
      	$sVirgula  = ",";
      	
      	foreach ( $aNumpar as $iNumpar => $ChaveReceita ) {
			      	
      		$clnotidebitos->incluir($clnotificacao->k50_notifica,$ChaveNumpre,$iNumpar);		
      		
      		if ($clnotidebitos->erro_status == "0"){
 		      $lSqlErro = true;
  		      $sMsgErro = $clnotidebitos->erro_msg;
  	  		}	
			
  	  		
  	  		//-------- Caso tenha retornado com Receita "0", consulta receita no arrecad. 
  	  		
  	  		if ( $ChaveReceita['Receita'] == 0 ) {
  	  		   
  	  		   $sSqlReceita  = " select distinct k00_receit        ";
  	  		   $sSqlReceita .= "   from arrecad                    "; 
  	  		   $sSqlReceita .= "  where k00_numpre = {$ChaveNumpre}"; 
  	  		   $sSqlReceita .= "    and k00_numpar = {$iNumpar}    ";	
  	  		   
  	  		   $rsReceita   = pg_query($sSqlReceita) or die($sSqlReceita);
  	  		   $iNroReceita = pg_num_rows($rsReceita);
  	  		   
  	  		   for ( $i=0; $i < $iNroReceita; $i++ ) {
  	  		   	$oReceita   = db_utils::fieldsMemory($rsReceita,$i);	
  	  		   	$aReceita[] = $oReceita->k00_receit;
  	  		   }
  	  		   
  	  		} else {
			  $aReceita[] = $ChaveReceita['Receita'];   	  			
  	  		}
  	  		
  	  		$rsDebitosNumpre   = debitos_numpre($ChaveNumpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"),$iNumpar);
			$iNroDebitosNumpre = pg_num_rows($rsDebitosNumpre); 

  	  		foreach ( $aReceita as $iReceita ) {
  	  		   
  	  		  for ($x=0; $x < $iNroDebitosNumpre; $x++) {
  	  		  	 	
  	  		  	$oDebitosNumpre  = db_utils::fieldsMemory($rsDebitosNumpre,$x);
  	  		  	
  	  		  	if ($oDebitosNumpre->k00_receit == $iReceita && $oDebitosNumpre->k00_numpre == $ChaveNumpre && $oDebitosNumpre->k00_numpar == $iNumpar ){
  	  		  		
  	  		  	  $clnotidebitosreg->k43_numpre   = $ChaveNumpre;
  	  		  	  $clnotidebitosreg->k43_numpar   = $iNumpar;
  	  		  	  $clnotidebitosreg->k43_receit   = $iReceita;
  	  		  	  $clnotidebitosreg->k43_notifica = $clnotificacao->k50_notifica;
  	  		  	  $clnotidebitosreg->k43_vlrcor	  = $oDebitosNumpre->vlrcor;
  	  		  	  $clnotidebitosreg->k43_vlrdes	  = $oDebitosNumpre->vlrdesconto;
  	  		      $clnotidebitosreg->k43_vlrjur	  = $oDebitosNumpre->vlrjuros;
  	  		      $clnotidebitosreg->k43_vlrmul   = $oDebitosNumpre->vlrmulta;
      	          $clnotidebitosreg->incluir(null);
      	          
	      	      if ($clnotidebitosreg->erro_status == "0"){
    	  	        $lSqlErro = true;
  			        $sMsgErro = $clnotidebitosreg->erro_msg;
  	  			  }	
  	  		  	}
  	  		  }  
  	  		}
  	  		unset($aReceita);
  	    }
      }
  	
  	  if (isset($oPost->k100_db_documento) && trim($oPost->k100_db_documento)!=""){

    	$clnotificadoc->k100_db_documento = $oPost->k100_db_documento;
    	$clnotificadoc->k100_notifica	  = $clnotificacao->k50_notifica;
    	$clnotificadoc->incluir(null);
    	if ($clnotificadoc->erro_status == 0 ){
    	  $lSqlErro = true;
    	  $sMsgErro = $clnotificadoc->erro_msg;	
    	}
      }
  	
  	}
  	
//	$lSqlErro = true;
  	db_fim_transacao($lSqlErro);
  
  	
  	
  } else if (isset($oPost->alterar)) {
  
  	
  	
    db_inicio_transacao();
  
	//------------------ Tabela Noticiação
  	  
  	$clnotificacao->k50_procede = $oPost->selTipoNoti;
  	$clnotificacao->k50_dtemite = $oPost->k50_dtemite_ano."-".$oPost->k50_dtemite_mes."-".$oPost->k50_dtemite_dia;
  	$clnotificacao->k50_obs	    = $oPost->k50_obs;
  	$clnotificacao->k50_instit  = db_getsession("DB_instit"); 
  	$clnotificacao->alterar($oPost->k50_notifica);  

	
	if ($clnotificacao->erro_status == "0"){
	   $lSqlErro = true;
  	   $sMsgErro = $clnotificacao->erro_msg;
  	}
	
    //------------------ Tabela Notiusu
    
  	$clnotiusu->k52_notifica   = $oPost->k50_notifica;
  	$clnotiusu->k52_id_usuario = db_getsession("DB_id_usuario");
  	$clnotiusu->k52_data	     = $oPost->k50_dtemite_ano."-".$oPost->k50_dtemite_mes."-".$oPost->k50_dtemite_dia;
  	$clnotiusu->k52_hora	     = db_hora();
  	$clnotiusu->alterar($oPost->k50_notifica);  	  

	if ($clnotiusu->erro_status == "0"){
	   $lSqlErro = true;
  	   $sMsgErro = $clnotiusu->erro_msg;
  	}
  	
  	//------------------ Inclui Notimatric, Notiinscr, Notinumcgm
  	
  	switch ($oPost->sDescrAcesso) {
  		case "matric":
		  $rsConsultaMatric = $clnotimatric->sql_record($clnotimatric->sql_query_file($clnotificacao->k50_notifica,$oPost->iValorAcesso));
  		  if ($clnotimatric->numrows == 0) {
		  	$clnotimatric->incluir($clnotificacao->k50_notifica,$oPost->iValorAcesso);
  		  	if ($clnotimatric->erro_status == "0"){
  		  	  $lSqlErro = true;
  		      $sMsgErro = $clnotimatric->erro_msg;
  		  	}
  		  }	
		break;
  		case "inscr":	
  		  $rsConsultaInscr = $clnotiinscr->sql_record($clnotiinscr->sql_query_file($clnotificacao->k50_notifica,$oPost->iValorAcesso));
  		  if ($clnotiinscr->numrows == 0) {
  		    $clnotiinscr->incluir($clnotificacao->k50_notifica,$oPost->iValorAcesso);
  		    if ($clnotiinscr->erro_status == "0"){
 		      $lSqlErro = true;
  		      $sMsgErro = $clnotiinscr->erro_msg;
  		    }
  		  }
  	    break;
  	}
  	
  	if ( $lSqlErro == false ) {
      
  	  $rsConsultaNumcgm = $clnotinumcgm->sql_record($clnotinumcgm->sql_query_file($clnotificacao->k50_notifica,$oPost->iNumcgm));
  	  if ($clnotinumcgm->numrows == 0) {
  	    $clnotinumcgm->incluir($clnotificacao->k50_notifica,$oPost->iNumcgm); 	
  	    if ($clnotinumcgm->erro_status == "0"){
 		    $lSqlErro = true;
  		    $sMsgErro = $clnotinumcgm->erro_msg;
  	    }
  	  }
  	  
  	  //---------------- Busca Numpre, Numpar, Receita
  	  
  	  $aNumpres = split("N",$oPost->sNumpres);
  	  
	  for ($i = 0; $i < count($aNumpres); $i++  ) {
		
		if ($aNumpres[$i] == "") {
		  continue;		
		}
		
	  	  $iNumpre = split("P",$aNumpres[$i]);
        $iNumpar = split("P", strstr($aNumpres[$i],"P"));
        $iNumpar = split("R",$iNumpar[1]);
        $iReceit = @$iNumpar[1];
        $iNumpar = @$iNumpar[0];
        $iNumpre = @$iNumpre[0];
		
        $aDebitos[$iNumpre][$iNumpar]['Receita'] = $iReceit;

	  }

	  //---------------- Inclui nas Tabelas Notidebitos, Notidebitosreg
	  
	  $sDebitos = "";
      $sVirgula = "";
	  
      
      
      foreach ( $aDebitos as $ChaveNumpre => $aNumpar ) {
      	
      	$sDebitos .= $sVirgula.$ChaveNumpre;
      	$sVirgula  = ",";
      	
      	foreach ( $aNumpar as $iNumpar => $ChaveReceita ) {
			      	
      		$clnotidebitos->incluir($clnotificacao->k50_notifica,$ChaveNumpre,$iNumpar);		
      		
      		if ($clnotidebitos->erro_status == "0"){
 		      $lSqlErro = true;
  		      $sMsgErro = $clnotidebitos->erro_msg;
  	  		}	
			
  	  		
  	  		//-------- Caso tenha retornado com Receita "0", consulta receita no arrecad. 
  	  		
  	  		if ( $ChaveReceita['Receita'] == 0 ) {
  	  		   
  	  		   $sSqlReceita  = " select distinct k00_receit        ";
  	  		   $sSqlReceita .= "   from arrecad                    "; 
  	  		   $sSqlReceita .= "  where k00_numpre = {$ChaveNumpre}"; 
  	  		   $sSqlReceita .= "    and k00_numpar = {$iNumpar}    ";	
  	  		   
  	  		   $rsReceita   = pg_query($sSqlReceita) or die($sSqlReceita);
  	  		   $iNroReceita = pg_num_rows($rsReceita);
  	  		   
  	  		   for ( $i=0; $i < $iNroReceita; $i++ ) {
  	  		   	$oReceita   = db_utils::fieldsMemory($rsReceita,$i);	
  	  		   	$aReceita[] = $oReceita->k00_receit;
  	  		   }
  	  		   
  	  		} else {
			  $aReceita[] = $ChaveReceita['Receita'];   	  			
  	  		}
  	  		
  	  		$rsDebitosNumpre   = debitos_numpre($ChaveNumpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"),$iNumpar);
  	  	
  	  		$iNroDebitosNumpre = pg_num_rows($rsDebitosNumpre);
  	  		
  	  		
  	  		foreach ( $aReceita as $iReceita ) {

   			  for ($x=0; $x < $iNroDebitosNumpre; $x++) {
   			    
   			  	$oDebitosNumpre  = db_utils::fieldsMemory($rsDebitosNumpre,$x);	
   			  	
   			  	if ($oDebitosNumpre->k00_receit == $iReceita && $oDebitosNumpre->k00_numpre == $ChaveNumpre && $oDebitosNumpre->k00_numpar == $iNumpar ){
  	  		  	  
  	  		      $clnotidebitosreg->k43_numpre   = $ChaveNumpre;
  	  		      $clnotidebitosreg->k43_numpar   = $iNumpar;
  	  		      $clnotidebitosreg->k43_receit   = $iReceita;
  	  		      $clnotidebitosreg->k43_notifica = $clnotificacao->k50_notifica;
  	  		      $clnotidebitosreg->k43_vlrcor	  = $oDebitosNumpre->vlrcor;
  	  		      $clnotidebitosreg->k43_vlrdes	  = $oDebitosNumpre->vlrdesconto;
  	  		      $clnotidebitosreg->k43_vlrjur	  = $oDebitosNumpre->vlrjuros;
  	  		      $clnotidebitosreg->k43_vlrmul   = $oDebitosNumpre->vlrmulta;
      	          $clnotidebitosreg->incluir(null);
			  
      	          if ($clnotidebitosreg->erro_status == "0"){
      	            $lSqlErro = true;
  		            $sMsgErro = $clnotidebitosreg->erro_msg;
  	  		      }
  	  		  	}  	
  	  		  }  
  	  	    }
  	  	    unset($aReceita);
  	    }
      }
  	  	
      if (isset($oPost->k100_db_documento) && trim($oPost->k100_db_documento)!=""){

      	$clnotificadoc->k100_db_documento = $oPost->k100_db_documento;
    	$clnotificadoc->alterar(null,$oPost->k50_notifica);
    	
    	if ($clnotificadoc->erro_status == 0 ){
    	  $lSqlErro = true;
    	  $sMsgErro = $clnotificadoc->erro_msg;	
    	}
      }
  	}
//  	$lSqlErro = true;

  	db_fim_transacao($lSqlErro);
  
  } else if (isset($oPost->iCodNotif) && trim($oPost->iCodNotif) != "") {
	
    $sCampoNotif   = "k50_notifica,k50_procede,k50_dtemite,k50_obs,k100_db_documento,db03_descr"; 
  	$rsExisteNotif = $clnotificacao->sql_record($clnotificacao->sql_query($oPost->iCodNotif,$sCampoNotif));
  	$oExisteNotif  = db_utils::fieldsMemory($rsExisteNotif,0);
  	
  	$k50_notifica 	   = $oExisteNotif->k50_notifica;  
  	$selTipoNoti  	   = $oExisteNotif->k50_procede;
  	$k50_dtemite  	   = $oExisteNotif->k50_dtemite;
  	$k50_obs	  	     = $oExisteNotif->k50_obs;
  	$k100_db_documento = $oExisteNotif->k100_db_documento;
  	$descrDocumento    = $oExisteNotif->db03_descr;
  	$db_opcao = 2;
  	
  }
  		
  if (isset($oPost->sNumpres)) {
	$sNumpres 	  = $oPost->sNumpres;
	$iNumcgm 	  = $oPost->iNumcgm;
	$sDescrAcesso = $oPost->sDescrAcesso;
	$iValorAcesso = $oPost->iValorAcesso;
  }

  	
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
  			  <legend><b>Notificação</b></legend>
  			  <table>
				<tr>
	  			  <td>
				    <?
					    //---- Valores retornados por POST
				      db_input("sNumpres"    ,10,"",true,"hidden",3,"");
			  		  db_input("sDescrAcesso",10,"",true,"hidden",3,"");
			    	  db_input("iValorAcesso",10,"",true,"hidden",3,"");
					    db_input("iNumcgm"     ,10,"",true,"hidden",3,"");
			    	?>
				  </td>
				</tr>
				<tr>
				  <td>
				  	<? echo $Lk50_notifica ?>
				  </td>
				  <td>
				  	<?
					  db_input("k50_notifica",10,$Ik50_notifica,true,"text",3,"");
				  	?>
				  </td>
				</tr>
				<tr>
				  <td>
				  	<? echo $Lk50_procede ?>
				  </td>
				  <td>
				  	<?
					  $rsNotiTipo = $clnotitipo->sql_record($clnotitipo->sql_query_file(null,"*","k51_procede",""));
			
					  if ( $clnotitipo->numrows > 0 ) {
					  	db_selectrecord("selTipoNoti",$rsNotiTipo,true,1,"");
					  } else {
					  	db_msgbox("Não há nenhum tipo de notificação cadastrado favor cadastrar.");
			      		echo "<script>parent.document.formatu.pesquisar.click();</script>";		  	
					  }
				  	?>
				  </td>
				</tr>
				<tr>
				  <td>
				  	<? echo $Lk50_dtemite ?>
				  </td>
				  <td>
				  	<?
				  	   $iDia = date("d",db_getsession("DB_datausu"));
				  	   $iMes = date("m",db_getsession("DB_datausu"));
				  	   $iAno = date("Y",db_getsession("DB_datausu"));  
				  	
				  	   db_inputdata("k50_dtemite",$iDia,$iMes,$iAno,true,"text",1); 	  
				  	?>
				  </td>
				</tr>
				<tr>
				  <td>
				  	<?
				  	   db_ancora("<b>Documento: </b>","js_pesquisaDoc(true);",$db_opcao);
				  	?>
				  </td>
				  <td>
				  	<?
				  	   db_input("k100_db_documento",10,"",true,"text",$db_opcao,"onChange='js_pesquisaDoc(false);'");
				  	   db_input("descrDocumento"   ,40,"",true,"text",3,"");
				  	?>
				  </td>
				</tr>
				<tr>
				  <td>
				  	<? echo $Lk50_obs ?>
				  </td>
				  <td>
				  	<? 
				  	  db_textarea("k50_obs"  ,3,51,$Ik50_obs,true,"text",1);
				  	?>
				  </td>
				</tr>
			    <tr>
				  <td>
				  	<b>Usuário :</b>
				  </td>
				  <td>
				  	<?
				  	   $rsNomeUsu = $cldb_usuarios->sql_record($cldb_usuarios->sql_query(db_getsession('DB_id_usuario'),"nome",null,""));
				  	   $oNomeUsu  = db_utils::fieldsMemory($rsNomeUsu,0);
				  	   $nomeUsu   = $oNomeUsu->nome;
				  	   
				  	   db_input("nomeUsu",54,"",true,"text",3,"");
				  	?>
				  </td>
				</tr>
			  </table> 
			  <table align="center"> 
			    <tr>
			      <td>
			      	<input name="<?=($db_opcao == 1?"incluir":"alterar")?>" type="submit" value="<?=($db_opcao == 1?"Incluir":"Alterar")?>">
			      </td>
			    </tr>
			  </table>
			  </fieldset>
			</td>
  			  <?				  	
				echo "<td valign='top'>";
				echo "	<fieldset>";
				echo "	<legend><b>Débitos Notificados : </b></legend>";
				echo "  <table cellspacing='0' style='border:2px inset white'> ";
				echo "    <tr>";
				echo "      <th class='table_header' width='70px'><b>Numpre</b>	  </th>";	 
				echo "      <th class='table_header' width='20px'><b>Parc.</b>	  </th>";
				echo "      <th class='table_header' width='180x>'<b>Tipo</b>     </th>";
				echo "      <th class='table_header' width='70px'><b>Dt.Venc.</b> </th>";
				echo "      <th class='table_header' width='70px'><b>Valor</b>	  </th>";
				echo "    </tr>";
				echo "    <tbody style=' height:185; overflow:scroll; overflow-x:hidden; background-color:white'>";
				  	
				if (isset($oPost->iCodNotif) && trim($oPost->iCodNotif)!= "") {

				  $sSqlNotiDebitos  = " select k00_numpre,									  			   ";
				  $sSqlNotiDebitos .= "		   k00_numpar,									  			   ";
				  $sSqlNotiDebitos .= "		   k00_dtvenc,									  			   ";
				  $sSqlNotiDebitos .= "		   arretipo.k00_descr,	    					  			   ";
				  $sSqlNotiDebitos .= "	  	   sum(k00_valor) as valor						  			   ";
				  $sSqlNotiDebitos .= "	  from notidebitos									  			   ";
				  $sSqlNotiDebitos .= "    	   inner join arrecad  on k00_numpre = k53_numpre 			   ";
				  $sSqlNotiDebitos .= "							  and k00_numpar = k53_numpar 			   ";
				  $sSqlNotiDebitos .= "	   	   inner join arretipo on arretipo.k00_tipo = arrecad.k00_tipo ";
				  $sSqlNotiDebitos .= "  where k53_notifica = {$oPost->iCodNotif}			  			   ";
				  $sSqlNotiDebitos .= "  group by k00_numpre,								  			   ";
				  $sSqlNotiDebitos .= "    		  k00_numpar,								  			   ";
				  $sSqlNotiDebitos .= "    		  k00_dtvenc,								  			   ";
				  $sSqlNotiDebitos .= "    		  arretipo.k00_descr						  			   "; 
				 
				  $rsNotiDebitos   = pg_query($sSqlNotiDebitos) or die($sSqlNotiDebitos);
				  $iNroNotiDebitos = pg_num_rows($rsNotiDebitos);

				  if ( $iNroNotiDebitos > 0 ) {
				  	
				  	for ($i=0; $i < $iNroNotiDebitos; $i++) {
				  		
				  	  $oNotiDebitos = db_utils::fieldsMemory($rsNotiDebitos,$i);	
				  	  
				  	  echo "    <tr>";
				  	  echo "      <td class='linhagrid'>{$oNotiDebitos->k00_numpre}</td>";	 
				  	  echo "      <td class='linhagrid'>{$oNotiDebitos->k00_numpar}</td>";
				  	  echo "      <td class='linhagrid'>{$oNotiDebitos->k00_descr }</td>";
				  	  echo "      <td class='linhagrid'>".db_formatar($oNotiDebitos->k00_dtvenc,"d")."</td>";
				  	  echo "      <td class='linhagrid'>".db_formatar($oNotiDebitos->valor,"f")		."</td>";
				  	  echo "    </tr>";
				  	  
				  	  $aTestaDebitos[$oNotiDebitos->k00_numpre][$oNotiDebitos->k00_numpar]['descr'] = $oNotiDebitos->k00_descr;  
				  	}
				  }
				}  	

				$aNumpres 		  = split("N",$oPost->sNumpres);
			  $sMsgExisteDebito = "";
				
				for ($i = 0; $i < count($aNumpres); $i++  ) {

				  if ($aNumpres[$i] == "") {
				    continue;		
				  }
				  
			  	    $iNumpre = @split("P",$aNumpres[$i]);
		          $iNumpar = @split("P", strstr($aNumpres[$i],"P"));
		          $iNumpar = @split("R",$iNumpar[1]);
		          $iReceit = @$iNumpar[1];
		          $iNumpar = @$iNumpar[0];
		          $iNumpre = @$iNumpre[0];
		          
		          
		          if (isset($aTestaDebitos)) {
		            foreach ($aTestaDebitos as $ChaveDebito => $aChave ) {
		          	  foreach ($aChave as $ChaveParcela => $ChaveDescr) {
		          	    if ($iNumpre == $ChaveDebito && $iNumpar == $ChaveParcela ){
		          	  	  $lExisteDebito     = true;
		          	  	  $sMsgExisteDebito .= "Débito:{$iNumpre} Parcela:{$iNumpar} - {$ChaveDescr['descr']}, ";
		          	    }
		          	  }
		            }
		          }
		      
		      $sSqlDebitosNovos  = " select k00_numpre,							  	  			      ";
				  $sSqlDebitosNovos .= "		k00_numpar,									        	  ";
				  $sSqlDebitosNovos .= "		k00_dtvenc,								      			  ";
				  $sSqlDebitosNovos .= "		arretipo.k00_descr,	    				    			  ";
				  $sSqlDebitosNovos .= "	    sum(k00_valor) as valor					    			  ";
				  $sSqlDebitosNovos .= "  from arrecad         							    			  ";
				  $sSqlDebitosNovos .= "	   inner join arretipo on arretipo.k00_tipo = arrecad.k00_tipo";
				  $sSqlDebitosNovos .= "  where k00_numpre = {$iNumpre} 		 		    			  ";
				  $sSqlDebitosNovos .= "    and k00_numpar = {$iNumpar}	 				    			  ";
				  $sSqlDebitosNovos .= "  group by k00_numpre,						  	    			  ";
				  $sSqlDebitosNovos .= "    	   k00_numpar,							    			  ";
				  $sSqlDebitosNovos .= "    	   k00_dtvenc,							    			  ";
				  $sSqlDebitosNovos .= "    	   arretipo.k00_descr					    			  "; 
				  $sSqlDebitosNovos .= "  order by k00_numpre,k00_numpar 			  	    			  ";

				  $rsDebitosNovos = pg_query($sSqlDebitosNovos) or die($sSqlDebitosNovos);
				  $oDebitosNovos  = db_utils::fieldsMemory($rsDebitosNovos,0);	

         	$rsDebitosNumpre   = debitos_numpre($oDebitosNovos->k00_numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"),$oDebitosNovos->k00_numpar);
         	$oDebitosNumpre    = db_utils::fieldsMemory($rsDebitosNumpre,0);
				  	  
			  	  echo "    <tr bgcolor='#CCCEEE' >";
			  	  echo "      <td class='linhagrid'>{$oDebitosNovos->k00_numpre}</td>";	 
			  	  echo "      <td class='linhagrid'>{$oDebitosNovos->k00_numpar}</td>";
			  	  echo "      <td class='linhagrid'>{$oDebitosNovos->k00_descr }</td>";
			  	  echo "      <td class='linhagrid'>".db_formatar($oDebitosNovos->k00_dtvenc,"d")."</td>";
			  	  echo "      <td class='linhagrid'>".db_formatar($oDebitosNumpre->total,"f")	 ."</td>";
			  	  echo "    </tr>";
			    }
				
				echo "    </tbody>";
				echo "	</table>";
				echo "	</fieldset>";
				echo "</td>";
				  
				if ($lExisteDebito) {
				  db_msgbox($sMsgExisteDebito." já estão na notificação {$oPost->iCodNotif}");
				  echo "<script>parent.document.formatu.pesquisar.click();</script>";
				}
			  ?>
  		  </tr>
  	    </table>
    </center>
  </form>
</body>
</html>
<script>
  
  function js_pesquisaDoc(lMostra){
  	if (lMostra){
	  js_OpenJanelaIframe("","db_iframe_db_documento","func_db_documento.php?chave_db03_tipodoc=1200&funcao_js=parent.js_preencheDoc|db03_docum|db03_descr","Pesquisa",true);  	
  	} else {
	  js_OpenJanelaIframe("","db_iframe_db_documento","func_db_documento.php?chave_db03_tipodoc=1200&funcao_js=parent.js_preencheDoc1&pesquisa_chave="+document.form1.k100_db_documento.value,"Pesquisa",false);
  	}
  }

  function js_preencheDoc(iChave,sChave){
    document.form1.k100_db_documento.value = iChave;
    document.form1.descrDocumento.value    = sChave;
    db_iframe_db_documento.hide();
  }

  function js_preencheDoc1(sChave,lErro){
  	document.form1.descrDocumento.value = sChave;
  	if (lErro) {
  	  document.form1.k100_db_documento.value = "";
  	  document.form1.k100_db_documento.focus();
  	}
  	db_iframe_db_documento.hide();
  }

</script>
<?
	if (isset($incluir) || isset($alterar)) {

		if($lSqlErro){	
			db_msgbox($sMsgErro);
			echo "<script>parent.document.formatu.pesquisar.click();</script>";
		} else {
			db_msgbox((isset($incluir)?"Inclusão":"Alteração")." feita com sucesso!");
			echo "<script>parent.document.formatu.pesquisar.click();</script>";	
		}
	
	}
?>