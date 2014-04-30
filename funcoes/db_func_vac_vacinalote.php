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


$sCampos             = ' vac_vacina.vc06_c_descr as db_vc06_c_descr,';
$sCampos            .= ' vac_vacina.vc06_c_descr as dl_vacina,';
$sCampos            .= ' matmater.m60_descr as dl_material,';
$sCampos            .= ' matestoqueitemlote.m77_sequencial,';
$sCampos            .= ' matestoqueitemlote.m77_lote,';
$sCampos            .= ' matestoqueitemlote.m77_dtvalidade,';
$sCampos            .= ' matunid.m61_descr as db_m61_descr,';

$sSubSqlAplicadas    = ' select coalesce(sum(vc16_n_quant),0) from vac_aplicalote ';
$sSubSqlAplicadas   .= '    inner join vac_aplica on vc16_i_codigo = vc17_i_aplica'; 
$sSubSqlAplicadas   .= ' where vc17_i_matetoqueitemlote = m77_sequencial';
$sSubSqlAplicadas   .= ' and not exists (select * from vac_aplicaanula where vc18_i_aplica=vc17_i_aplica) ';

$sSubSqlDescartadas  = ' select coalesce(sum(vc19_n_quant),0) from vac_descarte ';
$sSubSqlDescartadas .= ' where vc19_i_matetoqueitemlote=matestoqueitemlote.m77_sequencial';

$sSubSql             = " (m71_quant*vc29_i_dose) - (($sSubSqlAplicadas)+($sSubSqlDescartadas))";

$sCampos            .= " (m71_quant*vc29_i_dose) as dl_total,";
$sCampos            .= " ($sSubSqlAplicadas) as dl_aplicadas,";
$sCampos            .= " ($sSubSqlDescartadas) as dl_descartadas,";
$sCampos            .= " ($sSubSql) as dl_saldo";
?>