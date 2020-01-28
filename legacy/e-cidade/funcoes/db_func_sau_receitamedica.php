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

$sCampos  = 'sau_receitamedica.s158_i_codigo, ';
$sCampos .= 'sau_receitamedica.s158_i_profissional as db_s158_i_profissional, ';
$sCampos .= 'cgs_und.z01_v_nome as dl_paciente, ';
$sCampos .= 'sau_receitamedica.s158_t_prescricao, ';
$sCampos .= 'far_tiporeceita.fa03_c_descr as dl_receita, ';
$sCampos .= 'cgm.z01_nome as dl_profissional, ';
$sCampos .= 'sau_receitamedica.s158_i_situacao as db_s158_i_situacao, ';
$sCampos .= 'sau_receitamedica.s158_d_validade, ';
$sCampos .= 'sau_receitamedica.s158_i_login as db_s158_i_login, ';
$sCampos .= 'sau_receitamedica.s158_d_data as db_s158_d_data, ';
$sCampos .= 'sau_receitamedica.s158_c_hora as db_s158_c_hora, ';
$sCampos .= 'sau_receitamedica.s158_i_tiporeceita as db_s158_i_tiporeceita ';
?>