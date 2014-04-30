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
	j14_codigo as db_j14_codigo,
	x01_letra as db_x01_letra,
	x01_codrua as db_x01_codrua,
	x01_qtdeconomia as db_x01_qtdeconomia,
	case when x01_multiplicador='f' then 'Sim' else 'Não' end as db_x01_multiplicador,
	x01_numcgm as db_x01_numcgm,
	x01_zona as db_x01_zona,
	x04_matric as db_x04_matric,

	aguabase.x01_matric,
        z01_nome,
	aguabase.x01_codrua,
	j14_nome,
	aguabase.x01_codbairro,
	bairro.j13_descr as x01_codbairro,
	aguabase.x01_numero,
	aguabase.x01_quadra, 
	fc_agua_existecaract(aguabase.x01_matric, 5001) as x01_tipoimovel
	";
	/*,
	aguabase.x01_distrito,
	aguabase.x01_zona,
	aguabase.x01_orientacao,
	aguabase.x01_rota,
	aguabase.x01_qtdeconomia,
	aguabase.x01_dtcadastro,
	aguabase.x01_qtdponto,
	aguabase.x01_obs";
	*/
?>