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
?>
<table width="790" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td height="430" align="left" valign="top" bgcolor="#CCCCCC">
		
		<center>
		
			<form name="form1" method="post">
		
				<table>
 
					<?php
					 
						$result = @$clsepultamentos->sql_record($clsepultamentos->sql_query_dados_sepultamento($sepultamento,"*, cm01_i_cemiterio, cgm.z01_nome as nome_sepultamento"));
						db_fieldsmemory($result,0);
						 
						/**
						 * Verifica qual tela deve vir selecionada inicialmente.
						 */
						if (!isset($tipoant)) {
							 
							$local     = '';
							$codigoant = '';
							
							/**
							 * Sepulturas selecionado
							 */
							if (!empty($cm24_i_codigo )) {
						
								$local     = 1;
								$codigoant = $cm24_i_codigo;
							} elseif ( !empty($cm06_i_codigo) ) {
								/**
								 * Ossário Geral
								 */
								$local     = 2;
								$codigoant = $cm06_i_codigo;
							} elseif ( !empty($cm26_i_codigo) ) {
									
								/**
								 * Ossário particular
								 */
								
								if ( empty($cm27_i_restogaveta) ) {
									$local = 3;
						
								} else {
									/**
									 * Jazigo
									 */
									$local = 4;
								}
						
								$codigoant = $cm26_i_codigo;
							}
						
						}
					 
			      db_input('db_opcao',     10,  $db_opcao,     true, 'hidden',	3);
	       		db_input('sepultamento', 10, @$sepultamento, true, 'hidden',  3);
	       		db_input('cemiterio',    10, @$cemiterio,    true, 'hidden',  3);
	       		 
	       	?>
	       
	      	<tr>
		       <td>Localização</td>
		       <td>
		         	<?php
			          $arrayValores = array( "0"=>"Selecione",
			                                 "1"=>"Sepultura",
			                                 "2"=>"Ossoario Geral",
			                                 "3"=>"Ossoario Particular",
			                                 "4"=>"Jazigo");
			          db_select("local",$arrayValores,true,2,"onchange='submit()'"); 
		         	?>
		         
		       </td>
		      </tr>
	      
	     	</table>
     
	     	<table>
		      <tr>
		       <td>
		       
		        <?php
		        
			        /**
			         * Se for a primeira vez na tela, define o "tipoant" como o tipo atual (mesmo do banco)
			         */
			        if (!isset($tipoant)) {
			        	$tipoant = $local;
			        }
			         
			        db_input('tipoant',      10,  $tipoant,      true, 'hidden',	3);
			        /**
			         * Id do tipo de sepultamento atual
			        */
			        db_input('codigoant',    10,  $codigoant,    true, 'hidden',  3);
			        
		          if (isset($local)) {
		          
		            if ($local == 1) {
		          
									$cm24_i_sepultamento = $sepultamento;
		              include("forms/db_frmsepulta.php");
		              
		            } elseif ($local == 2) {
		          
									$lPesquisar = false;
		              $cm06_i_sepultamento = $sepultamento;
		              include("forms/db_frmossoario.php");
		              
		            } elseif ($local == 3) {
		          
		              $cm26_i_sepultamento = $sepultamento;
		              $tipo='O';
		              include("forms/db_frmrestosgavetas.php");
		              
		            } elseif($local == 4) {
		          
		              $cm26_i_sepultamento = $sepultamento;
		              $tipo='J';
		              include("forms/db_frmrestosgavetas.php");
		              // $cm13_i_sepultamento = $sepultamento;
		              //include("forms/db_frmgavetas.php");
		            }
		          }
		        ?>
		        
		       </td>
		       
     		 </tr>
     		 
	     </table>
     
     </form>
     
    </center>
   </td>
  </tr>
  
</table>