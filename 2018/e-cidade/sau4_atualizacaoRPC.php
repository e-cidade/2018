<?php
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

require ("libs/db_stdlib.php");
require ("libs/db_utils.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("dbforms/db_funcoes.php");
include ("libs/JSON.php");

$objJson = new services_json ( );
$objParam = $objJson->decode ( str_replace ( "\\", "", $_POST ["json"] ) );

$objRetorno = new stdClass ( );
$objRetorno->status = 1;
$objRetorno->message = '';

switch ($objParam->exec) {
	case "getCompetencia":
		$clsau_atualiza = db_utils::getDao( "sau_atualiza" );
		$resAtualiza = $clsau_atualiza->sql_record ( $clsau_atualiza->sql_query ( null, "*", "s100_i_codigo desc limit 1", "" ) );
		$objAtualiza = db_utils::fieldsMemory ( $resAtualiza, 0 );
		if ($objAtualiza->s100_i_codigo != $objParam->s100_i_codigo) {
			$objRetorno->status  = 2;
			$objRetorno->message = urlencode ( "Remova a última competência primeiramente" );
		} else {
			$arrTabelas  = array( 
                      array( "tabela"=> "sau_prochabilitacao","campos"=>"sd77_i_anocomp,sd77_i_mescomp" ),
							        array( "tabela"=> "sau_execaocompatibilidade","campos"=>"sd67_i_anocomp,sd67_i_mescomp" ),  
                      array( "tabela"=> "sau_proccompativel", "campos"=>"sd66_i_anocomp,sd66_i_mescomp"),
                      array( "tabela"=> "sau_proccbo", "campos"=>"sd96_i_anocomp,sd96_i_mescomp"),
                      array( "tabela"=> "sau_procsiasih", "campos"=>"sd94_i_anocomp,sd94_i_mescomp"),
                      array( "tabela"=> "sau_siasih", "campos"=>"sd92_i_anocomp,sd92_i_mescomp"),							 
                      array( "tabela"=> "sau_procservico", "campos"=>"sd88_i_anocomp,sd88_i_mescomp"),
                      array( "tabela"=> "sau_servclassificacao", "campos"=>"sd87_i_anocomp,sd87_i_mescomp"),
                      array( "tabela"=> "sau_procregistro", "campos"=>"sd85_i_anocomp,sd85_i_mescomp"),
                      array( "tabela"=> "sau_procorigem", "campos"=>"sd95_i_anocomp,sd95_i_mescomp"),
                      array( "tabela"=> "sau_procmodalidade","campos"=>"sd83_i_anocomp,sd83_i_mescomp" ),
                      array( "tabela"=> "sau_procleito", "campos"=>"sd81_i_anocomp,sd81_i_mescomp"),
                      array( "tabela"=> "sau_procincremento", "campos"=>"sd79_i_anocomp,sd79_i_mescomp"),
                      array( "tabela"=> "sau_procdetalhe","campos"=>"sd74_i_anocomp,sd74_i_mescomp" ),
                      array( "tabela"=> "sau_proccid","campos"=>"sd72_i_anocomp,sd72_i_mescomp" ),					
                      array( "tabela"=> "sau_procedimento", "campos"=>"sd63_i_anocomp,sd63_i_mescomp"),
                      array( "tabela"=> "sau_detalhe",  "campos"=>"sd73_i_anocomp, sd73_i_mescomp" ),
                      array( "tabela"=> "sau_financiamento", "campos"=>" sd65_i_anocomp, sd65_i_mescomp" ),
                      array( "tabela"=> "sau_formaorganizacao", "campos"=>"sd62_i_anocomp,sd62_i_mescomp" ),
                      array( "tabela"=> "sau_subgrupo", "campos"=>"sd61_i_anocomp,sd61_i_mescomp" ),
                      array( "tabela"=> "sau_grupohabilitacao", "campos"=>" sd75_i_anocomp, sd75_i_mescomp" ),
                      array( "tabela"=> "sau_grupo",  "campos"=>"sd60_i_anocomp, sd60_i_mescomp" ),
                      array( "tabela"=> "sau_habilitacao", "campos"=>" sd75_i_anocomp, sd75_i_mescomp" ),
                      array( "tabela"=> "sau_modalidade",  "campos"=>" sd82_i_anocomp, sd82_i_mescomp" ),
                      array( "tabela"=> "sau_registro",  "campos"=>" sd84_i_anocomp, sd84_i_mescomp" ),
                      array( "tabela"=> "sau_rubrica",  "campos"=>"sd64_i_anocomp, sd64_i_mescomp" ),
                      array( "tabela"=> "sau_servico",  "campos"=>"sd86_i_anocomp, sd86_i_mescomp" ),
                      array( "tabela"=> "sau_tipoleito","campos"=>"sd80_i_anocomp, sd80_i_mescomp" ),
                      array( "tabela"=> "sau_atualiza", "campos"=>"s100_i_anocomp,s100_i_mescomp" )							
							);
			 db_inicio_transacao();		
			 $objRetorno->message = urlencode(("Registro efetuado com sucesso"));
	          for( $x_arq=0; $x_arq<sizeof( $arrTabelas ); $x_arq++ ){	            	                  	         
				$arq_tb     = $arrTabelas[ $x_arq ]["tabela"];
				$arq_cp     = $arrTabelas[ $x_arq ]["campos"];											
				$arr_campos = explode(",",$arq_cp );					
				$tab = db_utils::getDao( $arq_tb );					      		  	            
		        if($arq_tb!='sau_grupohabilitacao'){
				      $resAtualiza=$tab->excluir(null, " {$arr_campos[0]} = {$objParam->s100_i_anocomp} and {$arr_campos[1]} = {$objParam->s100_i_mescomp} ");		        		
		        } else {
				      $resAtualiza=$tab->excluir(null, 
				                                 " sd76_i_habilitacao in (select sd75_i_codigo from sau_habilitacao 
				                                 where sd75_i_anocomp = {$objParam->s100_i_anocomp} and 
				                                 sd75_i_mescomp = {$objParam->s100_i_mescomp}) ");
		        }
				    if( $tab->numrows_excluir == 0 && $tab->erro_status == "0" ){
						
				    	$objRetorno->status  = 2;						
						  $objRetorno->message = urlencode( $tab->erro_msg );
					   	break;										  	
		        }		          							
			  } ////termina for	
			  db_fim_transacao($objRetorno->status  == 2 );
			  			  			 			 
		}////termina o else
		break;
}

echo $objJson->encode ( $objRetorno );
?>