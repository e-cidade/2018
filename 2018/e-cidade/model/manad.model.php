<?php
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

 

class manad {
  
	
	function __construct() { }
  
	public $aCredores = array(); 
  function getSqlK050($iInstit, $sDataini, $sDatafim) {

  	list ( $iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDatafim);
    list ( $iAnoUsuIni, $iMesUsuIni, $iDiaUsuIni ) = explode("-", $sDataini);

		/**
		 * K050
		 */
		$sSqlK050  = "select distinct
                         'K050'                          as reg, ";
		$sSqlK050 .= "       (select cgc       ";
		$sSqlK050 .= "          from db_config ";
		$sSqlK050 .= "         where codigo =  {$iInstit} ) as cnpj_cei, ";
		$sSqlK050 .= "       '01011900'                     as dt_inc_alt,     ";
		$sSqlK050 .= "       rhpessoal.rh01_regist    as cod_reg_trab,   ";
		$sSqlK050 .= "       cgm.z01_cgccpf           as cpf,            ";
		$sSqlK050 .= "       rhpesdoc.rh16_pis        as nit,            ";
		$sSqlK050 .= "       ( select tpcontra.h13_tpcont 
                             from tpcontra 
                                  inner join  rhpessoalmov on rhpessoalmov.rh02_tpcont = tpcontra.h13_codigo
                            where rhpessoalmov.rh02_regist = rhpessoal.rh01_regist 
                            order by rhpessoalmov.rh02_anousu desc,
                                     rhpessoalmov.rh02_mesusu desc limit 1 ) as cod_categ, ";

		$sSqlK050 .= "       cgm.z01_nome             as nome_trab,      ";
		$sSqlK050 .= "       to_char(rh01_nasc,'ddmmYYYY')   as dt_nasc,        ";
		
		$sSqlK050 .= "       to_char(rhpessoal.rh01_admiss, 'ddmmYYYY')    as dt_admissao,    ";
		
		$sSqlK050 .= "       to_char(rhpesrescisao.rh05_recis, 'ddmmYYYY') as dt_demissao, ";
		$sSqlK050 .= "       rh30_vinculomanad  as ind_vinc,       ";
		$sSqlK050 .= "       null                      as tipo_ato_nom,   ";
		$sSqlK050 .= "       null                      as nm_ato_nom,     ";
		$sSqlK050 .= "       null                      as dt_ato_nom      ";
		$sSqlK050 .= "  from rhpessoal ";
		$sSqlK050 .= "       inner join cgm           on z01_numcgm                = rh01_numcgm "; 
		$sSqlK050 .= "       inner join rhpessoalmov  on rh01_regist               = rh02_regist ";
    $sSqlK050 .= "       inner join rhregime      on rh30_codreg = rh02_codreg ";
    $sSqlK050 .= "                               and rh30_instit = rh02_instit "; 
		$sSqlK050 .= "       left  join rhpesdoc      on rhpesdoc.rh16_regist      = rhpessoal.rh01_regist ";
		$sSqlK050 .= "       left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
		$sSqlK050 .= " where "; 
		$sSqlK050 .= "       rhpessoalmov.rh02_anousu = {$iAnoUsuFim}";
                $sSqlK050 .= "   and rhpessoalmov.rh02_mesusu   = {$iMesUsuFim} ";
                $sSqlK050 .= "   and ( rhpesrescisao.rh05_recis is null 
                        or rhpesrescisao.rh05_recis >= '{$iAnoUsuFim}-{$iMesUsuIni}-{$iDiaUsuIni}' 
                        or rh02_regist in (select distinct r20_regist from gerfres where (r20_anousu >= {$iAnoUsuIni} and r20_mesusu >= {$iMesUsuIni} ) and (r20_anousu <= {$iAnoUsuFim} and r20_mesusu <= {$iMesUsuFim} ) )
                    ) ";
                $sSqlK050 .= "   and rhpessoalmov.rh02_instit = {$iInstit} ";
//echo $sSqlK050;exit;
		return $sSqlK050;   	
  	
  }
  
  function getSqlK100($iInstit) {
  	
  	$sSqlK100  = " select 'K100'            as reg,         ";
		$sSqlK100 .= "        '01011900'        as dt_inc_alt,  ";
		$sSqlK100 .= "        rhlota.r70_codigo as cod_ltc,     ";
		$sSqlK100 .= "        (select cgc        ";
    $sSqlK100 .= "           from db_config  ";
    $sSqlK100 .= "          where codigo =  {$iInstit}) as cnpj_cei, ";
		$sSqlK100 .= "        rhlota.r70_descr  as desc_ltc,    ";
		$sSqlK100 .= "        null              as cnpj_cei_tom ";
		$sSqlK100 .= "   from rhlota                            ";
		$sSqlK100 .= "  where r70_instit = {$iInstit}           ";
  	return $sSqlK100; 
  }
  
  function getSqlK150($iInstit){
  	
  	$sSqlK150  = " select 'K150'        as reg,";
		$sSqlK150 .= "        (select cgc        ";
		$sSqlK150 .= "           from db_config  ";
		$sSqlK150 .= "          where codigo =  {$iInstit}) as cnpj_cei, ";
		$sSqlK150 .= "        '01011900'          as dt_inc_alt,     ";
		$sSqlK150 .= "        rh27_rubric         as cod_rubrica,    ";
		$sSqlK150 .= "        rh27_descr          as desc_rubrica    ";
		$sSqlK150 .= "   from rhrubricas                             ";
		$sSqlK150 .= "  where rh27_instit = {$iInstit}               ";
		return $sSqlK150;		
  	
  }
  
  function getSqlK250($iInstit, $sDataini, $sDatafim){

  	list ( $iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDatafim);
    list ( $iAnoUsuIni, $iMesUsuIni, $iDiaUsuIni ) = explode("-", $sDataini);
  	
  	/*
  	 * K250
  	 */ 
    $sData1 = $iAnoUsuIni.$iMesUsuIni;
    $sData2 = $iAnoUsuFim.$iMesUsuFim;
    
    //die($sData1);
     
    $sSqlK250   = " select reg,
                           cnpj_cei,
                           ind_fl,
                           cod_ltc,
                           cod_reg_trab,
                           dt_comp,
                           dt_pgto,
                           cod_cbo,
                           cod_ocorr,
                           desc_cargo, 
                           
                           replace(cast(round(sum(cast(replace(coalesce(vl_base_irrf,'0,00'),',','.') as numeric)),2) as text),'.',',') as vl_base_irrf,
                           replace(cast(round(sum(cast(replace(coalesce(vl_base_ps,'0,00'),',','.') as numeric)),2) as text),'.',',')   as vl_base_ps,
                           
                           min(cast(coalesce(qtd_dep_ir,0) as numeric)) as qtd_dep_ir, ";
    $sSqlK250  .= "        min(cast(coalesce(qtd_dep_sf,0) as numeric)) as qtd_dep_sf  ";
    $sSqlK250  .= "   from ( ";
    
		$sSqlK250  .= " select case 
		                        when round(cast(substr(db_fxxx(cod_reg_trab,anousu,mesusu,instit),45,11)as numeric),0) = 0 then null 
		                        else round(cast(substr(db_fxxx(cod_reg_trab,anousu,mesusu,instit),45,11)as numeric),0) 
		                       end as qtd_dep_ir, 
		                        ";
		 
		$sSqlK250 .= "        case 
		                        when round(cast(substr(db_fxxx(cod_reg_trab,anousu,mesusu,instit),56,11)as numeric),0) = 0 then null 
		                        else round(cast(substr(db_fxxx(cod_reg_trab,anousu,mesusu,instit),56,11)as numeric),0)
                          end as qtd_dep_sf,";
		 
		$sSqlK250 .= "        x.* ";
		$sSqlK250 .= "   from (   ";
		$sSqlK250 .= "       select 'K250'                                        as reg,          ";
		$sSqlK250 .= "              (select cgc from db_config where codigo =  {$iInstit}) as cnpj_cei,         ";
		$sSqlK250 .= "              1                                             as ind_fl,       ";
		$sSqlK250 .= "              r14_lotac                                     as cod_ltc,      ";
		$sSqlK250 .= "              r14_regist                                    as cod_reg_trab, ";
		$sSqlK250 .= "              lpad(r14_mesusu,2,'0')||r14_anousu            as dt_comp,      ";
		$sSqlK250 .= "              ndias(r14_anousu,r14_mesusu)||lpad(r14_mesusu,2,'0')||r14_anousu as dt_pgto, "; 
		$sSqlK250 .= "              rh37_cbo                                      as cod_cbo,      ";
		$sSqlK250 .= "              rh02_ocorre                                   as cod_ocorr,    ";
		$sSqlK250 .= "              rh37_descr                                    as desc_cargo,   ";
		$sSqlK250 .= "              r14_mesusu                                    as mesusu,       ";
		$sSqlK250 .= "              r14_anousu                                    as anousu,       ";
		$sSqlK250 .= "              gerfsal.r14_instit                            as instit,       ";
		
		$sSqlK250 .= "              replace(cast(cast(round((case                                                ";
		$sSqlK250 .= "                                         when r14_rubric in ('R981','R983') then r14_valor "; 
		$sSqlK250 .= "                                         else 0                                            "; 
		$sSqlK250 .= "                                       end),2) as numeric) as text),'.',',') as vl_base_irrf, ";
		 
		$sSqlK250 .= "              replace(cast(cast(round((case                                      "; 
		$sSqlK250 .= "                                         when r14_rubric = 'R992' then r14_valor "; 
		$sSqlK250 .= "                                         else 0                                  "; 
		$sSqlK250 .= "                                       end),2) as numeric) as text),'.',',') as vl_base_ps ";
		 
		$sSqlK250 .= "         from gerfsal                                   ";
		$sSqlK250 .= "              inner join rhpessoal    on rhpessoal.rh01_regist    = gerfsal.r14_regist "; 
		$sSqlK250 .= "              inner join rhfuncao     on rhfuncao.rh37_funcao     = rhpessoal.rh01_funcao "; 
		$sSqlK250 .= "                                     and rhfuncao.rh37_instit     = gerfsal.r14_instit "; 
		$sSqlK250 .= "              inner join rhpessoalmov on rhpessoalmov.rh02_regist = gerfsal.r14_regist "; 
		$sSqlK250 .= "                                     and rhpessoalmov.rh02_mesusu = gerfsal.r14_mesusu "; 
		$sSqlK250 .= "                                     and rhpessoalmov.rh02_anousu = gerfsal.r14_anousu ";
                $sSqlK250 .= "       where gerfsal.r14_instit = {$iInstit} ";
                $sSqlK250 .= "         and cast( gerfsal.r14_anousu||lpad(gerfsal.r14_mesusu,2,'0') as integer)
                                           between cast('$sData1' as integer)
                                               and cast('$sData2' as integer)";    
                $sSqlK250 .= "          and exists ( select 1
                                                     from gerfsal g
			                             where g.r14_regist = gerfsal.r14_regist
					               and g.r14_anousu = gerfsal.r14_anousu
					               and g.r14_mesusu = gerfsal.r14_mesusu
					               and g.r14_instit = gerfsal.r14_instit
					               and g.r14_rubric <> 'R953' ) ";
                $sSqlK250 .= "          and exists ( select 1
                                                       from gerfsal g
                                                      where g.r14_regist = gerfsal.r14_regist
                                                        and g.r14_anousu = gerfsal.r14_anousu
                                                        and g.r14_mesusu = gerfsal.r14_mesusu
                                                        and g.r14_instit = gerfsal.r14_instit
                                                        and g.r14_rubric <> 'R991' ) ";
                $sSqlK250 .= "   and cast(rhpessoalmov.rh02_anousu||lpad(rhpessoalmov.rh02_mesusu,2,'0') as integer) 
                                           between cast('$sData1' as integer)
                                               and cast('$sData2' as integer)";    
                $sSqlK250 .= "   and rhpessoalmov.rh02_instit = {$iInstit} ";
		
                $sSqlK250 .= "       union ";
		$sSqlK250 .= "       select 'K250'                                        as reg, "; 
		$sSqlK250 .= "              (select cgc from db_config where codigo =  {$iInstit}) as cnpj, "; 
		$sSqlK250 .= "              2                                             as ind_fl, "; 
		$sSqlK250 .= "              rh02_lota::char(4)                            as cod_ltc, "; 
		$sSqlK250 .= "              r35_regist                                    as cod_reg_trab, "; 
		$sSqlK250 .= "              '13'||r35_anousu            as dt_comp, "; 
		$sSqlK250 .= "              ndias(r35_anousu,12)||lpad(12,2,'0')||r35_anousu as dt_pgto, "; 
		$sSqlK250 .= "              rh37_cbo                                      as cod_cbo, "; 
		$sSqlK250 .= "              rh02_ocorre                                   as cod_ocorr, "; 
		$sSqlK250 .= "              rh37_descr                                    as desc_cargo, "; 
		$sSqlK250 .= "              12                                            as mesusu, "; 
		$sSqlK250 .= "              r35_anousu                                    as anousu, "; 
		$sSqlK250 .= "              rh02_instit                                   as instit, "; 
		$sSqlK250 .= "              replace(cast(cast(round((case  "; 
		$sSqlK250 .= "                                         when r35_rubric in ('R981','R983') then r35_valor "; 
		$sSqlK250 .= "                                         else 0 "; 
		$sSqlK250 .= "                                       end),2) as numeric) as text),'.',',') as vl_base_irrf, "; 
		$sSqlK250 .= "              replace(cast(cast(round((case  "; 
		$sSqlK250 .= "                                         when r35_rubric = 'R992' then r35_valor "; 
		$sSqlK250 .= "                                         else 0 "; 
		$sSqlK250 .= "                                       end),2) as numeric) as text),'.',',') as vl_base_ps "; 
		$sSqlK250 .= "        from (select r35_anousu, 13, r35_regist, r35_rubric, round(sum(r35_valor),2) as r35_valor 
                                            from gerfs13 where r35_anousu between {$iAnoUsuIni} and {$iAnoUsuFim} 
                                                           and r35_instit = {$iInstit} 
                                            group by r35_anousu, r35_regist, r35_rubric) as gerfs13"; 
		$sSqlK250 .= "              inner join rhpessoal    on rhpessoal.rh01_regist = gerfs13.r35_regist "; 
		$sSqlK250 .= "              inner join rhpessoalmov on rhpessoalmov.rh02_regist = gerfs13.r35_regist "; 
		$sSqlK250 .= "              inner join rhfuncao     on rhfuncao.rh37_funcao  = rhpessoalmov.rh02_funcao "; 
		$sSqlK250 .= "                                     and rhfuncao.rh37_instit  = {$iInstit} "; 
                $sSqlK250 .= "       where ";
                $sSqlK250 .= "             rhpessoalmov.rh02_anousu = {$iAnoUsuFim}";
                $sSqlK250 .= "         and rhpessoalmov.rh02_mesusu = {$iMesUsuFim} ";
                $sSqlK250 .= "         and rhpessoalmov.rh02_instit = {$iInstit} ";
                                      
		$sSqlK250 .= "       union ";
		$sSqlK250 .= "       select 'K250'                                        as reg, "; 
		$sSqlK250 .= "              (select cgc from db_config where codigo =  {$iInstit}) as cnpj, "; 
		$sSqlK250 .= "              4                                             as ind_fl, "; 
		$sSqlK250 .= "              r48_lotac                                     as cod_ltc, "; 
		$sSqlK250 .= "              r48_regist                                    as cod_reg_trab, "; 
		$sSqlK250 .= "              lpad(r48_mesusu,2,'0')||r48_anousu            as dt_comp, "; 
		$sSqlK250 .= "              ndias(r48_anousu,r48_mesusu)||lpad(r48_mesusu,2,'0')||r48_anousu as dt_pgto, "; 
		$sSqlK250 .= "              rh37_cbo                                      as cod_cbo, "; 
		$sSqlK250 .= "              rh02_ocorre                                   as cod_ocorr, "; 
		$sSqlK250 .= "              rh37_descr                                    as desc_cargo, "; 
		$sSqlK250 .= "              r48_mesusu                                    as mesusu, "; 
		$sSqlK250 .= "              r48_anousu                                    as anousu, "; 
		$sSqlK250 .= "              gerfcom.r48_instit                            as instit, "; 
		$sSqlK250 .= "              replace(cast(cast(round((case  "; 
		$sSqlK250 .= "                                         when r48_rubric in ('R981','R983') then r48_valor "; 
		$sSqlK250 .= "                                         else 0 "; 
		$sSqlK250 .= "                                       end),2) as numeric) as text),'.',',') as vl_base_irrf, "; 
		$sSqlK250 .= "              replace(cast(cast(round((case  "; 
		$sSqlK250 .= "                                         when r48_rubric = 'R992' then r48_valor "; 
		$sSqlK250 .= "                                         else 0 "; 
		$sSqlK250 .= "                                       end),2) as numeric) as text),'.',',') as vl_base_ps "; 
		$sSqlK250 .= "         from gerfcom "; 
		$sSqlK250 .= "              inner join rhpessoal    on rhpessoal.rh01_regist = gerfcom.r48_regist "; 
		$sSqlK250 .= "              inner join rhfuncao     on rhfuncao.rh37_funcao  = rhpessoal.rh01_funcao "; 
		$sSqlK250 .= "                                     and rhfuncao.rh37_instit  = gerfcom.r48_instit "; 
		$sSqlK250 .= "              inner join rhpessoalmov on rhpessoalmov.rh02_regist = gerfcom.r48_regist "; 
		$sSqlK250 .= "                                     and rhpessoalmov.rh02_mesusu = gerfcom.r48_mesusu "; 
		$sSqlK250 .= "                                     and rhpessoalmov.rh02_anousu = gerfcom.r48_anousu ";
                $sSqlK250 .= "       where gerfcom.r48_instit = {$iInstit} ";
                $sSqlK250 .= "         and cast( gerfcom.r48_anousu||lpad(gerfcom.r48_mesusu,2,'0') as integer ) 
                                             between cast( '$sData1' as integer) 
                                                 and cast( '$sData2' as integer)";
                $sSqlK250 .= "          and exists ( select 1
                                                       from gerfcom c
                                                      where c.r48_regist = gerfcom.r48_regist
                                                        and c.r48_anousu = gerfcom.r48_anousu
                                                        and c.r48_mesusu = gerfcom.r48_mesusu
                                                        and c.r48_instit = gerfcom.r48_instit
                                                        and c.r48_rubric <> 'R953' ) ";
                $sSqlK250 .= "          and exists ( select 1
                                                       from gerfcom c
                                                      where c.r48_regist = gerfcom.r48_regist
                                                        and c.r48_anousu = gerfcom.r48_anousu
                                                        and c.r48_mesusu = gerfcom.r48_mesusu
                                                        and c.r48_instit = gerfcom.r48_instit
                                                        and c.r48_rubric <> 'R991' ) ";
                $sSqlK250 .= "   and cast(rhpessoalmov.rh02_anousu||lpad(rhpessoalmov.rh02_mesusu,2,'0') as integer) 
                                           between cast('$sData1' as integer)
                                               and cast('$sData2' as integer)";    

                $sSqlK250 .= "   and rhpessoalmov.rh02_instit = {$iInstit} ";
		$sSqlK250 .= "       union ";
		 
		$sSqlK250 .= "       select 'K250'                                        as reg, "; 
		$sSqlK250 .= "              (select cgc from db_config where codigo =  {$iInstit}) as cnpj, "; 
		$sSqlK250 .= "              6                                             as ind_fl, "; 
		$sSqlK250 .= "              r20_lotac                                     as cod_ltc, "; 
		$sSqlK250 .= "              r20_regist                                    as cod_reg_trab, "; 
		$sSqlK250 .= "              lpad(r20_mesusu,2,'0')||r20_anousu            as dt_comp, "; 
		$sSqlK250 .= "              ndias(r20_anousu,r20_mesusu)||lpad(r20_mesusu,2,'0')||r20_anousu as dt_pgto, "; 
		$sSqlK250 .= "              rh37_cbo                                      as cod_cbo, "; 
		$sSqlK250 .= "              rh02_ocorre                                   as cod_ocorr, "; 
		$sSqlK250 .= "              rh37_descr                                    as desc_cargo, "; 
		$sSqlK250 .= "              r20_mesusu                                    as mesusu, "; 
		$sSqlK250 .= "              r20_anousu                                    as anousu, "; 
		$sSqlK250 .= "              gerfres.r20_instit                            as instit, "; 
		$sSqlK250 .= "              replace(cast(cast(round((case  "; 
		$sSqlK250 .= "                                         when r20_rubric in ('R981','R983') then r20_valor "; 
		$sSqlK250 .= "                                         else 0 "; 
		$sSqlK250 .= "                                       end),2) as numeric) as text),'.',',') as vl_base_irrf, "; 
		$sSqlK250 .= "              replace(cast(cast(round((case  "; 
		$sSqlK250 .= "                                         when r20_rubric = 'R992' then r20_valor "; 
		$sSqlK250 .= "                                         else 0 "; 
		$sSqlK250 .= "                                       end),2) as numeric) as text),'.',',')  as vl_base_ps "; 
		$sSqlK250 .= "         from gerfres "; 
		$sSqlK250 .= "              inner join rhpessoal    on rhpessoal.rh01_regist = gerfres.r20_regist "; 
		$sSqlK250 .= "              inner join rhfuncao     on rhfuncao.rh37_funcao  = rhpessoal.rh01_funcao "; 
		$sSqlK250 .= "                                     and rhfuncao.rh37_instit  = gerfres.r20_instit "; 
		$sSqlK250 .= "              inner join rhpessoalmov on rhpessoalmov.rh02_regist = gerfres.r20_regist "; 
		$sSqlK250 .= "                                     and rhpessoalmov.rh02_mesusu = gerfres.r20_mesusu "; 
		$sSqlK250 .= "                                     and rhpessoalmov.rh02_anousu = gerfres.r20_anousu "; 
		$sSqlK250 .= "  where gerfres.r20_instit = {$iInstit} ";
                $sSqlK250 .= "    and cast( gerfres.r20_anousu||lpad(gerfres.r20_mesusu,2,'0') as integer)
                                            between cast( '$sData1' as integer) 
                                                and cast( '$sData2' as integer)";
                $sSqlK250 .= "   and cast(rhpessoalmov.rh02_anousu||lpad(rhpessoalmov.rh02_mesusu,2,'0') as integer) 
                                           between cast('$sData1' as integer)
                                               and cast('$sData2' as integer)";    
                $sSqlK250 .= "   and rhpessoalmov.rh02_instit = {$iInstit} ";                              
		$sSqlK250 .= " ) as x ) as y
		/* 
		where (vl_base_irrf != '0,00' and vl_base_ps != '0,00')
		*/
		               group by reg,
                           cnpj_cei,
                           ind_fl,
                           cod_ltc,
                           cod_reg_trab,
                           dt_comp,
                           dt_pgto,
                           cod_cbo,
                           cod_ocorr,
                           desc_cargo	";
		
 //   die($sSqlK250);exit;
		
	  return $sSqlK250;
  }
  
  function getSqlK300($iInstit,$sDataini,$sDatafim){
  	
  	list ( $iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDatafim);
    list ( $iAnoUsuIni, $iMesUsuIni, $iDiaUsuIni ) = explode("-", $sDataini);
    
  	/**
  	 * K300
  	 */ 

    $sData1 = $iAnoUsuIni.$iMesUsuIni;
    $sData2 = $iAnoUsuFim.$iMesUsuFim;
    
    $sSqlK300  = " select reg, "; 
    $sSqlK300 .= "        cnpj_cei, "; 
    $sSqlK300 .= "        ind_fl, "; 
    $sSqlK300 .= "        cod_ltc, "; 
    $sSqlK300 .= "        cod_reg_trab, "; 
    $sSqlK300 .= "        dt_comp, "; 
    $sSqlK300 .= "        cod_rubr, ";
    $sSqlK300 .= "        ind_rubr, ";
    $sSqlK300 .= "        ind_base_irrf, ";
    $sSqlK300 .= "        ind_base_ps,  ";
     
    // $sSqlK300 .= "        vlr_rubr, "; 
    // $sSqlK300 .= "        ind_rubr, "; 
    // $sSqlK300 .= "        ind_base_irrf, "; 
    // $sSqlK300 .= "        ind_base_ps ";
    
    $sSqlK300 .= "        replace(cast(round(sum(cast(replace(coalesce(vlr_rubr,'0,00'),',','.') as numeric)),2) as text),'.',',') as vlr_rubr ";
    
    $sSqlK300 .= "   from ( ";
    
  	$sSqlK300 .= " select 'K300'                                        as reg, "; 
		$sSqlK300 .= "        (select cgc from db_config where codigo =  {$iInstit}) as cnpj_cei, "; 
		$sSqlK300 .= "        1                                             as ind_fl, "; 
		$sSqlK300 .= "        r14_lotac                                     as cod_ltc, "; 
		$sSqlK300 .= "        r14_regist                                    as cod_reg_trab, "; 
		$sSqlK300 .= "        lpad(r14_mesusu,2,'0')||r14_anousu            as dt_comp, "; 
		$sSqlK300 .= "        r14_rubric                                    as cod_rubr, "; 
		$sSqlK300 .= "        replace(cast(cast(round(r14_valor,2) as numeric) as text),'.',',') as vlr_rubr, "; 
		$sSqlK300 .= "        case  "; 
		$sSqlK300 .= "          when r14_pd = 1 then 'P' "; 
		$sSqlK300 .= "          else 'D' "; 
		$sSqlK300 .= "        end as ind_rubr, "; 
		$sSqlK300 .= "        case  "; 
		$sSqlK300 .= "          when b1.r09_base is not null then 1 "; 
		$sSqlK300 .= "          else 3 "; 
		$sSqlK300 .= "        end as ind_base_irrf, "; 
		$sSqlK300 .= "        case  "; 
		$sSqlK300 .= "          when r14_rubric in (select r09_rubric from basesr where r09_anousu = rh02_anousu 
                                                    and r09_mesusu = rh02_mesusu and r09_base = 'B001' and r09_instit = {$iInstit}) then 1 "; 
		$sSqlK300 .= "          when r14_rubric between 'R901' and 'R912' then 3 "; 
		$sSqlK300 .= "          when r14_rubric in ('R918','R919', 'R920', 'R921' ) then 4 "; 
		$sSqlK300 .= "          else 8 "; 
		$sSqlK300 .= "        end as ind_base_ps "; 
		$sSqlK300 .= "   from gerfsal "; 
		$sSqlK300 .= "        inner join rhpessoal    on rhpessoal.rh01_regist = gerfsal.r14_regist "; 
		$sSqlK300 .= "        inner join rhfuncao     on rhfuncao.rh37_funcao  = rhpessoal.rh01_funcao "; 
		$sSqlK300 .= "                               and rhfuncao.rh37_instit  = gerfsal.r14_instit "; 
		$sSqlK300 .= "        inner join rhpessoalmov on rhpessoalmov.rh02_regist = gerfsal.r14_regist "; 
		$sSqlK300 .= "                               and rhpessoalmov.rh02_mesusu = gerfsal.r14_mesusu "; 
		$sSqlK300 .= "                               and rhpessoalmov.rh02_anousu = gerfsal.r14_anousu ";
                $sSqlK300 .= "        inner join rhregime     on rh30_codreg = rh02_codreg ";
                $sSqlK300 .= "                               and rh30_instit = rh02_instit ";
		$sSqlK300 .= "        left  join basesr as b1 on b1.r09_anousu  = r14_anousu "; 
		$sSqlK300 .= "                               and b1.r09_mesusu = r14_mesusu "; 
		$sSqlK300 .= "                               and b1.r09_instit = r14_instit "; 
		$sSqlK300 .= "                               and b1.r09_rubric = r14_rubric "; 
		$sSqlK300 .= "                               and b1.r09_base  in ('B004','B005') "; 
		$sSqlK300 .= "        left  join basesr as b2 on b2.r09_anousu  = r14_anousu "; 
		$sSqlK300 .= "                               and b2.r09_mesusu = r14_mesusu "; 
		$sSqlK300 .= "                               and b2.r09_instit = r14_instit "; 
		$sSqlK300 .= "                               and b2.r09_rubric = r14_rubric "; 
		$sSqlK300 .= "                               and b2.r09_base  in ('B001','B002') "; 
		$sSqlK300 .= "        left  join basesr as b3 on b3.r09_anousu  = r14_anousu "; 
		$sSqlK300 .= "                               and b3.r09_mesusu = r14_mesusu "; 
		$sSqlK300 .= "                               and b3.r09_instit = r14_instit "; 
		$sSqlK300 .= "                               and b3.r09_rubric = r14_rubric "; 
		$sSqlK300 .= "                               and b3.r09_base   = 'B003' "; 
		$sSqlK300 .= "  where gerfsal.r14_pd != 3 "; 
		$sSqlK300 .= "    and gerfsal.r14_instit = {$iInstit} ";
                $sSqlK300 .= "    and cast( gerfsal.r14_anousu||lpad(gerfsal.r14_mesusu,2,'0') as integer)
                                            between cast('$sData1' as integer)
                                                and cast('$sData2' as integer)";    
                $sSqlK300 .= "   and cast(rhpessoalmov.rh02_anousu||lpad(rhpessoalmov.rh02_mesusu,2,'0') as integer) 
                                           between cast('$sData1' as integer)
                                               and cast('$sData2' as integer)";    
                $sSqlK300 .= "   and rhpessoalmov.rh02_instit = {$iInstit} ";    
                                  
		$sSqlK300 .= " union "; 
		$sSqlK300 .= " select 'K300'                                        as reg, "; 
		$sSqlK300 .= "        (select cgc from db_config where codigo =  {$iInstit}) as cnpj, "; 
		$sSqlK300 .= "        2                                             as ind_fl, "; 
		$sSqlK300 .= "        rh02_lota::char(4)                            as cod_ltc, "; 
		$sSqlK300 .= "        r35_regist                                    as cod_reg_trab, "; 
		$sSqlK300 .= "        '13'||r35_anousu            as dt_comp, "; 
		$sSqlK300 .= "        r35_rubric                                    as cod_rubr, "; 
		$sSqlK300 .= "        replace(cast(cast(round(r35_valor,2) as numeric) as text),'.',',') as vlr_rubr, "; 
		$sSqlK300 .= "        case  "; 
		$sSqlK300 .= "          when r35_pd = 1 then 'P' "; 
		$sSqlK300 .= "          else 'D' "; 
		$sSqlK300 .= "        end as ind_rubr, "; 
		$sSqlK300 .= "        case  "; 
		$sSqlK300 .= "          when b1.r09_base is not null then 2 "; 
		$sSqlK300 .= "          else 3 "; 
		$sSqlK300 .= "        end as ind_base_irrf, "; 
		$sSqlK300 .= "        case  "; 
		$sSqlK300 .= "          when r35_rubric in (select r09_rubric from basesr where r09_anousu = rh02_anousu 
                                                    and r09_mesusu = rh02_mesusu and r09_base = 'B003' and r09_instit = {$iInstit}) then 1 "; 
		$sSqlK300 .= "          when r35_rubric between 'R901' and 'R912' then 3 "; 
		$sSqlK300 .= "          when r35_rubric in ('R918','R919', 'R920', 'R921' ) then 4 "; 
		$sSqlK300 .= "          else 8 "; 
		$sSqlK300 .= "        end as ind_base_ps "; 
		$sSqlK300 .= "        from (select r35_anousu, 13, r35_regist, r35_rubric, r35_pd, round(sum(r35_valor),2) as r35_valor 
                                            from gerfs13 where r35_anousu between {$iAnoUsuIni} and {$iAnoUsuFim} 
                                                           and r35_instit = {$iInstit} 
                                                           and r35_pd != 3
                                            group by r35_anousu, r35_regist, r35_rubric, r35_pd) as gerfs13"; 
		$sSqlK300 .= "        inner join rhpessoal    on rhpessoal.rh01_regist = gerfs13.r35_regist "; 
		$sSqlK300 .= "        inner join rhfuncao     on rhfuncao.rh37_funcao  = rhpessoal.rh01_funcao "; 
		$sSqlK300 .= "                               and rhfuncao.rh37_instit  = {$iInstit} "; 
		$sSqlK300 .= "        inner join rhpessoalmov on rhpessoalmov.rh02_regist = gerfs13.r35_regist "; 
                $sSqlK300 .= "        inner join rhregime     on rh30_codreg = rh02_codreg ";
                $sSqlK300 .= "                               and rh30_instit = rh02_instit ";
		$sSqlK300 .= "        left  join basesr as b1 on b1.r09_anousu  = rh02_anousu "; 
		$sSqlK300 .= "                               and b1.r09_mesusu = rh02_mesusu "; 
		$sSqlK300 .= "                               and b1.r09_instit = rh02_instit "; 
		$sSqlK300 .= "                               and b1.r09_rubric = r35_rubric "; 
		$sSqlK300 .= "                               and b1.r09_base  in ('B006') "; 
		$sSqlK300 .= "        left  join basesr as b2 on b2.r09_anousu  = rh02_anousu "; 
		$sSqlK300 .= "                               and b2.r09_mesusu = rh02_mesusu "; 
		$sSqlK300 .= "                               and b2.r09_instit = rh02_instit "; 
		$sSqlK300 .= "                               and b2.r09_rubric = r35_rubric "; 
		$sSqlK300 .= "                               and b2.r09_base  in ('B001','B002') "; 
		$sSqlK300 .= "        left  join basesr as b3 on b3.r09_anousu  = rh02_anousu "; 
		$sSqlK300 .= "                               and b3.r09_mesusu = rh02_mesusu "; 
		$sSqlK300 .= "                               and b3.r09_instit = rh02_instit "; 
		$sSqlK300 .= "                               and b3.r09_rubric = r35_rubric "; 
		$sSqlK300 .= "                               and b3.r09_base   = 'B003' "; 
		$sSqlK300 .= "  where "; 
                $sSqlK300 .= "       rhpessoalmov.rh02_anousu  = {$iAnoUsuFim}";
                $sSqlK300 .= "   and rhpessoalmov.rh02_mesusu  = {$iMesUsuFim} ";
                $sSqlK300 .= "   and rhpessoalmov.rh02_instit = {$iInstit} ";                              
		$sSqlK300 .= " union "; 
		$sSqlK300 .= " select 'K300'                                        as reg, "; 
		$sSqlK300 .= "        (select cgc from db_config where codigo =  {$iInstit}) as cnpj, "; 
		$sSqlK300 .= "        4                                             as ind_fl, "; 
		$sSqlK300 .= "        r48_lotac                                     as cod_ltc, "; 
		$sSqlK300 .= "        r48_regist                                    as cod_reg_trab, "; 
		$sSqlK300 .= "        lpad(r48_mesusu,2,'0')||r48_anousu            as dt_comp, "; 
		$sSqlK300 .= "        r48_rubric                                    as cod_rubr, "; 
		$sSqlK300 .= "        replace(cast(cast(round(r48_valor,2) as numeric) as text),'.',',') as vlr_rubr, "; 
		$sSqlK300 .= "        case  "; 
		$sSqlK300 .= "          when r48_pd = 1 then 'P' "; 
		$sSqlK300 .= "          else 'D' "; 
		$sSqlK300 .= "        end as ind_rubr, "; 
		$sSqlK300 .= "        case  "; 
		$sSqlK300 .= "          when b1.r09_base is not null then 1 "; 
		$sSqlK300 .= "          else 3 "; 
		$sSqlK300 .= "        end as ind_base_irrf, "; 
		$sSqlK300 .= "        case  "; 
		$sSqlK300 .= "          when r48_rubric in (select r09_rubric from basesr where r09_anousu = rh02_anousu 
                                                   and r09_mesusu = rh02_mesusu and r09_base = 'B001' and r09_instit = {$iInstit}) then 1 "; 
		$sSqlK300 .= "          when r48_rubric between 'R901' and 'R912' then 3 "; 
		$sSqlK300 .= "          when r48_rubric in ('R918','R919', 'R920', 'R921' ) then 4 "; 
		$sSqlK300 .= "          else 8 "; 
		$sSqlK300 .= "        end as ind_base_ps "; 
		$sSqlK300 .= "   from gerfcom "; 
		$sSqlK300 .= "        inner join rhpessoal    on rhpessoal.rh01_regist = gerfcom.r48_regist "; 
		$sSqlK300 .= "        inner join rhfuncao     on rhfuncao.rh37_funcao  = rhpessoal.rh01_funcao "; 
		$sSqlK300 .= "                               and rhfuncao.rh37_instit  = gerfcom.r48_instit "; 
		$sSqlK300 .= "        inner join rhpessoalmov on rhpessoalmov.rh02_regist = gerfcom.r48_regist "; 
		$sSqlK300 .= "                               and rhpessoalmov.rh02_mesusu = gerfcom.r48_mesusu "; 
		$sSqlK300 .= "                               and rhpessoalmov.rh02_anousu = gerfcom.r48_anousu "; 
                $sSqlK300 .= "        inner join rhregime     on rh30_codreg = rh02_codreg ";
                $sSqlK300 .= "                               and rh30_instit = rh02_instit ";
		$sSqlK300 .= "        left  join basesr as b1 on b1.r09_anousu  = r48_anousu "; 
		$sSqlK300 .= "                               and b1.r09_mesusu = r48_mesusu "; 
		$sSqlK300 .= "                               and b1.r09_instit = r48_instit "; 
		$sSqlK300 .= "                               and b1.r09_rubric = r48_rubric "; 
		$sSqlK300 .= "                               and b1.r09_base  in ('B004','B005') "; 
		$sSqlK300 .= "        left  join basesr as b2 on b2.r09_anousu  = r48_anousu "; 
		$sSqlK300 .= "                               and b2.r09_mesusu = r48_mesusu "; 
		$sSqlK300 .= "                               and b2.r09_instit = r48_instit "; 
		$sSqlK300 .= "                               and b2.r09_rubric = r48_rubric "; 
		$sSqlK300 .= "                               and b2.r09_base  in ('B001','B002') "; 
		$sSqlK300 .= "        left  join basesr as b3 on b3.r09_anousu  = r48_anousu "; 
		$sSqlK300 .= "                               and b3.r09_mesusu = r48_mesusu "; 
		$sSqlK300 .= "                               and b3.r09_instit = r48_instit "; 
		$sSqlK300 .= "                               and b3.r09_rubric = r48_rubric "; 
		$sSqlK300 .= "                               and b3.r09_base   = 'B003' "; 
		$sSqlK300 .= "  where gerfcom.r48_pd != 3 "; 
                $sSqlK300 .= "    and gerfcom.r48_instit = {$iInstit} ";
                $sSqlK300 .= "    and cast( gerfcom.r48_anousu||lpad(gerfcom.r48_mesusu,2,'0') as integer)
                                      between cast('$sData1' as integer)
                                          and cast('$sData2' as integer)";
                $sSqlK300 .= "   and cast(rhpessoalmov.rh02_anousu||lpad(rhpessoalmov.rh02_mesusu,2,'0') as integer) 
                                           between cast('$sData1' as integer)
                                               and cast('$sData2' as integer)";    
                $sSqlK300 .= "   and rhpessoalmov.rh02_instit = {$iInstit} ";
                                  
		$sSqlK300 .= " union "; 
		$sSqlK300 .= " select 'K300'                                        as reg, "; 
		$sSqlK300 .= "        (select cgc from db_config where codigo =  {$iInstit}) as cnpj, "; 
		$sSqlK300 .= "        6                                             as ind_fl, "; 
		$sSqlK300 .= "        r20_lotac                                     as cod_ltc, "; 
		$sSqlK300 .= "        r20_regist                                    as cod_reg_trab, "; 
		$sSqlK300 .= "        lpad(r20_mesusu,2,'0')||r20_anousu            as dt_comp, "; 
		$sSqlK300 .= "        r20_rubric                                    as cod_rubr, "; 
		$sSqlK300 .= "        replace(cast(cast(round(r20_valor,2) as numeric) as text),'.',',') as vlr_rubr, "; 
		$sSqlK300 .= "        case  "; 
		$sSqlK300 .= "          when r20_pd = 1 then 'P' "; 
		$sSqlK300 .= "          else 'D' "; 
		$sSqlK300 .= "        end as ind_rubr, "; 
		$sSqlK300 .= "        case  "; 
		$sSqlK300 .= "          when b1.r09_base is not null then 1 "; 
		$sSqlK300 .= "          else 3 "; 
		$sSqlK300 .= "        end as ind_base_irrf, "; 
		$sSqlK300 .= "        case  "; 
		$sSqlK300 .= "          when r20_rubric in (select r09_rubric from basesr where r09_anousu = rh02_anousu 
                                                    and r09_mesusu = rh02_mesusu and r09_base = 'B003' and r09_instit = {$iInstit}) then 2 "; 
		$sSqlK300 .= "          when r20_rubric between 'R901' and 'R912' then 3 "; 
		$sSqlK300 .= "          when r20_rubric in ('R918','R919', 'R920', 'R921' ) then 4 "; 
		$sSqlK300 .= "          else 8 "; 
		$sSqlK300 .= "        end as ind_base_ps "; 
		$sSqlK300 .= "   from gerfres "; 
		$sSqlK300 .= "        inner join rhpessoal    on rhpessoal.rh01_regist = gerfres.r20_regist "; 
		$sSqlK300 .= "        inner join rhfuncao     on rhfuncao.rh37_funcao  = rhpessoal.rh01_funcao "; 
		$sSqlK300 .= "                               and rhfuncao.rh37_instit  = gerfres.r20_instit "; 
		$sSqlK300 .= "        inner join rhpessoalmov on rhpessoalmov.rh02_regist = gerfres.r20_regist "; 
		$sSqlK300 .= "                               and rhpessoalmov.rh02_mesusu = gerfres.r20_mesusu "; 
		$sSqlK300 .= "                               and rhpessoalmov.rh02_anousu = gerfres.r20_anousu "; 
                $sSqlK300 .= "        inner join rhregime     on rh30_codreg = rh02_codreg ";
                $sSqlK300 .= "                               and rh30_instit = rh02_instit ";
		$sSqlK300 .= "        left  join basesr as b1 on b1.r09_anousu  = r20_anousu "; 
		$sSqlK300 .= "                               and b1.r09_mesusu = r20_mesusu "; 
		$sSqlK300 .= "                               and b1.r09_instit = r20_instit "; 
		$sSqlK300 .= "                               and b1.r09_rubric = r20_rubric "; 
		$sSqlK300 .= "                               and b1.r09_base  in ('B004','B005') "; 
		$sSqlK300 .= "        left  join basesr as b2 on b2.r09_anousu  = r20_anousu "; 
		$sSqlK300 .= "                               and b2.r09_mesusu = r20_mesusu "; 
		$sSqlK300 .= "                               and b2.r09_instit = r20_instit "; 
		$sSqlK300 .= "                               and b2.r09_rubric = r20_rubric "; 
		$sSqlK300 .= "                               and b2.r09_base  in ('B001','B002') "; 
		$sSqlK300 .= "        left  join basesr as b3 on b3.r09_anousu  = r20_anousu "; 
		$sSqlK300 .= "                               and b3.r09_mesusu = r20_mesusu "; 
		$sSqlK300 .= "                               and b3.r09_instit = r20_instit "; 
		$sSqlK300 .= "                               and b3.r09_rubric = r20_rubric "; 
		$sSqlK300 .= "                               and b3.r09_base   = 'B003' "; 
		$sSqlK300 .= "  where gerfres.r20_pd != 3 "; 
                $sSqlK300 .= "    and gerfres.r20_instit = {$iInstit} ";
                $sSqlK300 .= "    and cast( gerfres.r20_anousu||lpad(gerfres.r20_mesusu,2,'0') as integer)
                                      between cast('$sData1' as integer)
                                          and cast('$sData2' as integer)";
                $sSqlK300 .= "   and cast(rhpessoalmov.rh02_anousu||lpad(rhpessoalmov.rh02_mesusu,2,'0') as integer) 
                                           between cast('$sData1' as integer)
                                               and cast('$sData2' as integer)";    
                $sSqlK300 .= "   and rhpessoalmov.rh02_instit = {$iInstit} ";
                                              
                $sSqlK300 .= " ) as x ";
                $sSqlK300 .= " group by reg, "; 
                $sSqlK300 .= "          cnpj_cei, "; 
                $sSqlK300 .= "          ind_fl, "; 
                $sSqlK300 .= "          cod_ltc, "; 
                $sSqlK300 .= "          cod_reg_trab, "; 
                $sSqlK300 .= "          dt_comp, "; 
                $sSqlK300 .= "          cod_rubr, ";
                $sSqlK300 .= "          ind_rubr, ";
                $sSqlK300 .= "          ind_base_irrf, ";
                $sSqlK300 .= "          ind_base_ps  ";
                $sSqlK300 .= " order by reg, dt_comp, ind_fl, cod_rubr ";
///    echo $sSqlK300;exit; 
		return $sSqlK300;
		 
  }
  
  function getSql0000($iInstit,$iCodMun="",$sDtIni="",$sDtFim="",$iCodFin=""){
  	
    if ($sDtFim == "") {
      $sDtFim = "null";
    }
    if ($sDtIni == "") {
      $sDtIni = "null";
    }
    if ($iCodMun == "") {
      $sDtFim = "null";
    }
    if ($iCodFin == "") {
      $sDtFim = "null";
    }
    
  	$sSql0000  = " select '0000'      as reg, ";
		$sSql0000 .= "        nomeinst    as nome, ";
		$sSql0000 .= "        cgc         as cnpj, ";
		$sSql0000 .= "        null         as cpf, ";
		$sSql0000 .= "        null        as cei, ";
		$sSql0000 .= "        null        as nit, ";
		$sSql0000 .= "        uf          as uf, ";
		$sSql0000 .= "        null        as ie, ";
		$sSql0000 .= "        '{$iCodMun}'  as cod_mun, ";
		$sSql0000 .= "        null          as im, ";
		$sSql0000 .= "        null          as suframa, ";
		$sSql0000 .= "        '1'           as ind_centr, ";
		$sSql0000 .= "        '{$sDtIni}'   as dt_ini, ";
		$sSql0000 .= "        '{$sDtFim}'   as dt_fin, ";
		$sSql0000 .= "        '003'         as cod_ver, ";
		$sSql0000 .= "        '{$iCodFin}' as cod_fin, ";
		$sSql0000 .= "        '1'           as ind_ed ";
		$sSql0000 .= "   from db_config      ";
		$sSql0000 .= "  where codigo = {$iInstit} ";
		
		return $sSql0000;
  	
  }
  
  function getSql0001(){
    
    $sSql0001  = " select '0001' as reg, ";
    $sSql0001 .= "        '0'    as ind_mov ";
    return $sSql0001;
    
  }
  
  function getSqlK001(){
    
    $sSqlK001  = " select 'K001' as reg, ";
    $sSqlK001 .= "        '0'    as ind_mov ";
    return $sSqlK001;
    
  }
  
  function getSql0100($iInstit){
    
    $sSql0001  = " select '0100' as reg, ";
    $sSql0001 .= "        'PAULO RICARDO DA SILVA'   as emp_tec, ";
    $sSql0001 .= "        'DIRETOR'                  as cargo,   ";
    $sSql0001 .= "        to_char(dtcont,'ddmmYYYY') as dt_ini_serv_inf, ";
    $sSql0001 .= "        null                       as dt_fim_serv_inf, ";
    $sSql0001 .= "        '05238851000190'           as cnpj,  ";
    $sSql0001 .= "        null                       as cpf,   ";
    $sSql0001 .= "        '32122637'                 as fone,  ";
    $sSql0001 .= "        null                       as fax,   ";
    $sSql0001 .= "        'paulo@dbseller.com.br'    as email ";
    $sSql0001 .= "   from db_config ";
    $sSql0001 .= "  where codigo = {$iInstit} ";
    
    return $sSql0001;
    
  }
  
  function getSql0990(){
    
    $sSql0001  = " select '0990' as reg, ";
    $sSql0001 .= "        '4'    as qtd_lin ";
    return $sSql0001;
    
  }
  
  public function getLancamentosEmpenho($sDataInicial, $sDataFinal, $iAnoInicio) {
    
    list ($iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDataInicial);
    $iAno         = $iAnoUsuFim;
    $sListaInstit = db_getsession("DB_instit");
    /**
     * Aqui temos a lista de empenhos do exercicio., separado por documento contábil
     *  
     *  1  - ref. a empenho
     *  2  - ref. a anulacao de empenho
     *  31 - ref. a anulacao de RP
     *  32 - ref. a anulacao de RP
     */ 
    $sSqlEmpenho  = " select e60_numemp, ";
    $sSqlEmpenho .= "        e60_anousu, ";
    $sSqlEmpenho .= "        e60_anousu||trim(e60_codemp)::integer as nm_emp, ";
    $sSqlEmpenho .= "        o58_orgao as cod_org, ";
    $sSqlEmpenho .= "        o58_unidade as cod_un_orc, ";
    $sSqlEmpenho .= "        o58_funcao as cod_fun, ";
    $sSqlEmpenho .= "        o58_subfuncao as cod_subfun, ";
    $sSqlEmpenho .= "        cast(coalesce(o58_programa, '0') as varchar) as cod_progr, ";
    $sSqlEmpenho .= "        '0000' as cod_subprog, ";
    $sSqlEmpenho .= "        o58_projativ as cod_proj_ativ_oe,  ";
    $sSqlEmpenho .= "        case when o58_anousu >= 2005 then ";
    $sSqlEmpenho .= "             substr(trim(substr(o56_elemento,1,15))||'00000000000',1,15)::varchar(15) ";
    $sSqlEmpenho .= "        else ";
    $sSqlEmpenho .= "            substr(trim(o56_elemento)||'000000000',1,15)::varchar(15) ";
    $sSqlEmpenho .= "        end as cod_cta_desp, ";
    $sSqlEmpenho .= "        o15_codtri as cod_rec_vinc, ";
    $sSqlEmpenho .= "        (case when c71_coddoc in(32,31) then to_char(e60_emiss,'ddmmYYYY') else to_char(e60_emiss,'ddmmYYYY') end) as dt_emp, ";
    $sSqlEmpenho .= "        c70_valor as vl_emp, ";
    $sSqlEmpenho .= "        (case when c71_coddoc = 1 then 'D' else 'C' end)::char(1) as ind_deb_cred, ";  
    $sSqlEmpenho .= "        z01_numcgm as cod_credor, ";
    $sSqlEmpenho .= "        replace(e60_resumo,'\n', ' ')  as hist_emp, ";
    $sSqlEmpenho .= "        'L050' as reg, ";
    $sSqlEmpenho .= "        '' as cod_sub_pro, ";
    $sSqlEmpenho .= "        '' as cod_cont_rec";
    $sSqlEmpenho .= "   from empempenho  ";
    $sSqlEmpenho .= "        inner join conlancamemp on c75_numemp = e60_numemp ";
    $sSqlEmpenho .= "        inner join conlancamdoc on c71_codlan = c75_codlan ";
    $sSqlEmpenho .= "        inner join conlancam    on c70_codlan = c75_codlan ";
    $sSqlEmpenho .= "        inner join cgm          on z01_numcgm = e60_numcgm ";
    $sSqlEmpenho .= "        inner join orcdotacao   on o58_coddot = e60_coddot ";
    $sSqlEmpenho .= "                               and o58_anousu = e60_anousu "; 
    $sSqlEmpenho .= "                               and o58_instit = e60_instit ";
    $sSqlEmpenho .= "        inner join orcelemento  on o56_codele = o58_codele and o56_anousu = o58_anousu ";
    $sSqlEmpenho .= "        inner join orctiporec   on o58_codigo = o15_codigo";
    $sSqlEmpenho .= "  where c75_data  between '{$sDataInicial}' and '{$sDataFinal}' "; 
    $sSqlEmpenho .= "    and e60_emiss between '{$sDataInicial}' and '{$sDataFinal}' ";
    $sSqlEmpenho .= "    and c71_coddoc in (1,2,31,32) ";
    $sSqlEmpenho .= "    and e60_instit in ({$sListaInstit}) ";
          
   /**
    * Empenhos RP:
    */
    $sSqlEmpenho .= " union all ";
    $sSqlEmpenho .= " select distinct (e91_numemp) , ";
    $sSqlEmpenho .= "        e60_anousu, ";
    $sSqlEmpenho .= "        e60_anousu||trim(e60_codemp)::integer as e60_codemp, ";
    $sSqlEmpenho .= "        o58_orgao, ";
    $sSqlEmpenho .= "        o58_unidade, ";
    $sSqlEmpenho .= "        o58_funcao, ";
    $sSqlEmpenho .= "        o58_subfuncao, ";
    $sSqlEmpenho .= "        cast(coalesce(o58_programa, '0') as varchar) as o58_programa, ";
    $sSqlEmpenho .= "        '0000' as cod_subprogr, ";
    $sSqlEmpenho .= "        o58_projativ, ";
    $sSqlEmpenho .= "        case when o58_anousu >= 2005 then ";
    $sSqlEmpenho .= "             substr(trim(substr(o56_elemento,1,15))||'00000000000',1,15)::varchar(15) ";
    $sSqlEmpenho .= "        else ";
    $sSqlEmpenho .= "             substr(trim(o56_elemento)||'000000000',1,15)::varchar(15) ";
    $sSqlEmpenho .= "        end as rubrica, ";
    $sSqlEmpenho .= "        o15_codtri as recurso, ";
    $sSqlEmpenho .= "        to_char(e60_emiss,'ddmmYYYY'), ";
    $sSqlEmpenho .= "        round((e91_vlremp-e91_vlranu-e91_vlrpag),2)::float8 as valor_empenho, ";
    $sSqlEmpenho .= "        'D'::char(1) as sinal, ";  
    $sSqlEmpenho .= "        z01_numcgm, ";
    $sSqlEmpenho .= "        replace(e60_resumo,'\n', ' ') as e60_resumo, ";
    $sSqlEmpenho .= "        'L050' as reg, ";
    $sSqlEmpenho .= "        '' as cod_sub_pro, ";
    $sSqlEmpenho .= "        '' as cod_cont_rec";
    $sSqlEmpenho .= "   from empresto ";
    $sSqlEmpenho .= "        inner join empempenho on e60_numemp = e91_numemp         ";
    $sSqlEmpenho .= "        inner join cgm          on z01_numcgm = e60_numcgm ";
    $sSqlEmpenho .= "        inner join orcdotacao on o58_coddot=e60_coddot and  o58_anousu=e60_anousu and o58_instit = e60_instit ";
    $sSqlEmpenho .= "        inner join orcelemento on o56_codele = o58_codele and o56_anousu = o58_anousu ";
    $sSqlEmpenho .= "        inner join orctiporec   on o58_codigo = o15_codigo";
    $sSqlEmpenho .= "  where e91_anousu = {$iAno}"; 
    $sSqlEmpenho .= "    and e60_instit in  ({$sListaInstit}) ";
    $sSqlEmpenho .= "    and e91_rpcorreto is false ";
                    

    $sSqlEmpenho .= "  union all ";
    $sSqlEmpenho .= " select e60_numemp, ";
    $sSqlEmpenho .= "        e60_anousu, ";
    $sSqlEmpenho .= "        e60_anousu||trim(e60_codemp)::integer as e60_codemp, ";
    $sSqlEmpenho .= "        o58_orgao, ";
    $sSqlEmpenho .= "        o58_unidade, ";
    $sSqlEmpenho .= "        o58_funcao, ";
    $sSqlEmpenho .= "        o58_subfuncao, ";
    $sSqlEmpenho .= "        cast(coalesce(o58_programa, '0') as varchar) as o58_programa, ";
    $sSqlEmpenho .= "        '0000' as cod_subprogr, ";
    $sSqlEmpenho .= "        o58_projativ,  ";
    $sSqlEmpenho .= "        case when o58_anousu >= 2005 then ";
    $sSqlEmpenho .= "           substr(trim(substr(o56_elemento,1,15))||'00000000000',1,15)::varchar(15) ";
    $sSqlEmpenho .= "        else ";
    $sSqlEmpenho .= "           substr(trim(o56_elemento)||'000000000',1,15)::varchar(15) ";
    $sSqlEmpenho .= "        end as rubrica, ";
    $sSqlEmpenho .= "        o15_codtri as recurso, ";
    $sSqlEmpenho .= "        (case when c71_coddoc in(32,31) then to_char(e60_emiss,'ddmmYYYY') else to_char(e60_emiss,'ddmmYYYY') end) as e60_emiss, ";
    $sSqlEmpenho .= "        c70_valor as valor_empenho, ";
    $sSqlEmpenho .= "        (case when c71_coddoc = 1 then 'D' else 'C' end)::char(1) as sinal, ";  
    $sSqlEmpenho .= "        z01_numcgm, ";
    $sSqlEmpenho .= "        replace(e60_resumo,'\n', ' ') as e60_resumo, ";
    $sSqlEmpenho .= "        'L050' as reg, ";
    $sSqlEmpenho .= "        '' as cod_sub_pro, ";
    $sSqlEmpenho .= "        '' as cod_cont_rec";
    $sSqlEmpenho .= "   from empresto";
    $sSqlEmpenho .= "        inner join empempenho   on e91_numemp = e60_numemp";
    $sSqlEmpenho .= "        inner join conlancamemp on c75_numemp = e60_numemp";
    $sSqlEmpenho .= "        inner join conlancamdoc on c71_codlan = c75_codlan";
    $sSqlEmpenho .= "        inner join conhistdoc   on c71_coddoc = c53_coddoc";
    $sSqlEmpenho .= "        inner join conlancam on c70_codlan = c75_codlan";
    $sSqlEmpenho .= "        inner join cgm          on z01_numcgm = e60_numcgm ";
    $sSqlEmpenho .= "        inner join orcdotacao on o58_coddot=e60_coddot and o58_anousu=e60_anousu and o58_instit = e60_instit";
    $sSqlEmpenho .= "        inner join orcelemento on o56_codele = o58_codele and o56_anousu = o58_anousu";
    $sSqlEmpenho .= "        inner join orctiporec   on o58_codigo = o15_codigo";
    $sSqlEmpenho .= "  where e91_anousu = {$iAno}";
    $sSqlEmpenho .= "    and c75_data <= '{$sDataFinal}'";
    $sSqlEmpenho .= "    and c71_coddoc in (1,2,31,32) ";
    $sSqlEmpenho .= "    and e91_rpcorreto is true";
    $sSqlEmpenho .= "    and e60_instit in ({$sListaInstit})";
    $sSqlEmpenho .= "  order by 5, 6,7,8,9,10,11,12,13";
    $rsEmpenho    = db_query($sSqlEmpenho);
    $iTotalLinhas = pg_num_rows($rsEmpenho);
    return db_utils::getColectionByRecord($rsEmpenho);
  }
  
  /**
   * Retorna os dados de liquidacao de empenho feitas no periodo
   * 
   *
   * @param unknown_type $sDataInicial
   * @param unknown_type $sDataFinal
   */
  public function getLancamentosLiquidacao($sDataInicial, $sDataFinal, $iAnoInicio) {
    
    list ($iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDataInicial);
    $iAno         = $iAnoUsuFim;
    
    $sListaInstit = db_getsession("DB_instit");
     
    $sSqlLiquidacoes  = " select e60_anousu as ano, ";
    $sSqlLiquidacoes .= "        e60_anousu||trim(e60_codemp)::integer as nm_emp, ";
    $sSqlLiquidacoes .= "        to_char(c75_data,'ddmmYYYY') as dt_liquid,";
    $sSqlLiquidacoes .= "        round(c70_valor,2)::float8 as vl_liquid,";
    $sSqlLiquidacoes .= "        case when c53_tipo = 20 "; 
    $sSqlLiquidacoes .= "             then 'D'"; 
    $sSqlLiquidacoes .= "        else 'C' end as ind_deb_cred,";
    $sSqlLiquidacoes .= "        'Liquidação Número :'||c70_codlan as hist_liquid,";
    $sSqlLiquidacoes .= "        c75_codlan as nm_liquid,";
    $sSqlLiquidacoes .= "        'L100' as reg";
    $sSqlLiquidacoes .= "   from conlancamemp";
    $sSqlLiquidacoes .= "        inner join conlancam    on c70_codlan = c75_codlan";
    $sSqlLiquidacoes .= "        inner join empempenho   on e60_numemp = c75_numemp";
    $sSqlLiquidacoes .= "        inner join cgm          on e60_numcgm = z01_numcgm";
    $sSqlLiquidacoes .= "        inner join conlancamdoc on c71_codlan = c75_codlan";
    $sSqlLiquidacoes .= "        inner join conhistdoc   on c53_coddoc = c71_coddoc ";
    $sSqlLiquidacoes .= "                               and (c53_tipo = 20 or c53_tipo = 21) ";        
    $sSqlLiquidacoes .= "  where c75_data between '{$sDataInicial}' and '{$sDataFinal}'";
    $sSqlLiquidacoes .= "   and e60_emiss  <='{$sDataFinal}'";
    $sSqlLiquidacoes .= "   and e60_anousu  = {$iAno}";
    $sSqlLiquidacoes .= "   and e60_instit in ($sListaInstit)";
    
    $sSqlLiquidacoes .= " union all ";     
    
    $sSqlLiquidacoes .= " select e60_anousu as ano, ";
    $sSqlLiquidacoes .= "        e60_anousu||trim(e60_codemp)::integer as e60_codemp, ";
    $sSqlLiquidacoes .= "        to_char(c75_data,'ddmmYYYY') as dt_liquid,";
    $sSqlLiquidacoes .= "        round(c70_valor,2)::float8 as vl_liquid,";
    $sSqlLiquidacoes .= "        case when c53_tipo = 20 "; 
    $sSqlLiquidacoes .= "             then 'D'"; 
    $sSqlLiquidacoes .= "        else 'C' end as ind_deb_cred,";
    $sSqlLiquidacoes .= "        'Liquidação Número :'||c70_codlan as hist_liquid,";
    $sSqlLiquidacoes .= "        c75_codlan as nm_liquid,";
    $sSqlLiquidacoes .= "        'L100' as reg";
    $sSqlLiquidacoes .= "  from empresto  ";
    $sSqlLiquidacoes .= "       inner join conlancamemp on e91_numemp = c75_numemp ";
    $sSqlLiquidacoes .= "       inner join conlancam    on c70_codlan = c75_codlan ";
    $sSqlLiquidacoes .= "       inner join empempenho   on e60_numemp = c75_numemp ";
    $sSqlLiquidacoes .= "       inner join cgm          on e60_numcgm = z01_numcgm ";
    $sSqlLiquidacoes .= "       inner join conlancamdoc on c71_codlan = c75_codlan ";
    $sSqlLiquidacoes .= "       inner join conhistdoc   on c53_coddoc = c71_coddoc ";
    $sSqlLiquidacoes .= "                              and (c53_tipo  = 20 or c53_tipo = 21) ";      
    $sSqlLiquidacoes .= "  where (c75_data between '{$sDataInicial}' and '{$sDataFinal}')";
    $sSqlLiquidacoes .= "     and e60_emiss <='{$sDataFinal}' ";
    $sSqlLiquidacoes .= "     and e60_anousu < {$iAno} ";
    $sSqlLiquidacoes .= "     and e60_instit in ($sListaInstit) and e91_anousu = ".db_getsession("DB_anousu");
    $rsLiquidacoes    = db_query($sSqlLiquidacoes);
    return db_utils::getColectionByRecord($rsLiquidacoes);
    
  }
  
  
  public function getLancamentosPagamento($sDataInicial, $sDataFinal, $iAnoInicio) {
    
    list ($iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDataInicial);
    $iAno         = $iAnoUsuFim;
    
    $sListaInstit = db_getsession("DB_instit");
    $sWhere          =  " and e60_instit in ({$sListaInstit})";
    $sSqlPagamentos  = "select e60_anousu as ano, ";
    $sSqlPagamentos .= "       e60_anousu||trim(e60_codemp)::integer as nm_emp, ";
    $sSqlPagamentos .= "       c75_codlan as nm_pgto, ";
    $sSqlPagamentos .= "       to_char(c75_data, 'ddmmYYYY') as dt_pgto, ";
    $sSqlPagamentos .= "       round(c70_valor,2) as vl_pgto, ";
    $sSqlPagamentos .= "       case when c53_tipo = 30 then ";
    $sSqlPagamentos .= "           'D'  else 'C' ";  
    $sSqlPagamentos .= "       end as ind_deb_cred, ";
    $sSqlPagamentos .= "       case when c72_complem is null then '' else replace(c72_complem,'\n',' ')  end || ";
    $sSqlPagamentos .= "            ' Ordem Pagto:' || replace(c80_codord::text, '\n',' ') as hist_pgto, ";
    $sSqlPagamentos .= "       c82_reduz as cta_cred, ";
    $sSqlPagamentos .= "       case when c69_debito = c82_reduz then ";
    $sSqlPagamentos .= "       c69_credito  else c69_debito ";
    $sSqlPagamentos .= "       end as cta_deb,    ";
    $sSqlPagamentos .= "       db_config.codtrib as cod_org_un_deb, ";
    $sSqlPagamentos .= "       db_config.codtrib as cod_org_un_cred, ";
    $sSqlPagamentos .= "       'L150' as reg ";
    $sSqlPagamentos .= "  from conlancamemp    ";
    $sSqlPagamentos .= "       inner join conlancam      on c70_codlan = c75_codlan ";
    $sSqlPagamentos .= "       inner join conlancampag   on c82_codlan = c70_codlan ";
    $sSqlPagamentos .= "       inner join conlancamval   on c69_codlan = c70_codlan ";
    $sSqlPagamentos .= "                                and  ( c69_debito =  c82_reduz or c69_credito = c82_reduz) ";
    $sSqlPagamentos .= "       inner join empempenho     on e60_numemp    = c75_numemp ";
    $sSqlPagamentos .= "       inner join db_config      on db_config.codigo = empempenho.e60_instit ";
    $sSqlPagamentos .= "       inner join conlancamdoc   on c71_codlan = c75_codlan ";
    $sSqlPagamentos .= "       inner join conhistdoc     on c53_coddoc = c71_coddoc ";
    $sSqlPagamentos .= "                                and (c53_tipo  = 30 or c53_tipo = 31)  ";
    $sSqlPagamentos .= "       left  join conlancamord   on c80_codlan = c70_codlan ";
    $sSqlPagamentos .= "       left  join conlancamcompl on c72_codlan = c70_codlan ";
    $sSqlPagamentos .= " where c75_data between '{$sDataInicial}' and '{$sDataFinal}' ";
    $sSqlPagamentos .= "   and e60_emiss <= '{$sDataFinal}' {$sWhere} ";     
    $sSqlPagamentos .= "   and e60_anousu = {$iAno} ";     
    $sSqlPagamentos .= " union all ";
    
    $sSqlPagamentos  .= "select e60_anousu as ano, ";
    $sSqlPagamentos .= "       e60_anousu||trim(e60_codemp)::integer as nm_emp, ";
    $sSqlPagamentos .= "       c75_codlan as nm_pgto, ";
    $sSqlPagamentos .= "       to_char(c75_data, 'ddmmYYYY') as dt_pgto, ";
    $sSqlPagamentos .= "       round(c70_valor,2) as vl_pgto, ";
    $sSqlPagamentos .= "       case when c53_tipo = 30 then ";
    $sSqlPagamentos .= "           'D'  else 'C' ";  
    $sSqlPagamentos .= "       end as ind_deb_cred, ";
    $sSqlPagamentos .= "       case when c72_complem is null then '' else replace(c72_complem,'\n',' ')  end || ";
    $sSqlPagamentos .= "            ' Ordem Pagto:' || replace(c80_codord::text, '\n',' ') as historico, ";
    $sSqlPagamentos .= "       c82_reduz as cta_cred, ";
    $sSqlPagamentos .= "       case when c69_debito = c82_reduz then ";
    $sSqlPagamentos .= "       c69_credito  else c69_debito ";
    $sSqlPagamentos .= "       end as cta_deb,    ";
    $sSqlPagamentos .= "       db_config.codtrib as cod_org_un_deb, ";
    $sSqlPagamentos .= "       db_config.codtrib as cod_org_un_cre, ";
    $sSqlPagamentos .= "       'L150' as reg ";
    $sSqlPagamentos .= "  from empresto ";
    $sSqlPagamentos .= "       inner join conlancamemp on c75_numemp = e91_numemp ";
    $sSqlPagamentos .= "       inner join conlancam    on c70_codlan = c75_codlan ";
    $sSqlPagamentos .= "       inner join conlancampag on c82_codlan = c70_codlan ";
    $sSqlPagamentos .= "       inner join conlancamval on c69_codlan = c70_codlan ";
    $sSqlPagamentos .= "                              and  ( c69_debito   =  c82_reduz or c69_credito = c82_reduz) ";
    $sSqlPagamentos .= "       inner join empempenho on e60_numemp = c75_numemp ";
    $sSqlPagamentos .= "       inner join db_config on db_config.codigo = empempenho.e60_instit ";
    $sSqlPagamentos .= "       inner join conlancamdoc on c71_codlan = c75_codlan ";
    $sSqlPagamentos .= "       inner join conhistdoc on c53_coddoc = c71_coddoc ";
    $sSqlPagamentos .= "                            and (c53_tipo = 30 or c53_tipo = 31)  ";
    $sSqlPagamentos .= "       left outer join conlancamord on c80_codlan = c70_codlan ";
    $sSqlPagamentos .= "       left outer join conlancamcompl on c72_codlan = c70_codlan ";
    $sSqlPagamentos .= " where e91_anousu = {$iAno}";
    $sSqlPagamentos .= "   and e91_rpcorreto is true ";
    $sSqlPagamentos .= "   and c75_data between '{$sDataInicial}' and '{$sDataFinal}' ";
    $sSqlPagamentos .= "   and e60_emiss <= '{$sDataFinal}' {$sWhere} ";             
    $sSqlPagamentos .= " order by ano,2 ";
    $rsPagamentos    = db_query($sSqlPagamentos);
    $iTotalLinhas    = pg_num_rows($rsPagamentos);
    $aPagamentos     = array();
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $oPagamento = db_utils::fieldsMemory($rsPagamentos, $i);
      
      $sSqlContaPagadora  = "select c60_estrut "; 
      $sSqlContaPagadora .= "  from conplanoreduz ";
      $sSqlContaPagadora .= "       inner join conplano on c60_codcon = c61_codcon and c60_anousu = c61_anousu ";
      $sSqlContaPagadora .= "  where c61_reduz = ".$oPagamento->cta_cred;
      $rsContaPagadora    = db_query($sSqlContaPagadora);
      if (pg_num_rows($rsContaPagadora) > 0) { 
        $iContaPagadora  = str_pad(pg_result($rsContaPagadora,0,"c60_estrut"), 15,'0', STR_PAD_RIGHT);
      }
      
      $iContraPartida     = $oPagamento->cta_deb;
      $sSqlContraPartida  = "select c60_estrut "; 
      $sSqlContraPartida .= "  from conplanoreduz ";
      $sSqlContraPartida .= "       inner join conplano on c60_codcon = c61_codcon and c60_anousu = c61_anousu ";
      $sSqlContraPartida .= "  where c61_reduz = ".$oPagamento->cta_deb;
      $rsContraPartida    = db_query($sSqlContraPartida);
      if (pg_num_rows($rsContraPartida) > 0) { 
        $iContraPartida  = str_pad(pg_result($rsContraPartida,0,"c60_estrut"), 15,'0', STR_PAD_RIGHT);
      }
      
      $iContaDebito  = $iContraPartida;
      $iContaCredito = $iContaPagadora;
      
      $oPagamento->cta_debito  = $iContaDebito;                                                                              
      $oPagamento->cta_credito = $iContaCredito;
      $aPagamentos[] = $oPagamento; 
    }
    return $aPagamentos;    
  }
  
  /**
   * Retorna dos dados do balancete de receita
   *
   * @param unknown_type $sDataInicial
   * @param unknown_type $sDataFinal
   */
  public function getDadosBalanceteReceita($sDataInicial, $sDataFinal, $iAnoInicio) {
    
    list ($iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDataInicial);
    $iAno           = $iAnoUsuFim;
    $oInstituicao   = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $sListaInstit   = db_getsession("DB_instit");
    $sWhere         =  " o70_instit in ({$sListaInstit})";
    
    $sSqlDadosBalancete  = db_receitasaldo(11, 1, 3, true, $sWhere, $iAno, $sDataInicial, $sDataFinal, true);

    $sSqlBalancete  = " select case  when {$iAno} <= 2007 then ";
    $sSqlBalancete .= "         substr(o57_fonte,2,14) "; 
    $sSqlBalancete .= "       else  ";
    $sSqlBalancete .= "         case  when fc_conplano_grupo({$iAno}, substr(o57_fonte,1,1) || '%', 9000 ) is false then "; 
    $sSqlBalancete .= "           substr(o57_fonte, 2, 14) "; 
    $sSqlBalancete .= "         else  ";
    $sSqlBalancete .= "          substr(o57_fonte,1,15) "; 
    $sSqlBalancete .= "         end  ";
    $sSqlBalancete .= "       end as o57_fonte, ";
    $sSqlBalancete .= "       max(o57_descr) as o57_descr, ";
    $sSqlBalancete .= "       round(sum(saldo_inicial),2) as saldo_inicial, ";
    $sSqlBalancete .= "       round(sum(saldo_arrecadado),2) as saldo_arrecadado, ";  
    $sSqlBalancete .= "       max(o15_codigo) as o70_codigo, ";
    $sSqlBalancete .= "       max(x.o70_codrec) as o70_codrec,           ";
    $sSqlBalancete .= "       max(coalesce(o70_instit,0)) as o70_instit, ";
    $sSqlBalancete .= "       max(fc_nivel_plano2005(rpad(x.o57_fonte,20,'0'))) as nivel, ";
    $sSqlBalancete .= "       round(sum(saldo_prevadic_acum),2) as saldo_prevadic_acum ";
    $sSqlBalancete .= " from ({$sSqlDadosBalancete}) as x  ";
    $sSqlBalancete .= "       left join orcreceita on orcreceita.o70_codrec = x.o70_codrec and o70_anousu={$iAno} ";
    $sSqlBalancete .= "       left join orctiporec on orcreceita.o70_codigo = o15_codigo ";
    $sSqlBalancete .= " group by o57_fonte ";
    $sSqlBalancete .= " order by o57_fonte asc ";

    $rsBalancete    = db_query($sSqlBalancete);
    $iTotalLinhas = pg_num_rows($rsBalancete);
    if (PostgreSQLUtils::isTableExists("work_receita")) {
      db_query("drop table if exists work_receita");
    }

    $aContas = array();
    for ($i = 0; $i < $iTotalLinhas; $i++) {

      $oConta = new stdClass();
      $oContaReceita = db_utils::fieldsMemory($rsBalancete, $i);

      $oConta->reg   = 'L200';
      $oConta->exerc = $iAno; 
      $oConta->cod_cta_receita  = $oContaReceita->o57_fonte;
      $oConta->cod_org_un_orc   = $oInstituicao->codtrib;
      $oConta->vl_rec_orcada    = number_format($oContaReceita->saldo_inicial,'2', ",","");
      $oConta->vl_rec_realizada = number_format($oContaReceita->saldo_arrecadado,'2', ",","");
      $oConta->cod_rec_vinc     = $oContaReceita->o70_codigo;
      $oConta->desc_receita     = $oContaReceita->o57_descr;
      $oConta->ind_tipo_conta   = "S";
      if ($oContaReceita->o70_codrec > 0) {
         $oConta->ind_tipo_conta   = "A";
      }
      $oConta->nm_nivel_conta   = $oContaReceita->nivel;

      $aContas[] = $oConta;

    }
    return $aContas;
  }
  
  /**
   * Retorna os dados do balancete da despesa
   *
   */
  public function getDadosBalanceteDespesa($sDataInicial, $sDataFinal, $iAnoInicio) {
    
    list ($iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDataInicial);
    $iAno            = $iAnoUsuFim;
    $lUsaSubElemento = false;
    $oParamOrcamento = db_stdClass::getParametro("orcparametro", array($iAno));
    if ($oParamOrcamento[0]->o50_subelem == 't') {
      $lUsaSubElemento = true; 
    }
        
    /**
     * Separamos a data do em ano, mes, dia
     */
    $oInstituicao = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $sListaInstit = db_getsession("DB_instit");
    $sWhere       = "w.o58_instit in ({$sListaInstit}) ";
    db_query("begin");
    db_query("create temp table t as select * from orcdotacao where o58_anousu = ".db_getsession("DB_anousu"));
    
    if ($lUsaSubElemento){
      $rsDotacaoSaldo = db_dotacaosaldo(8, 1, 4, true, $sWhere, $iAno,  
                                        $sDataInicial, $sDataFinal,'8','0',false,'1',true,"sim");
    }else { 
      $rsDotacaoSaldo = db_dotacaosaldo(8, 1, 4, true, $sWhere, $iAno,
                                       $sDataInicial, $sDataFinal);
    }
    db_query("rollback");
    
    $aDotacoes    = array();
    $iTotalLinhas = pg_num_rows($rsDotacaoSaldo);
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $oDespesa            = db_utils::fieldsMemory($rsDotacaoSaldo, $i);

      if($oDespesa->o58_codigo == 0) {
        continue;
      }
      
      $sSqlSuplementacoes  = "  select sum(case when c71_coddoc in (7,52,53,54,55,71) then c70_valor else 0 end ) - ";
      $sSqlSuplementacoes .= "  sum(case when c71_coddoc in (8) then c70_valor else 0 end ) ";
      $sSqlSuplementacoes .= "  as sup , ";
      $sSqlSuplementacoes .= "  sum(case when c71_coddoc in (62,56,58,59,60,61,64) then c70_valor else 0 end ) - ";
      $sSqlSuplementacoes .= "  sum(case when c71_coddoc in (10) then c70_valor else 0 end ) ";
      $sSqlSuplementacoes .= "  as cre, ";
      $sSqlSuplementacoes .= "  sum(case when c71_coddoc in (63) then c70_valor else 0 end )- ";
      $sSqlSuplementacoes .= "  sum(case when c71_coddoc in (14) then c70_valor else 0 end ) ";
      $sSqlSuplementacoes .= "  as esp ";
      $sSqlSuplementacoes .= "  from conlancamdoc  ";
      $sSqlSuplementacoes .= "  inner join conlancam on c70_codlan = c71_codlan ";
      $sSqlSuplementacoes .= "  inner join conlancamdot on c73_codlan = c71_codlan  ";
      $sSqlSuplementacoes .= "  inner join conlancamsup on c79_codlan = c71_codlan  ";
      $sSqlSuplementacoes .= "  where c71_coddoc in (7,8,10,14,52,53,54,55,56,58,59,60,61,62,63,64,71) ";
      $sSqlSuplementacoes .= "  and c71_data between '{$sDataInicial}' and '{$sDataFinal}' ";
      $sSqlSuplementacoes .= "  and c73_coddot = $oDespesa->o58_coddot and ";
      $sSqlSuplementacoes .= "  c73_anousu = ".db_getsession("DB_anousu");
        
      $sSqlDesdobramento  = "select sum(case when c71_coddoc in (7,52,53,54,55) then c70_valor else 0 end ) as sup , ";
      $sSqlDesdobramento .= "       sum(case when c71_coddoc in (56,58,59,60,61,64) then c70_valor else 0 end ) as cre, ";
      $sSqlDesdobramento .= "       sum(case when c71_coddoc in (62,63) then c70_valor else 0 end ) as esp ";
      $sSqlDesdobramento .= "  from conlancamdoc  ";
      $sSqlDesdobramento .= "        inner join conlancam on c70_codlan = c71_codlan ";
      $sSqlDesdobramento .= "        inner join conlancamdot on c73_codlan = c71_codlan  ";
      $sSqlDesdobramento .= "        inner join conlancamsup on c79_codlan = c71_codlan ";
      $sSqlDesdobramento .= "        inner join orcdotacao  on c73_coddot = o58_coddot and "; 
      $sSqlDesdobramento .= "                              o58_anousu = ".db_getsession("DB_anousu")." and "; 
      $sSqlDesdobramento .= "                              o58_orgao = $oDespesa->o58_orgao and  ";
      $sSqlDesdobramento .= "                              o58_unidade = $oDespesa->o58_unidade and  ";
      $sSqlDesdobramento .= "                              o58_funcao = $oDespesa->o58_funcao and  ";
      $sSqlDesdobramento .= "                              o58_subfuncao = $oDespesa->o58_subfuncao and  ";
      $sSqlDesdobramento .= "                              o58_programa = $oDespesa->o58_programa and  ";
      $sSqlDesdobramento .= "                              o58_projativ = $oDespesa->o58_projativ ";
      $sSqlDesdobramento .= "                          and o58_codigo = $oDespesa->o58_codigo ";
      $sSqlDesdobramento .= "        inner join orcelemento on o56_codele = o58_codele and o56_anousu = o58_anousu ";
      $sSqlDesdobramento .= "  where c71_coddoc in (7,52,53,54,55,56,58,59,60,61,62,63,64) ";
      $sSqlDesdobramento .= "    and c71_data between '{$sDataInicial}' and '{$sDataFinal}' ";
      //$sql_desdobramento .= "  /* and c73_coddot = $o58_coddot */ ";
      $sSqlDesdobramento .= "    and substr(o56_elemento,1,7)='".substr($oDespesa->o58_elemento,0,7)."' ";
      $sSqlDesdobramento .= "    and c73_anousu = ".db_getsession("DB_anousu");
      
      if ($lUsaSubElemento) {
          $rsSuplementacoes = db_query($sSqlDesdobramento);      
      } else {
          $rsSuplementacoes = db_query($sSqlDesdobramento);     
      }  
      
      $nSuplementacoes        = 0;
      $nCreditoEspecias       = 0;
      $nCreditoExtraordinario = 0;
      if (pg_num_rows($rsSuplementacoes) >0 ) {
        
        $oSuplementacao         = db_utils::fieldsMemory($rsSuplementacoes, 0);
        $nSuplementacoes        = $oSuplementacao->sup; 
        $nCreditoEspecias       = $oSuplementacao->cre; 
        $nCreditoExtraordinario = $oSuplementacao->esp; 
          
      }
      
      $oDotacaoRetorno = new stdClass();
      $oDotacaoRetorno->reg                = "L250";
      $oDotacaoRetorno->exerc              = $iAno;
      $oDotacaoRetorno->cod_org            = str_pad($oDespesa->o58_orgao, 2, 0, STR_PAD_LEFT);
      $oDotacaoRetorno->cod_un_orc         = str_pad($oDespesa->o58_unidade, 2, 0, STR_PAD_LEFT);
      $oDotacaoRetorno->cod_fun            = str_pad($oDespesa->o58_funcao, 2, 0, STR_PAD_LEFT);
      $oDotacaoRetorno->cod_subfun         = str_pad($oDespesa->o58_subfuncao, 3, 0, STR_PAD_LEFT);
      $oDotacaoRetorno->cod_prog           = str_pad($oDespesa->o58_programa, 4 , 0, STR_PAD_LEFT);
      $oDotacaoRetorno->cod_subprog        = '0000';
      $oDotacaoRetorno->cod_proj_ativ_oe   = str_pad($oDespesa->o58_projativ, 4, "0", STR_PAD_LEFT);
      $oDotacaoRetorno->cod_subelemento    = str_pad($oDespesa->o58_elemento, 15, "0", STR_PAD_RIGHT);
      $oDotacaoRetorno->cod_cta_desp       = str_pad($oDespesa->o58_elemento, 15, "0", STR_PAD_RIGHT);
      $oDotacaoRetorno->cod_rec_vinc       = str_pad($oDespesa->o58_codigo, 4, 0, STR_PAD_LEFT);
      $oDotacaoRetorno->vl_dotacao_inicial = $this->corrigeValor($oDespesa->dot_ini, 13);
      $oDotacaoRetorno->vl_at_monetaria    = $this->corrigeValor(0, 13);
      $oDotacaoRetorno->vl_cred_sup        = $this->corrigeValor($nSuplementacoes, 13);
      $oDotacaoRetorno->vl_cred_esp        = $this->corrigeValor($nCreditoEspecias, 13);
      $oDotacaoRetorno->vl_cred_ext        = $this->corrigeValor($nCreditoExtraordinario, 13);
      $oDotacaoRetorno->vl_red_dotacao     = $this->corrigeValor(abs($oDespesa->reduzido_acumulado), 13);
      $oDotacaoRetorno->vl_sup_rec_vinc    = $this->corrigeValor(0, 13);
      $oDotacaoRetorno->vl_red_rec_vinc    = $this->corrigeValor(0, 13);
      $oDotacaoRetorno->vl_empenhado       = $this->corrigeValor(abs(
                                                                 round($oDespesa->empenhado-$oDespesa->anulado,2)), 13);
      $oDotacaoRetorno->vl_liquidado       = $this->corrigeValor(abs($oDespesa->liquidado), 13);                                                                  
      $oDotacaoRetorno->vl_pago            = $this->corrigeValor(abs($oDespesa->pago), 13);                                                                  
      $oDotacaoRetorno->vl_lmtdo_lrf       = $this->corrigeValor(0, 13);
      $aDotacoes[]                         = $oDotacaoRetorno;                                                                  
    }
    return $aDotacoes;
  }

  public function getDadosDecretos ($sDataInicial, $sDataFinal, $iAnoInicio) {
    
    list ($iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDataInicial);
    $iAno         = $iAnoUsuFim;
    /**
     * Separamos a data do em ano, mes, dia
     */
    $aDecretos    = array();
    $oInstituicao = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $sListaInstit = db_getsession("DB_instit");
    $sSqlDecreto  = " select *, ";
    $sSqlDecreto .= "        case when (  ";
    $sSqlDecreto .= "           select count(distinct(o58_instit)) ";
    $sSqlDecreto .= "           from orcsuplemval s ";
    $sSqlDecreto .= "                 inner join orcdotacao on o58_coddot=s.o47_coddot and o58_anousu=s.o47_anousu ";
    $sSqlDecreto .= "           where s.o47_codsup = x.codsup ";
    $sSqlDecreto .= "           group by o47_codsup ";                                                 
    $sSqlDecreto .= "         ) = 1  ";
    $sSqlDecreto .= "        then false  ";
    $sSqlDecreto .= "        else true end     ";   
    $sSqlDecreto .= "        as interinstit ";
    $sSqlDecreto .= "  from (select o45_numlei as num_lei, ";
    $sSqlDecreto .= "               to_char(o45_dataini, 'ddmmYYYY') as data_lei, ";
    $sSqlDecreto .= "               o39_numero as num_decreto, ";    
    $sSqlDecreto .= "               to_char(o39_data, 'ddmmYYYY') as data_decreto, ";
    $sSqlDecreto .= "               o46_codsup as codsup, ";
    $sSqlDecreto .= "               o46_tiposup as tipo_credito, ";
    $sSqlDecreto .= "               o46_obs     as sinopse, ";
    $sSqlDecreto .= "               round(sum(case when o47_valor > 0 then o47_valor else 0 end),2) as valor_credito, ";
    $sSqlDecreto .= "               round(sum(case when o47_valor < 0 then abs(o47_valor) else 0 end),2) as valor_reducao ";
    $sSqlDecreto .= "          from orcprojeto  ";
    $sSqlDecreto .= "               inner join orclei              on o45_codlei = o39_codlei ";
    $sSqlDecreto .= "               inner join orcsuplem as suplem on o46_codlei = orcprojeto.o39_codproj ";
    $sSqlDecreto .= "               inner join orcsuplemval        on o47_codsup = o46_codsup ";
    $sSqlDecreto .= "               inner join orcdotacao d        on o58_coddot = o47_coddot ";
    $sSqlDecreto .= "                                             and o58_anousu =".db_getsession("DB_anousu");
    $sSqlDecreto .= "                                             and o58_instit in ({$sListaInstit}) ";                                            
    $sSqlDecreto .= "               inner join orcsuplemlan on orcsuplemlan.o49_codsup= o46_codsup ";
    $sSqlDecreto .= "               left join orcsuplemretif on o48_retificado = orcprojeto.o39_codproj ";
    $sSqlDecreto .= "         where o39_anousu= {$iAno} ";
    $sSqlDecreto .= "           and o49_data between '{$sDataInicial}' and '{$sDataFinal}' ";
    $sSqlDecreto .= "         group by o45_numlei,o45_dataini,o39_numero,o39_data,o46_codsup,o46_tiposup,o46_obs ";   
    $sSqlDecreto .= "         order by o45_numlei,o45_dataini,o39_numero,o39_data ";
    $sSqlDecreto .= "      ) as x ";
    $rsDecretos   = db_query($sSqlDecreto);
    $iTotalLinhas = pg_num_rows($rsDecretos);
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $oDecreto = db_utils::fieldsMemory($rsDecretos, $i);
      switch ($oDecreto->tipo_credito) {
        
        case '1001':
         
          $iTipo   = '1';
          $iOrigem = '5';
          break;
        case '1002':
       
          $iTipo   = '1';
          $iOrigem = '3';
          break;
        case '1003':
         
          $iTipo   = '1';
          $iOrigem = '1';
          break;
        case '1004':
         
          $iTipo   = '1';
          $iOrigem = '2';
          break;
        case '1005':
         
          $iTipo   = '1';
          $iOrigem = '4';
          break;
        case '1006':
         
          $iTipo   = '2';
          $iOrigem = '5';
          break;
        case '1007':
        
          $iTipo   = '2';
          $iOrigem = '3';
          break;
        case'1008':

          $iTipo   = '2';
          $iOrigem = '1';
          break;
        case  '1009':
        
          $iTipo   = '2';
          $iOrigem = '2';
          break;
        case '1010':

          $iTipo   = '2';
          $iOrigem = '4';
          break;   
        case '1011':
         
          $iTipo   = '3';
          $iOrigem = '1';
          break;
        case'1012':
       
          $iTipo   = '2';
          $iOrigem = '1';
          break;
        case '1013':
       
          $iTipo   = '2';
          $iOrigem = '3';
          break;
        case  '1014':
       
          $iTipo   = '1';
          $iOrigem = '5';
          break;
        case '1015':
     
          $iTipo   = '1';
          $iOrigem = '5';
          break;
        case '1016':
      
          $iTipo   = '1';
          $iOrigem = '5';
          break;
      } 
      
      if ($oDecreto->interinstit == "t") {
        $iOrigem = 5;
      }
      $oDecretoRetorno = new stdClass();
      $oDecretoRetorno->reg                = 'L300';
      $oDecretoRetorno->nm_lei_decreto     = $oDecreto->num_decreto;
      $oDecretoRetorno->dt_lei_decreto     = $oDecreto->data_decreto;
      $oDecretoRetorno->vl_cred_adicional  = $this->corrigeValor($oDecreto->valor_credito, 13);
      $oDecretoRetorno->vl_red_dotacoes    = $this->corrigeValor($oDecreto->valor_reducao, 13);
      $oDecretoRetorno->tip_cred_adicional = $iTipo;
      $oDecretoRetorno->tip_orig_recurso   = $iOrigem;
      $aDecretos[] =  $oDecretoRetorno; 
    }
    return $aDecretos;
  }
  public function corrigeValor($nValor) {
    
    $nValor = number_format((double) $nValor, 2, ",",""); 
    return $nValor;
  }
  
  public function getDadosOrgao($sDataInicial, $sDataFinal, $iAnoInicio) {
    
    list ($iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDataFinal);
    $iAno         = $iAnoUsuFim;


    $aOrgaos      = array();
    $sWhereInstit = " and o58_instit = ".db_getsession("DB_instit");
    $sSqlOrgaos   = "select distinct    "; 
    $sSqlOrgaos  .= "       o40_anousu, ";
    $sSqlOrgaos  .= "       o40_orgao,  ";
    $sSqlOrgaos  .= "       o40_descr";
    $sSqlOrgaos  .= "  from orcorgao  ";
    $sSqlOrgaos  .= "      inner join orcdotacao on o58_orgao = o40_orgao  ";
    $sSqlOrgaos  .= "                           and o58_anousu = o40_anousu ";
    $sSqlOrgaos  .= " where o58_anousu between {$iAnoInicio} and  {$iAno} {$sWhereInstit}";
    $rsOrgaos     = db_query($sSqlOrgaos);
    $iTotalLinhas = pg_num_rows($rsOrgaos); 
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $oOrgao        = db_utils::fieldsMemory($rsOrgaos, $i);
      
      $oOrgaoRetorno             = new stdClass();
      $oOrgaoRetorno->reg        = "L350";
      $oOrgaoRetorno->exercicio  = $oOrgao->o40_anousu;
      $oOrgaoRetorno->cod_org    = $oOrgao->o40_orgao;
      $oOrgaoRetorno->nome_org   = $oOrgao->o40_descr;
      $aOrgaos[] =  $oOrgaoRetorno;
      
    }
    return $aOrgaos;
  }
  
  public function getDadosUnidades($sDataInicial, $sDataFinal, $iAnoInicio) {
    
    list ($iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDataFinal);
    $iAno         = $iAnoUsuFim;
    
    $iCodigoInstit = db_getsession("DB_instit");
    $sSqlUnidades  = "select distinct ";
    $sSqlUnidades .= "       o41_anousu  as anousu,";
    $sSqlUnidades .= "       o41_orgao  as orgao,";
    $sSqlUnidades .= "       o41_unidade as unidade,";
    $sSqlUnidades .= "       o41_descr as nome,";
    $sSqlUnidades .= "       o41_ident as identificador,"; 
    $sSqlUnidades .= "       cgc  as cnpj ";
    $sSqlUnidades .= "  from orcunidade";
    $sSqlUnidades .= "       inner join orcdotacao on o58_orgao   = o41_orgao";
    $sSqlUnidades .= "                            and o58_unidade = o41_unidade";
    $sSqlUnidades .= "                            and o58_anousu  = o41_anousu ";
    $sSqlUnidades .= "       inner join orcorgao   on o41_orgao   = o40_orgao ";
    $sSqlUnidades .= "                            and o41_anousu  = o40_anousu";
    $sSqlUnidades .= "                            and o41_instit in ({$iCodigoInstit})";
    $sSqlUnidades .= "       inner join db_config on codigo = o58_instit ";
    $sSqlUnidades .= " where o58_anousu between {$iAnoInicio} and {$iAno}";
    $sSqlUnidades .= "   and o58_instit in ({$iCodigoInstit})";
    $rsUnidades    = db_query($sSqlUnidades);
    $iTotalLinhas = pg_num_rows($rsUnidades);

    $aUnidades  = array();
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $oUnidade = db_utils::fieldsMemory($rsUnidades, $i);
      
      $oUnidadeRetorno             = new stdClass();
      $oUnidadeRetorno->reg        = "L400";
      $oUnidadeRetorno->exercicio  = $oUnidade->anousu;
      $oUnidadeRetorno->cod_org    = str_pad($oUnidade->orgao, 2, "0", STR_PAD_LEFT);
      $oUnidadeRetorno->cod_un_orc = str_pad($oUnidade->unidade, 2, "0", STR_PAD_LEFT);
      $oUnidadeRetorno->nom_un_orc = $oUnidade->nome;
      $oUnidadeRetorno->tip_un_orc = str_pad($oUnidade->identificador, 2, "0", STR_PAD_LEFT);
      $oUnidadeRetorno->cnpj       = str_pad($oUnidade->cnpj, 14, 0, STR_PAD_LEFT);
      array_push($aUnidades, $oUnidadeRetorno);
      
    }
    return $aUnidades;
  }
  /**
   * retorna os dados das funcões
   *
   * @return array Colecão de Objetos com os dados das funcoes
   */
  public function getDadosFuncao($sDataInicial, $sDataFinal, $iAnoInicio) {
    
    list ($iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDataFinal);
    $iAno         = $iAnoUsuFim;
    $sSqlFuncao  = "select distinct ";
    $sSqlFuncao .= "       o58_anousu as anousu,";
    $sSqlFuncao .= "       o52_funcao as funcao,";
    $sSqlFuncao .= "       o52_descr as nome";
    $sSqlFuncao .= "  from orcfuncao ";
    $sSqlFuncao .= "       inner join orcdotacao on o58_funcao = o52_funcao";
    $sSqlFuncao .= " where o52_funcao > 0";
    $sSqlFuncao .= "   and o58_anousu between {$iAnoInicio} and {$iAno}";
    $sSqlFuncao .= "       union";
    $sSqlFuncao .= " select distinct";
    $sSqlFuncao .= "        o73_anousu  as anousu,";
    $sSqlFuncao .= "        o52_funcao as funcao,";
    $sSqlFuncao .= "        o52_descr as nome";
    $sSqlFuncao .= "   from orcfuncao ";
    $sSqlFuncao .= "        inner join orcdotacaorp on o73_funcao = o52_funcao";                
    $sSqlFuncao .= "  where o73_funcao > 0 ";
    $sSqlFuncao .= "    and o73_anousu between {$iAnoInicio} and {$iAno}";
    $rsFuncao    = db_query($sSqlFuncao);
    $iTotalLinhas = pg_num_rows($rsFuncao);

    $aFuncoes = array();
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $oFuncao      = db_utils::fieldsMemory($rsFuncao, $i);
      
      $oFuncaoRetorno            = new stdClass();
      $oFuncaoRetorno->reg       = "L450";
      $oFuncaoRetorno->exercicio = $oFuncao->anousu;
      $oFuncaoRetorno->cod_fun   = str_pad($oFuncao->funcao, 2, "0", STR_PAD_LEFT);
      $oFuncaoRetorno->nom_fun   = $oFuncao->nome;
      array_push($aFuncoes, $oFuncaoRetorno);
      
    }
    return $aFuncoes;
  }
  
  public function getDadosSubFuncao($sDataInicial, $sDataFinal, $iAnoInicio) {
    
    list ($iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDataFinal);
    $iAno           = $iAnoUsuFim;    
    $iCodigoInstit  = db_getsession("DB_instit");
    $sSqlSubFuncao  = "select distinct ";
    $sSqlSubFuncao .= "       o58_anousu as anousu,";
    $sSqlSubFuncao .= "       o53_subfuncao as subfuncao,";
    $sSqlSubFuncao .= "       o53_descr as nome";
    $sSqlSubFuncao .= "  from orcsubfuncao ";
    $sSqlSubFuncao .= "       inner join orcdotacao on o58_subfuncao = o53_subfuncao";
    $sSqlSubFuncao .= " where o53_subfuncao > 0";
    $sSqlSubFuncao .= "   and o58_anousu between {$iAnoInicio} and {$iAno}";
    $sSqlSubFuncao .= "       union";
    $sSqlSubFuncao .= " select distinct";
    $sSqlSubFuncao .= "        o73_anousu  as anousu,";
    $sSqlSubFuncao .= "        o53_subfuncao as subfuncao,";
    $sSqlSubFuncao .= "        o53_descr as nome";
    $sSqlSubFuncao .= "   from orcsubfuncao ";
    $sSqlSubFuncao .= "        inner join orcdotacaorp on o73_subfuncao = o53_subfuncao";                
    $sSqlSubFuncao .= "  where o73_subfuncao > 0 ";
    $sSqlSubFuncao .= "    and o73_anousu between {$iAnoInicio} and {$iAno}";
    $rsSubFuncao    = db_query($sSqlSubFuncao);
    $iTotalLinhas = pg_num_rows($rsSubFuncao);
    $aSubFuncoes  = array();      
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $oSubFuncao = db_utils::fieldsMemory($rsSubFuncao, $i);
      
      $oSubFuncaoRetorno             = new stdClass();
      $oSubFuncaoRetorno->reg        = "L500";
      $oSubFuncaoRetorno->exercicio  = $oSubFuncao->anousu;
      $oSubFuncaoRetorno->cod_subfun = str_pad($oSubFuncao->subfuncao, 3, "0", STR_PAD_LEFT);
      $oSubFuncaoRetorno->nom_subfun = $oSubFuncao->nome;
      array_push($aSubFuncoes, $oSubFuncaoRetorno);
      
    }
    return $aSubFuncoes;
  }
  
  public function getDadosPrograma($sDataInicial, $sDataFinal, $iAnoInicio) {
    
    list ($iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDataFinal);
    $iAno         = $iAnoUsuFim;
    
    $iCodigoInstit  = db_getsession("DB_instit");
    
    $sSqlProgramas  = "select distinct ";
    $sSqlProgramas .= "       o54_anousu as anousu,";
    $sSqlProgramas .= "       o54_programa as codigo,";
    $sSqlProgramas .= "       o54_descr as nome";
    $sSqlProgramas .= "  from orcprograma";
    $sSqlProgramas .= "       inner join orcdotacao on o58_programa = o54_programa";
    $sSqlProgramas .= "                            and o58_anousu   = o54_anousu";
    $sSqlProgramas .= "    and o54_anousu between {$iAnoInicio} and {$iAno}"; 
    $rsPrograma     = db_query($sSqlProgramas);
    $iTotalLinhas = pg_num_rows($rsPrograma);
    $aProgramas   = array();     
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $oPrograma = db_utils::fieldsMemory($rsPrograma, $i);
      
      $oProgramaRetorno                     = new stdClass();
      $oProgramaRetorno->reg       = "L550";
      $oProgramaRetorno->exercicio = $oPrograma->anousu;
      $oProgramaRetorno->cod_progr = str_pad($oPrograma->codigo, 3, "0", STR_PAD_LEFT);
      $oProgramaRetorno->nom_prg   = $oPrograma->nome;
      array_push($aProgramas, $oProgramaRetorno);
    }
    return $aProgramas;
  }
  
  public function getDadosProjAtiv ($sDataInicial, $sDataFinal, $iAnoInicio) {
    
    list ($iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDataFinal);
    $iAno   = $iAnoUsuFim;
    
    /**
     * Separamos a data do em ano, mes, dia
     */
    $iCodigoInstit  = db_getsession("DB_instit");
    
    $sSqlProAtiv  = "select distinct ";
    $sSqlProAtiv .= "       o55_anousu as anousu,";
    $sSqlProAtiv .= "       o55_projativ as codigo,";
    $sSqlProAtiv .= "       o55_descr as nome,";
    $sSqlProAtiv .= "       2 as identificador";
    $sSqlProAtiv .= "  from orcprojativ";
    $sSqlProAtiv .= "       inner join orcdotacao on o58_projativ = o55_projativ";
    $sSqlProAtiv .= "                            and o58_anousu   = o55_anousu";
    $sSqlProAtiv .= "    and o55_anousu between {$iAnoInicio} and {$iAno}";
    $rsProjAtiv   = db_query($sSqlProAtiv);
    $iTotalLinhas = pg_num_rows($rsProjAtiv);
    $aProjAtiv    = array(); 
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $oProjAtiv = db_utils::fieldsMemory($rsProjAtiv, $i);
      
      $oProjAtivRetorno                             = new stdClass();
      $oProjAtivRetorno->reg              = "L650";
      $oProjAtivRetorno->exercicio        = $oProjAtiv->anousu;
      $oProjAtivRetorno->cod_proj_ativ_oe = str_pad($oProjAtiv->codigo, 3, "0", STR_PAD_LEFT);
      $oProjAtivRetorno->nom_proj_ativ_oe = $oProjAtiv->nome;
      $oProjAtivRetorno->tip_proj_ativ_oe = str_pad($oProjAtiv->identificador, 2, '0', STR_PAD_LEFT);
      array_push($aProjAtiv, $oProjAtivRetorno);
      
    }
    return $aProjAtiv;
  }
  
  public function getDadosRubricas($sDataInicial, $sDataFinal, $iAnoInicio) {
    
    list ($iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDataFinal);
    $iAno         = $iAnoUsuFim;  
    $sWhereInstit = " and o58_instit = ".db_getsession("DB_instit");
    $sSqlRubrica  = "select distinct on (o56_anousu,elemento) elemento,";
    $sSqlRubrica .= "       o56_anousu as ano,";
    $sSqlRubrica .= "       tipo,";
    $sSqlRubrica .= "       o56_descr,";
    $sSqlRubrica .= "       nivel";
    $sSqlRubrica .= "  from (";
    $sSqlRubrica .= "        select o56_anousu,";
    $sSqlRubrica .= "               case when o56_anousu >= 2005 then";
    $sSqlRubrica .= "                    substr(trim(substr(o56_elemento,1,15))||'00000000000',1,15)::varchar(15)";
    $sSqlRubrica .= "                else";
    $sSqlRubrica .= "                   substr(trim(o56_elemento)||'000000000',1,15)::varchar(15)";
    $sSqlRubrica .= "                end as elemento,";
    $sSqlRubrica .= "               case when o56_anousu >= 2005 then";
    $sSqlRubrica .= "                  case when c61_anousu is null then";
    $sSqlRubrica .= "                      'S' else 'A'";
    $sSqlRubrica .= "                  end";
    $sSqlRubrica .= "               else";
    $sSqlRubrica .= "                  case when o58_anousu is null then";
    $sSqlRubrica .= "                    'S' else 'A'";
    $sSqlRubrica .= "                 end";
    $sSqlRubrica .= "               end as tipo,";
    $sSqlRubrica .= "               o56_descr,";
    $sSqlRubrica .= "               case when o56_anousu >= 2005 then";
    $sSqlRubrica .= "                    fc_nivel_plano2005(substr(o56_elemento,2,12)::varchar(15)||'000')";
    $sSqlRubrica .= "               else";
    $sSqlRubrica .= "                    fc_nivel_plano2005(substr(trim(o56_elemento)||'000000000',1,15)::varchar(15))";
    $sSqlRubrica .= "               end as nivel";
    $sSqlRubrica .= "          from orcelemento";
    $sSqlRubrica .= "               left join conplanoreduz  on o56_codele = c61_codcon";
    $sSqlRubrica .= "                                       and o56_anousu = c61_anousu";
    $sSqlRubrica .= "               left join orcdotacao     on o58_anousu = o56_anousu";
    $sSqlRubrica .= "                                       and o58_codele = o56_codele";
    $sSqlRubrica .= "         where o56_anousu between {$iAnoInicio} and {$iAnoUsuFim} ";             
    $sSqlRubrica .= "         order by o56_anousu,elemento";
    $sSqlRubrica .= "      ) as x "; 
    $rsRubrica    = db_query(analiseQueryPlanoOrcamento($sSqlRubrica));
    $iTotalLinhas = pg_num_rows($rsRubrica);
    $aRubricas    = array(); 
    for ($i = 0; $i < $iTotalLinhas; $i++) {
          
      $oRubrica        = db_utils::fieldsMemory($rsRubrica, $i);
      if (($oRubrica->elemento+0) == 0) { 
       continue;
      }
      $oRubricaRetorno                 = new stdClass();
      $oRubricaRetorno->reg            = "L700";
      $oRubricaRetorno->exercicio      = $oRubrica->ano;
      $oRubricaRetorno->cod_cta_desp   = str_pad($oRubrica->elemento, 15, "0", STR_PAD_RIGHT);
      $oRubricaRetorno->nom_desp       = substr($oRubrica->o56_descr, 0, 110);
      $oRubricaRetorno->ind_tipo_conta = $oRubrica->tipo;
      $oRubricaRetorno->nm_nivel_conta = str_pad($oRubrica->nivel, 2, "0", STR_PAD_LEFT);
      array_push($aRubricas, $oRubricaRetorno);
    }
    return $aRubricas;
  }
  
  public function getDadosFornecedores($sDataInicial, $sDataFinal, $iAnoInicio) {
    
    list ($iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDataFinal);
    
    $sWhereInstit = " and o58_instit = ".db_getsession("DB_instit");
    $iAno         = $iAnoUsuFim;
    $oInstituicao = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    
    $sSqlCredor  = "select distinct z01_numcgm as codigo,";
    $sSqlCredor .= "       z01_nome   as nome,";
    $sSqlCredor .= "       z01_cgccpf as cnpj,";
    $sSqlCredor .= "       z01_cgccpf as cgc,";
    $sSqlCredor .= "       '' as iss,";
    $sSqlCredor .= "       z01_ender as endereco,";
    $sSqlCredor .= "       z01_munic as cidade,";
    $sSqlCredor .= "       z01_uf as uf,";
    $sSqlCredor .= "       z01_cep as cep,";
    $sSqlCredor .= "       z01_telcon as fone,";
    $sSqlCredor .= "       z01_telcon as fax,";
    $sSqlCredor .= "      '1' as tipo"; 
    $sSqlCredor .= "  from cgm";
    $rsCredor    = db_query($sSqlCredor);
    $iTotalLinhas = pg_num_rows($rsCredor);

    $aCredores = array();
    for ($i = 0; $i < $iTotalLinhas; $i++) {
      
      $oCredor   = db_utils::fieldsMemory($rsCredor, $i);
      if (isset($this->aCredores[$oCredor->codigo])) {
        
        foreach ($this->aCredores[$oCredor->codigo] as $iAno) {
          
          if (trim($oCredor->cidade) == "" || strlen($oCredor->cidade) < 2) {
            $oCredor->cidade = $oInstituicao->munic;
          }
          $oCredorRetorno                  = new stdClass();
          $oCredorRetorno->reg             = "L750";
          $oCredorRetorno->exercicio       = $iAno;
          $oCredorRetorno->cod_fornecedor  = $oCredor->codigo;
          $oCredorRetorno->nom_fornecedor  = $oCredor->nome;
          $iTipoFornecedor                 = strlen($oCredor->cnpj);
          $oCredorRetorno->tip_fornec      = $iTipoFornecedor == 14 ? 2 : 1;
          $oCredorRetorno->cnpj_fornecedor = "";
          $oCredorRetorno->cpf_fornecedor  = "";
          if ($iTipoFornecedor == 14) {
            $oCredorRetorno->cnpj_fornecedor = str_pad($oCredor->cnpj, 14, "0", STR_PAD_LEFT);;  
          } else if ($iTipoFornecedor == 11) {
            $oCredorRetorno->cpf_fornecedor = str_pad($oCredor->cnpj, 11, "0", STR_PAD_LEFT);
          }
          $oCredorRetorno->nit_fornecedor = '';
          $oCredorRetorno->end_fornecedor = substr($oCredor->endereco, 0, 50);
          $oCredorRetorno->cid_fornecedor = substr($oCredor->cidade, 0, 30);
          if ($oCredor->uf == "") {
            $oCredor->uf = $oInstituicao->uf;
          }
          $oCredorRetorno->uf_fornecedor  = str_pad($oCredor->uf, 2, "0");
          $sCep                                          = str_replace(".","",str_replace("-","",$oCredor->cep));
          $oCredorRetorno->cep_fornecedor                = str_pad($sCep, 8, "0", STR_PAD_LEFT);
          $sFone                                         = str_replace(array("(",")","-"),
                                                                       array("","",""),                                        
                                                                       $oCredor->fone);
          $sFax                                          = str_replace(array("(",")","-"),
                                                                       array("","",""),                                        
                                                                       $oCredor->fax);                                                                   
          $oCredorRetorno->desc_tip_fornec = '';
          $aCredores[] = $oCredorRetorno;
        }
      }
    }
    return $aCredores;
  }
  
  public function getDadosSubPrograma($sDataInicial, $sDataFinal, $iAnoInicio) {
    
    list ($iAnoUsuFim, $iMesUsuFim, $iDiaUsuFim ) = explode("-", $sDataFinal);
    $aRetorno = array();
    while ($iAnoInicio <= $iAnoUsuFim) {
      
      $oSubPrograma              = new stdClass();
      $oSubPrograma->reg         = "L600";
      $oSubPrograma->exercicio   = $iAnoInicio;
      $oSubPrograma->cod_subprog = "0000";
      $oSubPrograma->nom_subprog = "Sem Subprograma";
      $aRetorno[] = $oSubPrograma;
      $iAnoInicio++;
    }
    return $aRetorno;
  }
}

?>