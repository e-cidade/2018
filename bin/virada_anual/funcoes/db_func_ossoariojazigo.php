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

$campos = "ossoariojazigo.cm25_i_codigo,
           ossoariojazigo.cm25_c_numero,
           ossoariojazigo.cm25_i_lotecemit,
           ossoariojazigo.cm25_f_comprimento,
           ossoariojazigo.cm25_f_largura,
           case when ossoariojazigo.cm25_c_tipo = 'O' then
                'OSSOÁRIO'
           else
                'JAZIGO'
           end as cm25_c_tipo,
           cm23_i_codigo,
           cm23_i_lotecemit,
           cm23_i_quadracemit,
           case when cm23_c_situacao = 'D' then
                'DISPONÍVEL'
           else
                'OCUPADO'
           end as cm23_c_situacao,
           cm22_c_quadra,
           cm22_i_cemiterio,
           case when cgm.z01_nome is null then
                 cemiteriorural.cm16_c_nome
           else
                 cgm.z01_nome
           end as z01_nome,
           cm28_i_proprietario,
           cgmpropri.z01_nome as Proprietario
           ";
?>