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

$campos  = " distinct modcarnepadrao.k48_sequencial,				 							                                            ";
$campos .= " modcarnepadrao.k48_cadtipomod, 	 												                                                ";
$campos .= " (modcarnepadrao.k48_parcini || ' - ' || modcarnepadrao.k48_parcfim)::varchar(15)  as dl_Parcelas_Regra,  ";
$campos .= " cadtipomod.k46_descr, 			 													                                                    ";
$campos .= " modcarnepadrao.k48_dataini, 														                                                  ";
$campos .= " modcarnepadrao.k48_datafim, 		 												                                                  ";
$campos .= " case 																			  	                                                          ";
$campos .= "   when k36_sequencial is not null then 'Com Excessão' else 'Sem Excessão' 			                          ";
$campos .= " end as dl_Excessao, 																                                                      "; 
$campos .= "(case when k49_sequencial is not null                                                                     ";
$campos .= "      then 'Com Tipo de Débito'                                                                           ";
$campos .= "      else 'Sem Tipo de Débito'                                                                           ";
$campos .= " end)::varchar(20)	 as dl_Tipo_de_Debito, 															                                  ";	
$campos .= " m01_cadmodcarne, 															                                                          ";
$campos .= "(select coalesce(array_to_string(array_accum(distinct k49_tipo), ', '), ' ')                              ";
$campos .= "   from modcarnepadraotipo                                                                                ";
$campos .= "  where k49_modcarnepadrao = modcarnepadrao.k48_sequencial                                                ";
$campos .= ") as dl_Tipos_de_debito                                                                                   ";
?>