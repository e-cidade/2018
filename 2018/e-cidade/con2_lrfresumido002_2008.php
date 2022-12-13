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

set_time_limit(7000000000);

include("fpdf151/pdf.php");
include("fpdf151/assinatura.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
include("libs/db_libtxt.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcparamrel_classe.php");
include("classes/db_conrelinfo_classe.php");
include("classes/db_empresto_classe.php");
include("classes/db_conrelvalor_classe.php");
require("libs/db_libpostgres.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

//echo($HTTP_SERVER_VARS['QUERY_STRING']); exit;

$classinatura  = new cl_assinatura;
$orcparamrel   = new cl_orcparamrel;
$clconrelinfo  = new cl_conrelinfo;
$clempresto    = new cl_empresto;
$clconrelvalor = new cl_conrelvalor;

$instituicao  = str_replace("-",",",$db_selinstit);
$instit_rpps  ='';
/*
 * Definos quando é o ultimo Bimestre.
 * quando for o ultimo bimestre, a informações diferentes no relatorio.
 */
$lUltimoBimestre = false;
if ($bimestre == "2S"|| $bimestre == "6B") {
  $lUltimoBimestre = true;
}
if ($emite_rec_desp==1||$emite_proj==1){
  // seleciona instituio do RPPS
  $sql         = "select codigo  from db_config where db21_tipoinstit in (5,6) ";
  $resultinst  = pg_exec($sql);
  $xvirg       = '';
  for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
    $instit_rpps .= $xvirg.$codigo; // salva insituio
    $xvirg        = ', ';		  
  }
}

$anousu  = db_getsession("DB_anousu");
$dados   = data_periodo($anousu,$bimestre); // no dbforms/db_funcoes.php
$dt_ini  = $dados[0]; // data inicial do perodo
$dt_fin  = $dados[1]; // data final do perodo
$sPeriodo = $bimestre;
$periodo_selecao = $dados["periodo"];
//die($bimestre);
$anousu_ant = $anousu-1;

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// BALANCO ORCAMENTARIO - RECEITAS
if ($emite_balorc_rec==1||$emite_balorc_desp==1){
  $total_inicial        = 0;
  $total_atualizada     = 0;
  $total_nobim          = 0;
  $total_atebim         = 0;
  $total_deficit        = 0;
  $total_saldo_ant      = 0;

  $m_impostos                           = $orcparamrel->sql_parametro('22','1',"f",$instituicao,$anousu);
  $m_taxas                              = $orcparamrel->sql_parametro('22','2',"f",$instituicao,$anousu);
  $m_melhorias                          = $orcparamrel->sql_parametro('22','3',"f",$instituicao,$anousu);
  $m_sociais                            = $orcparamrel->sql_parametro('22','4',"f",$instituicao,$anousu);
  $m_economicas                         = $orcparamrel->sql_parametro('22','5',"f",$instituicao,$anousu);
  $m_imobiliarias                       = $orcparamrel->sql_parametro('22','6',"f",$instituicao,$anousu);
  $m_valmobiliarias                     = $orcparamrel->sql_parametro('22','7',"f",$instituicao,$anousu);
  $m_permissoes                         = $orcparamrel->sql_parametro('22','8',"f",$instituicao,$anousu);
  $m_patrimoniais                       = $orcparamrel->sql_parametro('22','9',"f",$instituicao,$anousu);
  $m_vegetal                            = $orcparamrel->sql_parametro('22','10',"f",$instituicao,$anousu);
  $m_animal                             = $orcparamrel->sql_parametro('22','11',"f",$instituicao,$anousu);
  $m_agropecuarias                      = $orcparamrel->sql_parametro('22','12',"f",$instituicao,$anousu);
  $m_transformacao                      = $orcparamrel->sql_parametro('22','13',"f",$instituicao,$anousu);
  $m_construcao                         = $orcparamrel->sql_parametro('22','14',"f",$instituicao,$anousu);
  $m_industriais                        = $orcparamrel->sql_parametro('22','15',"f",$instituicao,$anousu);
  $m_servicos                           = $orcparamrel->sql_parametro('22','16',"f",$instituicao,$anousu);
  $m_intergovernamental                 = $orcparamrel->sql_parametro('22','17',"f",$instituicao,$anousu);
  $m_privadas                           = $orcparamrel->sql_parametro('22','18',"f",$instituicao,$anousu);
  $m_transf_exterior                    = $orcparamrel->sql_parametro('22','19',"f",$instituicao,$anousu);
  $m_transf_pessoas                     = $orcparamrel->sql_parametro('22','20',"f",$instituicao,$anousu);
  $m_transf_convenios                   = $orcparamrel->sql_parametro('22','21',"f",$instituicao,$anousu);
  $m_transf_fome                        = $orcparamrel->sql_parametro('22','22',"f",$instituicao,$anousu);
  $m_multas                             = $orcparamrel->sql_parametro('22','23',"f",$instituicao,$anousu);
  $m_indenizacao                        = $orcparamrel->sql_parametro('22','24',"f",$instituicao,$anousu);
  $m_divida_ativa                       = $orcparamrel->sql_parametro('22','25',"f",$instituicao,$anousu);
  $m_correntes_diversas                 = $orcparamrel->sql_parametro('22','26',"f",$instituicao,$anousu);
  $m_oper_internas                      = $orcparamrel->sql_parametro('22','27',"f",$instituicao,$anousu);
  $m_oper_externas                      = $orcparamrel->sql_parametro('22','28',"f",$instituicao,$anousu);
  $m_bens_moveis                        = $orcparamrel->sql_parametro('22','29',"f",$instituicao,$anousu);
  $m_bens_imoveis                       = $orcparamrel->sql_parametro('22','30',"f",$instituicao,$anousu);
  $m_emprestimos                        = $orcparamrel->sql_parametro('22','31',"f",$instituicao,$anousu);
  $m_transf_capital_intergovernamentais = $orcparamrel->sql_parametro('22','32',"f",$instituicao,$anousu);
  $m_transf_capital_privadas            = $orcparamrel->sql_parametro('22','33',"f",$instituicao,$anousu);
  $m_transf_capital_exterior            = $orcparamrel->sql_parametro('22','34',"f",$instituicao,$anousu);
  $m_transf_capital_pessoas             = $orcparamrel->sql_parametro('22','35',"f",$instituicao,$anousu);
  $m_transf_capital_outras              = $orcparamrel->sql_parametro('22','36',"f",$instituicao,$anousu);
  $m_transf_capital_convenios           = $orcparamrel->sql_parametro('22','37',"f",$instituicao,$anousu);
  $m_transf_capital_fome                = $orcparamrel->sql_parametro('22','38',"f",$instituicao,$anousu);
  $m_outras_social                      = $orcparamrel->sql_parametro('22','39',"f",$instituicao,$anousu);
  $m_outras_disponibilidades            = $orcparamrel->sql_parametro('22','40',"f",$instituicao,$anousu);
  $m_outras_restituicoes                = $orcparamrel->sql_parametro('22','41',"f",$instituicao,$anousu);
  $m_outras_diversas                    = $orcparamrel->sql_parametro('22','42',"f",$instituicao,$anousu);

  // RECEITAS
  $db_filtro  = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
      $result_rec = db_receitasaldo(11,1,3,true,$db_filtro,$anousu,$dt_ini,$dt_fin);
      @pg_exec("drop table work_receita");
      for($i = 0;$i < pg_numrows($result_rec); $i++) {
      db_fieldsmemory($result_rec,$i);
      $estrutural = $o57_fonte;
      if (in_array($estrutural,$m_impostos)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_taxas)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_melhorias)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_sociais)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_economicas)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_imobiliarias)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_valmobiliarias)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_permissoes)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_patrimoniais)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_vegetal)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_animal)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_agropecuarias)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_transformacao)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_construcao)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_industriais)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_servicos)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_intergovernamental)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_privadas)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_transf_exterior)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_transf_pessoas)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_transf_convenios)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_transf_fome)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_multas)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_indenizacao)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_divida_ativa)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_correntes_diversas)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_oper_internas)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_oper_externas)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_bens_moveis)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_bens_imoveis)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_emprestimos)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_transf_capital_intergovernamentais)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_transf_capital_privadas)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_transf_capital_exterior)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_transf_capital_pessoas)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_transf_capital_outras)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_transf_capital_convenios)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_transf_capital_fome)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_outras_social)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_outras_disponibilidades)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_outras_restituicoes)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      if (in_array($estrutural,$m_outras_diversas)){
        $total_inicial    += $saldo_inicial;
        $total_atualizada += $saldo_inicial_prevadic;
        $total_nobim  += $saldo_arrecadado;
        $total_atebim += $saldo_arrecadado_acumulado;
      }
      }  

      // OPERACOES DE CREDITO/REFINANCIAMENTO(IV)
      // OPER. DE CREDITO INTERNAS MOBILIARIA
      $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo in (301,305,309,313) and c83_periodo = '".$bimestre."' and c83_instit in (".$instituicao.")"));
      if ($clconrelvalor->numrows > 0){
        $total_inicial    += pg_result($res_valor,0,"c83_informacao");
        $total_atualizada += pg_result($res_valor,1,"c83_informacao");
        $total_nobim      += pg_result($res_valor,2,"c83_informacao");
        $total_atebim     += pg_result($res_valor,3,"c83_informacao");
      }

      // OPER. DE CREDITO INTERNAS CONTRATUAL
      $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo in (302,306,310,314) and c83_periodo = '".$bimestre."' and c83_instit in (".$instituicao.")"));
      if ($clconrelvalor->numrows > 0){
        $total_inicial    += pg_result($res_valor,0,"c83_informacao");
        $total_atualizada += pg_result($res_valor,1,"c83_informacao");
        $total_nobim      += pg_result($res_valor,2,"c83_informacao");
        $total_atebim     += pg_result($res_valor,3,"c83_informacao");
      }

      // OPER. DE CREDITO EXTERNAS MOBILIARIA
      $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo in (303,307,311,315) and c83_periodo = '".$bimestre."' and c83_instit in (".$instituicao.")"));
      if ($clconrelvalor->numrows > 0){
        $total_inicial    += pg_result($res_valor,0,"c83_informacao");
        $total_atualizada += pg_result($res_valor,1,"c83_informacao");
        $total_nobim      += pg_result($res_valor,2,"c83_informacao");
        $total_atebim     += pg_result($res_valor,3,"c83_informacao");
      }

      // OPER. DE CREDITO EXTERNAS CONTRATUAL
      $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo in (304,308,312,316) and c83_periodo = '".$bimestre."' and c83_instit in (".$instituicao.")"));
      if ($clconrelvalor->numrows > 0){
        $total_inicial    += pg_result($res_valor,0,"c83_informacao");
        $total_atualizada += pg_result($res_valor,1,"c83_informacao");
        $total_nobim      += pg_result($res_valor,2,"c83_informacao");
        $total_atebim     += pg_result($res_valor,3,"c83_informacao");
      }
}

// BALANCO ORCAMENTARIO - DESPESAS
if ($emite_balorc_desp==1||$emite_balorc_rec==1 || $emite_desp_funcsub==1) {
  $total_inicial_desp    = 0;
  $total_adicional       = 0;
  $total_atualizada_desp = 0;
  $total_emp_nobim       = 0;
  $total_emp_atebim      = 0;
  $total_liq_nobim       = 0;
  $total_liq_atebim      = 0;
  $total_rp_np_nobim     = 0;
  $total_rp_np_atebim    = 0;

  $sele_work   = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
  $result_desp = db_dotacaosaldo(7,1,4,true,$sele_work,$anousu,$dt_ini,$dt_fin);
  
  for($i = 0; $i < pg_numrows($result_desp); $i++){
    db_fieldsmemory($result_desp,$i);
    $estrutural = $o58_elemento;
    if (strlen($o58_elemento) < 15) {
      $estrutural .= "00";
    }
    if (substr($estrutural,0,3)=='331') {
      
      $total_inicial_desp += $dot_ini;
      $total_adicional    += $suplementado_acumulado - $reduzido_acumulado;
      $total_emp_nobim    += $empenhado  - $anulado;
      $total_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
      $total_liq_nobim    += $liquidado;
      $total_liq_atebim   += $liquidado_acumulado;
      
      $total_rp_np_nobim  += abs($empenhado - $anulado - $liquidado);
      $total_rp_np_atebim += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
      
    }

    if (substr($estrutural,0,3)=='332') {
      
      $total_inicial_desp += $dot_ini;
      $total_adicional    += $suplementado_acumulado - $reduzido_acumulado;
      $total_emp_nobim    += $empenhado  - $anulado;
      $total_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
      $total_liq_nobim    += $liquidado;
      $total_liq_atebim   += $liquidado_acumulado;
      $total_rp_np_nobim  += abs($empenhado - $anulado - $liquidado);
      $total_rp_np_atebim += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
      
    }

    if (substr($estrutural,0,3)=='333') {
      
      $total_inicial_desp += $dot_ini;
      $total_adicional    += $suplementado_acumulado - $reduzido_acumulado;
      $total_emp_nobim    += $empenhado  - $anulado;
      $total_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
      $total_liq_nobim    += $liquidado;
      $total_liq_atebim   += $liquidado_acumulado;
      $total_rp_np_nobim  += abs($empenhado - $anulado - $liquidado);
      $total_rp_np_atebim += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
      
    }

    if (substr($estrutural,0,3)=='344') {
      
      $total_inicial_desp += $dot_ini;
      $total_adicional    += $suplementado_acumulado - $reduzido_acumulado;
      $total_emp_nobim    += $empenhado  - $anulado;
      $total_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
      $total_liq_nobim    += $liquidado;
      $total_liq_atebim   += $liquidado_acumulado;
      $total_rp_np_nobim  += abs($empenhado - $anulado - $liquidado);
      $total_rp_np_atebim += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
      
    }

    if (substr($estrutural,0,3)=='345') {
      
      $total_inicial_desp += $dot_ini;
      $total_adicional    += $suplementado_acumulado - $reduzido_acumulado;
      $total_emp_nobim    += $empenhado  - $anulado;
      $total_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
      $total_liq_nobim    += $liquidado;
      $total_liq_atebim   += $liquidado_acumulado;
      $total_rp_np_nobim  += abs($empenhado - $anulado - $liquidado);
      $total_rp_np_atebim += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
      
    }

    if (substr($estrutural,0,3)=='346') {
      
      $total_inicial_desp += $dot_ini;
      $total_adicional    += $suplementado_acumulado - $reduzido_acumulado;
      $total_emp_nobim    += $empenhado  - $anulado;
      $total_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
      $total_liq_nobim    += $liquidado;
      $total_liq_atebim   += $liquidado_acumulado;
      $total_rp_np_nobim  += abs($empenhado - $anulado - $liquidado);
      $total_rp_np_atebim += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
      
    }

    if ((substr($estrutural,0,3)=='399') || (substr($estrutural,0,3)=='377'))  {
      
      $total_inicial_desp += $dot_ini;
      $total_adicional    += $suplementado_acumulado - $reduzido_acumulado;
      $total_emp_nobim    += $empenhado  - $anulado;
      $total_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
      $total_liq_nobim    += $liquidado;
      $total_liq_atebim   += $liquidado_acumulado;
      $total_rp_np_nobim  += abs($empenhado - $anulado - $liquidado);
      $total_rp_np_atebim += abs($empenhado_acumulado - $anulado_acumulado - $liquidado_acumulado);
      
    }
  }
  // AMORTIZACAO DA DIVIDA INTERNA
  // DIVIDA MOBILIARIA 
  $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo between 322 and 328 and c83_periodo = '".$bimestre."' and c83_instit in (".$instituicao.")"));
  if ($clconrelvalor->numrows > 0){
    $total_inicial_desp += pg_result($res_valor,0,"c83_informacao");
    $total_adicional    += pg_result($res_valor,1,"c83_informacao"); 
    $total_emp_nobim    += pg_result($res_valor,3,"c83_informacao");
    $total_emp_atebim   += pg_result($res_valor,4,"c83_informacao");
    $total_liq_nobim    += pg_result($res_valor,5,"c83_informacao");
    $total_liq_atebim   += pg_result($res_valor,6,"c83_informacao");
  }

  // OUTRAS DIVIDAS
  $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo between 329 and 335 and c83_periodo = '".$bimestre."' and c83_instit in (".$instituicao.")"));
  if ($clconrelvalor->numrows > 0){
    $total_inicial_desp += pg_result($res_valor,0,"c83_informacao");
    $total_adicional    += pg_result($res_valor,1,"c83_informacao"); 
    $total_emp_nobim    += pg_result($res_valor,3,"c83_informacao");
    $total_emp_atebim   += pg_result($res_valor,4,"c83_informacao");
    $total_liq_nobim    += pg_result($res_valor,5,"c83_informacao");
    $total_liq_atebim   += pg_result($res_valor,6,"c83_informacao");
  }

  // AMORTIZACAO DA DIVIDA EXTERNA
  // DIVIDA MOBILIARIA 
  $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo between 336 and 342 and c83_periodo = '".$bimestre."' and c83_instit in (".$instituicao.")"));
  if ($clconrelvalor->numrows > 0){
    $total_inicial_desp += pg_result($res_valor,0,"c83_informacao");
    $total_adicional    += pg_result($res_valor,1,"c83_informacao"); 
    $total_emp_nobim    += pg_result($res_valor,3,"c83_informacao");
    $total_emp_atebim   += pg_result($res_valor,4,"c83_informacao");
    $total_liq_nobim    += pg_result($res_valor,5,"c83_informacao");
    $total_liq_atebim   += pg_result($res_valor,6,"c83_informacao");
  }

  // OUTRAS DIVIDAS
  $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo between 343 and 349 and c83_periodo = '".$bimestre."' and c83_instit in (".$instituicao.")"));
  if ($clconrelvalor->numrows > 0){
    $total_inicial_desp += pg_result($res_valor,0,"c83_informacao");
    $total_adicional    += pg_result($res_valor,1,"c83_informacao"); 
    $total_emp_nobim    += pg_result($res_valor,3,"c83_informacao");
    $total_emp_atebim   += pg_result($res_valor,4,"c83_informacao");
    $total_liq_nobim    += pg_result($res_valor,5,"c83_informacao");
    $total_liq_atebim   += pg_result($res_valor,6,"c83_informacao");
  }

  $total_atualizada_desp = $total_inicial_desp + $total_adicional;
}
// FIM DO BALANCO ORCAMENTARIO //////////////////////////////////////////////////////////////////////////////////////////////

// DESPESAS POR FUNCAO/SUBFUNCAO
/*
COMENTADO POIS SEGUNDO LEANDRO É O MESMO CÁLCULO DO ANTERIOR, LOGO NAO PRECISA RECALCULAR
09/01/2009 - FABRIZIO DE ROYES MELLO
if ($emite_desp_funcsub==1){
  $total_emp_nobim     = 0;
  $total_emp_atebim    = 0;
  $total_liq_nobim     = 0;
  $total_liq_atebim    = 0;

  $sele_work      = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
  $result_funcsub = db_dotacaosaldo(7,3,3,true,$sele_work,$anousu,$dt_ini,$dt_fin,8,0,false,1,false,2,3);
  for ($i = 0; $i < pg_numrows($result_funcsub); $i++){
    db_fieldsmemory($result_funcsub,$i);
    $total_emp_nobim  += $empenhado  - $anulado;
    $total_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
    $total_liq_nobim  += $liquidado;
    $total_liq_atebim += $liquidado_acumulado;
  }  
}
*/
// FIM DAS DESPESAS POR FUNCAO/SUBFUNCAO ////////////////////////////////////////////////////////////////////////////////////

// RECEITA CORRENTE LIQUIDA - RCL
if ($emite_rcl==1||$emite_ppp==1){     
  /* @pg_query("begin"); 
     @pg_query("drop table work_receita"); 
     @pg_query("drop table work_pl");
     @pg_query("drop table work_pl_estrut");
     @pg_query("drop table work_pl_estrutmae");     
     @pg_query("rollback"); 
   */

  // se o ano atual  bissexto deve subtrair 366 somente se a data for superior a 28/02/200X
  $dt = split('-',$dt_fin);  // mktime -- (mes,dia,ano)
  $dt_ini_ant = "{$anousu_ant}-01-01";
  $dt_fin_ant = "{$anousu_ant}-12-31";  

  /*
     echo $dt_ini." => ".$dt_fin."<br>";
     echo $dt_ini_ant." => ".$dt_fin_ant."<br>"; exit;
   */  

  $total_rcl  = 0;

  $dtini      = "";
  $dtfin      = "";
  $arqinclude = true;

  //include("con2_lrfreceitacorrente002_2008.php");

  $Trec[0][13]  = 0; 
  $Trec[1][13]  = 0; 
  $TrecB[0][13] = 0; 
  $TrecB[1][13] = 0;

  $indrec  = (int)substr($bimestre,0,1);
  $indrec += 2;
  $total_receita      = 0;
  $total_receitas_ant = 0;
  $total_receitas_ant = $TrecB[0][13] - $Trec[0][13]  ;

  /*for($i = $indrec; $i <= 13; $i++){
    $total_receitas_ant += $Trec[0][$i];
    }

    $total_recdeducoes_ant = 0;
    for($i = $indrec; $i < 13; $i++){
    $total_recdeducoes_ant += $Trec[1][$i];
    }

    $total_rec_ant  = $total_receitas_ant ;#- $total_recdeducoes_ant;  // Total das receitas do exercicio anterior

    $total_receitas = 0;
    for($i = 1; $i <= $indrec; $i++){
    $total_receitas += $TrecB[0][$i];
    }

    $total_recdeducoes = 0;
    for($i = 1; $i <= $indrec; $i++){
    $total_recdeducoes += $TrecB[1][$i];
    }
   */
  $sTodasInstit = null;
  $rsInstit =  pg_query("select codigo from db_config");
  for ($xinstit=0; $xinstit < pg_num_rows($rsInstit); $xinstit++) {
    db_fieldsmemory($rsInstit, $xinstit);
    $sTodasInstit .= $codigo . ($xinstit==pg_num_rows($rsInstit)-1?"":",");
  }
  //ano corrente
  $nTotalRcl  = calcula_rcl2($anousu,"{$anousu}-01-01",$dt_fin,$sTodasInstit, false,27);
  //ano anterior
  $nTotalRcl += calcula_rcl2($anousu_ant,$dt_ini_ant,$dt_fin_ant,$sTodasInstit, false,27,$dt_fin);
  $total_rcl = $nTotalRcl;// + $total_rec;           // Total dos ultimos 12 meses
  //unset($arqinclude);
}
// FIM DA RECEITA CORRENTE LIQUIDA //////////////////////////////////////////////////////////////////////////////////////////

// RECEITAS/DESPESAS DO RPPS
if ($emite_rec_desp==1){
  if (PostgreSQLUtils::isTableExists("work_receita")) { 
    pg_query("drop table work_receita"); 
  }
  if (PostgreSQLUtils::isTableExists("work_pl")) { 
    pg_query("drop table work_pl");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrut")) { 
    pg_query("drop table work_pl_estrut");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrutmae")) { 
    pg_query("drop table work_pl_estrutmae");
  }
  $arqinclude = true;

  if ($anousu < 2007){
    $executar = "con2_lrfrecdesprpps002.php";
  } else {
    $executar = "con2_lrfrecdesprpps002_2008.php";
  }
  $periodo         = $bimestre;   
  include($executar);
   
  $total_rpps_rec_nobim    = $total_rec_bimestre;
  $total_rpps_rec_atebim   = $total_rec_exercicio;
  $total_rpps_desp_nobim   = $total_desp_bimestre; 
  $total_rpps_desp_atebim  = $total_desp_exercicio;

  // RPs Nao Processados
  $total_rpps_rp_np_nobim  = $total_desp_rp_np_bim;
  $total_rpps_rp_np_atebim = $total_desp_rp_np_exe;

  // resultado
  $res_rpps_prev_nobim    = $total_rec_bimestre - $total_desp_bimestre; 
  $res_rpps_prev_atebim   = $total_rec_exercicio - $total_desp_exercicio;
  unset($arqinclude);
}
// FIM DAS RECEITAS/DESPESAS DO RPPS ////////////////////////////////////////////////////////////////////////////////////////

// RESULTADOS NOMINAL/PRIMARIO
if ($emite_resultado==1){

  if (PostgreSQLUtils::isTableExists("work_receita")) { 
    pg_query("drop table work_receita"); 
  }
  if (PostgreSQLUtils::isTableExists("work_pl")) { 
    pg_query("drop table work_pl");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrut")) { 
    pg_query("drop table work_pl_estrut");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrutmae")) { 
    pg_query("drop table work_pl_estrutmae");
  }
  $arqinclude = true;

  $META_NOMINAL = 0;    
  $dt_ini = $anousu.'-01-01';

  $periodo = $sPeriodo;

  include("con2_lrfnominal002.php");    

  $tot_bi  = (($somador_III_bim  + $somador_IV_bim) - $somador_V_bim );
  $tot_ant = (($somador_III_ant + $somador_IV_ant) - $somador_V_ant);

  $total_nominal = $tot_bi - $tot_ant;

  unset($arqinclude);

  // RESULTADO PRIMARIO
  $arqinclude = true;

  $META_PRIMARIA = 0;
  include("con2_lrfprimario002_$anousu.php");

  $result_info = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores(17,str_replace('-',',',$db_selinstit)));
  if ($clconrelinfo->numrows > 0 ){
    db_fieldsmemory($result_info,0);
    $META_PRIMARIA = $c83_informacao;
  } 

  $total_primario = ($receita_primaria_total[3]);
  if ($lUltimoBimestre) {
    $total_primario -= ($m_desp[1][3]+$m_desp[3][3]+ $m_desp[4][3]+$m_desp[7][3]+$m_desp[9][3]+$m_desp[10][3]+
                        $m_desp[1][5]+$m_desp[3][5]+ $m_desp[4][5]+$m_desp[7][5]+$m_desp[9][5]+$m_desp[10][5]);   
  } else {
    $total_primario -=  ($m_desp[1][3]+$m_desp[3][3]+$m_desp[4][3]+$m_desp[7][3]+$m_desp[9][3]+$m_desp[10][3]);
  }
  unset($arqinclude);
}
// FIM DOS RESULTADOS NOMINAL/PRIMARIO //////////////////////////////////////////////////////////////////////////////////////
if ($emite_rp == 1){
  $arqinclude = true;

  $db_filtro  = ' e60_instit in (' . str_replace('-',', ',$db_selinstit) . ')';

      include("con2_lrfdemonstrativorp002_{$anousu}.php");

      // Totais do Poder Executivo
      $total_proc_insc       = $tot_restos_pc_insc_ant_exec + $tot_restos_pc_inscritos_exec;
      $total_proc_canc       = $tot_restos_pc_cancelados_exec;
      $total_proc_pgto       = $tot_restos_pc_pagos_exec;
      $total_proc_a_pagar    = $tot_restos_pc_saldo_exec;

      $total_naoproc_insc    = $tot_restos_naopc_insc_ant_exec + $tot_restos_naopc_inscritos_exec;
      $total_naoproc_canc    = $tot_restos_naopc_cancelados_exec;
      $total_naoproc_pgto    = $tot_restos_naopc_pagos_exec;
      $total_naoproc_a_pagar = $tot_restos_naopc_saldo_exec;

      // Totais do Poder Legislativo
      $ltotal_proc_insc       = $tot_restos_pc_insc_ant_legal + $tot_restos_pc_inscritos_legal;
      $ltotal_proc_canc       = $tot_restos_pc_cancelados_legal;
      $ltotal_proc_pgto       = $tot_restos_pc_pagos_legal;
      $ltotal_proc_a_pagar    = $tot_restos_pc_saldo_legal;

      $ltotal_naoproc_insc    = $tot_restos_naopc_insc_ant_legal + $tot_restos_naopc_inscritos_legal;
      $ltotal_naoproc_canc    = $tot_restos_naopc_cancelados_legal;
      $ltotal_naoproc_pgto    = $tot_restos_naopc_pagos_legal;
      $ltotal_naoproc_a_pagar = $tot_restos_naopc_saldo_legal;

      // Total Geral RP Processado
      $tot_geral_proc_insc    = $total_proc_insc    + $ltotal_proc_insc;
      $tot_geral_proc_canc    = $total_proc_canc    + $ltotal_proc_canc;
      $tot_geral_proc_pgto    = $total_proc_pgto    + $ltotal_proc_pgto;
      $tot_geral_proc_a_pagar = $total_proc_a_pagar + $ltotal_proc_a_pagar;

      // Total Geral RP Nao Processado
      $tot_geral_naoproc_insc    = $total_naoproc_insc    + $ltotal_naoproc_insc;
      $tot_geral_naoproc_canc    = $total_naoproc_canc    + $ltotal_naoproc_canc;
      $tot_geral_naoproc_pgto    = $total_naoproc_pgto    + $ltotal_naoproc_pgto;
      $tot_geral_naoproc_a_pagar = $total_naoproc_a_pagar + $ltotal_naoproc_a_pagar;

      unset($arqinclude);
}
// FIM DE RESTOS A PAGAR ////////////////////////////////////////////////////////////////////////////////////////////////////               
// FUNDEB - MDE
if ($emite_mde==1) {
  if (PostgreSQLUtils::isTableExists("work_receita")) { 
    pg_query("drop table work_receita"); 
  }
  if (PostgreSQLUtils::isTableExists("work_pl")) { 
    pg_query("drop table work_pl");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrut")) { 
    pg_query("drop table work_pl_estrut");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrutmae")) { 
    pg_query("drop table work_pl_estrutmae");
  }
  $arqinclude = true;

  include("con2_lrfmdefundeb002_2008.php");

  
  
  $total_A = 0;
  $total_B = 0;
  $perc_A  = 0;
  $perc_B  = 0;
  $nPercentual60ParaSimplificado    = 0;
  $nPercentual25ParaSimplificado    = 0;
  $nSoma25ParaRelatorioSimplificado = 0;
  $nSoma60ParaRelatorioSimplificado = 0;
 
  $perc_12    = $despesa[1]["exercicio"]  + @$despesa[1]["inscritas"];
  $perc_10    = $receita[43]["exercicio"] ;

  $nPercentual60ParaSimplificado = @(($perc_12/$perc_10)*100);
  $nTotal25AteBimestre  = $despesa[7]["exercicio"]   + $despesa[10]["exercicio"]  + $despesa[13]["exercicio"]  +
                         $despesa[14]["exercicio"]  + $despesa[15]["exercicio"]  + $despesa[16]["exercicio"];
 
  $nTotal25Inscritas    = @$despesa[7]["inscritas"]   + @$despesa[10]["inscritas"]  + @$despesa[13]["inscritas"]  +
                         @$despesa[14]["inscritas"]  + @$despesa[15]["inscritas"]  + @$despesa[16]["inscritas"];
                         
  $nSoma25ParaRelatorioSimplificado =  $nTotal25AteBimestre+$nTotal25Inscritas;
  
  $nPercentual25ParaSimplificado    = 0;
  $somador3_atebimestre             = $receita[1]["exercicio"]  + $receita[22]["exercicio"];
  $somador30_valor      = 0;
  $somador31_valor      = 0;
  $somador3_atebimestre = 0;

  $somador30_valor = ($receita[44]["exercicio"] - $receita[36]["exercicio"]); 

  $res_variaveis = $clconrelinfo->sql_record($clconrelinfo->sql_query_file(null,"c83_codigo","c83_codigo","c83_codrel = 31 and c83_anousu = ".db_getsession("DB_anousu")));
  if ($clconrelinfo->numrows > 0){
    for ($i = 0; $i < $clconrelinfo->numrows; $i++){
      db_fieldsmemory($res_variaveis,$i);
      $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo = $c83_codigo and c83_periodo = '$bimestre' and c83_instit in (".str_replace("-",",",$db_selinstit).")"));
      if ($clconrelvalor->numrows > 0){
        db_fieldsmemory($res_valor,0);
        $valor = $c83_informacao;  
      } else {
        $valor = 0;
      }

      $somador30_valor += $valor;
    }
  }

  $somador30_valor += $m_restos_mde["cancelado"] + $m_aplicacao_fundeb["valor"];
  $somador3_atebimestre = $receita[1]["exercicio"]  + $receita[22]["exercicio"];
  $somador31_valor      = @((($despesa[7]["exercicio"]  + $despesa[10]["exercicio"] 
                            + @$despesa[7]["inscritas"] + @$despesa[10]["inscritas"]) - $somador30_valor)/$somador3_atebimestre)*100;

  $perc_12    = $despesa[1]["exercicio"];
  $perc_10    = $receita[43]["exercicio"];
  $nPercentual25ParaSimplificado = $somador31_valor; 
  $percentual = 0;
  $percentual = @(($perc_12/$perc_10)*100);
   
  @$total_A = $nSoma25ParaRelatorioSimplificado;//($despesa[7]["exercicio"] + $despesa[10]["exercicio"])-$somador30_valor;
  @$perc_A  = $nPercentual25ParaSimplificado; 
  
  @$total_B            = $despesa[2]["exercicio"] + $despesa[3]["exercicio"]+@$despesa[3]["inscritas"]+@$despesa[2]["inscritas"];
  $nTotal60AteBimestre = ($despesa[2]["exercicio"] + $despesa[3]["exercicio"]);
   
  $nTotal60Inscritas   = @ ($despesa[2]["inscritas"] + $despesa[3]["inscritas"]); 
  @$perc_B  = $percentual;

  unset($arqinclude);
}

// OPERACOES DE CREDITO E DESPESAS DE CAPITAL
if ($emite_oper==1){
  if (PostgreSQLUtils::isTableExists("work_receita")) { 
    pg_query("drop table work_receita"); 
  }
  if (PostgreSQLUtils::isTableExists("work_pl")) { 
    pg_query("drop table work_pl");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrut")) { 
    pg_query("drop table work_pl_estrut");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrutmae")) { 
    pg_query("drop table work_pl_estrutmae");
  }

  $total_oper     = 0;
  $saldo_oper     = 0;
  $total_despesa  = 0;
  $saldo_despesa  = 0;

  $m_operacoes[0] = $orcparamrel->sql_parametro('6','0');
  $m_operacoes[1] = $orcparamrel->sql_parametro('6','1');
  $m_operacoes[2] = $orcparamrel->sql_parametro('6','2');

  $sele_work      = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
      $result_oper    = db_receitasaldo(11,1,3,true,$sele_work,$anousu,$dt_ini,$dt_fin,false);
      @pg_exec("drop table work_receita");

      $sele_work      = 'o58_instit in ('.str_replace('-', ', ', $db_selinstit).')   ';
      $result_desp    = db_dotacaosaldo(8,2,3,true,$sele_work,$anousu,$dt_ini,$dt_fin);
      
      for($i = 0; $i < pg_numrows($result_oper); $i++){
      db_fieldsmemory($result_oper, $i);
      $estrutural = $o57_fonte;
      if (in_array($estrutural, $m_operacoes)){
      $total_oper += $saldo_arrecadado_acumulado;
      $saldo_oper += $saldo_arrecadado_acumulado - $saldo_prevadic_acum;
      }
      }

      for($i = 0; $i < pg_numrows($result_desp); $i++){
      db_fieldsmemory($result_desp,$i);
      $estrutural = $o58_elemento;
      if (substr($estrutural,0,3)=='334'){
        $total_despesa += $liquidado_acumulado;
        $saldo_despesa += $liquidado_acumulado - $dot_ini;
      }
      }  
}
// PROJECAO ATUARIAL DO RPPS
if ($emite_proj==1){
  if (PostgreSQLUtils::isTableExists("work_receita")) { 
    pg_query("drop table work_receita"); 
  }
  if (PostgreSQLUtils::isTableExists("work_pl")) { 
    pg_query("drop table work_pl");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrut")) { 
    pg_query("drop table work_pl_estrut");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrutmae")) { 
    pg_query("drop table work_pl_estrutmae");
  }


  $total_rec          = 0;
  $total_rec_patronal = 0;
  $total_desp         = 0;
  $total_res_rpps     = 0;
  $total_rep_rpps     = 0;

  $RPPS_10_EXERCICIO_PATRONAL = 0;
  $RPPS_20_EXERCICIO_PATRONAL = 0;
  $RPPS_35_EXERCICIO_PATRONAL = 0;

  $RPPS_10_EXERCICIO_RECEITAS = 0;
  $RPPS_20_EXERCICIO_RECEITAS = 0;
  $RPPS_35_EXERCICIO_RECEITAS = 0;

  $RPPS_10_EXERCICIO_DESPESAS = 0;
  $RPPS_20_EXERCICIO_DESPESAS = 0;
  $RPPS_35_EXERCICIO_DESPESAS = 0;

  $RPPS_10_EXERCICIO_RESULTADO = 0;
  $RPPS_20_EXERCICIO_RESULTADO = 0;
  $RPPS_35_EXERCICIO_RESULTADO = 0;

  $RPPS_10_EXERCICIO_REPASSE   = 0;
  $RPPS_20_EXERCICIO_REPASSE   = 0;
  $RPPS_35_EXERCICIO_REPASSE   = 0;

  $clconrelinfo = new cl_conrelinfo;
  $result_info  = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores(18,str_replace('-',',',$db_selinstit)));

  if ($clconrelinfo->numrows > 0){
    for ($i = 0; $i < $clconrelinfo->numrows; $i++){
      db_fieldsmemory($result_info, $i);

      if ($c83_codigo==273){
        $RPPS_10_EXERCICIO_PATRONAL = $c83_informacao;
      }
      if ($c83_codigo==274){
        $RPPS_20_EXERCICIO_PATRONAL = $c83_informacao;
      }
      if ($c83_codigo==275){
        $RPPS_35_EXERCICIO_PATRONAL = $c83_informacao;
      }
      if ($c83_codigo==276){
        $RPPS_10_EXERCICIO_RECEITAS = $c83_informacao;
      }
      if ($c83_codigo==277){
        $RPPS_20_EXERCICIO_RECEITAS = $c83_informacao;
      }
      if ($c83_codigo==278){
        $RPPS_35_EXERCICIO_RECEITAS = $c83_informacao;
      }
      if ($c83_codigo==279){
        $RPPS_10_EXERCICIO_DESPESAS = $c83_informacao;
      }
      if ($c83_codigo==280){
        $RPPS_20_EXERCICIO_DESPESAS = $c83_informacao;
      }
      if ($c83_codigo==281){
        $RPPS_35_EXERCICIO_DESPESAS = $c83_informacao;
      }
      if ($c83_codigo==282){
        $RPPS_10_EXERCICIO_RESULTADO = $c83_informacao;
      }
      if ($c83_codigo==283){
        $RPPS_20_EXERCICIO_RESULTADO = $c83_informacao;
      }
      if ($c83_codigo==284){
        $RPPS_35_EXERCICIO_RESULTADO = $c83_informacao;
      }
      if ($c83_codigo==285){
        $RPPS_10_EXERCICIO_REPASSE   = $c83_informacao;
      }
      if ($c83_codigo==286){
        $RPPS_20_EXERCICIO_REPASSE   = $c83_informacao;
      }
      if ($c83_codigo==287){
        $RPPS_35_EXERCICIO_REPASSE   = $c83_informacao;
      }
    }
  }

  for ($linha=1;$linha<=28;$linha++){	
    $m_receita[$linha]['estrut'] = $orcparamrel->sql_parametro('42',$linha); 
  }	

  for ($linha=29;$linha<=39;$linha++){	
    $m_despesa[$linha]['estrut'] = $orcparamrel->sql_parametro('42',$linha);
    $m_despesa[$linha]['nivel']  = $orcparamrel->sql_nivel('42',$linha);
  }

  $db_filtro  = " o70_instit in (".$instit_rpps.")";

  // Exercicio Atual
  $result_rec = db_receitasaldo(11,1,3,true,$db_filtro,$anousu,$dt_ini,$dt_fin);
  @pg_exec("drop table work_receita");

  $sele_work = ' c61_instit in ('.$instit_rpps.')';

      // Exercicio Atual
      $result_res_rep = db_planocontassaldo_matriz($anousu,$dt_ini,$dt_fin,false,$sele_work); 
      @pg_exec("drop table work_pl"); 

      $db_filtro = "o58_instit in (".$instit_rpps.") ";

      // Exercicio Atual
      $result_despesa = db_dotacaosaldo(8,2,3,true,$db_filtro,$anousu,$dt_ini,$dt_fin);
      
      for ($i=0; $i < pg_numrows($result_rec); $i++){
      db_fieldsmemory($result_rec,$i);
      $estrutural = $o57_fonte;

      for ($linha=1;$linha < 15;$linha++){
      if (in_array($estrutural,$m_receita[$linha]['estrut'])){
      $total_rec += $saldo_arrecadado;
      }
      if (substr($estrutural,0,7)=="6121701"){
        $total_rec_patronal += $saldo_arrecadado;
      }
      }
      }             

      for ($i=0; $i < pg_numrows($result_res_rep); $i++) {
        db_fieldsmemory($result_res_rep,$i);

        for ($linha=15;$linha<=28;$linha++){
          if (substr($estrutural,0,1)=="6"){ // RESULTADOS(6) 
            if (in_array($estrutural,$m_receita[$linha]['estrut'])){
              $total_rec += $saldo_final;
            }
            if (substr($estrutural,0,6)=="612117"){
              $total_rep_rpps += $saldo_final;
            }
          }
        }
      }

      for ($i = 0; $i < pg_numrows($result_despesa); $i ++) {
        db_fieldsmemory($result_despesa, $i);

        for ($linha=29;$linha<=39;$linha++){
          $nivel        = $m_despesa[$linha]['nivel'];
          $estrutural   = $o58_elemento.'00';
          $estrutural   = substr($estrutural,0,$nivel);
          $v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);	

          if (in_array($v_estrutural, $m_despesa[$linha]['estrut'])){
            $total_desp += $liquidado;
          }
        }     
      }

      $total_res_rpps = $total_rec - $total_desp;
}
// RECEITA DA ALIENACAO DE ATIVOS E APLICACAO DOS RECURSOS 
if ($emite_alienacao==1){
  if (PostgreSQLUtils::isTableExists("work_receita")) { 
    pg_query("drop table work_receita"); 
  }
  if (PostgreSQLUtils::isTableExists("work_pl")) { 
    pg_query("drop table work_pl");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrut")) { 
    pg_query("drop table work_pl_estrut");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrutmae")) { 
    pg_query("drop table work_pl_estrutmae");
  }


  $total_alien   = 0;
  $saldo_alien   = 0;
  $total_recurso = 0;
  $saldo_recurso = 0;

  $sele_work     = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
      $result_rec    = db_receitasaldo(11,1,3,true,$sele_work,$anousu,$dt_ini,$dt_fin,false);
      @pg_exec("drop table work_receita");

      for($i = 0; $i < pg_numrows($result_rec); $i++){
      db_fieldsmemory($result_rec, $i);
      $estrutural = $o57_fonte;
      if (substr($estrutural,0,3)=="422"){
      $total_alien += $saldo_arrecadado_acumulado;
      $saldo_alien += $saldo_arrecadado_acumulado - $saldo_prevadic_acum;
      }
      if (substr($estrutural,0,3)=="413"){
      $total_recurso += $saldo_arrecadado_acumulado;
      $saldo_recurso += $saldo_arrecadado_acumulado - $saldo_prevadic_acum;
      }
      }
      }
      // DESPESAS COM SAUDE
      if ($emite_saude==1){
      if (PostgreSQLUtils::isTableExists("work_receita")) { 
      pg_query("drop table work_receita"); 
      }
      if (PostgreSQLUtils::isTableExists("work_pl")) { 
        pg_query("drop table work_pl");
      }
      if (PostgreSQLUtils::isTableExists("work_pl_estrut")) { 
        pg_query("drop table work_pl_estrut");
      }
      if (PostgreSQLUtils::isTableExists("work_pl_estrutmae")) { 
        pg_query("drop table work_pl_estrutmae");
      }


      $arqinclude = true;
      $txtper     = "";
      $periodo    = $bimestre;
      include("con2_lrfimpostossaude002_2008.php"); 

      $total_saude       = $total_V_atebim;
      if ($lUltimoBimestre) {
        
        $nTotalSaudeAteBimestre =  $total_V_atebim-$total_V_rp;
        $nTotalSaudeInscritos   =  $total_V_rp;
        
      }
      $perc_aplic_atebim = $t_participacao;

      unset($arqinclude); 
      }
// DESPESA DE PPP
if ($emite_ppp==1){
  if (PostgreSQLUtils::isTableExists("work_receita")) { 
    pg_query("drop table work_receita"); 
  }
  if (PostgreSQLUtils::isTableExists("work_pl")) { 
    pg_query("drop table work_pl");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrut")) { 
    pg_query("drop table work_pl_estrut");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrutmae")) { 
    pg_query("drop table work_pl_estrutmae");
  }


  $total_despesa  = 0;

  $sele_work      = 'o58_instit in ('.str_replace('-', ', ', $db_selinstit).')   ';
  $result_despesa = db_dotacaosaldo(8,2,3,true,$sele_work,$anousu,$dt_ini,$dt_fin);
  
  for ($i = 0; $i < pg_numrows($result_despesa); $i++) {
    db_fieldsmemory($result_despesa, $i);
    $estrutural = $o58_elemento;

    if (strlen($estrutural) < 15){
      $estrutural .= "00";
    }

    if (substr($estrutural,0,2) == "33"){
      $total_despesa += $liquidado_acumulado;
    }
  }

  $perc_apurado = ($total_despesa/$total_rcl) * 100;
}
if ($emite_oper == 1) {   
 
  if (PostgreSQLUtils::isTableExists("work_receita")) { 
    pg_query("drop table work_receita"); 
  }
  if (PostgreSQLUtils::isTableExists("work_pl")) { 
    pg_query("drop table work_pl");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrut")) { 
    pg_query("drop table work_pl_estrut");
  }
  if (PostgreSQLUtils::isTableExists("work_pl_estrutmae")) { 
    pg_query("drop table work_pl_estrutmae");
  }
  $lNaoGeraPDF              = true;
  require_once("con2_lrfdemrecopcreddesp002.php");
  $nOperacoesCreditoApurado = $aRecOperCreditoVal["ReceitaAteBim"];
  $nOperacoesARealizar      = $aRecOperCreditoVal["SaldoRealizar"];
  $nDespesaCapitalLiquidada = $aTotDespesaCapitalLiq["DespLiqAteBim"]+$aTotDespesaCapitalLiq["InscrRestPagNaoProc"];
  $nDespesaARelizar         = $aTotDespesaCapitalLiq["SaldoExecutar"];
  unset($lNaoGeraPDF);

}
//////////////////////////////// Impresso do PDF /////////////////////////////////
$xinstit    = split("-",$db_selinstit);
$resultinst = pg_exec("select munic from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
db_fieldsmemory($resultinst,0);

unset($arqinclude);

if (!isset($arqinclude)){  
  $head1  = "";
  $head2  = "";
  $head3  = "";
  $head4  = "";
  $head5  = "";
  $head6  = "";
  $head7  = "";
  $head8  = "";
  $head9  = "";

  $head2  = "MUNICÍPIO DE ".$munic;
  $head3  = "DEMONSTRATIVO SIMPLIFICADO DO RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
  $head4  = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";

  $mes    = split("-",$dt_ini); 
  $mesini = strtoupper(db_mes($mes[1]));
  $txt    = "JANEIRO";
  $mes    = split("-",$dt_fin); 
  $mesfin = strtoupper(db_mes($mes[1]));
  $txt   .= " A $mesfin/$anousu";

  if ($bimestre != "1S" && $bimestre != "2S"){
    $txt .= " - ".strtoupper($periodo_selecao)." $mesini - $mesfin";
  }

  $head5 = "$txt";
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','',7);
$alt     = 4;
$num_rel = 0;

$pdf->addpage();

$pdf->setfont('arial','',6);
$pdf->setX(10);
$pdf->cell(170,$alt,"RREO - Anexo XVIII (LRF, Art. 48)",'0',0,"L",0);
$pdf->cell(20,$alt,"R$ 1,00","B",1,"R",0);

//------------------------------------------------------------------
// BALANCO ORCAMENTARIO - RECEITAS
$n1 = 5;
$n2 = 10;
if ($emite_balorc_rec == 1 || $emite_balorc_desp == 1) {
  $num_rel++;

  $pdf->cell(90,$alt,"BALANÇO ORÇAMENTÁRIO","BT",0,"C",0);
  $pdf->cell(50,$alt,"No ".$dados["periodo"],"BTRL",0,"C",0);
  $pdf->cell(50,$alt,"Até o ".$dados["periodo"],"BT",1,"C",0);

  $pdf->cell(90,$alt,"RECEITAS","R",0,"L",0);
  $pdf->cell(50,$alt,"","R",0,"L",0);
  $pdf->cell(50,$alt,"","",1,"L",0);

  $pdf->cell(90,$alt,espaco($n1).'Previsão Inicial da Receita','R',0,"L",0);
  $pdf->cell(50,$alt,"-",'R',0,"C",0);
  $pdf->cell(50,$alt,db_formatar($total_inicial,'f'),'0',1,"R",0);

  $pdf->cell(90,$alt,espaco($n1).'Previsão Atualizada da Receita','R',0,"L",0);
  $pdf->cell(50,$alt,"-",'R',0,"C",0);
  $pdf->cell(50,$alt,db_formatar($total_atualizada,'f'),'0',1,"R",0);

  $pdf->cell(90,$alt,espaco($n1).'Receitas Realizadas','R',0,"L",0);
  $pdf->cell(50,$alt,db_formatar($total_nobim,'f'),'R',0,"R",0);
  $pdf->cell(50,$alt,db_formatar($total_atebim,'f'),'0',1,"R",0);
  // DEFICIT
  $total_rec_realizadas  =  $total_atebim;
  $total_desp_liquidadas =  $total_liq_atebim;

  $pos_deficit = $pdf->getY();
  $pdf->cell(90,$alt,espaco($n1).'Déficit Orçamentário','R',0,"L",0);
  if ($total_desp_liquidadas > $total_rec_realizadas){
    $pdf->cell(50,$alt,'-','R',0,"C",0);
    $pdf->cell(50,$alt,db_formatar($total_desp_liquidadas-$total_rec_realizadas,"f"),'0',1,"R",0);
  }else{
    $pdf->cell(50,$alt,'-','R',0,"C",0);
    $pdf->cell(50,$alt,'-','0',1,"C",0);
  }
  $c83_informacao = 0;

  $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo = 321 and c83_periodo = '".$sPeriodo."' and c83_instit in (".$instituicao.")"));
  if ($clconrelvalor->numrows > 0){
    db_fieldsmemory($res_valor,0);
  }

  $total_saldo_ant = $c83_informacao;

  // SALDO ANTERIOR
  $pdf->cell(90,$alt,espaco($n1).'Saldo de Exercícios Anteriores(Utilizados para Créditos Adicionais)','R',0,"L",0);
  $pdf->cell(50,$alt,"-",'R',0,"C",0);
  $pdf->cell(50,$alt,db_formatar($total_saldo_ant,"f"),'0',1,"R",0);

  $emite_balorc_rec = 1;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////              
// BALANCO ORCAMENTARIO - DESPESAS
if ($emite_balorc_desp==1){
  $num_rel++;

  if ($emite_balorc_rec==0){ 
    $pdf->cell(90,$alt,"BALANÇO ORÇAMENTÁRIO","BT",0,"C",0);
    $pdf->cell(50,$alt,"No ".$dados["periodo"],"BTRL",0,"C",0);
    $pdf->cell(50,$alt,"Até o ".$dados["periodo"],"BT",1,"C",0);
  }

  $pdf->cell(90,$alt,"DESPESAS","R",0,"L",0);
  $pdf->cell(50,$alt,"","R",0,"L",0);
  $pdf->cell(50,$alt,"","",1,"L",0);

  $pdf->cell(90,$alt,espaco($n1).'Dotacão Inicial','R',0,"L",0);
  //$pdf->cell(50,$alt,db_formatar($total_inicial_desp,'f'),'R',0,"R",0);
  $pdf->cell(50,$alt,"-",'R',0,"C",0);
  $pdf->cell(50,$alt,db_formatar($total_inicial_desp,'f'),'0',1,"R",0);

  $pdf->cell(90,$alt,espaco($n1).'Créditos Adicionais','R',0,"L",0);
  //$pdf->cell(50,$alt,db_formatar($total_adicional,'f'),'R',0,"R",0);
  $pdf->cell(50,$alt,"-",'R',0,"C",0);
  $pdf->cell(50,$alt,db_formatar($total_adicional,'f'),'0',1,"R",0);

  $pdf->cell(90,$alt,espaco($n1).'Dotação Atualizada','R',0,"L",0);
  $pdf->cell(50,$alt,"-",'R',0,"C",0);
  $pdf->cell(50,$alt,db_formatar($total_atualizada_desp,'f'),'0',1,"R",0);

  $pdf->cell(90,$alt,espaco($n1).'Despesas Empenhadas','R',0,"L",0);
  $pdf->cell(50,$alt,db_formatar($total_emp_nobim,'f'),'R',0,"R",0);
  $pdf->cell(50,$alt,db_formatar($total_emp_atebim,'f'),'0',1,"R",0);
  if ($lUltimoBimestre) {

    $pdf->cell(90,$alt,espaco($n1).'Despesas Executadas','R',0,"L",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_nobim,'f'),'R',0,"R",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_atebim+$total_rp_np_atebim,'f'),'0',1,"R",0);
    
    $pdf->cell(90,$alt,espaco($n2).'Despesas Liquidadas','R',0,"L",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_nobim,'f'),'R',0,"R",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_atebim,'f'),'0',1,"R",0);
    $pdf->cell(90,$alt,espaco($n2).'Inscritas em Restos a Pagar Não Processados','R',0,"L",0);
    $pdf->cell(50,$alt,"-",'R',0,"C",0);
    $pdf->cell(50,$alt,db_formatar($total_rp_np_atebim,'f'),'0',1,"R",0);
    
  } else {

    $pdf->cell(90,$alt,espaco($n1).'Despesas Liquidadas','R',0,"L",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_nobim,'f'),'R',0,"R",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_atebim,'f'),'0',1,"R",0);
    
  }
  //   SUPERAVIT
  $pos_superavit = $pdf->getY();
  $pdf->cell(90,$alt,espaco($n1).'Superávit Orçamentário','R',0,"L",0);
  if ($total_desp_liquidadas < $total_rec_realizadas){
    $pdf->cell(50,$alt,"-",'R',0,"C",0);
    $pdf->cell(50,$alt,db_formatar($total_rec_realizadas-$total_desp_liquidadas,"f"),'0',1,"R",0);
  }else{
    $pdf->cell(50,$alt,'-','R',0,"C",0);
    $pdf->cell(50,$alt,'-','0',1,"C",0);
  }
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////              
// DESPESAS POR FUNCAO/SUBFUNCAO
if ($emite_desp_funcsub==1){
  $num_rel++;

  $pdf->cell(90,$alt,"DESPESAS POR FUNÇÃO/SUBFUNÇÃO","BT",0,"C",0);
  $pdf->cell(50,$alt,"No ".$dados["periodo"],"BTRL",0,"C",0);
  $pdf->cell(50,$alt,"Até o ".$dados["periodo"],"BT",1,"C",0);

  $pdf->cell(90,$alt,'Despesas Empenhadas','R',0,"L",0);
  $pdf->cell(50,$alt,db_formatar($total_emp_nobim,'f'),'R',0,"R",0);
  $pdf->cell(50,$alt,db_formatar($total_emp_atebim,'f'),'0',1,"R",0);
  
  if ($lUltimoBimestre) {

    $pdf->cell(90,$alt,espaco($n1).'Despesas Executadas','R',0,"L",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_nobim,'f'),'R',0,"R",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_atebim+$total_rp_np_atebim,'f'),'0',1,"R",0);
    
    $pdf->cell(90,$alt,espaco($n2).'Despesas Liquidadas','R',0,"L",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_nobim,'f'),'R',0,"R",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_atebim,'f'),'0',1,"R",0);
    $pdf->cell(90,$alt,espaco($n2).'Inscritas em Restos a Pagar Não Processados','R',0,"L",0);
    
    $pdf->cell(50,$alt,"-",'R',0,"C",0);
    $pdf->cell(50,$alt,db_formatar($total_rp_np_atebim,'f'),'0',1,"R",0);
    
  } else {

    $pdf->cell(90,$alt,espaco($n1).'Despesas Liquidadas','R',0,"L",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_nobim,'f'),'R',0,"R",0);
    $pdf->cell(50,$alt,db_formatar($total_liq_atebim,'f'),'0',1,"R",0);
    
  }
}
// RECEITA CORRENTE LIQUIDA - RCL
if ($emite_rcl==1){
  $num_rel++;

  $pdf->cell(90,$alt,"RECEITA CORRENTE LÍQUIDA - RCL","BT",0,"C",0);
  $pdf->cell(50,$alt,"","BTR",0,"C",0);
  $pdf->cell(50,$alt,"Até o ".$dados["periodo"],"BT",1,"C",0);

  $pdf->cell(140,$alt,'Receita Corrente Líquida', 'R',0,"L",0);
  $pdf->cell( 50,$alt,db_formatar($total_rcl,'f'),'0',1,"R",0);
}
// RECEITAS/DESPESAS DO RPPS
if ($emite_rec_desp==1){
  if ($num_rel > 0){
    $pdf->cell(190,$alt,"","TB",1,"C",0);
  }

  $num_rel++;

  $pdf->cell(90,$alt,"RECEITAS E DESPESAS DOS REGIMES DE PREVIDÊNCIA","BT",0,"C",0);
  $pdf->cell(50,$alt,"No ".$dados["periodo"],"BTRL",0,"C",0);
  $pdf->cell(50,$alt,"Até o ".$dados["periodo"],"BT",1,"C",0);

  $pdf->cell(90,$alt,'Regime Próprio de Previdência Social dos Servidores Públicos','R',0,"L",0);
  $pdf->cell(50,$alt,"",'R',0,"L",0);
  $pdf->cell(50,$alt,"",0,1,"L",0);
  $pdf->cell(90,$alt,'  Receitas Previdenciárias Realizadas(IV)','R',0,"L",0);
  $pdf->cell(50,$alt,db_formatar($total_rpps_rec_nobim,"f"),'R',0,"R",0);
  $pdf->cell(50,$alt,db_formatar($total_rpps_rec_atebim,"f"),0,1,"R",0);
  if ($lUltimoBimestre){
    
    $pdf->cell(90,$alt,'  Despesas Previdenciárias Executadas(V)','R',0,"L",0);
    $pdf->cell(50,$alt,db_formatar($total_rpps_desp_nobim,"f"),'R',0,"R",0);
    $pdf->cell(50,$alt,db_formatar($total_rpps_desp_atebim+$total_rpps_rp_np_atebim,"f"),0,1,"R",0);
    
    $pdf->cell(90,$alt,'    Liquidadas','R',0,"L",0);
    $pdf->cell(50,$alt,db_formatar($total_rpps_desp_nobim,"f"),'R',0,"R",0);
    $pdf->cell(50,$alt,db_formatar($total_rpps_desp_atebim,"f"),0,1,"R",0);
    $pdf->cell(90,$alt,'    Inscritas em Restos a Pagar Não Processados','R',0,"L",0);
    $pdf->cell(50,$alt, "-",'R',0,"C",0);
    $pdf->cell(50,$alt,db_formatar($total_rpps_rp_np_atebim,"f"),0,1,"R",0);
    $total_rpps_desp_atebim += $total_rpps_rp_np_atebim;
  } else {

    $pdf->cell(90,$alt,'  Despesas Previdenciárias Liquidadas(V)','R',0,"L",0);
    $pdf->cell(50,$alt,db_formatar($total_rpps_desp_nobim,"f"),'R',0,"R",0);
    $pdf->cell(50,$alt,db_formatar($total_rpps_desp_atebim,"f"),0,1,"R",0);
    
  }
  
  $pdf->cell(90,$alt,'  Resultado Previdenciário(VI) = (IV-V)','R',0,"L",0);
  $pdf->cell(50,$alt,db_formatar($res_rpps_prev_nobim,"f"),'R',0,"R",0);
  $pdf->cell(50,$alt,db_formatar($res_rpps_prev_atebim,"f"),0,1,"R",0);
}
// RESULTADOS NOMINAL/PRIMARIO
if ($emite_resultado==1){
  $num_rel++;

  $pdf->cell(90,$alt,"","T","C",0);
  $pdf->cell(25,$alt,"Meta Fixada no","TRL",0,"C",0);
  $pdf->cell(25,$alt,"Resultado Apurado","TRL",0,"C",0);
  $pdf->cell(50,$alt,"% em Relação à Meta","T",1,"C",0);
  $pdf->cell(90,$alt,"RESULTADOS NOMINAL E PRIMÁRIO","R",0,"C",0);
  $pdf->cell(25,$alt,"AMF da LDO","RL",0,"C",0);
  $pdf->cell(25,$alt,"Até o ".$dados["periodo"],"RL",0,"C",0);
  $pdf->cell(50,$alt,"",0,1,"C",0);
  $pdf->cell(90,$alt,"","B","C",0);
  $pdf->cell(25,$alt,"(a)","BRL",0,"C",0);
  $pdf->cell(25,$alt,"(b)","BRL",0,"C",0);
  $pdf->cell(50,$alt,"(b/a)","B",1,"C",0);
  $pdf->cell(90,$alt,"Resultado Nominal","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($META_NOMINAL,"f"),"BRL",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($total_nominal,"f"),"BRL",0,"R",0);
  if ($META_NOMINAL==0) {
    $perc_meta_nom = 0;
  }else{
    $perc_meta_nom = ($total_nominal/$META_NOMINAL)*100;
  }
  $pdf->cell(50,$alt,db_formatar($perc_meta_nom,"f"),"B",1,"R",0);
  $pdf->cell(90,$alt,"Resultado Primário","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($META_PRIMARIA,"f"),"BRL",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($total_primario,"f"),"BRL",0,"R",0);
  if ($META_PRIMARIA==0) {
    $perc_meta_prim = 0;
  }else{
    $perc_meta_prim = ($total_primario/$META_PRIMARIA)*100;
  }
  $pdf->cell(50,$alt,db_formatar($perc_meta_prim,"f"),0,1,"R",0);
}
// RESTOS A PAGAR
if ($emite_rp==1){
  $num_rel++;

  $pdf->cell(90,($alt*2),"RESTOS À PAGAR POR PODER E MINISTÉRIO PÚBLICO","TR",0,"C",0);
  $pdf->cell(25,$alt,"Inscrição","TRL",0,"C",0);
  $pdf->cell(25,$alt,"Cancelamento","TRL",0,"C",0);
  $pdf->cell(25,$alt,"Pagamento","TRL",0,"C",0);
  $pdf->cell(25,$alt,"Saldo","TL",1,"C",0);
  $pdf->cell(90,$alt,"","BR",0,"C",0);
  $pdf->cell(25,$alt,"","BRL",0,"C",0);
  $pdf->cell(25,$alt,"Até o ".$dados["periodo"],"BRL",0,"C",0);
  $pdf->cell(25,$alt,"Até o ".$dados["periodo"],"BRL",0,"C",0);
  $pdf->cell(25,$alt,"a Pagar","BL",1,"C",0);

  $pdf->cell(90,$alt,espaco($n1)."RESTOS À PAGAR PROCESSADOS","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_proc_insc,"f"),"RL",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_proc_canc,"f"),"RL",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_proc_pgto,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_proc_a_pagar,"f"),"L",1,"R",0);

  $pdf->cell(90,$alt,espaco($n2)."Poder Executivo","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($total_proc_insc,"f"),"RL",0,"R",0);    // Inscricao
  $pdf->cell(25,$alt,db_formatar($total_proc_canc,"f"),"RL",0,"R",0);    // Cancelados
  $pdf->cell(25,$alt,db_formatar($total_proc_pgto,"f"),"R",0,"R",0);     // Pagamentos
  $pdf->cell(25,$alt,db_formatar($total_proc_a_pagar,"f"),"L",1,"R",0);  // a Pagar 

  $pdf->cell(90,$alt,espaco($n2)."Poder Legislativo","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($ltotal_proc_insc,"f"),"RL",0,"R",0);    // Inscricao
  $pdf->cell(25,$alt,db_formatar($ltotal_proc_canc,"f"),"RL",0,"R",0);    // Cancelados
  $pdf->cell(25,$alt,db_formatar($ltotal_proc_pgto,"f"),"R",0,"R",0);     // Pagamentos
  $pdf->cell(25,$alt,db_formatar($ltotal_proc_a_pagar,"f"),"L",1,"R",0);  // a Pagar 

  $pdf->cell(90,$alt,espaco($n1)."RESTOS À PAGAR NÃO-PROCESSADOS","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_naoproc_insc,"f"),"RL",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_naoproc_canc,"f"),"RL",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_naoproc_pgto,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_naoproc_a_pagar,"f"),"L",1,"R",0);

  $pdf->cell(90,$alt,espaco($n2)."Poder Executivo","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($total_naoproc_insc,"f"),"RL",0,"R",0);    // Inscricao
  $pdf->cell(25,$alt,db_formatar($total_naoproc_canc,"f"),"RL",0,"R",0);    // Cancelados
  $pdf->cell(25,$alt,db_formatar($total_naoproc_pgto,"f"),"R",0,"R",0);     // Pagamentos
  $pdf->cell(25,$alt,db_formatar($total_naoproc_a_pagar,"f"),"L",1,"R",0);  // a Pagar 

  $pdf->cell(90,$alt,espaco($n2)."Poder Legislativo","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($ltotal_naoproc_insc,"f"),"RL",0,"R",0);    // Inscricao
  $pdf->cell(25,$alt,db_formatar($ltotal_naoproc_canc,"f"),"RL",0,"R",0);    // Cancelados
  $pdf->cell(25,$alt,db_formatar($ltotal_naoproc_pgto,"f"),"R",0,"R",0);     // Pagamentos
  $pdf->cell(25,$alt,db_formatar($ltotal_naoproc_a_pagar,"f"),"L",1,"R",0);  // a Pagar 

  $pdf->cell(90,$alt,"TOTAL","TR",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_proc_insc    + $tot_geral_naoproc_insc,"f"),"TRL",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_proc_canc    + $tot_geral_naoproc_canc,"f"),"TRL",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_proc_pgto    + $tot_geral_naoproc_pgto,"f"),"TR",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($tot_geral_proc_a_pagar + $tot_geral_naoproc_a_pagar,"f"),"T",1,"R",0);
}
// MDE
$fonte = 6;
if ($emite_mde==1) {
  $num_rel++;

  $fonte = 6;
  $pdf->cell(90,$alt,"","TR",0,"L",0);
  $pdf->cell(25,$alt,"Valor apurado","TR",0,"C",0);
  $pdf->cell(75,$alt,"Limites Constitucionais Anuais","TB",1,"C",0);
  $pdf->cell(90,$alt,"DESPESAS COM MANUTENÇÃO E DESENVOLVIMENTO DO ENSINO - MDE","R",0,"C",0);
  $pdf->cell(25,$alt,"Até o ".$dados["periodo"],"R",0,"C",0);
  $pdf->cell(25,$alt,"% Mínimo a","R",0,"C",0);
  $pdf->cell(50,$alt,"% Aplicado Até o ".$dados["periodo"],"T",1,"C",0);
  $pdf->cell(90,$alt,"","BR",0,"L",0);
  $pdf->cell(25,$alt,"","BR",0,"L",0);
  $pdf->cell(25,$alt,"Aplicar no Exercício","BR",0,"C",0);
  $pdf->cell(50,$alt,"","B",1,"C",0);
  $pdf->setfont('arial','',$fonte);
  $pdf->cell(90,$alt,"Mínimo Anual de 25% das Receitas de Impostos no MDE","R",0,"L",0);
  $pdf->setfont('arial','',($fonte+2));
  $pdf->cell(25,$alt,db_formatar($total_A,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,"25%","R",0,"C",0);
  $pdf->cell(50,$alt,db_formatar($perc_A,"f"),0,1,"R",0);
  if ($lUltimoBimestre) {

    $pdf->setfont('arial','',$fonte);
    $pdf->cell(90,$alt,"  Liquidadas","R",0,"L",0);
    $pdf->setfont('arial','',($fonte+2));
    $pdf->cell(25,$alt,db_formatar($nTotal25AteBimestre,"f"),"R",0,"R",0);
    $pdf->cell(25,$alt,"-","R",0,"C",0);
    $pdf->cell(50,$alt,"-",0,1,"R",0);
    $pdf->setfont('arial','',$fonte);
    $pdf->cell(90,$alt,"  Inscritas em Restos a Pagar Não Processados","R",0,"L",0);
    $pdf->setfont('arial','',($fonte+2));
    $pdf->cell(25,$alt,db_formatar($nTotal25Inscritas,"f"),"R",0,"R",0);
    $pdf->cell(25,$alt,"-","R",0,"C",0);
    $pdf->cell(50,$alt,"-",0,1,"R",0);
    
  }
  if ($lUltimoBimestre) {
    
    
    $pdf->setfont('arial','',$fonte);
    $pdf->cell(90,$alt,"Mínimo Anual de 60% do FUNDEB na Rem. do Magistério com Educação Inf. e Ensino Fund.","R",0,"L",0);
    $pdf->setfont('arial','',($fonte+2));
    $pdf->cell(25,$alt,db_formatar($total_B,"f"),"R",0,"R",0);
    $pdf->cell(25,$alt,"60%","R",0,"C",0);
    $pdf->cell(50,$alt,db_formatar($perc_B,"f"),"",1,"R",0);
    $pdf->setfont('arial','',$fonte);
    $pdf->cell(90,$alt,"  Liquidadas","R",0,"L",0);
    $pdf->setfont('arial','',($fonte+2));
    $pdf->cell(25,$alt,db_formatar($nTotal60AteBimestre,"f"),"R",0,"R",0);
    $pdf->cell(25,$alt,"-","R",0,"C",0);
    $pdf->cell(50,$alt,"-",0,1,"R",0);
    $pdf->setfont('arial','',$fonte);
    $pdf->cell(90,$alt,"  Inscritas em Restos a Pagar Não Processados","BR",0,"L",0);
    $pdf->setfont('arial','',($fonte+2));
    $pdf->cell(25,$alt,db_formatar($nTotal60Inscritas,"f"),"BR",0,"R",0);
    $pdf->cell(25,$alt,"-","BR",0,"C",0);
    $pdf->cell(50,$alt,"-",0,1,"C",0);
    
  } else {
  
    $pdf->setfont('arial','',$fonte);
    $pdf->cell(90,$alt,"Mínimo Anual de 60% do FUNDEB na Rem. do Magistério com Educação Inf. e Ensino Fund.","BR",0,"L",0);
    $pdf->setfont('arial','',($fonte+2));
    $pdf->cell(25,$alt,db_formatar($total_B,"f"),"BR",0,"R",0);
    $pdf->cell(25,$alt,"60%","BR",0,"C",0);
    $pdf->cell(50,$alt,db_formatar($perc_B,"f"),"B",1,"R",0);
    
  }
}
// OPERACOES DE CREDITO E DESPESAS DE CAPITAL
if ($emite_oper==1){
  $num_rel++;
  
  $pdf->setfont('arial','',$fonte);
  $pdf->cell(90,$alt,"RECEITAS DE OPERAÇÕES DE CRÉDITO E DESPESAS DE CAPITAL","TBR",0,"C",0);
  $pdf->cell(50,$alt,"Valor Apurado Até o ".$dados["periodo"],"TBR",0,"C",0);
  $pdf->cell(50,$alt,"Saldo a Realizar","TB",1,"C",0);
  $pdf->cell(90,$alt,"Receita de Operação de Crédito","R",0,"L",0);
  $pdf->cell(50,$alt,db_formatar($nOperacoesCreditoApurado,"f"),"R",0,"R",0);
  $pdf->cell(50,$alt,db_formatar($nOperacoesARealizar,"f"),0,1,"R",0);
  $pdf->cell(90,$alt,"Despesa de Capital Líquida","BR",0,"L",0);
  $pdf->cell(50,$alt,db_formatar($nDespesaCapitalLiquidada,"f"),"BR",0,"R",0);
  $pdf->cell(50,$alt,db_formatar($nDespesaARelizar,"f"),"B",1,"R",0);
}
// PROJECAO ATUARIAL DO RPPS
if ($emite_proj==1){
  if ($num_rel > 0){
    if ($num_rel > 7){
      $pdf->cell(190,($alt+2),"Continução na página 2",0,1,"L",0);
      $pdf->addpage();
      $pdf->cell(190,($alt+2),"Continução da página 1",0,1,"L",0);
    } else{
      $pdf->cell(190,$alt,"","TB",1,"C",0);
    }
  }

  $num_rel++;

  $pdf->cell(90,$alt,"PROJEÇÃO ATUARIAL DOS REGIMES DE PREVIDÊNCIA","TBR",0,"C",0);
  $pdf->cell(25,$alt,"Exercício","TBR",0,"C",0);
  $pdf->cell(25,$alt,"10 Exercício","TBR",0,"C",0);
  $pdf->cell(25,$alt,"20 Exercício","TBR",0,"C",0);
  $pdf->cell(25,$alt,"35 Exercício","TB",1,"C",0);
  $pdf->cell(90,$alt,"Regime Próprio de Previdência Social dos Servidores Públicos","R",0,"L",0);
  $pdf->cell(25,$alt,"","R",0,"C",0);
  $pdf->cell(25,$alt,"","R",0,"C",0);
  $pdf->cell(25,$alt,"","R",0,"C",0);
  $pdf->cell(25,$alt,"",0,1,"C",0);
  $pdf->cell(90,$alt,"    Receita da Contribuição Patronal (III)","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($total_rec_patronal,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($RPPS_10_EXERCICIO_PATRONAL,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($RPPS_20_EXERCICIO_PATRONAL,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($RPPS_35_EXERCICIO_PATRONAL,"f"),0,1,"R",0);
  $pdf->cell(90,$alt,"    Receitas Previdenciárias (IV)","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($total_rec,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($RPPS_10_EXERCICIO_RECEITAS,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($RPPS_20_EXERCICIO_RECEITAS,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($RPPS_35_EXERCICIO_RECEITAS,"f"),0,1,"R",0);
  $pdf->cell(90,$alt,"    Despesas Previdenciárias (V)","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($total_desp,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($RPPS_10_EXERCICIO_DESPESAS,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($RPPS_20_EXERCICIO_DESPESAS,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($RPPS_35_EXERCICIO_DESPESAS,"f"),0,1,"R",0);
  $pdf->cell(90,$alt,"    Resultado Previdenciário (IV - V)","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($total_res_rpps,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($RPPS_10_EXERCICIO_RESULTADO,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($RPPS_20_EXERCICIO_RESULTADO,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($RPPS_35_EXERCICIO_RESULTADO,"f"),0,1,"R",0);
  $pdf->cell(90,$alt,"    Repasse Recebido para Cobertura de Déficit do RPPS (VI)","R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($total_rep_rpps,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($RPPS_10_EXERCICIO_REPASSE,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($RPPS_20_EXERCICIO_REPASSE,"f"),"R",0,"R",0);
  $pdf->cell(25,$alt,db_formatar($RPPS_35_EXERCICIO_REPASSE,"f"),0,1,"R",0);
}
if (isset($emite_aplicacao_recursos) &&  $emite_aplicacao_recursos == 1 && $lUltimoBimestre ) {
  
  if ($num_rel > 7 && $lUltimoBimestre){
     
    $pdf->cell(190,($alt+2),"Continução na página 2",0,1,"L",0);
    $pdf->addpage();
    $pdf->cell(190,($alt+2),"Continução da página 1",0,1,"L",0);
  } else{
      $pdf->cell(190,$alt,"","TB",1,"C",0);
  }
  $nValorReceitaAteBimestre    = db_formatar(0,"f");
  $nValorReceitaSaldoARealizar = db_formatar(0,"f");
  $nValorAplicacaoAteBimestre  = db_formatar(0,"f");
  $nValorAplicacaoSaldoARealizar = db_formatar(0,"f");
  $sSqlValores   = $clconrelinfo->sql_query_valores(18, $instituicao);
  
  $res_variaveis = $clconrelinfo->sql_record($sSqlValores);
  if ($clconrelinfo->numrows > 0){
    
    for ($i = 0; $i < $clconrelinfo->numrows; $i++){
      
      db_fieldsmemory($res_variaveis, $i);
      if ($c83_codigo == 372 ) {
        $nValorReceitaAteBimestre = db_formatar($c83_informacao,"f"); 
      }
      if ($c83_codigo == 373) {
        $nValorReceitaSaldoARealizar = db_formatar($c83_informacao,"f"); 
      }
      if ($c83_codigo == 374) {
        $nValorAplicacaoAteBimestre = db_formatar($c83_informacao,"f"); 
      }
      if ($c83_codigo == 375) {
        $nValorAplicacaoSaldoARealizar = db_formatar($c83_informacao,"f"); 
      }
    }
  }
  $num_rel++;
  $pdf->cell(90,$alt,"RECEITA DA ALIENAÇÃO DE ATIVOS  E APLICAÇÃO DE RECURSOS","TBR",0,"C",0);
  $pdf->cell(50,$alt,"Valor Apurado Até o Bimestre","TBR",0,"C",0);
  $pdf->cell(50,$alt,"Saldo a Realizar","TB",1,"C",0);
  $pdf->cell(90,$alt,"Receita de Capital Resultante da Alienação de Ativos","TR",0,"L",0);
  $pdf->cell(50,$alt, $nValorReceitaAteBimestre,"TR",0,"R",0);
  $pdf->cell(50,$alt, $nValorReceitaSaldoARealizar,"TL",1,"R",0);
  $pdf->cell(90,$alt,"Aplicação dos Recursos da Alienação de Ativos","BR",0,"L",0);
  $pdf->cell(50,$alt, $nValorAplicacaoAteBimestre,"BL",0,"R",0);
  $pdf->cell(50,$alt, $nValorAplicacaoSaldoARealizar,"BL",1,"R",0);
  $pdf->cell(190,$alt,"","T",1,"L",0);
  
}
// RECEITA DA ALIENACAO DE ATIVOS E APLICACAO DOS RECURSOS 
if ($emite_alienacao==1){
  $num_rel++;
   
  $pdf->cell(90,$alt,"RECEITA DA ALIENAÇÃO DE ATIVOS E APLICAÇÃO DOS RECURSOS","TBR",0,"C",0);
  $pdf->cell(50,$alt,"Valor Apurado Até o ".$dados["periodo"],"TBR",0,"C",0);
  $pdf->cell(50,$alt,"Saldo a Realizar","TB",1,"C",0);
  $pdf->cell(90,$alt,"Receita de Capital Resultante da Alienação de Ativos","R",0,"L",0);
  $pdf->cell(50,$alt,db_formatar($total_alien,"f"),"R",0,"R",0);
  $pdf->cell(50,$alt,db_formatar($saldo_alien,"f"),0,1,"R",0);
  $pdf->cell(90,$alt,"Aplicação dos Recursos da Alienação de Ativos","BR",0,"L",0);
  $pdf->cell(50,$alt,db_formatar($total_recurso,"f"),"BR",0,"R",0);
  $pdf->cell(50,$alt,db_formatar($saldo_recurso,"f"),"B",1,"R",0);
}
// DESPESAS COM SAUDE
if ($emite_saude==1){
  $num_rel++;
  $sBordaBottom = "B";
  if ($lUltimoBimestre) {
    $sBordaBottom = "";
  }
  $pdf->cell(90,$alt,"","TR",0,"C",0);
  $pdf->cell(25,$alt,"Valor apurado","TR",0,"C",0);
  $pdf->cell(75,$alt,"Limite Constitucional Anual","TB",1,"C",0);
  $pdf->cell(90,$alt,"DESPESAS COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE","R",0,"C",0);
  $pdf->cell(25,$alt,"Até o ".$dados["periodo"],"R",0,"C",0);
  $pdf->cell(25,$alt,"% Mínimo a","R",0,"C",0);
  $pdf->cell(50,$alt,"% Aplicado Até o ".$dados["periodo"],0,1,"C",0);
  $pdf->cell(90,$alt,"","BR",0,"C",0);
  $pdf->cell(25,$alt,"","BR",0,"C",0);
  $pdf->cell(25,$alt,"Aplicar no Exercício","BR",0,"C",0);
  $pdf->cell(50,$alt,"","B",1,"C",0);
  $pdf->cell(90,$alt,"Despesas Próprias com Ações e Serviços Públicos de Saúde","{$sBordaBottom}R",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($total_saude,"f"),"{$sBordaBottom}R",0,"R",0);
  $pdf->cell(25,$alt,"15%","{$sBordaBottom}R",0,"R",0);
  $pdf->cell(50,$alt,db_formatar($perc_aplic_atebim,"f"),"{$sBordaBottom}",1,"R",0);
  if ($lUltimoBimestre) {
    
    $pdf->cell(90,$alt,"  Liquidadas","R",0,"L",0);
    $pdf->cell(25,$alt,db_formatar($nTotalSaudeAteBimestre,"f"),"R",0,"R",0);
    $pdf->cell(25,$alt,"-","R",0,"C",0);
    $pdf->cell(50,$alt,"-","0",1,"R",0);
    
    $pdf->cell(90,$alt,"  Inscritas em Restos a Pagar Não Processados","BR",0,"L",0);
    $pdf->cell(25,$alt,db_formatar($nTotalSaudeInscritos,"f"),"BR",0,"R",0);
    $pdf->cell(25,$alt,"-","BR",0,"C",0);
    $pdf->cell(50,$alt,"-","0",1,"R",0);
  }
}
// DESPESA DE PPP
if ($emite_ppp==1){
  $num_rel++;

  $pdf->cell(90, $alt,"DESPESAS DE CARÁCTER CONTINUADO DERIVADAS DE","TR",0,"C",0);
  $pdf->cell(100,$alt,"VALOR APURADO NO EXERCÍCIO CORRENTE","T",1,"C",0);
  $pdf->cell(90, $alt,"PPP'S CONTRATADAS","BR",0,"C",0);
  $pdf->cell(100,$alt,"","B",1,"C",0);
  $pdf->cell(90, $alt,"Total das Despesas/RCL (%)","BR",0,"L",0);
  $pdf->cell(100,$alt,db_formatar($perc_apurado,"f"),"B",1,"R",0);
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$pdf->cell(190,$alt,"","T",1,"L",0);

notasExplicativas(&$pdf,18,"{$sPeriodo}",190);

$pdf->ln(20);

assinaturas(&$pdf,&$classinatura,'LRF');

$pdf->Output();
?>