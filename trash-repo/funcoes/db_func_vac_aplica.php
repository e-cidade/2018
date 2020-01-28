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

$sCampos  = ' vac_aplica.vc16_i_codigo,';
$sCampos .= ' vac_aplica.vc16_i_dosevacina,';
$sCampos .= ' vac_vacinadose.vc07_c_nome,';
$sCampos .= ' vac_aplica.vc16_d_dataaplicada,';
$sCampos .= ' vac_aplica.vc16_t_obs,';
$sCampos .= ' vac_aplica.vc16_n_quant,';
$sCampos .= ' matunid.m61_descr,';
$sCampos .= ' matestoqueitemlote.m77_lote,';
$sCampos .= ' matestoqueitemlote.m77_dtvalidade,';
$sCampos .= ' vac_sala.vc01_c_nome,';
$sCampos .= ' vac_aplica.vc16_i_usuario as db_vc16_i_usuario,';
$sCampos .= ' db_usuarios.login as db_login,';
$sCampos .= ' db_usuarios.nome as db_nome,';
$sCampos .= ' vac_aplica.vc16_i_departamento as db_vc16_i_departamento,';
$sCampos .= ' vac_aplica.vc16_d_data as db_vc16_d_data,';
$sCampos .= ' vac_aplica.vc16_c_hora as db_vc16_c_hora';
?>