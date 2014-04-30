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

$campos = "veicabast.ve70_codigo as dl_Cod_Abast,veiculos.ve01_placa,veiccadcomb.ve26_descr,veicabast.ve70_dtabast as dl_Data_Abast,veicabast.ve70_litros,veicabast.ve70_valor as dl_Valor_Abast,to_char(veicabast.ve70_vlrun,'999990.000') as ve70_vlrun,veicabast.ve70_medida,case when veicabast.ve70_ativo = 1 then 'SIM' else 'NAO' end as ve70_ativo,veicabast.ve70_usuario,case when veicabastposto.ve71_nota is null or veicabastposto.ve71_nota = '' then 'INTERNO' else 'EXTERNO' end as ve71_veiccadposto";
?>