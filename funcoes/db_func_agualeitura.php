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

$campos = "
     x21_codleitura,
	   x04_nrohidro,
	   x01_matric,
	   z01_nome,
     x21_codhidrometro as db_x21_codhidrometro,
	   x21_exerc         as db_x21_exerc,
	   x21_mes           as db_x21_mes,
	   x21_situacao      as db_x21_situacao,
	   x21_numcgm        as db_x21_numcgm,
	   x21_dtleitura,
	   x21_usuario       as db_x21_usuario,
	   x21_dtinc         as db_x21_dtinc,
	   x21_leitura,
	   x21_consumo       as x19_conspadrao,
     x21_consumo + 
     x21_excesso       as x21_consumo,
	   x21_excesso, 
	   CASE WHEN x21_tipo = 1 THEN 'Digitaчуo Manual' 
	        WHEN x21_tipo = 2 THEN 'Exportada Coletor' 
	        ELSE 'Importada Coletor' 
	   END as x21_tipo, 
	   CASE WHEN x21_status = 1 THEN 'Ativo' 
	        WHEN x21_status = 2 THEN 'Inativo' 
	        ELSE 'Cancelado'
	   END as x21_status
	  ";


?>