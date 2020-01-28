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

$campos = " distinct
           isencao.v10_sequencial,
           isencaotipo.v11_descr,
					 case 
					     when isencaomatric.v15_sequencial is not null then 'Matrícula'
						   when isencaoinscr.v16_sequencial  is not null then 'Inscrição'
						   when isencaocgm.v12_sequencial    is not null then 'Cgm'
					 end as dl_CampoOrigem,
					 case 
					     when isencaomatric.v15_sequencial is not null then isencaomatric.v15_matric 
						   when isencaoinscr.v16_sequencial  is not null then isencaoinscr.v16_inscr 
						   when isencaocgm.v12_sequencial    is not null then isencaocgm.v12_numcgm
					 end as dl_Origem,
					 case 
					     when isencaomatric.v15_sequencial is not null then (select z01_nome from cgm 
							                                                       inner join iptubase on iptubase.j01_numcgm = cgm.z01_numcgm 
																																	   where j01_matric = isencaomatric.v15_matric limit 1) 
						   when isencaoinscr.v16_sequencial  is not null then (select z01_nome from cgm
                                                                     inner join issbase on issbase.q02_numcgm = cgm.z01_numcgm
                                                                     where q02_inscr = isencaoinscr.v16_inscr limit 1) 
						   when isencaocgm.v12_sequencial    is not null then (select z01_nome from cgm where z01_numcgm = isencaocgm.v12_numcgm limit 1)
					 end as dl_nome,
					 isencao.v10_dtisen,
					 isencao.v10_dtlan ";
?>