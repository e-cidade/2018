<?php
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
	require_once("libs/db_usuariosonline.php");
	require_once("classes/db_sepultamentos_classe.php");
	require_once("classes/db_sepulturas_classe.php");
	require_once("classes/db_sepulta_classe.php");
	require_once("classes/db_lotecemit_classe.php");
	require_once("classes/db_ossoario_classe.php");
	require_once("classes/db_restosgavetas_classe.php");
	require_once("classes/db_gavetas_classe.php");
	require_once("dbforms/db_funcoes.php");
	
	parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

  db_postmemory($HTTP_POST_VARS);
	
	$clsepultamentos = new cl_sepultamentos;
	$cllotecemit     = new cl_lotecemit;
	$clsepulturas    = new cl_sepulturas;
	$clsepulta       = new cl_sepulta;
	$clossoario      = new cl_ossoario;
	$clrestosgavetas = new cl_restosgavetas;
	$clgavetas       = new cl_gavetas;
	
	if (!isset($db_opcao)) {
		$db_opcao = 1;
	}
	
	$lErro      = false;
	$sErroBanco = _M("tributario.cemiterio.cem1_sepultamentos003.alteracao");
	$db_botao   = true;
	

	/**
	 * Utilizado para liberar vaga no lote quando deletado a localizacao antiga.
	 */
	if (!isset($lotecemit)) {
		$lotecemit = "";
	}
	
	
	if ( isset($alterar) || isset($incluir) ) {
		
		db_inicio_transacao();

		/**
		 * Caso seja alteração, exlcui os registro das tabelas corretas e insere novamente.
		 */
		if ( isset($alterar) ) {
		 
			if ($tipoant == 1) {
				/**
				 * Sepulturas
				 */
				$clsepulta->excluir( $codigoant );
				if ($clsepulta->erro_status == 0) {
					
					$lErro = true;
					$sErroBanco = $clsepulta->erro_msg;
				}
			} elseif ($tipoant == 2) {
				/**
				 * Ossoário geral
				 */
				$clossoario->excluir( $codigoant );
				if ($clossoario->erro_status == 0) {
					
					$lErro = true;
					$sErroBanco = $clossoario->erro_msg;
				}
					
			} elseif ($tipoant == 3 || $tipoant == 4) {
				/**
				 * Ossoário particular ou Jazigos
				 */

				/**
				 * Apaga todas as gavetas do cara no Jazigo
				 */
				if ($tipoant == 4) {
					
					$clgavetas->excluir(null, "cm27_i_restogaveta = {$codigoant}");
				}
				
				$clrestosgavetas->excluir($codigoant);
				if ($clrestosgavetas->erro_status == 0) {
					
					$lErro = true;
					$sErroBanco  = $clrestosgavetas->erro_msg;
				}	
				
				
			}
			
			if ($lotecemit != "") {
				
				$cllotecemit->cm23_i_codigo = $lotecemit;
				$cllotecemit->cm23_c_situacao = 'D';
				$cllotecemit->alterar($lotecemit);
				if (!$lErro && $cllotecemit->erro_status == 0 ) {
					$lErro = true;
					$sErroBanco = $cllotecemit->erro_msg;
				}
			}
			
			$incluir = true;
		}
		
		
		
		//incluir
		if ( isset($incluir) ) {
		 
		  if ($local == 1) {
		   
		    //sepulturas
		    $cllotecemit->cm23_i_codigo = $cm23_i_codigo;
		    $cllotecemit->cm23_c_situacao = 'O';
		    $cllotecemit->alterar( $cm23_i_codigo );
		   
		    $clsepulta->incluir(null);
		    if ($clsepulta->erro_status == 0) {
		    	
		    	 $lErro = true;
		    	 $sErroBanco = $clsepulta->erro_msg;
		    } else {
		    
			    $codigoant = $clsepulta->cm24_i_codigo;
			    $tipoant = 1;
		    }
		    
		  } elseif($local == 2) {
		    
		    //ossoario geral
		    $clossoario->cm06_d_entrada = date("Y-m-d",db_getsession("DB_datausu"));
		    $clossoario->incluir(null);
		    if ($clossoario->erro_status == 0) {
		    	
			    $lErro = true;
			    $sErroBanco = $clossoario->erro_msg;
		    } else {
		    
		    	$codigoant = $clossoario->cm06_i_codigo;
		    	$tipoant = 2;
		    }
		    
		  } elseif($local == 3) {
		    
		    //ossoario particular / restos
		    $cllotecemit->cm23_i_codigo = $cm23_i_codigo;
		    $cllotecemit->cm23_c_situacao = 'O';
		    $cllotecemit->alterar( $cm23_i_codigo );
		    
		    $clrestosgavetas->incluir(null);
		    if ($clrestosgavetas->erro_status == 0) {
		    	 
		    	$lErro = true;
		    	$sErroBanco = $clrestosgavetas->erro_msg;
		    } else {
		    
		    	$codigoant = $clrestosgavetas->cm26_i_codigo;
		    	$tipoant = 3;
		    }
		    
		  } elseif($local == 4) {
		   
		    //jazigo
		    $cllotecemit->cm23_i_codigo = $cm23_i_codigo;
		    $cllotecemit->cm23_c_situacao = 'O';
		    $cllotecemit->alterar( $cm23_i_codigo );
		    $clrestosgavetas->incluir(null);
		    
		    if ($clrestosgavetas->erro_status == 0) {
		    
		    	$lErro = true;
		    	$sErroBanco = $clrestosgavetas->erro_msg;
		    } else {
		    
		    	$codigoant = $clrestosgavetas->cm26_i_codigo;
		    	$tipoant = 4;
		    }
		    
		    //Gavetas
		    $clgavetas->cm27_i_restogaveta = $clrestosgavetas->cm26_i_codigo;
		    $clgavetas->incluir(null);
		    if ($clgavetas->erro_status == 0) {
		    
		    	$lErro = true;
		    	$sErroBanco = $clgavetas->erro_msg;
		    }
		  }
		 
		}
	
		db_fim_transacao($lErro);
	
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
	
	<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
		<?php 
			require_once ('forms/db_frmSepultamentosNovo.php');
		?>
	</body>
</html>
<?php

	if ( isset($incluir) || isset($alterar)) {
      
    db_msgbox($sErroBanco);
    
    if ( ! $lErro) { 	
      
      echo "<script>";
      echo " parent.document.formaba.a4.disabled=false;";
      echo " parent.document.formaba.a2.disabled=true; ";
      echo " parent.document.formaba.a3.disabled=true; ";
      echo " top.corpo.iframe_a1.location.href='cem1_sepultamentos001.php';";
      echo " parent.mo_camada('a4'); ";
      echo "</script>";
      
    }
    
    
// 		if ($local == 1) {

// 			db_msgbox($clsepulta->erro_msg);
			
			
// 		  if ($clsepulta->erro_status != "0") { 
// 				$OK = 1; 
// 			}
// 	 	} elseif ($local == 2) {

// 			db_msgbox($clossoario->erro_msg);
//   	 	if ($clossoario->erro_status != "0") { 
//      		$OK = 1; 
// 			}
// 	 	} elseif ($local == 3) {

// 			db_msgbox($clrestosgavetas->erro_msg);
// 	  	if ($clrestosgavetas->erro_status != "0") { 
// 				$OK = 1; 
// 			}
		
// 		} elseif ($local == 4) {

// 		  db_msgbox($clgavetas->erro_msg);
// 		  if ($clgavetas->erro_status != "0") { 
// 				$OK = 1; 
// 			}
// 		}
	 
// 		//se não deu erro, volta à página inicial do cadastro
// 		if(@$OK == 1){
// 		  echo "<script>";
// 		  echo " parent.document.formaba.a4.disabled=false;";
// 		  echo " parent.document.formaba.a2.disabled=true; ";
// 		  echo " parent.document.formaba.a3.disabled=true; ";
// 		  echo " top.corpo.iframe_a1.location.href='cem1_sepultamentos001.php';";
// 		  echo " parent.mo_camada('a4'); ";
// 		  echo "</script>";
// 		}
		
	}
?>