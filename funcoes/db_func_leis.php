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

$campos = "leis.h08_codlei,
           leis.h08_numero,
	   leis.h08_dtlanc,
	   leis.h08_tipo as db_h08_tipo,
	   case leis.h08_tipo when 'A' then 'Avanço'
	                      when 'G' then 'Gratificação'
			      when 'C' then 'Cargos'
			      else 'Outros'
	   end as tipo,
	   leis.h08_dtini,
	   leis.h08_dtfim";
/*
leis.h08_anos1,leis.h08_perc1,leis.h08_anos2,leis.h08_perc2,leis.h08_anos3,leis.h08_perc3,leis.h08_anos4,
leis.h08_perc4,leis.h08_anos5,leis.h08_perc5,leis.h08_anos6,leis.h08_perc6,leis.h08_anos7,leis.h08_perc7,
leis.h08_anos8,leis.h08_perc8,leis.h08_anos9,leis.h08_perc9,leis.h08_anos10,leis.h08_perc10,leis.h08_anos11,
leis.h08_perc11,leis.h08_anos12,leis.h08_perc12,leis.h08_anos13,leis.h08_perc13,leis.h08_anos14,leis.h08_perc14,
leis.h08_anos15,leis.h08_perc15,leis.h08_car1,leis.h08_car2,leis.h08_car3,leis.h08_car4,leis.h08_car5,leis.h08_car6,
leis.h08_car7,leis.h08_car8,leis.h08_car9,leis.h08_car10,leis.h08_car11,leis.h08_car12,leis.h08_car13,leis.h08_car14,
leis.h08_car15,leis.h08_anos16,leis.h08_anos17,leis.h08_anos18,leis.h08_car16,leis.h08_car17,leis.h08_car18,leis.h08_perc16,
leis.h08_perc17,leis.h08_perc18
*/
?>